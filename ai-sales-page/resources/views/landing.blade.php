<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="alternate icon" href="/favicon.ico">
    <title>Pitchly — AI-Powered Sales Pages in 90 Seconds</title>
    <meta name="description" content="Generate high-converting sales pages instantly with GPT-4o. Fill in your product details, and Pitchly writes the copy, designs the layout, and delivers ready-to-publish HTML.">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        violet: {
                            50:  '#f5f3ff', 100: '#ede9fe', 200: '#ddd6fe',
                            300: '#c4b5fd', 400: '#a78bfa', 500: '#8b5cf6',
                            600: '#7c3aed', 700: '#6d28d9', 800: '#5b21b6',
                            900: '#4c1d95', 950: '#2e1065',
                        },
                        indigo: {
                            50:  '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe',
                            300: '#a5b4fc', 400: '#818cf8', 500: '#6366f1',
                            600: '#4f46e5', 700: '#4338ca', 800: '#3730a3',
                            900: '#312e81', 950: '#1e1b4b',
                        },
                    },
                    animation: {
                        'fade-up': 'fadeUp 0.5s ease-out forwards',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                    },
                    boxShadow: {
                        'card': '0 1px 3px 0 rgb(0 0 0 / 0.05), 0 1px 2px -1px rgb(0 0 0 / 0.05)',
                        'card-lg': '0 4px 24px -2px rgb(0 0 0 / 0.08), 0 2px 8px -2px rgb(0 0 0 / 0.06)',
                        'violet': '0 8px 32px -4px rgb(124 58 237 / 0.35)',
                        'glow': '0 0 0 1px rgb(124 58 237 / 0.15), 0 8px 32px -4px rgb(124 58 237 / 0.25)',
                    },
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased; }
        .gradient-text {
            background: linear-gradient(135deg, #a78bfa 0%, #818cf8 50%, #60a5fa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-bg {
            background: linear-gradient(135deg, #0f0a2a 0%, #1a1145 40%, #130d35 70%, #0f0a2a 100%);
        }
        .hero-grid {
            background-image: radial-gradient(circle, rgba(139,92,246,0.07) 1px, transparent 1px);
            background-size: 36px 36px;
        }
        .mockup-float {
            animation: mockupFloat 7s ease-in-out infinite;
        }
        @keyframes mockupFloat {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-12px); }
        }
        .float-card-1 { animation: floatSmall 8s ease-in-out infinite; animation-delay: 0.5s; }
        .float-card-2 { animation: floatSmall 9s ease-in-out infinite; animation-delay: 1.5s; }
        .float-badge  { animation: floatSmall 7s ease-in-out infinite; animation-delay: 1s; }
        @keyframes floatSmall {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-8px); }
        }
        .card-glass {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            backdrop-filter: blur(12px);
        }
        .pricing-popular {
            background: linear-gradient(white, white) padding-box,
                        linear-gradient(135deg, #7c3aed, #4f46e5) border-box;
            border: 2px solid transparent;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-white text-gray-900 antialiased"
      x-data="{ mobileOpen: false, scrolled: false }"
      @scroll.window="scrolled = window.scrollY > 100">

{{-- ════════════════════════════════════════════════════════════════════════
     1. NAVBAR
════════════════════════════════════════════════════════════════════════ --}}
<header class="fixed top-0 inset-x-0 z-50 transition-all duration-300"
        :class="scrolled
            ? 'bg-white/95 backdrop-blur-md border-b border-gray-200 shadow-sm'
            : 'bg-transparent border-b border-transparent'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2.5 shrink-0">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-violet-600 to-indigo-600
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
                <span class="font-bold text-sm tracking-tight transition-colors duration-300"
                      :class="scrolled ? 'text-gray-900' : 'text-white'">Pitchly</span>
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden md:flex items-center gap-1">
                <a href="#features"
                   class="px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300"
                   :class="scrolled ? 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' : 'text-white/80 hover:text-white hover:bg-white/10'">Features</a>
                <a href="#how-it-works"
                   class="px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300"
                   :class="scrolled ? 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' : 'text-white/80 hover:text-white hover:bg-white/10'">How it Works</a>
                <a href="#pricing"
                   class="px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300"
                   :class="scrolled ? 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' : 'text-white/80 hover:text-white hover:bg-white/10'">Pricing</a>
                <a href="#faq"
                   class="px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300"
                   :class="scrolled ? 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' : 'text-white/80 hover:text-white hover:bg-white/10'">FAQ</a>
            </nav>

            {{-- Desktop CTA --}}
            <div class="hidden md:flex items-center gap-2">
                <a href="{{ route('login') }}"
                   class="px-4 py-2 text-sm font-semibold rounded-xl border transition-all duration-300"
                   :class="scrolled
                       ? 'text-gray-700 border-gray-200 hover:bg-gray-50 hover:border-gray-300'
                       : 'text-white border-white/25 hover:bg-white/10 hover:border-white/40'">
                    Sign In
                </a>
                <a href="{{ route('register') }}"
                   class="px-4 py-2 text-sm font-semibold text-white rounded-xl
                          bg-gradient-to-r from-violet-600 to-indigo-600
                          hover:from-violet-700 hover:to-indigo-700
                          shadow-sm hover:shadow-violet transition-all">
                    Get Started Free
                </a>
            </div>

            {{-- Mobile hamburger --}}
            <button @click="mobileOpen = !mobileOpen"
                    class="md:hidden p-2 rounded-xl transition-all duration-300"
                    :class="scrolled ? 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' : 'text-white/80 hover:text-white hover:bg-white/10'">
                <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
                <svg x-show="mobileOpen" x-cloak class="w-5 h-5" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

        </div>
    </div>

    {{-- Mobile menu --}}
    <div x-show="mobileOpen" x-cloak
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden border-t border-gray-100 bg-white">
        <div class="px-4 py-4 space-y-1">
            <a href="#features" @click="mobileOpen = false"
               class="block px-3 py-2.5 text-sm font-medium text-gray-700 rounded-xl hover:bg-gray-50">Features</a>
            <a href="#how-it-works" @click="mobileOpen = false"
               class="block px-3 py-2.5 text-sm font-medium text-gray-700 rounded-xl hover:bg-gray-50">How it Works</a>
            <a href="#pricing" @click="mobileOpen = false"
               class="block px-3 py-2.5 text-sm font-medium text-gray-700 rounded-xl hover:bg-gray-50">Pricing</a>
            <a href="#faq" @click="mobileOpen = false"
               class="block px-3 py-2.5 text-sm font-medium text-gray-700 rounded-xl hover:bg-gray-50">FAQ</a>
            <div class="pt-3 pb-1 flex flex-col gap-2">
                <a href="{{ route('login') }}"
                   class="w-full px-4 py-2.5 text-sm font-semibold text-center text-gray-700
                          border border-gray-200 rounded-xl hover:bg-gray-50 transition-all">
                    Sign In
                </a>
                <a href="{{ route('register') }}"
                   class="w-full px-4 py-2.5 text-sm font-semibold text-center text-white rounded-xl
                          bg-gradient-to-r from-violet-600 to-indigo-600 transition-all">
                    Get Started Free
                </a>
            </div>
        </div>
    </div>
</header>

{{-- ════════════════════════════════════════════════════════════════════════
     2. HERO
════════════════════════════════════════════════════════════════════════ --}}
<section class="hero-grid relative overflow-hidden min-h-screen flex flex-col justify-center pt-16"
         style="background: linear-gradient(135deg, #0f0a2a 0%, #1a1145 40%, #130d35 70%, #0f0a2a 100%);">

    {{-- Ambient orb — violet, top-left --}}
    <div class="absolute -top-20 -left-20 w-[520px] h-[520px] rounded-full
                blur-3xl pointer-events-none"
         style="background: #7c3aed; opacity: 0.45;"></div>

    {{-- Ambient orb — indigo, bottom-right --}}
    <div class="absolute -bottom-24 -right-24 w-[440px] h-[440px] rounded-full
                blur-3xl pointer-events-none"
         style="background: #4f46e5; opacity: 0.35;"></div>

    {{-- Subtle center radial --}}
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(124,58,237,0.12)_0%,transparent_65%)]
                pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 lg:gap-20 items-center">

            {{-- ── LEFT: Copy ──────────────────────────────────────────── --}}
            <div class="text-center lg:text-left">

                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full
                            bg-violet-500/15 border border-violet-400/25 mb-7">
                    <span class="w-1.5 h-1.5 rounded-full bg-violet-400 animate-pulse"></span>
                    <span class="text-xs font-semibold text-violet-300 tracking-wide">
                        Powered by GPT-4o
                    </span>
                </div>

                {{-- Headline --}}
                <h1 class="text-5xl lg:text-6xl xl:text-7xl font-black text-white
                           leading-[1.05] tracking-tight mb-6"
                    style="color: #ffffff !important;">
                    High-Converting<br>Sales Pages<br>
                    <span class="gradient-text">in 90 Seconds.</span>
                </h1>

                {{-- Sub-headline --}}
                <p class="text-lg text-violet-200/75 leading-relaxed max-w-lg mx-auto lg:mx-0 mb-9"
                   style="color: rgba(221, 214, 254, 0.8);">
                    Fill in your product details. GPT-4o writes the copy, designs the layout,
                    and delivers ready-to-publish HTML — no designer or copywriter needed.
                </p>

                {{-- CTA buttons --}}
                <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center gap-2 px-7 py-3.5
                              text-sm font-bold text-white rounded-xl
                              bg-gradient-to-r from-violet-500 to-indigo-500
                              hover:from-violet-400 hover:to-indigo-400
                              shadow-[0_8px_28px_-4px_rgba(124,58,237,0.5)]
                              hover:shadow-[0_8px_32px_-4px_rgba(124,58,237,0.65)]
                              transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                             stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0
                                     00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0
                                     003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0
                                     003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0
                                     00-3.09 3.09z"/>
                        </svg>
                        Generate Your First Page
                    </a>
                    <a href="#how-it-works"
                       class="inline-flex items-center justify-center gap-2 px-7 py-3.5
                              text-sm font-semibold text-white/75 rounded-xl
                              border border-white/15 hover:border-white/35 hover:text-white
                              hover:bg-white/5 transition-all duration-200"
                       style="color: rgba(255,255,255,0.85); border-color: rgba(255,255,255,0.35);">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                        See How It Works
                    </a>
                </div>

                {{-- Social proof row --}}
                <div class="flex items-center gap-4 mt-10 justify-center lg:justify-start">
                    {{-- Stacked avatars --}}
                    <div class="flex -space-x-2.5">
                        @foreach([
                            ['letter' => 'S', 'from' => 'from-violet-500', 'to' => 'to-purple-600'],
                            ['letter' => 'M', 'from' => 'from-indigo-500', 'to' => 'to-blue-600'],
                            ['letter' => 'P', 'from' => 'from-purple-500', 'to' => 'to-violet-600'],
                            ['letter' => 'A', 'from' => 'from-violet-600', 'to' => 'to-indigo-700'],
                        ] as $av)
                        <div class="w-8 h-8 rounded-full border-2 border-[#0f0a2a]
                                    bg-gradient-to-br {{ $av['from'] }} {{ $av['to'] }}
                                    flex items-center justify-center shadow-sm">
                            <span class="text-[9px] font-black text-white">{{ $av['letter'] }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div>
                        {{-- 5 stars --}}
                        <div class="flex gap-0.5 mb-0.5">
                            @for($i = 0; $i < 5; $i++)
                            <svg class="w-3 h-3 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 17l-6.2 4.3 2.4-7.4L2 9.4h7.6z"/>
                            </svg>
                            @endfor
                        </div>
                        <p class="text-xs text-violet-300/80" style="color: rgba(196, 181, 253, 0.9);">
                            <span class="font-bold text-white">500+</span> marketers generating pages
                        </p>
                    </div>
                </div>

            </div>

            {{-- ── RIGHT: App mockup ────────────────────────────────────── --}}
            <div class="hidden lg:flex justify-center lg:justify-end">

                {{-- Wrapper: float animation (no rotate) --}}
                <div class="relative w-full max-w-[320px] mockup-float" style="overflow: visible;">

                    {{-- Floating mini stats card — top left --}}
                    <div class="float-card-1 absolute -left-24 top-8 w-40 bg-white rounded-xl
                                pointer-events-none z-10"
                         style="box-shadow: 0 8px 24px -4px rgba(0,0,0,0.18), 0 2px 8px -2px rgba(0,0,0,0.1);">
                        <div class="p-3">
                            <div class="flex items-center gap-1.5 mb-1">
                                <span class="text-sm">📈</span>
                                <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wide">Reply Rate</span>
                            </div>
                            <p class="text-2xl font-black text-gray-900 leading-none">3.2×</p>
                            <p class="text-[10px] text-emerald-600 font-medium mt-0.5">+180% vs template</p>
                        </div>
                    </div>

                    {{-- Floating generation time card — bottom right --}}
                    <div class="float-card-2 absolute -right-28 bottom-20 w-44 bg-white rounded-xl
                                pointer-events-none z-10"
                         style="box-shadow: 0 8px 24px -4px rgba(0,0,0,0.18), 0 2px 8px -2px rgba(0,0,0,0.1);">
                        <div class="p-3">
                            <div class="flex items-center gap-1.5 mb-2">
                                <span class="text-sm">⚡</span>
                                <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wide">Gen Time</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5 mb-1.5">
                                <div class="bg-gradient-to-r from-violet-500 to-indigo-500 h-1.5 rounded-full"
                                     style="width: 78%;"></div>
                            </div>
                            <p class="text-[10px] font-semibold text-gray-700">87s average</p>
                        </div>
                    </div>

                    {{-- Floating badge — bottom left --}}
                    <div class="float-badge absolute -left-10 -bottom-5 z-10 pointer-events-none">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full
                                    text-[11px] font-semibold text-violet-200"
                             style="background: rgba(124,58,237,0.25); backdrop-filter: blur(8px);
                                    border: 1px solid rgba(167,139,250,0.3);
                                    box-shadow: 0 4px 12px rgba(124,58,237,0.25);">
                            ✨ 2,847 pages generated
                        </div>
                    </div>

                    {{-- Glow behind card --}}
                    <div class="absolute inset-0 bg-violet-600/20 rounded-3xl blur-2xl
                                scale-105 pointer-events-none"></div>

                    {{-- Main white card --}}
                    <div class="relative bg-white rounded-2xl shadow-2xl overflow-hidden">

                        {{-- Browser chrome bar --}}
                        <div class="flex items-center gap-2 px-4 py-3
                                    bg-gray-50 border-b border-gray-100">
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                                <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                            </div>
                            <div class="flex-1 bg-white border border-gray-200 rounded-md
                                        px-3 py-1 ml-2">
                                <p class="text-[10px] text-gray-400 font-mono truncate">
                                    app.pitchly.io/generate
                                </p>
                            </div>
                        </div>

                        {{-- Form body --}}
                        <div class="p-5 space-y-4">

                            {{-- Header --}}
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-violet-600 to-indigo-600
                                            flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                         stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0
                                                 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0
                                                 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0
                                                 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0
                                                 00-3.09 3.09z"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-gray-800">New Sales Page</span>
                            </div>

                            {{-- Product Name field --}}
                            <div>
                                <p class="text-[10px] font-semibold text-gray-400 uppercase
                                          tracking-widest mb-1.5">Product Name</p>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5
                                            flex items-center gap-2">
                                    <span class="text-sm font-semibold text-gray-800">ProFlow CRM</span>
                                    <span class="ml-auto w-0.5 h-4 bg-violet-500 rounded-full
                                                 animate-pulse"></span>
                                </div>
                            </div>

                            {{-- Description field --}}
                            <div>
                                <p class="text-[10px] font-semibold text-gray-400 uppercase
                                          tracking-widest mb-1.5">Description</p>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5
                                            h-16 overflow-hidden">
                                    <span class="text-xs text-gray-500 leading-relaxed">
                                        CRM built for SaaS teams. Automate follow-ups, track pipeline,
                                        and close deals 2× faster…
                                    </span>
                                </div>
                            </div>

                            {{-- Template selector --}}
                            <div>
                                <p class="text-[10px] font-semibold text-gray-400 uppercase
                                          tracking-widest mb-1.5">Template</p>
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="bg-violet-50 border-2 border-violet-500 rounded-lg
                                                px-3 py-2 text-center">
                                        <span class="text-xs font-bold text-violet-700">Modern</span>
                                    </div>
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg
                                                px-3 py-2 text-center">
                                        <span class="text-xs text-gray-400">Bold</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Generate button --}}
                            <button class="w-full py-2.5 rounded-xl text-sm font-bold text-white
                                           bg-gradient-to-r from-violet-600 to-indigo-600
                                           flex items-center justify-center gap-2
                                           shadow-[0_4px_16px_-2px_rgba(124,58,237,0.4)]">
                                <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor"
                                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                                Generating your page…
                            </button>

                        </div>

                        {{-- Notification badge — bottom of card --}}
                        <div class="mx-5 mb-5 flex items-center gap-3 bg-emerald-50
                                    border border-emerald-200 rounded-xl px-4 py-2.5">
                            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shrink-0"></div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-emerald-800">✓ Page ready!</p>
                                <p class="text-[10px] text-emerald-600">Generated in 87 seconds</p>
                            </div>
                            <span class="ml-auto text-xs font-semibold text-emerald-700
                                         bg-emerald-100 px-2 py-0.5 rounded-full shrink-0">
                                View →
                            </span>
                        </div>

                    </div>
                    {{-- /white card --}}

                </div>
                {{-- /mockup wrapper --}}

            </div>
            {{-- /right --}}

        </div>
    </div>

