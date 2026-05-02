@extends('layouts.main')
@section('title', $salesPage->product_name)

{{-- ── Breadcrumb ──────────────────────────────────────────────────────── --}}
@section('breadcrumb')
    <a href="{{ route('sales-pages.index') }}"
       class="text-gray-400 hover:text-gray-600 transition-colors">My Pages</a>
    <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor"
         stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="text-gray-700 font-medium truncate max-w-[200px]">
        {{ $salesPage->product_name }}
    </span>
    <span class="badge-violet">v{{ $salesPage->version }}</span>
@endsection

{{-- ── Topbar actions ─────────────────────────────────────────────────── --}}
@section('topbar-actions')
    {{-- Copy HTML --}}
    <button onclick="copyHTML()"
            id="copy-btn"
            class="btn-secondary btn-sm gap-1.5">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
             stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166
                     1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75
                     0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11
                     1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0
                     01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057
                     1.907-2.185a48.208 48.208 0 011.927-.184"/>
        </svg>
        <span id="copy-label">Copy HTML</span>
    </button>

    {{-- Download --}}
    <a href="{{ route('sales-pages.export', $salesPage) }}"
       class="btn-secondary btn-sm gap-1.5">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
             stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0
                     0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
        </svg>
        Download .html
    </a>

    {{-- Delete --}}
    <button onclick="document.getElementById('delete-modal').classList.remove('hidden')"
            class="btn-ghost btn-sm gap-1.5 text-red-500 hover:text-red-600
                   hover:bg-red-50">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
             stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107
                     1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244
                     2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456
                     0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114
                     1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91
                     -2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09
                     1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
        </svg>
        Delete
    </button>
@endsection

@section('content')

{{-- Hidden textarea — source of truth for Copy HTML --}}
<textarea id="html-source" class="sr-only" aria-hidden="true" readonly
>{{ $salesPage->generated_html }}</textarea>

{{-- ── Toast notification ──────────────────────────────────────────────── --}}
<div id="toast"
     class="fixed bottom-6 right-6 z-50 flex items-center gap-3
            px-4 py-3 rounded-xl bg-gray-900 text-white text-sm
            shadow-xl translate-y-4 opacity-0 pointer-events-none
            transition-all duration-300">
    <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none"
         stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M4.5 12.75l6 6 9-13.5"/>
    </svg>
    <span id="toast-msg">Copied!</span>
</div>

