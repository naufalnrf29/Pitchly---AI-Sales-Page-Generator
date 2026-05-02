@extends('layouts.main')
@section('title', 'New Sales Page')

@section('breadcrumb')
    <a href="{{ route('sales-pages.index') }}"
       class="text-gray-400 hover:text-gray-600 transition-colors">My Pages</a>
    <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor"
         stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="text-gray-700 font-medium">New Sales Page</span>
@endsection

@section('content')

{{-- ── Alpine root — manages all form interactivity ─────────────────────── --}}
<div x-data="{
    productName:    '{{ old('product_name', '') }}',
    description:    '{{ old('description', '') }}',
    features:       '{{ old('features', '') }}',
    targetAudience: '{{ old('target_audience', '') }}',
    price:          '{{ old('price', '') }}',
    usp:            '{{ old('unique_selling_point', '') }}',
    template:       '{{ old('template', 'modern') }}',
    loading:        false,

    overlayVisible: false,
    overlayStatus:  'loading',
    statusText:     'Writing compelling headlines...',
    progress:       0,
    errorMessage:   '',
    _salesPageId:   null,
    _timers:        [],

    get descLen()   { return this.description.length },
    get featLen()   { return this.features.length },
    get uspLen()    { return this.usp.length },
    get canSubmit() {
        return this.productName.trim() !== ''
            && this.description.trim() !== ''
            && this.features.trim() !== ''
            && this.targetAudience.trim() !== ''
            && this.price.trim() !== ''
            && this.usp.trim() !== ''
            && !this.loading
    },

    async submitAsync(form) {
        if (!this.canSubmit) return
        this.loading        = true
        this.overlayVisible = true
        this.overlayStatus  = 'loading'
        this.progress       = 0
        this.statusText     = 'Writing compelling headlines...'
        this._timers        = []

        // Progress: increments 0.6% every 500 ms → reaches ~90% in ~75 s
        this._timers.push(setInterval(() => {
            if (this.progress < 90)
                this.progress = parseFloat(Math.min(90, this.progress + 0.6).toFixed(1))
        }, 500))

        // Sub-text rotation based on elapsed seconds
        const messages = [
            { after: 0,  text: 'Writing compelling headlines...' },
            { after: 5,  text: 'Crafting persuasive copy...' },
            { after: 10, text: 'Designing the layout...' },
            { after: 20, text: 'Building pricing section...' },
            { after: 35, text: 'Adding final touches...' },
            { after: 60, text: 'Almost there...' },
        ]
        let elapsed = 0
        this._timers.push(setInterval(() => {
            elapsed++
            if (this.overlayStatus !== 'loading') return
            const cur = messages.filter(m => m.after <= elapsed).pop()
            if (cur) this.statusText = cur.text
        }, 1000))

        try {
            const fd  = new FormData(form)
            const res = await fetch(form.action, {
                method:  'POST',
                headers: {
                    'Accept':       'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                },
                body: fd,
            })

            if (!res.ok) {
                const data = await res.json().catch(() => ({}))
                throw new Error(data.message || 'Submission failed. Please try again.')
            }

            const data = await res.json()
            this._salesPageId = data.salesPageId

            // Poll every 3 seconds for completion
            this._timers.push(setInterval(async () => {
                try {
                    const sr   = await fetch('/sales-pages/' + this._salesPageId + '/status',
                                            { headers: { 'Accept': 'application/json' } })
                    const info = await sr.json()

                    if (info.status === 'completed') {
                        this._clearTimers()
                        this.progress      = 100
                        this.statusText    = 'Your page is ready!'
                        this.overlayStatus = 'completed'
                        setTimeout(() => { window.location.href = info.redirect_url }, 1200)
                    } else if (info.status === 'failed') {
                        this._clearTimers()
                        this._showError(info.error_message || 'Generation failed. Please try again.')
                    }
                } catch (e) { /* network blip — keep polling */ }
            }, 3000))

        } catch (err) {
            this._showError(err.message)
        }
    },

    _clearTimers() {
        this._timers.forEach(t => clearInterval(t))
        this._timers = []
    },
    _showError(message) {
        this._clearTimers()
        this.overlayStatus = 'failed'
        this.errorMessage  = message || 'An unexpected error occurred.'
    },
    _resetOverlay() {
        this._clearTimers()
        this.overlayVisible = false
        this.loading        = false
        this._salesPageId   = null
        this.progress       = 0
        this.errorMessage   = ''
        this.overlayStatus  = 'loading'
        this.statusText     = 'Writing compelling headlines...'
    },
}">

