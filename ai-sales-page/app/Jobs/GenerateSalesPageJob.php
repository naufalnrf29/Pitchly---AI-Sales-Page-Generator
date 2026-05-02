<?php

namespace App\Jobs;

use App\Models\SalesPage;
use App\Services\SalesPageService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateSalesPageJob implements ShouldQueue
{
    use Queueable;

    public int $timeout = 200;
    public int $tries   = 2;
    public array $backoff = [10, 30];

    public function __construct(
        private SalesPage $salesPage,
        private ?string $originalHtml = null,
        private ?string $feedback = null,
    ) {}

    public function handle(SalesPageService $service): void
    {
        $this->salesPage->update(['status' => 'generating']);

        $data = [
            'product_name'         => $this->salesPage->product_name,
            'description'          => $this->salesPage->description,
            'features'             => $this->salesPage->features,
            'target_audience'      => $this->salesPage->target_audience,
            'price'                => $this->salesPage->price,
            'unique_selling_point' => $this->salesPage->unique_selling_point,
        ];

        if ($this->originalHtml !== null) {
            // Refinement path
            $html = $service->regenerate(
                originalData: $data,
                originalHtml: $this->originalHtml,
                heroImageUrl: $this->salesPage->hero_image_url,
                feedback:     $this->feedback,
                template:     $this->salesPage->template,
            );

            $this->salesPage->update([
                'generated_html' => $html,
                'status'         => 'completed',
                'error_message'  => null,
            ]);
        } else {
            // Initial generation path
            $result = $service->generate($data, $this->salesPage->template);

            $this->salesPage->update([
                'generated_html' => $result['html'],
                'hero_image_url' => $result['hero_image_url'],
                'status'         => 'completed',
                'error_message'  => null,
            ]);
        }
    }

    public function failed(\Throwable $e): void
    {
        $this->salesPage->update([
            'status'        => 'failed',
            'error_message' => $e->getMessage(),
        ]);
    }
}