</section>

{{-- ════════════════════════════════════════════════════════════════════════
     3. SOCIAL PROOF BAR
════════════════════════════════════════════════════════════════════════ --}}
<section class="bg-gray-50 border-y border-gray-100 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-center text-xs font-semibold uppercase tracking-widest text-gray-400 mb-7">
            Trusted by 500+ marketers, founders, and agencies
        </p>
        <div class="flex flex-wrap items-center justify-center gap-x-10 gap-y-4">
            {{-- Epicurus: 3 dots connected in triangle --}}
            <div class="flex items-center gap-2.5 opacity-70 hover:opacity-100 transition-opacity">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="4" r="2.5" fill="#ea6c00"/>
                    <circle cx="4" cy="18" r="2.5" fill="#ea6c00"/>
                    <circle cx="20" cy="18" r="2.5" fill="#ea6c00"/>
                    <line x1="12" y1="4" x2="4" y2="18" stroke="#ea6c00" stroke-width="2" stroke-linecap="round"/>
                    <line x1="12" y1="4" x2="20" y2="18" stroke="#ea6c00" stroke-width="2" stroke-linecap="round"/>
                    <line x1="4" y1="18" x2="20" y2="18" stroke="#ea6c00" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span class="text-sm font-bold text-gray-900 tracking-tight">Epicurus</span>
            </div>

            {{-- Wildcrafted: leaf curve path --}}
            <div class="flex items-center gap-2.5 opacity-70 hover:opacity-100 transition-opacity">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 19 C5 19 6 8 16 5 C16 5 18 13 12 17 C9 19 5 19 5 19Z" stroke="#059669" stroke-width="2" stroke-linejoin="round" fill="#059669" fill-opacity="0.25"/>
                    <path d="M5 19 C8 15 12 12 16 5" stroke="#059669" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span class="text-sm font-bold text-gray-900 tracking-tight">Wildcrafted</span>
            </div>

            {{-- CodeCraft: stylized < /> brackets --}}
            <div class="flex items-center gap-2.5 opacity-70 hover:opacity-100 transition-opacity">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 7 L3 12 L8 17" stroke="#2563eb" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16 7 L21 12 L16 17" stroke="#2563eb" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    <line x1="14" y1="5" x2="10" y2="19" stroke="#2563eb" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span class="text-sm font-bold text-gray-900 tracking-tight">CodeCraft</span>
            </div>

            {{-- Convergex: 2 inward-converging arrows --}}
            <div class="flex items-center gap-2.5 opacity-70 hover:opacity-100 transition-opacity">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 5 L12 12" stroke="#7c3aed" stroke-width="2" stroke-linecap="round"/>
                    <path d="M21 5 L12 12" stroke="#7c3aed" stroke-width="2" stroke-linecap="round"/>
                    <path d="M3 19 L12 12" stroke="#7c3aed" stroke-width="2" stroke-linecap="round"/>
                    <path d="M21 19 L12 12" stroke="#7c3aed" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="12" cy="12" r="2.5" fill="#7c3aed"/>
                </svg>
                <span class="text-sm font-bold text-gray-900 tracking-tight">Convergex</span>
            </div>

            {{-- ImgCompr: frame/box with diagonal compression line --}}
            <div class="flex items-center gap-2.5 opacity-70 hover:opacity-100 transition-opacity">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="3" width="18" height="18" rx="2" stroke="#e11d48" stroke-width="2"/>
                    <path d="M8 16 L16 8" stroke="#e11d48" stroke-width="2" stroke-linecap="round"/>
                    <path d="M10 8 L16 8 L16 14" stroke="#e11d48" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M14 16 L8 16 L8 10" stroke="#e11d48" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="text-sm font-bold text-gray-900 tracking-tight">ImgCompr</span>
            </div>

            {{-- Watchtower: shield/pentagon shape --}}
            <div class="flex items-center gap-2.5 opacity-70 hover:opacity-100 transition-opacity">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 3 L20 7 L20 13 C20 17 16 20.5 12 22 C8 20.5 4 17 4 13 L4 7 Z" stroke="#b45309" stroke-width="2" stroke-linejoin="round" fill="#b45309" fill-opacity="0.2"/>
                    <path d="M9 12 L11 14 L15 10" stroke="#b45309" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="text-sm font-bold text-gray-900 tracking-tight">Watchtower</span>
            </div>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════════════════════════════════
     4. FEATURES
