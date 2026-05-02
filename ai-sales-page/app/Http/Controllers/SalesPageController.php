<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateSalesPageJob;
use App\Models\SalesPage;
use App\Services\SalesPageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class SalesPageController extends Controller
{
    /**
     * History — list all saved pages for the current user.
     * Supports search by product_name via ?q=
     */
    public function index(Request $request): View
    {
        $query = $request->user()
            ->salesPages()
            ->originals();

        if ($search = $request->query('q')) {
            $query->search($search);
        }

        $pages = $query->paginate(12)->withQueryString();

        return view('sales-pages.index', compact('pages'));
    }

    /**
     * Create — show the product input form.
     */
    public function create(): View
    {
        return view('sales-pages.create');
    }

    /**
     * Store — validate input, create a pending record, dispatch AI job.
     * Returns JSON when called via AJAX (Accept: application/json).
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'product_name'         => ['required', 'string', 'max:255'],
            'description'          => ['required', 'string', 'max:2000'],
            'features'             => ['required', 'string', 'max:1000'],
            'target_audience'      => ['required', 'string', 'max:255'],
            'price'                => ['required', 'string', 'max:100'],
            'unique_selling_point' => ['required', 'string', 'max:500'],
            'template'             => ['nullable', 'string', 'in:modern,bold'],
        ]);

        $salesPage = $request->user()->salesPages()->create([
            'product_name'         => $validated['product_name'],
            'description'          => $validated['description'],
            'features'             => $validated['features'],
            'target_audience'      => $validated['target_audience'],
            'price'                => $validated['price'],
            'unique_selling_point' => $validated['unique_selling_point'],
            'template'             => $validated['template'] ?? 'modern',
            'generated_html'       => '',
            'version'              => 1,
            'status'               => 'pending',
        ]);

        GenerateSalesPageJob::dispatch($salesPage);

        if ($request->expectsJson()) {
            return response()->json([
                'salesPageId' => $salesPage->id,
                'redirect'    => route('sales-pages.show', $salesPage),
            ]);
        }

        return redirect()
            ->route('sales-pages.show', $salesPage)
            ->with('success', 'Your sales page is being generated…');
    }

    /**
     * Status — poll endpoint for the async generate overlay.
     */
    public function status(SalesPage $salesPage): JsonResponse
    {
        $this->authorize('view', $salesPage);

        return response()->json([
            'status'        => $salesPage->status,
            'error_message' => $salesPage->error_message,
            'redirect_url'  => route('sales-pages.show', $salesPage),
        ]);
    }

    /**
     * Show — live preview of a single saved page.
     */
    public function show(SalesPage $salesPage): View
    {
        $this->authorize('view', $salesPage);

        // $root is always the original (version 1) page
        $root     = $salesPage->isOriginal ? $salesPage : $salesPage->parent;
        $versions = $root?->versions()->orderBy('version')->get() ?? collect();

        return view('sales-pages.show', compact('salesPage', 'root', 'versions'));
    }

    /**
     * Destroy — delete a page and all its regenerated versions.
     */
    public function destroy(SalesPage $salesPage): RedirectResponse
    {
        $this->authorize('delete', $salesPage);

        // Delete child versions first (parent_id FK), then the original
        $salesPage->versions()->delete();
        $salesPage->delete();

        return redirect()->route('sales-pages.index')
            ->with('success', 'Sales page deleted.');
    }

    /**
     * Regenerate — create a new version record and dispatch a refinement job.
     */
    public function regenerate(Request $request, SalesPage $salesPage): RedirectResponse
    {
        $this->authorize('update', $salesPage);

        $request->validate([
            'feedback' => ['required', 'string', 'max:1000'],
        ]);

        $root = $salesPage->isOriginal ? $salesPage : $salesPage->parent;

        $newVersion = $request->user()->salesPages()->create([
            'product_name'         => $root->product_name,
            'description'          => $root->description,
            'features'             => $root->features,
            'target_audience'      => $root->target_audience,
            'price'                => $root->price,
            'unique_selling_point' => $root->unique_selling_point,
            'template'             => $root->template,
            'generated_html'       => '',
            'hero_image_url'       => $salesPage->hero_image_url,
            'parent_id'            => $root->id,
            'feedback'             => $request->input('feedback'),
            'version'              => $root->versions()->max('version') + 1,
            'status'               => 'pending',
        ]);

        GenerateSalesPageJob::dispatch($newVersion, $salesPage->generated_html, $request->input('feedback'));

        return redirect()
            ->route('sales-pages.show', $newVersion)
            ->with('success', 'Refining your page based on feedback…');
    }

    /**
     * Export — download the page as a standalone .html file.
     */
    public function export(SalesPage $salesPage): Response
    {
        $this->authorize('view', $salesPage);

        $slug     = str($salesPage->product_name)->slug()->value();
        $filename = "{$slug}-v{$salesPage->version}.html";

        // TODO (Step 5): build standalone HTML with embedded URLs
        $html = $salesPage->generated_html;

        return response($html, 200, [
            'Content-Type'        => 'text/html',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