{{-- ── Main layout: preview + sidebar ─────────────────────────────────── --}}
<div class="grid grid-cols-1 xl:grid-cols-[1fr_300px] gap-6 items-start">

    {{-- ════════════════════════════════════════════════════════════════
         LEFT — iframe preview
    ════════════════════════════════════════════════════════════════ --}}
    <div class="space-y-4"
         x-data="{ status: '{{ $salesPage->status }}' }"
         x-init="
            if (status === 'pending' || status === 'generating') {
                const poll = setInterval(async () => {
                    const res  = await fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const text = await res.text();
                    const match = text.match(/data-status=[\"'](\w+)[\"']/);
                    if (match) {
                        status = match[1];
                        if (status === 'completed' || status === 'failed') {
                            clearInterval(poll);
                            window.location.reload();
                        }
                    }
                }, 5000);
            }
         ">

        {{-- Hidden status anchor for polling --}}
        <span data-status="{{ $salesPage->status }}" class="sr-only"></span>

        {{-- ── Generating state ──────────────────────────────────── --}}
        <div x-show="status === 'pending' || status === 'generating'"
             class="card p-10 flex flex-col items-center justify-center gap-4 min-h-[400px]">
            <div class="relative">
                <div class="w-16 h-16 rounded-full border-4 border-violet-100
                            border-t-violet-600 animate-spin"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-6 h-6 text-violet-600" fill="none"
                         stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0
                                 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0
                                 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0
                                 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0
                                 00-3.09 3.09z"/>
                    </svg>
                </div>
            </div>
            <div class="text-center">
                <p class="text-sm font-semibold text-gray-900">
                    Generating your sales page…
                </p>
                <p class="text-xs text-gray-400 mt-1">
                    GPT-4o is writing your copy. This may take up to 90 seconds.
                    This page will refresh automatically when ready.
                </p>
            </div>
        </div>

        {{-- ── Failed state ──────────────────────────────────────── --}}
        <div x-show="status === 'failed'"
             class="card p-8 flex flex-col items-center gap-4 border-red-100 bg-red-50">
            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9
                             3.75h.008v.008H12v-.008z"/>
                </svg>
            </div>
            <div class="text-center">
                <p class="text-sm font-semibold text-red-700">Generation failed</p>
                @if($salesPage->error_message)
                <p class="text-xs text-red-500 mt-1">{{ $salesPage->error_message }}</p>
                @endif
                <a href="{{ route('sales-pages.create') }}"
                   class="btn-primary btn-sm mt-4 inline-flex">
                    Try again
                </a>
            </div>
        </div>

        {{-- ── Completed state ──────────────────────────────────── --}}
        <div x-show="status === 'completed'">

        {{-- Preview header bar --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                {{-- Browser chrome dots --}}
                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-white
                            rounded-lg border border-gray-200 shadow-sm">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                    <span class="ml-2 text-xs text-gray-400 font-mono truncate max-w-[180px]">
                        {{ Str::slug($salesPage->product_name) }}.html
                    </span>
                </div>
                <span class="badge-gray text-xs">Live Preview</span>
            </div>

            {{-- Template badge --}}
            <span class="badge {{ $salesPage->template === 'bold' ? 'badge-gray' : 'badge-violet' }}
                         capitalize">
                {{ $salesPage->template }} template
            </span>
        </div>

        {{-- iframe container --}}
        <div class="card overflow-hidden p-0 ring-1 ring-gray-200 mt-4">
            <iframe
                id="preview-frame"
                srcdoc="{{ $salesPage->generated_html }}"
                class="w-full border-0"
                style="height: 85vh; min-height: 600px;"
                loading="lazy"
                sandbox="allow-scripts"
                title="Sales page preview for {{ $salesPage->product_name }}">
            </iframe>
        </div>

        {{-- ── Regenerate with feedback ──────────────────────────────── --}}
        <div class="card px-6 py-5 mt-4" id="regenerate-section">
            <div class="flex items-start gap-3 mb-4">
                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center
                            justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-amber-600" fill="none"
                         stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0
                                 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0
                                 0013.803-3.7M4.031 9.865a8.25 8.25 0
                                 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">
                        Not quite right? Refine with feedback
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Describe what to change — AI rewrites only that part.
                        Original input stays the same.
                    </p>
                </div>
            </div>

            <form method="POST"
                  action="{{ route('sales-pages.regenerate', $salesPage) }}"
                  x-data="{ feedback: '', loading: false }"
                  @submit.prevent="if(feedback.trim()) { loading = true; $el.submit() }">
                @csrf

                <div class="space-y-3">
                    <textarea
                        name="feedback"
                        x-model="feedback"
                        rows="3"
                        placeholder="Examples:&#10;• Make the headline more direct and punchy&#10;• Add urgency to the CTA section&#10;• Tone down the testimonials — they sound too perfect&#10;• Make the pricing section clearer"
                        class="input resize-none text-sm @error('feedback') input-error @enderror"
                        :disabled="loading"
                    ></textarea>
                    @error('feedback')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="flex items-center justify-between gap-3">
                        <p class="text-xs text-gray-400">
                            This creates a new version — previous versions are preserved.
                        </p>
                        <button type="submit"
                                :disabled="!feedback.trim() || loading"
                                :class="feedback.trim() && !loading
                                    ? 'opacity-100' : 'opacity-40 cursor-not-allowed'"
                                class="btn-primary btn-sm gap-2 shrink-0 min-w-[140px]">
                            <template x-if="!loading">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none"
                                         stroke="currentColor" stroke-width="2.5"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M16.023 9.348h4.992v-.001M2.985
                                                 19.644v-4.992m0 0h4.992m-4.993
                                                 0l3.181 3.183a8.25 8.25 0
                                                 0013.803-3.7M4.031 9.865a8.25
                                                 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                    </svg>
                                    Regenerate
                                </span>
                            </template>
                            <template x-if="loading">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 animate-spin" fill="none"
                                         viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor"
                                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                    </svg>
                                    Refining…
                                </span>
                            </template>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        </div>
        {{-- /completed --}}

    </div>
    {{-- /left --}}

    {{-- ════════════════════════════════════════════════════════════════
         RIGHT — sticky sidebar: info + versions
    ════════════════════════════════════════════════════════════════ --}}
    <div class="hidden xl:block">
        <div class="sticky top-24 space-y-4">

            {{-- Product info card --}}
            <div class="card px-5 py-4 space-y-4">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-widest
                               text-gray-400 mb-2">
                        Product
                    </p>
                    <h2 class="font-bold text-gray-900 text-base leading-tight">
                        {{ $salesPage->product_name }}
                    </h2>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $salesPage->price }}
                    </p>
                </div>

                <div class="divider my-0"></div>

                {{-- Meta --}}
                <dl class="space-y-2">
                    <div class="flex justify-between items-start gap-2">
                        <dt class="text-xs text-gray-400 shrink-0">Template</dt>
                        <dd class="text-xs font-medium text-gray-700 capitalize text-right">
                            {{ $salesPage->template }}
                        </dd>
                    </div>
                    <div class="flex justify-between items-start gap-2">
                        <dt class="text-xs text-gray-400 shrink-0">Audience</dt>
                        <dd class="text-xs text-gray-700 text-right leading-relaxed">
                            {{ Str::limit($salesPage->target_audience, 60) }}
                        </dd>
                    </div>
                    <div class="flex justify-between items-start gap-2">
                        <dt class="text-xs text-gray-400 shrink-0">Generated</dt>
                        <dd class="text-xs text-gray-700 text-right">
                            {{ $salesPage->created_at->diffForHumans() }}
                        </dd>
                    </div>
                </dl>

                <div class="divider my-0"></div>

                {{-- Feature tags --}}
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-widest
                               text-gray-400 mb-2">
                        Features
                    </p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($salesPage->features_array as $feature)
                        <span class="badge-gray text-[11px]">{{ $feature }}</span>
                        @endforeach
                    </div>
                </div>

                @if($salesPage->feedback)
                <div class="divider my-0"></div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-widest
                               text-gray-400 mb-1.5">
                        Feedback used
                    </p>
                    <p class="text-xs text-gray-500 italic leading-relaxed">
                        "{{ $salesPage->feedback }}"
                    </p>
                </div>
                @endif
            </div>

            {{-- Version history --}}
            @if($root && ($versions->count() > 0 || !$salesPage->isOriginal))
            <div class="card px-5 py-4">
                <p class="text-[10px] font-semibold uppercase tracking-widest
                           text-gray-400 mb-3">
                    Version history
                </p>
                <div class="space-y-1">

                    {{-- Original (v1) --}}
                    @if($root)
                    <a href="{{ route('sales-pages.show', $root) }}"
                       class="flex items-center justify-between gap-2 px-3 py-2
                              rounded-lg transition-colors duration-150 group
                              {{ ($salesPage->id === $root->id)
                                  ? 'bg-violet-50 text-violet-700'
                                  : 'text-gray-600 hover:bg-gray-50' }}">
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-xs font-semibold
                                         {{ ($salesPage->id === $root->id)
                                             ? 'text-violet-600' : 'text-gray-500' }}">
                                v1
                            </span>
                            <span class="text-xs">Original</span>
                        </div>
                        <span class="text-[10px] {{ ($salesPage->id === $root->id)
                                                     ? 'text-violet-400'
                                                     : 'text-gray-400' }}">
                            {{ $root->created_at->format('d M') }}
                        </span>
                    </a>
                    @endif

                    {{-- Regenerated versions (v2, v3 …) --}}
                    @foreach($versions as $ver)
                    <a href="{{ route('sales-pages.show', $ver) }}"
                       class="flex items-start justify-between gap-2 px-3 py-2
                              rounded-lg transition-colors duration-150
                              {{ ($salesPage->id === $ver->id)
                                  ? 'bg-violet-50 text-violet-700'
                                  : 'text-gray-600 hover:bg-gray-50' }}">
                        <div class="flex items-start gap-2 min-w-0">
                            <span class="font-mono text-xs font-semibold shrink-0 mt-0.5
                                         {{ ($salesPage->id === $ver->id)
                                             ? 'text-violet-600' : 'text-gray-500' }}">
                                v{{ $ver->version }}
                            </span>
                            @if($ver->feedback)
                            <span class="text-xs truncate leading-tight">
                                {{ Str::limit($ver->feedback, 40) }}
                            </span>
                            @endif
                        </div>
                        <span class="text-[10px] shrink-0 mt-0.5
                                     {{ ($salesPage->id === $ver->id)
                                         ? 'text-violet-400' : 'text-gray-400' }}">
                            {{ $ver->created_at->format('d M') }}
                        </span>
                    </a>
                    @endforeach

                </div>
            </div>
            @endif

            {{-- Quick actions --}}
            <div class="card px-5 py-4 space-y-2">
                <p class="text-[10px] font-semibold uppercase tracking-widest
                           text-gray-400 mb-3">
                    Actions
                </p>
                <a href="{{ route('sales-pages.create') }}"
                   class="btn-secondary w-full btn-sm justify-start gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Generate new page
                </a>
                <button onclick="copyHTML()"
                        class="btn-secondary w-full btn-sm justify-start gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03
                                 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75
                                 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332
                                 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907
                                 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0
                                 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208
                                 48.208 0 011.927-.184"/>
                    </svg>
                    Copy HTML
                </button>
                <a href="{{ route('sales-pages.export', $salesPage) }}"
                   class="btn-secondary w-full btn-sm justify-start gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25
                                 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                    </svg>
                    Download .html
                </a>
                <a href="{{ route('sales-pages.index') }}"
                   class="btn-ghost w-full btn-sm justify-start gap-2 text-gray-500">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/>
                    </svg>
                    Back to My Pages
                </a>
            </div>

        </div>
    </div>
    {{-- /sidebar --}}