════════════════════════════════════════════════════════════════════════ --}}
<section id="features" class="py-20 lg:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section header --}}
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full
                         bg-violet-50 text-violet-700 text-xs font-semibold mb-4">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 17l-6.2 4.3 2.4-7.4L2 9.4h7.6z"/>
                </svg>
                Features
            </span>
            <h2 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight mb-4">
                Everything you need to sell,<br>nothing you don't.
            </h2>
            <p class="text-gray-500 text-lg leading-relaxed">
                SalesAI handles copy, design, and export so you can focus on your product.
            </p>
        </div>

        {{-- Feature cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            @foreach([
                [
                    'icon_path' => 'M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z',
                    'bg' => 'bg-violet-50',
                    'icon_color' => 'text-violet-600',
                    'title' => 'AI-Powered Copy',
                    'desc' => 'GPT-4o writes persuasive, conversion-focused copy using proven copywriting frameworks — PAS, AIDA, and more.',
                ],
                [
                    'icon_path' => 'M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3',
                    'bg' => 'bg-indigo-50',
                    'icon_color' => 'text-indigo-600',
                    'title' => 'One-Click Export',
                    'desc' => 'Download your sales page as a standalone HTML file. No dependencies, no build step — just upload and go live.',
                ],
                [
                    'icon_path' => 'M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42',
                    'bg' => 'bg-purple-50',
                    'icon_color' => 'text-purple-600',
                    'title' => 'Multiple Templates',
                    'desc' => 'Choose between Modern (light, clean) and Bold (dark, high-contrast) templates. Fits any brand style.',
                ],
                [
                    'icon_path' => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
                    'bg' => 'bg-blue-50',
                    'icon_color' => 'text-blue-600',
                    'title' => 'Version History',
                    'desc' => 'Not happy with the result? Give feedback and regenerate. All versions are saved so you can always go back.',
                ],
            ] as $feature)
            <div class="group bg-white rounded-2xl border border-gray-100 shadow-card
                        hover:shadow-card-lg hover:-translate-y-1 transition-all duration-200 p-6">
                <div class="w-11 h-11 rounded-xl {{ $feature['bg'] }} flex items-center justify-center mb-5">
                    <svg class="w-5 h-5 {{ $feature['icon_color'] }}" fill="none"
                         stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="{{ $feature['icon_path'] }}"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">{{ $feature['title'] }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $feature['desc'] }}</p>
            </div>
            @endforeach

        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════════════════════════════════
     5. HOW IT WORKS
════════════════════════════════════════════════════════════════════════ --}}
<section id="how-it-works" class="py-20 lg:py-28 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full
                         bg-violet-50 text-violet-700 text-xs font-semibold mb-4">
                How it Works
            </span>
            <h2 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight mb-4">
                From idea to live page<br>in three steps.
            </h2>
        </div>

        {{-- Steps --}}
        <div class="relative grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">

            {{-- Connecting line (desktop) --}}
            <div class="hidden md:block absolute top-10 left-[calc(16.67%+20px)] right-[calc(16.67%+20px)]
                        h-px bg-gradient-to-r from-violet-200 via-indigo-200 to-violet-200 z-0"></div>

            @foreach([
                [
                    'step' => '01',
                    'title' => 'Fill in product details',
                    'desc' => 'Enter your product name, description, key features, target audience, price, and unique selling point.',
                    'icon_path' => 'M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10',
                ],
                [
                    'step' => '02',
                    'title' => 'AI generates your page',
                    'desc' => 'GPT-4o writes your headline, hero copy, benefits, social proof, pricing, and CTA — all in about 90 seconds.',
                    'icon_path' => 'M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z',
                ],
                [
                    'step' => '03',
                    'title' => 'Export & publish',
                    'desc' => 'Preview your page, refine with feedback if needed, then download the standalone HTML and publish anywhere.',
                    'icon_path' => 'M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3',
                ],
            ] as $i => $step)
            <div class="relative z-10 text-center md:text-left">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl
                            bg-white border border-gray-100 shadow-card mb-6 mx-auto md:mx-0">
                    <div class="relative">
                        <div class="absolute -top-3 -right-3 w-6 h-6 rounded-full
                                    bg-gradient-to-br from-violet-600 to-indigo-600
                                    flex items-center justify-center shadow-sm">
                            <span class="text-[9px] font-black text-white">{{ $step['step'] }}</span>
                        </div>
                        <svg class="w-8 h-8 text-violet-600" fill="none" stroke="currentColor"
                             stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="{{ $step['icon_path'] }}"/>
                        </svg>
                    </div>
                </div>
                <h3 class="font-bold text-gray-900 text-lg mb-2">{{ $step['title'] }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
            </div>
            @endforeach

        </div>

        {{-- CTA below steps --}}
        <div class="text-center mt-12">
            <a href="{{ route('register') }}"
               class="inline-flex items-center gap-2 px-6 py-3.5 text-sm font-bold text-white
                      rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600
                      hover:from-violet-700 hover:to-indigo-700 shadow-sm hover:shadow-violet
                      transition-all duration-200">
                Try it free — no credit card needed
                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                     stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        </div>

    </div>
</section>

{{-- ════════════════════════════════════════════════════════════════════════
     6. STATS BAR
════════════════════════════════════════════════════════════════════════ --}}
<section class="py-14 bg-gradient-to-r from-violet-600 to-indigo-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            @foreach([
                ['value' => '2,000+', 'label' => 'Pages Generated'],
                ['value' => '4.9★', 'label' => 'User Rating'],
                ['value' => '~90s', 'label' => 'Avg. Generation Time'],
                ['value' => '$0', 'label' => 'Design Cost'],
            ] as $stat)
            <div>
                <p class="text-3xl sm:text-4xl font-black text-white mb-1">{{ $stat['value'] }}</p>
                <p class="text-sm text-violet-200 font-medium">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════════════════════════════════
     7. PRICING
