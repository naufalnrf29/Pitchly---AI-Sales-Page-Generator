@extends('layouts.main')
@section('title', 'My Pages')

@section('topbar-actions')
    <a href="{{ route('sales-pages.create') }}" class="btn-primary btn-sm gap-1.5">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
             stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        New Page
    </a>
@endsection

@section('content')

{{-- ── Page header ──────────────────────────────────────────────────────── --}}
<div class="page-header">
    <div>
        <h1 class="page-title">My Sales Pages</h1>
        <p class="page-subtitle">
            @if($pages->total() > 0)
                {{ $pages->total() }} {{ Str::plural('page', $pages->total()) }} generated
                @if(request('q'))
                    matching <span class="font-medium text-gray-700">"{{ request('q') }}"</span>
                @endif
            @else
                Nothing here yet
            @endif
        </p>
    </div>
</div>

{{-- ── Search bar ───────────────────────────────────────────────────────── --}}
<div x-data="{ query: '{{ request('q', '') }}', loading: false }" class="mb-6">
    <form method="GET" action="{{ route('sales-pages.index') }}"
          @submit="loading = true"
          class="flex items-center gap-2">

        <div class="relative flex-1 max-w-sm">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400
                        pointer-events-none"
                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0
                         0010.607 10.607z"/>
            </svg>
            <input type="text"
                   name="q"
                   x-model="query"
                   placeholder="Search by product name…"
                   class="input pl-9 pr-8 text-sm"
                   autocomplete="off">
            {{-- Clear button --}}
            <button type="button"
                    x-show="query !== ''"
                    x-transition
                    @click="query = ''; $el.closest('form').submit()"
                    class="absolute right-2.5 top-1/2 -translate-y-1/2
                           text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                     stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <button type="submit"
                :class="loading ? 'opacity-60 cursor-wait' : ''"
                class="btn-secondary btn-sm gap-1.5">
            <template x-if="!loading">
                <span>Search</span>
            </template>
            <template x-if="loading">
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    Searching…
                </span>
            </template>
        </button>

        @if(request('q'))
        <a href="{{ route('sales-pages.index') }}"
           class="text-sm text-gray-400 hover:text-gray-600 transition-colors">
            Clear
        </a>
        @endif

    </form>
</div>

{{-- ════════════════════════════════════════════════════════════════════════
     STATE 1 — Empty (no pages at all)
════════════════════════════════════════════════════════════════════════ --}}
@if($pages->total() === 0 && !request('q'))

<div class="flex flex-col items-center justify-center py-24 text-center">
    {{-- Icon --}}
    <div class="relative mb-6">
        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-violet-100 to-indigo-100
                    flex items-center justify-center mx-auto">
            <svg class="w-10 h-10 text-violet-400" fill="none" stroke="currentColor"
                 stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125
                         1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0
                         12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125
                         1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0
                         1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
            </svg>
        </div>
        {{-- Decorative dots --}}
        <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full
                    bg-gradient-to-br from-violet-400 to-indigo-400 opacity-60"></div>
        <div class="absolute -bottom-1 -left-2 w-3 h-3 rounded-full
                    bg-indigo-300 opacity-40"></div>
    </div>

    <h2 class="text-xl font-bold text-gray-900 mb-2">No sales pages yet</h2>
    <p class="text-gray-500 text-sm max-w-xs leading-relaxed mb-8">
        Describe your product and GPT-4o will write a complete,
        styled sales page in about 15 seconds.
    </p>

    <a href="{{ route('sales-pages.create') }}" class="btn-primary btn-lg gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor"
             stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0
                     00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0
                     003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0
                     003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
        </svg>
        Generate your first page
    </a>

    {{-- What you'll get --}}
    <div class="mt-12 grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-lg text-left">
        @foreach([
            ['~15 seconds', 'From form submit to full sales page'],
            ['8 sections', 'Hero, benefits, features, testimonials, pricing'],
            ['Export ready', 'Download as .html or copy to paste anywhere'],
        ] as [$stat, $desc])
        <div class="card px-4 py-3.5">
            <p class="font-bold text-gray-900 text-sm">{{ $stat }}</p>
            <p class="text-xs text-gray-400 mt-0.5 leading-relaxed">{{ $desc }}</p>
        </div>
        @endforeach
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════
     STATE 2 — Search returned no results
════════════════════════════════════════════════════════════════════════ --}}
@elseif($pages->total() === 0 && request('q'))