</div>
{{-- /grid --}}

{{-- ── Mobile-only bottom bar ─────────────────────────────────────────── --}}
<div class="xl:hidden fixed bottom-0 inset-x-0 z-40 bg-white border-t
            border-gray-200 px-4 py-3 flex items-center gap-2">
    <button onclick="copyHTML()"
            class="btn-secondary btn-sm flex-1 gap-1.5">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
             stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03
                     0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75
                     0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332
                     0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907
                     2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0
                     014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208
                     48.208 0 011.927-.184"/>
        </svg>
        Copy HTML
    </button>
    <a href="{{ route('sales-pages.export', $salesPage) }}"
       class="btn-secondary btn-sm flex-1 gap-1.5">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
             stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25
                     0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
        </svg>
        Download
    </a>
    <a href="#regenerate-section"
       class="btn-primary btn-sm flex-1 gap-1.5">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
             stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0
                     0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0
                     0013.803-3.7M4.031 9.865a8.25 8.25 0
                     0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
        </svg>
        Refine
    </a>
</div>

{{-- ── Delete confirmation modal ──────────────────────────────────────── --}}
<div id="delete-modal"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm"
         onclick="document.getElementById('delete-modal').classList.add('hidden')">
    </div>
    {{-- Dialog --}}
    <div class="relative bg-white rounded-2xl shadow-dropdown max-w-sm w-full p-6
                animate-slide-up">
        <div class="flex items-start gap-4 mb-6">
            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center
                        justify-center shrink-0">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948
                             3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949
                             3.378c-.866-1.5-3.032-1.5-3.898 0L2.697
                             16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900">Delete this page?</h3>
                <p class="text-sm text-gray-500 mt-1">
                    <strong>{{ $salesPage->product_name }}</strong> and all its
                    regenerated versions will be permanently deleted.
                    This can't be undone.
                </p>
            </div>
        </div>
        <div class="flex gap-3">
            <button onclick="document.getElementById('delete-modal').classList.add('hidden')"
                    class="btn-secondary flex-1">
                Cancel
            </button>
            <form method="POST"
                  action="{{ route('sales-pages.destroy', $root ?? $salesPage) }}"
                  class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger w-full">
                    Yes, delete
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ── Copy HTML to clipboard ─────────────────────────────────────────────
function copyHTML() {
    const source = document.getElementById('html-source');
    const html   = source.value;

    if (!html) return;

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(html)
            .then(() => showToast('HTML copied to clipboard!'))
            .catch(() => fallbackCopy(html));
    } else {
        fallbackCopy(html);
    }
}