════════════════════════════════════════════════════════════════════════ --}}
<section id="pricing" class="py-20 lg:py-28 bg-white" x-data="{ yearly: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full
                         bg-violet-50 text-violet-700 text-xs font-semibold mb-4">
                Pricing
            </span>
            <h2 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight mb-4">
                Simple, transparent pricing.
            </h2>
            <p class="text-gray-500 text-lg mb-8">
                Start free. Upgrade when you're ready.
            </p>

            {{-- Billing toggle --}}
            <div class="inline-flex items-center gap-3 bg-gray-100 rounded-xl p-1">
                <button @click="yearly = false"
                        :class="!yearly ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500'"
                        class="px-4 py-2 text-sm font-semibold rounded-lg transition-all">
                    Monthly
                </button>
                <button @click="yearly = true"
                        :class="yearly ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500'"
                        class="px-4 py-2 text-sm font-semibold rounded-lg transition-all">
                    Yearly
                    <span class="ml-1.5 text-xs font-bold text-emerald-600 bg-emerald-50
                                 px-1.5 py-0.5 rounded-full">-20%</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">

            {{-- Free --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-7">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-3">Free</p>
                <div class="flex items-end gap-1 mb-1">
                    <span class="text-4xl font-black text-gray-900">$0</span>
                </div>
                <p class="text-sm text-gray-400 mb-7">forever, no card needed</p>
                <ul class="space-y-3 mb-8 text-sm text-gray-600">
                    @foreach([
                        '3 pages per month',
                        'Modern template',
                        'HTML export',
                        'Version history (2 versions)',
                        'Community support',
                    ] as $item)
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-violet-500 shrink-0" fill="none"
                             stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('register') }}"
                   class="block w-full py-2.5 text-sm font-semibold text-center text-gray-700
                          border border-gray-200 rounded-xl hover:bg-gray-50
                          hover:border-gray-300 transition-all">
                    Get Started Free
                </a>
            </div>

            {{-- Pro (highlighted) --}}
            <div class="pricing-popular rounded-2xl p-7 relative shadow-glow">
                <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs
                                 font-bold text-white bg-gradient-to-r from-violet-600 to-indigo-600
                                 shadow-sm">
                        ⚡ Most Popular
                    </span>
                </div>
                <p class="text-xs font-semibold uppercase tracking-widest text-violet-500 mb-3 mt-1">Pro</p>
                <div class="flex items-end gap-1 mb-1">
                    <span class="text-4xl font-black text-gray-900"
                          x-text="yearly ? '$23' : '$29'"></span>
                    <span class="text-gray-400 text-sm mb-1.5">/ month</span>
                </div>
                <p class="text-sm text-gray-400 mb-7"
                   x-text="yearly ? 'billed $276/year (save $72)' : 'billed monthly'"></p>
                <ul class="space-y-3 mb-8 text-sm text-gray-600">
                    @foreach([
                        'Unlimited pages',
                        'All templates (Modern + Bold)',
                        'Priority generation (faster queue)',
                        'Unlimited version history',
                        'HTML export',
                        'Email support',
                    ] as $item)
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-violet-600 shrink-0" fill="none"
                             stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('register') }}"
                   class="block w-full py-2.5 text-sm font-bold text-center text-white rounded-xl
                          bg-gradient-to-r from-violet-600 to-indigo-600
                          hover:from-violet-700 hover:to-indigo-700
                          shadow-sm hover:shadow-violet transition-all">
                    Start Pro Trial
                </a>
            </div>

            {{-- Agency --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-7">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-3">Agency</p>
                <div class="flex items-end gap-1 mb-1">
                    <span class="text-4xl font-black text-gray-900"
                          x-text="yearly ? '$79' : '$99'"></span>
                    <span class="text-gray-400 text-sm mb-1.5">/ month</span>
                </div>
                <p class="text-sm text-gray-400 mb-7"
                   x-text="yearly ? 'billed $948/year (save $240)' : 'billed monthly'"></p>
                <ul class="space-y-3 mb-8 text-sm text-gray-600">
                    @foreach([
                        'Everything in Pro',
                        '5 team seats',
                        'White-label export (remove branding)',
                        'API access',
                        'Custom templates (coming soon)',
                        'Dedicated Slack support',
                    ] as $item)
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-violet-500 shrink-0" fill="none"
                             stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('register') }}"
                   class="block w-full py-2.5 text-sm font-semibold text-center text-gray-700
                          border border-gray-200 rounded-xl hover:bg-gray-50
                          hover:border-gray-300 transition-all">
                    Contact Sales
                </a>
            </div>

        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════════════════════════════════
     8. TESTIMONIALS