<div class="flex flex-col items-center justify-center py-20 text-center">
    <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center
                justify-center mx-auto mb-5">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
             stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0
                     0010.607 10.607z"/>
        </svg>
    </div>
    <h2 class="text-lg font-bold text-gray-900 mb-1">No results for "{{ request('q') }}"</h2>
    <p class="text-sm text-gray-400 mb-6">Try a different product name</p>
    <a href="{{ route('sales-pages.index') }}"
       class="btn-secondary btn-sm">
        Clear search
    </a>
</div>

{{-- ════════════════════════════════════════════════════════════════════════
     STATE 3 — Grid of pages
════════════════════════════════════════════════════════════════════════ --}}
@else

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 animate-fade-in">
    @foreach($pages as $page)

    {{-- ── Page card ─────────────────────────────────────────────── --}}
    <div class="card-hover flex flex-col overflow-hidden group"
         x-data="{ confirmDelete: false }">

        {{-- Hero image --}}
        <div class="relative h-40 bg-gradient-to-br from-gray-100 to-gray-200
                    overflow-hidden shrink-0">
            @if($page->hero_image_url)
            <img src="{{ $page->hero_image_url }}"
                 alt="{{ $page->product_name }}"
                 class="w-full h-full object-cover transition-transform
                        duration-500 group-hover:scale-105"
                 loading="lazy">
            @else
            {{-- Fallback gradient --}}
            <div class="w-full h-full bg-gradient-to-br from-violet-100
                        via-indigo-100 to-blue-100 flex items-center justify-center">
                <svg class="w-10 h-10 text-violet-300" fill="none"
                     stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0
                             00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0
                             003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0
                             003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0
                             00-3.09 3.09z"/>
                </svg>
            </div>
            @endif

            {{-- Gradient overlay --}}
            <div class="absolute inset-0 bg-gradient-to-t from-black/50
                        via-black/10 to-transparent"></div>

            {{-- Template badge (overlay on image) --}}
            <div class="absolute top-3 left-3">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full
                             text-[10px] font-semibold backdrop-blur-sm
                             {{ $page->template === 'bold'
                                 ? 'bg-black/60 text-yellow-400 border border-yellow-400/30'
                                 : 'bg-white/80 text-violet-700' }}">
                    {{ ucfirst($page->template) }}
                </span>
            </div>

            {{-- Version count badge (if has regenerations) --}}
            @php $versionCount = $page->versions()->count(); @endphp
            @if($versionCount > 0)
            <div class="absolute top-3 right-3">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full
                             text-[10px] font-semibold bg-black/60 text-white
                             backdrop-blur-sm">
                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor"
                         stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0
                                 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0
                                 0013.803-3.7M4.031 9.865a8.25 8.25 0
                                 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                    </svg>
                    {{ $versionCount }} {{ Str::plural('revision', $versionCount) }}
                </span>
            </div>
            @endif
        </div>

        {{-- Card body --}}
        <div class="flex flex-col flex-1 p-4">

            {{-- Product name + price --}}
            <div class="mb-3">
                <h3 class="font-semibold text-gray-900 text-sm leading-tight
                           line-clamp-2 group-hover:text-violet-700
                           transition-colors duration-150">
                    {{ $page->product_name }}
                </h3>
                <p class="text-xs text-gray-400 mt-1">{{ $page->price }}</p>
            </div>

            {{-- Target audience snippet --}}
            <p class="text-xs text-gray-500 leading-relaxed line-clamp-2 flex-1">
                For {{ $page->target_audience }}
            </p>

            {{-- Divider --}}
            <div class="divider my-3"></div>

            {{-- Footer: date + actions --}}
            <div class="flex items-center justify-between gap-2">

                {{-- Date --}}
                <span class="text-xs text-gray-400 tabular-nums">
                    {{ $page->created_at->format('d M Y') }}
                </span>

                {{-- Actions --}}
                <div class="flex items-center gap-1.5">

                    {{-- Delete confirm inline --}}
                    <div x-show="!confirmDelete">
                        <button @click="confirmDelete = true"
                                title="Delete"
                                class="p-1.5 rounded-lg text-gray-300
                                       hover:text-red-500 hover:bg-red-50
                                       transition-all duration-150">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                 stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107
                                         1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0
                                         01-2.244 2.077H8.084a2.25 2.25 0
                                         01-2.244-2.077L4.772 5.79m14.456 0a48.108
                                         48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114
                                         1.022-.165m0 0a48.11 48.11 0
                                         013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964
                                         51.964 0 00-3.32 0c-1.18.037-2.09
                                         1.022-2.09 2.201v.916m7.5 0a48.667
                                         48.667 0 00-7.5 0"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Confirm state --}}
                    <div x-show="confirmDelete"
                         x-transition
                         class="flex items-center gap-1">
                        <span class="text-[10px] text-red-500 font-medium">Delete?</span>
                        <form method="POST"
                              action="{{ route('sales-pages.destroy', $page) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-2 py-1 rounded-md text-[10px] font-semibold
                                           bg-red-500 text-white hover:bg-red-600
                                           transition-colors duration-150">
                                Yes
                            </button>
                        </form>
                        <button @click="confirmDelete = false"
                                class="px-2 py-1 rounded-md text-[10px] font-medium
                                       text-gray-500 hover:bg-gray-100
                                       transition-colors duration-150">
                            No
                        </button>
                    </div>

                    {{-- View button --}}
                    <a href="{{ route('sales-pages.show', $page) }}"
                       class="btn-primary btn-sm gap-1">
                        View
                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                             stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>

                </div>
            </div>
        </div>
    </div>
    {{-- /card --}}

    @endforeach
