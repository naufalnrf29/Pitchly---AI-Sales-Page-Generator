<?php

namespace App\Providers;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use OpenAI;
use OpenAI\Contracts\ClientContract;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // openai-php/laravel is a deferred provider. Force-loading it here
        // marks it as already registered, so the deferred mechanism won't
        // fire again later and overwrite our binding below.
        $this->app->register(\OpenAI\Laravel\ServiceProvider::class);

        if ($this->app->isLocal()) {
            $caBundle  = storage_path('app/cacert.pem');
            $sslVerify = file_exists($caBundle) ? $caBundle : false;

            // Bind to ClientContract::class — the real target of the 'openai' alias.
            // Binding to the string 'openai' is bypassed once the package sets its alias.
            $this->app->singleton(ClientContract::class, function () use ($sslVerify) {
                return OpenAI::factory()
                    ->withApiKey(config('openai.api_key'))
                    ->withOrganization(config('openai.organization'))
                    ->withHttpClient(new GuzzleClient([
                        'verify'          => $sslVerify,
                        'timeout'         => 90,
                        'connect_timeout' => 10,
                    ]))
                    ->make();
            });
        }
    }

    public function boot(): void
    {
        RateLimiter::for('openai', function (Request $request) {
            return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip());
        });
    }
}