════════════════════════════════════════════════════════════════════════ --}}
<section class="py-20 lg:py-28 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full
                         bg-violet-50 text-violet-700 text-xs font-semibold mb-4">
                Testimonials
            </span>
            <h2 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight mb-4">
                Loved by builders & marketers.
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                [
                    'initials' => 'SA',
                    'name' => 'Sarah A.',
                    'role' => 'Indie Maker, ProductHunt',
                    'color' => 'from-violet-500 to-purple-500',
                    'stars' => 5,
                    'quote' => 'I launched a landing page for my SaaS in under 10 minutes. The copy was surprisingly good — way better than what I\'d write myself at 2am. Ended up getting 47 signups in 24 hours.',
                ],
                [
                    'initials' => 'MR',
                    'name' => 'Marcus R.',
                    'role' => 'Marketing Director',
                    'color' => 'from-indigo-500 to-blue-500',
                    'stars' => 5,
                    'quote' => 'We use SalesAI for every new product feature launch. The version history is clutch — we test different angles and keep what converts. Saved us at least $3k/month in copywriting fees.',
                ],
                [
                    'initials' => 'PK',
                    'name' => 'Priya K.',
                    'role' => 'Freelance Designer',
                    'color' => 'from-purple-500 to-pink-500',
                    'stars' => 5,
                    'quote' => 'My clients pay me to design, not write copy. SalesAI handles the words while I focus on the visuals. The export HTML is clean enough that I can actually work with it.',
                ],
            ] as $testimonial)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-card p-6
                        hover:shadow-card-lg hover:-translate-y-0.5 transition-all duration-200">

                {{-- Stars --}}
                <div class="flex gap-0.5 mb-4">
                    @for($i = 0; $i < $testimonial['stars']; $i++)
                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 17l-6.2 4.3 2.4-7.4L2 9.4h7.6z"/>
                    </svg>
                    @endfor
                </div>

                <p class="text-sm text-gray-600 leading-relaxed mb-5">
                    "{{ $testimonial['quote'] }}"
                </p>

                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br {{ $testimonial['color'] }}
                                flex items-center justify-center shrink-0">
                        <span class="text-xs font-bold text-white">{{ $testimonial['initials'] }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $testimonial['name'] }}</p>
                        <p class="text-xs text-gray-400">{{ $testimonial['role'] }}</p>
                    </div>
                </div>

            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ════════════════════════════════════════════════════════════════════════
     9. FAQ