function fallbackCopy(text) {
    const ta = document.getElementById('html-source');
    ta.removeAttribute('readonly');
    ta.select();
    ta.setSelectionRange(0, 99999);
    try {
        document.execCommand('copy');
        showToast('HTML copied to clipboard!');
    } catch {
        showToast('Copy failed — please copy manually.', true);
    }
    ta.setAttribute('readonly', '');
    window.getSelection()?.removeAllRanges();
}

// ── Toast ──────────────────────────────────────────────────────────────
function showToast(message, isError = false) {
    const toast = document.getElementById('toast');
    const msg   = document.getElementById('toast-msg');

    msg.textContent = message;

    // Show
    toast.classList.remove('translate-y-4', 'opacity-0', 'pointer-events-none');
    toast.classList.add('translate-y-0', 'opacity-100');

    // Update copy button label briefly
    const label = document.getElementById('copy-label');
    if (label && !isError) {
        const prev = label.textContent;
        label.textContent = 'Copied!';
        setTimeout(() => { label.textContent = prev; }, 2000);
    }

    // Hide after 3s
    setTimeout(() => {
        toast.classList.add('translate-y-4', 'opacity-0', 'pointer-events-none');
        toast.classList.remove('translate-y-0', 'opacity-100');
    }, 3000);
}

// ── Close modal on Escape ──────────────────────────────────────────────
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.getElementById('delete-modal').classList.add('hidden');
    }
});
</script>
@endpush