<form method="POST" action="{{ route('sales-pages.store') }}"
      @submit.prevent="submitAsync($el)">
    @csrf

    {{-- ── Page header ─────────────────────────────────────────────── --}}
    <div class="page-header mb-6">
        <div>
            <h1 class="page-title">Generate a Sales Page</h1>
            <p class="page-subtitle">
                Fill in your product details — GPT-4o handles the rest.
            </p>
        </div>
    </div>

    {{-- ── Two-column layout ────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 xl:grid-cols-[1fr_320px] gap-6 items-start">

        {{-- ════════════════════════════════════════════════════════════
             LEFT — Main form
        ════════════════════════════════════════════════════════════ --}}
        <div class="space-y-5">

            {{-- ── Section 1: Product identity ───────────────────── --}}
            <div class="card px-6 py-5">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 rounded-lg bg-violet-50 flex items-center
                                justify-center shrink-0">
                        <svg class="w-4 h-4 text-violet-600" fill="none"
                             stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993
                                     l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125
                                     1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0
                                     015.513 7.5h12.974c.576 0 1.059.435
                                     1.119 1.007zM8.625 10.5a.375.375 0
                                     11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375
                                     0 11-.75 0 .375.375 0 01.75 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900">Product Identity</h2>
                        <p class="text-xs text-gray-400 mt-0.5">The basics — name and price</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Product name --}}
                    <div class="sm:col-span-2">
                        <label for="product_name" class="label">
                            Product / Service name
                            <span class="text-red-400 ml-0.5">*</span>
                        </label>
                        <input id="product_name"
                               name="product_name"
                               type="text"
                               x-model="productName"
                               maxlength="120"
                               placeholder="e.g. Notion for Developers, SaaS Dashboard Pro"
                               class="input @error('product_name') input-error @enderror"
                               required>
                        @error('product_name')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1.5 text-xs text-gray-400">
                            Exact name, no taglines — those come from the AI.
                        </p>
                    </div>

                    {{-- Price --}}
                    <div class="sm:col-span-2">
                        <label for="price" class="label">
                            Price / Pricing model
                            <span class="text-red-400 ml-0.5">*</span>
                        </label>
                        <input id="price"
                               name="price"
                               type="text"
                               x-model="price"
                               maxlength="80"
                               placeholder="e.g. Rp 299.000/mo · Free trial 14 days"
                               class="input @error('price') input-error @enderror"
                               required>
                        @error('price')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1.5 text-xs text-gray-400">
                            Include currency, billing cycle, or any free tier info.
                        </p>
                    </div>

                </div>
            </div>

            {{-- ── Section 2: Product details ─────────────────────── --}}
            <div class="card px-6 py-5">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center
                                justify-center shrink-0">
                        <svg class="w-4 h-4 text-indigo-600" fill="none"
                             stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125
                                     1.125 0 0113.5 7.125v-1.5a3.375 3.375 0
                                     00-3.375-3.375H8.25m0 12.75h7.5m-7.5
                                     3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125
                                     1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621
                                     0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900">Product Details</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Description and key features</p>
                    </div>
                </div>

                <div class="space-y-4">

                    {{-- Description --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="description" class="label mb-0">
                                Description
                                <span class="text-red-400 ml-0.5">*</span>
                            </label>
                            <span class="text-xs tabular-nums"
                                  :class="descLen > 900 ? 'text-amber-500 font-medium' : 'text-gray-400'">
                                <span x-text="descLen"></span>/1000
                            </span>
                        </div>
                        <textarea id="description"
                                  name="description"
                                  x-model="description"
                                  maxlength="1000"
                                  rows="4"
                                  placeholder="Explain what the product does and the problem it solves. Write like you're explaining to a smart friend — no jargon."
                                  class="input resize-none @error('description') input-error @enderror"
                                  required></textarea>
                        @error('description')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1.5 text-xs text-gray-400">
                            The more specific, the better the output. Avoid vague phrases
                            like "all-in-one solution".
                        </p>
                    </div>

                    {{-- Features --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="features" class="label mb-0">
                                Key features
                                <span class="text-red-400 ml-0.5">*</span>
                            </label>
                            <span class="text-xs tabular-nums"
                                  :class="featLen > 450 ? 'text-amber-500 font-medium' : 'text-gray-400'">
                                <span x-text="featLen"></span>/500
                            </span>
                        </div>
                        <textarea id="features"
                                  name="features"
                                  x-model="features"
                                  maxlength="500"
                                  rows="3"
                                  placeholder="Real-time collaboration, Built-in version control, One-click deploy, 99.9% uptime SLA"
                                  class="input resize-none font-mono text-sm
                                         @error('features') input-error @enderror"
                                  required></textarea>
                        @error('features')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1.5 text-xs text-gray-400">
                            Separate with commas. Be specific — "2× faster builds"
                            beats "fast performance".
                        </p>

                        {{-- Live feature preview --}}
                        <div x-show="features.trim() !== ''"
                             x-transition
                             class="mt-3 flex flex-wrap gap-1.5">
                            <template x-for="feat in features.split(',').filter(f => f.trim() !== '')"
                                      :key="feat">
                                <span class="badge-violet text-xs py-1 px-2.5"
                                      x-text="feat.trim()">
                                </span>
                            </template>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── Section 3: Audience & positioning ──────────────── --}}
            <div class="card px-6 py-5">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center
                                justify-center shrink-0">
                        <svg class="w-4 h-4 text-emerald-600" fill="none"
                             stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0
                                     00-4.682-2.72m.94 3.198l.001.031c0
                                     .225-.012.447-.037.666A11.944 11.944
                                     0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062
                                     6.062 0 016 18.719m12 0a5.971 5.971 0
                                     00-.941-3.197m0 0A5.995 5.995 0 0012
                                     12.75a5.995 5.995 0 00-5.058 2.772m0
                                     0a3 3 0 00-4.681 2.72 8.986 8.986 0
                                     003.74.477m.94-3.197a5.971 5.971 0
                                     00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3
                                     0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25
                                     2.25 0 014.5 0zm-13.5 0a2.25 2.25 0
                                     11-4.5 0 2.25 2.25 0 014.5 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900">Audience & Positioning</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Who this is for and why it's different</p>
                    </div>
                </div>

                <div class="space-y-4">

                    {{-- Target audience --}}
                    <div>
                        <label for="target_audience" class="label">
                            Target audience
                            <span class="text-red-400 ml-0.5">*</span>
                        </label>
                        <input id="target_audience"
                               name="target_audience"
                               type="text"
                               x-model="targetAudience"
                               maxlength="200"
                               placeholder="e.g. Freelance designers who manage multiple clients"
                               class="input @error('target_audience') input-error @enderror"
                               required>
                        @error('target_audience')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1.5 text-xs text-gray-400">
                            The more specific the person, the sharper the copy.
                            Avoid "everyone" or "SMBs".
                        </p>
                    </div>

                    {{-- USP --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="unique_selling_point" class="label mb-0">
                                Unique selling point (USP)
                                <span class="text-red-400 ml-0.5">*</span>
                            </label>
                            <span class="text-xs tabular-nums"
                                  :class="uspLen > 270 ? 'text-amber-500 font-medium' : 'text-gray-400'">
                                <span x-text="uspLen"></span>/300
                            </span>
                        </div>
                        <textarea id="unique_selling_point"
                                  name="unique_selling_point"
                                  x-model="usp"
                                  maxlength="300"
                                  rows="3"
                                  placeholder="The one thing that makes this different from every competitor. e.g. 'The only tool that auto-generates reports directly from Slack conversations — no integrations needed.'"
                                  class="input resize-none @error('unique_selling_point') input-error @enderror"
                                  required></textarea>
                        @error('unique_selling_point')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1.5 text-xs text-gray-400">
                            Complete the sentence: <em>"Unlike [competitor], we…"</em>
                        </p>
                    </div>

                </div>
            </div>

            {{-- ── Section 4: Template selector ───────────────────── --}}
            <div class="card px-6 py-5">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 rounded-lg bg-pink-50 flex items-center
                                justify-center shrink-0">
                        <svg class="w-4 h-4 text-pink-600" fill="none"
                             stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0
                                     01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0
                                     0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0
                                     011.622-3.395m3.42 3.42a15.995 15.995 0
                                     004.764-4.648l3.876-5.814a1.151 1.151 0
                                     00-1.597-1.597L14.146 6.32a15.996 15.996 0
                                     00-4.649 4.763m3.42 3.42a6.776 6.776 0
                                     00-3.42-3.42"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900">Design Template</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Visual style for your sales page</p>
                    </div>
                </div>

                <input type="hidden" name="template" :value="template">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                    {{-- Modern template --}}
                    <button type="button"
                            @click="template = 'modern'"
                            :class="template === 'modern'
                                ? 'ring-2 ring-violet-500 border-violet-200 bg-violet-50/50'
                                : 'border-gray-200 hover:border-gray-300 bg-white hover:bg-gray-50'"
                            class="relative text-left rounded-xl border-2 p-4
                                   transition-all duration-150 focus:outline-none
                                   focus:ring-2 focus:ring-violet-500">

                        {{-- Mini preview --}}
                        <div class="rounded-lg overflow-hidden border border-gray-200
                                    mb-3 bg-white h-20 relative">
                            <div class="h-7 bg-gradient-to-r from-violet-600 to-indigo-600
                                        flex items-center px-2 gap-1">
                                <div class="w-8 h-1.5 bg-white/70 rounded-full"></div>
                            </div>
                            <div class="p-2 space-y-1">
                                <div class="w-4/5 h-1.5 bg-gray-200 rounded-full"></div>
                                <div class="w-3/5 h-1.5 bg-gray-100 rounded-full"></div>
                                <div class="flex gap-1 mt-2">
                                    <div class="w-10 h-3 bg-violet-500 rounded-full"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Check --}}
                        <div x-show="template === 'modern'"
                             class="absolute top-3 right-3 w-5 h-5 rounded-full
                                    bg-violet-600 flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none"
                                 stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                        </div>

                        <p class="text-sm font-semibold text-gray-900">Modern</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Clean, gradient accents, white space heavy.
                            Works for SaaS, apps, digital products.
                        </p>
                    </button>

                    {{-- Bold template --}}
                    <button type="button"
                            @click="template = 'bold'"
                            :class="template === 'bold'
                                ? 'ring-2 ring-violet-500 border-violet-200 bg-violet-50/50'
                                : 'border-gray-200 hover:border-gray-300 bg-white hover:bg-gray-50'"
                            class="relative text-left rounded-xl border-2 p-4
                                   transition-all duration-150 focus:outline-none
                                   focus:ring-2 focus:ring-violet-500">

                        {{-- Mini preview --}}
                        <div class="rounded-lg overflow-hidden border border-gray-200
                                    mb-3 bg-black h-20 relative">
                            <div class="h-7 bg-black flex items-center px-2 justify-between">
                                <div class="w-6 h-1.5 bg-white rounded-full"></div>
                                <div class="w-8 h-2 bg-yellow-400 rounded-sm text-[5px]
                                            font-bold text-black flex items-center
                                            justify-center">GO</div>
                            </div>
                            <div class="p-2 space-y-1">
                                <div class="w-4/5 h-2 bg-white rounded-full"></div>
                                <div class="w-3/5 h-1.5 bg-gray-600 rounded-full"></div>
                                <div class="flex gap-1 mt-1">
                                    <div class="w-10 h-3 bg-yellow-400 rounded-sm"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Check --}}
                        <div x-show="template === 'bold'"
                             class="absolute top-3 right-3 w-5 h-5 rounded-full
                                    bg-violet-600 flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none"
                                 stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                        </div>

                        <p class="text-sm font-semibold text-gray-900">Bold</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Dark background, high contrast, punchy.
                            Works for courses, coaching, events.
                        </p>
                    </button>

                </div>
            </div>

            {{-- ── Submit button ───────────────────────────────────── --}}
            <div class="flex items-center justify-between gap-4 pt-1 pb-4">
                <a href="{{ route('sales-pages.index') }}"
                   class="btn-secondary">
                    Cancel
                </a>

                <button type="submit"
                        @click="submitAsync($el.closest('form'))"
                        :disabled="!canSubmit"
                        :class="canSubmit ? 'opacity-100' : 'opacity-40 cursor-not-allowed'"
                        class="btn-primary btn-lg gap-3 min-w-[200px]">

                    {{-- Idle state --}}
                    <template x-if="!loading">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                 stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0
                                         00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0
                                         003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0
                                         003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0
                                         00-3.09 3.09z"/>
                            </svg>
                            Generate Sales Page
                        </span>
                    </template>

                    {{-- Loading state --}}
                    <template x-if="loading">
                        <span class="flex items-center gap-2.5">
                            <svg class="w-5 h-5 animate-spin" fill="none"
                                 viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Generating… this may take up to 90 seconds. Please don't close this tab.
                        </span>
                    </template>

                </button>
            </div>

        </div>
        {{-- /left column --}}

        {{-- ════════════════════════════════════════════════════════════
             RIGHT — Sticky tips panel
        ════════════════════════════════════════════════════════════ --}}
        <div class="hidden xl:block">
            <div class="sticky top-24 space-y-4">

                {{-- Progress indicator --}}
                <div class="card px-5 py-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase
                               tracking-wider mb-3">
                        Form progress
                    </p>
                    <div class="space-y-2.5">
                        @foreach([
                            ['productName',    'Product name'],
                            ['price',          'Price'],
                            ['description',    'Description'],
                            ['features',       'Key features'],
                            ['targetAudience', 'Target audience'],
                            ['usp',            'Unique selling point'],
                        ] as [$field, $label])
                        <div class="flex items-center gap-2.5">
                            <div class="w-4 h-4 rounded-full shrink-0 flex items-center
                                        justify-center transition-all duration-200"
                                 :class="{{ $field }}.trim() !== ''
                                    ? 'bg-emerald-100'
                                    : 'bg-gray-100'">
                                <template x-if="{{ $field }}.trim() !== ''">
                                    <svg class="w-2.5 h-2.5 text-emerald-600"
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1
                                                 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8
                                                 12.586l7.293-7.293a1 1 0 011.414 0z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </template>
                                <template x-if="{{ $field }}.trim() === ''">
                                    <div class="w-1.5 h-1.5 rounded-full bg-gray-300"></div>
                                </template>
                            </div>
                            <span class="text-xs transition-colors duration-150"
                                  :class="{{ $field }}.trim() !== ''
                                    ? 'text-gray-700 font-medium'
                                    : 'text-gray-400'">
                                {{ $label }}
                            </span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Progress bar --}}
                    <div class="mt-4">
                        <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-violet-500 to-indigo-500
                                        rounded-full transition-all duration-300"
                                 :style="'width:' + (
                                    [productName, price, description,
                                     features, targetAudience, usp]
                                    .filter(v => v.trim() !== '').length / 6 * 100
                                 ) + '%'">
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-1.5 tabular-nums">
                            <span x-text="[productName, price, description,
                                           features, targetAudience, usp]
                                          .filter(v => v.trim() !== '').length">
                            </span>
                            of 6 fields filled
                        </p>
                    </div>
                </div>

                {{-- Tips --}}
                <div class="card px-5 py-4">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-amber-500" fill="none"
                             stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01
                                     6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0
                                     01-4.5 0m3.75 2.383a14.406 14.406 0
                                     01-3 0M14.25 18v-.192c0-.983.658-1.823
                                     1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493
                                     1.509 1.333 1.509 2.316V18"/>
                        </svg>
                        <p class="text-xs font-semibold text-gray-700">Tips for better output</p>
                    </div>
                    <ul class="space-y-2.5">
                        @foreach([
                            'Be specific about numbers — "saves 3 hours/week" beats "saves time"',
                            'Name the exact problem your product solves, not just what it does',
                            'For USP, think: why would someone switch from what they use today?',
                            'Features become section headers in the page — keep them short',
                            'Audience specificity → copy specificity → higher conversion',
                        ] as $tip)
                        <li class="flex items-start gap-2">
                            <span class="w-1 h-1 rounded-full bg-amber-400 mt-1.5 shrink-0"></span>
                            <span class="text-xs text-gray-500 leading-relaxed">{{ $tip }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- What AI generates --}}
                <div class="card px-5 py-4 bg-gradient-to-br from-violet-50 to-indigo-50
                            border-violet-100">
                    <p class="text-xs font-semibold text-violet-700 mb-3">
                        What GPT-4o will generate
                    </p>
                    <ul class="space-y-1.5">
                        @foreach([
                            'Hero headline + sub-headline',
                            'Product description (prose, not bullets)',
                            'Benefits breakdown with icons',
                            'Features deep-dive section',
                            '3 testimonial placeholders',
                            'Pricing display with CTA',
                            'Auto hero image from Unsplash',
                        ] as $item)
                        <li class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-violet-500 shrink-0"
                                 fill="none" stroke="currentColor"
                                 stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                            <span class="text-xs text-violet-700">{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
        {{-- /right column --}}

    </div>
    {{-- /grid --}}

</form>

{{-- ══════════════════════════════════════════════════════════════════════
     FULLSCREEN GENERATION OVERLAY
══════════════════════════════════════════════════════════════════════ --}}
<div x-show="overlayVisible"
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center"
     style="background: rgba(0,0,0,0.75); backdrop-filter: blur(12px);">

    {{-- ── Loading / Completed card ──────────────────────────────────── --}}
    <div x-show="overlayStatus !== 'failed'"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="max-w-sm w-full mx-4 bg-white shadow-2xl text-center"
         style="padding: 3.5rem 2.5rem; border-radius: 1.5rem;">

        {{-- 1. Logo Pitchly --}}
        <div class="flex items-center justify-center gap-2.5" style="margin-bottom: 2rem;">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-violet-600 to-indigo-600
                        flex items-center justify-center shadow-sm shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                     stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0
                             00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0
                             003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0
                             003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0
                             00-3.09 3.09z"/>
                </svg>
            </div>
            <span class="text-lg font-bold text-gray-800">Pitchly</span>
        </div>

        {{-- 2. Spinner — spins while loading, solid ring when completed --}}
        <div class="w-12 h-12 rounded-full border-4 mx-auto transition-all duration-300"
             style="margin-bottom: 1.5rem;"
             :class="overlayStatus === 'completed'
                 ? 'border-emerald-400'
                 : 'border-violet-100 border-t-violet-600 animate-spin'">
        </div>

        {{-- 3. Headline --}}
        <h2 class="text-base font-bold text-gray-900"
            style="margin-bottom: 0.5rem;"
            x-text="overlayStatus === 'completed'
                ? 'Your page is ready!'
                : 'Generating your page...'">
        </h2>

        {{-- 4. Rotating sub-text --}}
        <p class="text-sm text-gray-400"
           style="margin-bottom: 2rem;"
           x-text="statusText"></p>

        {{-- 5. Progress bar --}}
        <div class="w-full bg-gray-100 rounded-full overflow-hidden"
             style="height: 6px; margin-bottom: 1rem;">
            <div class="h-full rounded-full transition-all duration-500"
                 :class="overlayStatus === 'completed'
                     ? 'bg-emerald-400'
                     : 'bg-gradient-to-r from-violet-600 to-indigo-600'"
                 :style="'width:' + progress + '%'">
            </div>
        </div>

        {{-- 6. Note --}}
        <p class="text-xs text-gray-300">
            <span x-show="overlayStatus === 'loading'">Up to 90 seconds</span>
            <span x-show="overlayStatus === 'completed'" class="text-emerald-500">Redirecting…</span>
        </p>
    </div>
    {{-- /loading card --}}

    {{-- ── Error card ────────────────────────────────────────────────── --}}
    <div x-show="overlayStatus === 'failed'"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="max-w-sm w-full mx-4 bg-white shadow-2xl text-center"
         style="padding: 3.5rem 2.5rem; border-radius: 1.5rem;">

        <div class="w-12 h-12 mx-auto rounded-full bg-red-50
                    flex items-center justify-center"
             style="margin-bottom: 1.5rem;">
            <svg class="w-6 h-6 text-red-500" fill="none"
                 stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374
                         1.948 3.374h14.71c1.73 0 2.813-1.874
                         1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898
                         0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
            </svg>
        </div>

        <h2 class="text-base font-bold text-gray-900" style="margin-bottom: 0.5rem;">
            Generation failed
        </h2>
        <p class="text-sm text-gray-400" style="margin-bottom: 1.5rem;" x-text="errorMessage"></p>

        <button type="button"
                @click="_resetOverlay()"
                class="btn-primary">
            Try Again
        </button>
    </div>
    {{-- /error card --}}

</div>
{{-- /overlay --}}

</div>
{{-- /alpine root --}}

@endsection