════════════════════════════════════════════════════════════════════════ --}}
<section id="faq" class="py-20 lg:py-28 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-14">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full
                         bg-violet-50 text-violet-700 text-xs font-semibold mb-4">
                FAQ
            </span>
            <h2 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight">
                Common questions.
            </h2>
        </div>

        <div class="space-y-3" x-data="{ open: null }">
            @foreach([
                [
                    'q' => 'How good is the AI-generated copy, really?',
                    'a' => 'SalesAI uses GPT-4o with a carefully crafted system prompt built on proven copywriting frameworks (PAS, AIDA). The output avoids AI clichés and is tailored to your specific product, audience, and price point. Most users publish with minimal edits — and many say it\'s better than what they\'d write themselves.',
                ],
                [
                    'q' => 'Can I edit the generated page?',
                    'a' => 'Yes. You get the full standalone HTML file, so you can open it in any editor and tweak anything you like. Alternatively, use the feedback feature inside SalesAI to regenerate specific sections with new instructions — without starting over.',
                ],
                [
                    'q' => 'Do I need hosting or a website to use this?',
                    'a' => 'No. The exported HTML is fully standalone — it includes all CSS and scripts inline. You can upload it to any web host, Dropbox, GitHub Pages, Notion, or even email it directly. No CMS required.',
                ],
                [
                    'q' => 'What happens to my data? Is it used to train AI?',
                    'a' => 'Your product data is sent to OpenAI\'s API to generate your page and is not used to train OpenAI models (per OpenAI\'s API data usage policy). We do not sell or share your data with third parties.',
                ],
                [
                    'q' => 'How is SalesAI different from ChatGPT?',
                    'a' => 'ChatGPT gives you raw text. SalesAI gives you a complete, styled, responsive HTML sales page — including layout, typography, colors, sections (hero, benefits, pricing, testimonials, FAQ, CTA), and a hero image. No prompting, no copy-pasting, no design work needed.',
                ],
            ] as $i => $faq)
            <div class="border border-gray-100 rounded-2xl overflow-hidden
                        hover:border-violet-100 transition-colors duration-200"
                 x-data="{ id: {{ $i }} }">
                <button @click="open === id ? open = null : open = id"
                        class="w-full flex items-center justify-between gap-4
                               px-5 py-4 text-left bg-white hover:bg-gray-50
                               transition-colors duration-150">
                    <span class="font-semibold text-gray-900 text-sm">{{ $faq['q'] }}</span>
                    <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-200"
                         :class="open === id ? 'rotate-45' : ''"
                         fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                </button>
                <div x-show="open === id"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-cloak
                     class="px-5 pb-5 text-sm text-gray-500 leading-relaxed border-t border-gray-50">
                    <p class="pt-4">{{ $faq['a'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ════════════════════════════════════════════════════════════════════════
     10. FINAL CTA
════════════════════════════════════════════════════════════════════════ --}}
<section class="py-20 lg:py-28 bg-gradient-to-br from-violet-600 via-indigo-600 to-violet-700 relative overflow-hidden">

    {{-- Decorative blobs --}}
    <div class="absolute top-0 left-1/3 w-64 h-64 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/3 w-80 h-80 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full
                    bg-white/15 border border-white/20 mb-6">
            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
            <span class="text-xs font-semibold text-white/90 tracking-wide">
                No credit card required
            </span>
        </div>

        <h2 class="text-4xl sm:text-5xl font-black text-white leading-tight tracking-tight mb-5">
            Your next sales page is<br>90 seconds away.
        </h2>
        <p class="text-lg text-violet-200 max-w-xl mx-auto mb-10 leading-relaxed">
            Join 500+ marketers and founders who are shipping faster and converting better with AI-written sales pages.
        </p>

        <a href="{{ route('register') }}"
           class="inline-flex items-center gap-2.5 px-8 py-4 text-base font-bold
                  text-violet-700 bg-white rounded-xl shadow-xl
                  hover:bg-violet-50 hover:shadow-2xl hover:-translate-y-0.5
                  transition-all duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                 stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0
                         00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0
                         003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0
                         003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0
                         00-3.09 3.09z"/>
            </svg>
            Generate Your First Page Free
        </a>

        <p class="mt-4 text-sm text-violet-300">
            Free plan includes 3 pages/month · No card required · Cancel anytime
        </p>

    </div>
</section>

{{-- ════════════════════════════════════════════════════════════════════════
     11. FOOTER
════════════════════════════════════════════════════════════════════════ --}}
<footer class="bg-gray-950 text-gray-400">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

        <div class="grid grid-cols-2 md:grid-cols-5 gap-8 pb-12 border-b border-gray-800">

            {{-- Brand --}}
            <div class="col-span-2">
                <a href="/" class="flex items-center gap-2.5 mb-4">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-violet-600 to-indigo-600
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
                    <span class="font-bold text-white text-sm">SalesAI</span>
                </a>
                <p class="text-sm leading-relaxed max-w-xs text-gray-500">
                    AI-powered sales page generator. Fill in your product details. GPT-4o writes the rest.
                </p>
                {{-- Social icons --}}
                <div class="flex items-center gap-3 mt-5">
                    @foreach([
                        ['label' => 'Twitter', 'path' => 'M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z'],
                        ['label' => 'GitHub', 'path' => 'M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 00-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0020 4.77 5.07 5.07 0 0019.91 1S18.73.65 16 2.48a13.38 13.38 0 00-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 005 4.77a5.44 5.44 0 00-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 009 18.13V22'],
                        ['label' => 'LinkedIn', 'path' => 'M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z M4 6a2 2 0 100-4 2 2 0 000 4z'],
                    ] as $social)
                    <a href="#" aria-label="{{ $social['label'] }}"
                       class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center
                              text-gray-500 hover:text-white hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                             stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="{{ $social['path'] }}"/>
                        </svg>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Links --}}
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mb-4">Product</p>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="#features" class="hover:text-white transition-colors">Features</a></li>
                    <li><a href="#pricing" class="hover:text-white transition-colors">Pricing</a></li>
                    <li><a href="#how-it-works" class="hover:text-white transition-colors">How it Works</a></li>
                    <li><a href="#faq" class="hover:text-white transition-colors">FAQ</a></li>
                </ul>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mb-4">Account</p>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Sign In</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Get Started</a></li>
                </ul>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mb-4">Legal</p>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                </ul>
            </div>

        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-8">
            <p class="text-sm text-gray-600">
                &copy; 2026 SalesAI. All rights reserved.
            </p>
            <p class="text-xs text-gray-700">
                Built with Laravel &amp; GPT-4o
            </p>
        </div>

    </div>
</footer>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</body>
</html>
