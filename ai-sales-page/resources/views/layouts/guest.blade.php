<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="alternate icon" href="/favicon.ico">

    <title>{{ config('app.name', 'Pitchly') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">

<div class="min-h-screen flex">

    {{-- ── Left panel — branding (hidden on mobile) ─────────────────── --}}
    <div class="hidden lg:flex lg:w-[45%] xl:w-[40%] relative overflow-hidden
                bg-gradient-to-br from-violet-600 via-indigo-600 to-indigo-700
                flex-col justify-between p-12">

        {{-- Decorative circles --}}
        <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full
                    bg-white/5 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 rounded-full
                    bg-indigo-800/40 blur-2xl pointer-events-none"></div>

        {{-- Logo --}}
        <div class="relative flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center
                        justify-center backdrop-blur-sm">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                     stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0
                             00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0
                             003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0
                             003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0
                             00-3.09 3.09z"/>
                </svg>
            </div>
            <span class="font-bold text-white text-lg tracking-tight">Pitchly</span>
        </div>

        {{-- Headline --}}
        <div class="relative space-y-6">
            <div class="space-y-3">
                <h1 class="text-4xl font-bold text-white leading-tight text-balance">
                    Sales pages that<br>actually convert.
                </h1>
                <p class="text-indigo-200 text-lg leading-relaxed max-w-sm">
                    Write your product details. Get a complete, professional
                    sales page in under 30 seconds.
                </p>
            </div>

            {{-- Feature list --}}
            <ul class="space-y-3">
                @foreach([
                    ['GPT-4o writes copy that sounds human', 'M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z'],
                    ['Hero images pulled from Unsplash automatically', 'M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z'],
                    ['Export as standalone HTML — paste anywhere', 'M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3'],
                ] as [$text, $path])
                <li class="flex items-start gap-3">
                    <div class="w-5 h-5 rounded-full bg-white/15 flex items-center
                                justify-center shrink-0 mt-0.5">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                             stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                    </div>
                    <span class="text-indigo-100 text-sm leading-relaxed">{{ $text }}</span>
                </li>
                @endforeach
            </ul>
        </div>

        {{-- Bottom quote --}}
        <div class="relative">
            <p class="text-indigo-300 text-xs">
                © {{ date('Y') }} Pitchly — GPT-4o powered
            </p>
        </div>
    </div>

    {{-- ── Right panel — auth form ────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col justify-center items-center
                px-6 py-12 sm:px-12 bg-[#f7f8fa]">

        {{-- Mobile logo --}}
        <div class="lg:hidden mb-8 flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-violet-600 to-indigo-500
                        flex items-center justify-center shadow-sm">
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
            <span class="font-bold text-gray-900 text-lg">Pitchly</span>
        </div>

        {{-- Form card --}}
        <div class="w-full max-w-sm">
            {{ $slot }}
        </div>

    </div>
</div>

</body>
</html>