</div>

{{-- ── Pagination ───────────────────────────────────────────────────── --}}
@if($pages->hasPages())
<div class="mt-8 flex justify-center">
    <nav class="flex items-center gap-1" aria-label="Pagination">

        {{-- Previous --}}
        @if($pages->onFirstPage())
        <span class="px-3 py-2 rounded-xl text-sm text-gray-300 cursor-not-allowed">
            ← Prev
        </span>
        @else
        <a href="{{ $pages->previousPageUrl() }}"
           class="px-3 py-2 rounded-xl text-sm text-gray-600 hover:bg-white
                  hover:text-gray-900 transition-all duration-150 border
                  border-transparent hover:border-gray-200 hover:shadow-sm">
            ← Prev
        </a>
        @endif

        {{-- Page numbers --}}
        @foreach($pages->getUrlRange(
            max(1, $pages->currentPage() - 2),
            min($pages->lastPage(), $pages->currentPage() + 2)
        ) as $pageNum => $url)
        @if($pageNum === $pages->currentPage())
        <span class="px-3.5 py-2 rounded-xl text-sm font-semibold text-white
                     bg-gradient-to-r from-violet-600 to-indigo-600 shadow-sm">
            {{ $pageNum }}
        </span>
        @else
        <a href="{{ $url }}"
           class="px-3.5 py-2 rounded-xl text-sm text-gray-600 hover:bg-white
                  hover:text-gray-900 transition-all duration-150 border
                  border-transparent hover:border-gray-200 hover:shadow-sm">
            {{ $pageNum }}
        </a>
        @endif
        @endforeach

        {{-- Next --}}
        @if($pages->hasMorePages())
        <a href="{{ $pages->nextPageUrl() }}"
           class="px-3 py-2 rounded-xl text-sm text-gray-600 hover:bg-white
                  hover:text-gray-900 transition-all duration-150 border
                  border-transparent hover:border-gray-200 hover:shadow-sm">
            Next →
        </a>
        @else
        <span class="px-3 py-2 rounded-xl text-sm text-gray-300 cursor-not-allowed">
            Next →
        </span>
        @endif

    </nav>
</div>
<p class="text-center text-xs text-gray-400 mt-3">
    Showing {{ $pages->firstItem() }}–{{ $pages->lastItem() }}
    of {{ $pages->total() }} {{ Str::plural('page', $pages->total()) }}
</p>
@endif

@endif
{{-- /states --}}

@endsection
