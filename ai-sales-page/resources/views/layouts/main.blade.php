<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="alternate icon" href="/favicon.ico">

    <title>@yield('title', 'Dashboard') — {{ config('app.name', 'Pitchly') }}</title>

    {{-- Inter font --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>[x-cloak] { display: none !important; }</style>
</head>

{{-- Alpine: sidebarOpen controls mobile sidebar --}}
<body class="font-sans antialiased" x-data="{ sidebarOpen: false }">

{{-- ═══════════════════════════════════════════════════════════════════════
     LAYOUT WRAPPER — sidebar on left, content on right
═══════════════════════════════════════════════════════════════════════ --}}
<div class="flex h-screen overflow-hidden">

    {{-- ── SIDEBAR ────────────────────────────────────────────────────── --}}

    {{-- Mobile overlay --}}
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 z-30 bg-gray-900/40 backdrop-blur-sm lg:hidden"
         style="display:none">
    </div>

    {{-- Sidebar panel --}}
    <aside id="sidebar"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-40 w-72 flex flex-col
                  bg-white border-r border-gray-100
                  transform transition-transform duration-200 ease-in-out
                  lg:relative lg:translate-x-0 lg:flex lg:flex-shrink-0">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 h-16 border-b border-gray-100 shrink-0">
            <a href="{{ route('sales-pages.index') }}"
               class="flex items-center gap-2.5 group">
                {{-- Icon mark --}}
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-violet-600 to-indigo-500
                            flex items-center justify-center shadow-sm shrink-0
                            group-hover:shadow-violet-200 transition-shadow duration-200">
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
                <div>
                    <p class="font-bold text-sm text-gray-900 leading-none">Pitchly</p>
                    <p class="text-[10px] text-gray-400 mt-0.5 leading-none">Powered by GPT-4o</p>
                </div>
            </a>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">

            {{-- Primary actions --}}
            <a href="{{ route('sales-pages.create') }}"
               class="btn-primary w-full mb-4 py-2.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                     stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                New Sales Page
            </a>

            {{-- Section label --}}
            <p class="px-3 pt-1 pb-1.5 text-[10px] font-semibold uppercase tracking-widest text-gray-400">
                Workspace
            </p>

            {{-- My Pages --}}
            @php $count = auth()->user()->salesPages()->originals()->count(); @endphp
            <a href="{{ route('sales-pages.index') }}"
               class="{{ request()->routeIs('sales-pages.index') ? 'nav-item-active' : 'nav-item-default' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 014.25 6v2.25a2.25 2.25 0
                             01-2.25 2.25H6A2.25 2.25 0 013.75 8.25V6zM3.75 15.75A2.25 2.25 0
                             016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25
                             2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0
                             0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0
                             01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0
                             012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                </svg>
                <span class="flex-1">My Pages</span>
                @if($count > 0)
                    <span class="badge-gray text-xs">{{ $count }}</span>
                @endif
            </a>

            {{-- Generate --}}
            <a href="{{ route('sales-pages.create') }}"
               class="{{ request()->routeIs('sales-pages.create') ? 'nav-item-active' : 'nav-item-default' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5
                             4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5
                             4.5 0 00-3.09 3.09z"/>
                </svg>
                <span>Generate</span>
            </a>

            {{-- Divider --}}
            <div class="pt-4 pb-1">
                <div class="divider my-0"></div>
            </div>

            <p class="px-3 pt-1 pb-1.5 text-[10px] font-semibold uppercase tracking-widest text-gray-400">
                Account
            </p>

            {{-- Profile --}}
            <a href="{{ route('profile.edit') }}"
               class="{{ request()->routeIs('profile.*') ? 'nav-item-active' : 'nav-item-default' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0
                             0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                </svg>
                <span>Profile</span>
            </a>

        </nav>

        {{-- User footer --}}
        <div class="shrink-0 px-3 py-3 border-t border-gray-100">
            <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl
                        hover:bg-gray-50 transition-colors duration-150">
                {{-- Avatar --}}
                <div class="w-8 h-8 rounded-full shrink-0 overflow-hidden ring-2 ring-violet-100">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=7c3aed&color=fff&size=64&bold=true"
                         alt="{{ auth()->user()->name }}"
                         class="w-full h-full object-cover">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate leading-tight">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="text-[11px] text-gray-400 truncate leading-tight">
                        {{ auth()->user()->email }}
                    </p>
                </div>
                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Sign out"
                            class="p-1.5 rounded-lg text-gray-400
                                   hover:text-red-500 hover:bg-red-50
                                   transition-colors duration-150">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                             stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25
                                     2.25 0 00-2.25 2.25v13.5A2.25 2.25 0
                                     007.5 21h6a2.25 2.25 0 002.25-2.25V15M12
                                     9l-3 3m0 0l3 3m-3-3h12.75"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

    </aside>
    {{-- /sidebar --}}

    {{-- ── MAIN AREA ───────────────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- ── TOPBAR (mobile hamburger + page-level breadcrumb) ─────── --}}
        <header class="shrink-0 h-16 flex items-center gap-4
                       bg-white border-b border-gray-100 px-4 sm:px-6 lg:px-8">

            {{-- Mobile hamburger --}}
            <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden p-2 -ml-2 rounded-xl text-gray-500
                           hover:text-gray-900 hover:bg-gray-100
                           transition-colors duration-150">
                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
            </button>

            {{-- Breadcrumb / page title slot --}}
            <div class="flex-1 min-w-0">
                @hasSection('breadcrumb')
                    <div class="flex items-center gap-2 text-sm">
                        @yield('breadcrumb')
                    </div>
                @else
                    <h1 class="text-sm font-semibold text-gray-900 truncate">
                        @yield('title', 'Dashboard')
                    </h1>
                @endif
            </div>

            {{-- Right side topbar actions --}}
            <div class="flex items-center gap-2">
                @yield('topbar-actions')

                {{-- Mobile: quick generate button --}}
                <a href="{{ route('sales-pages.create') }}"
                   class="lg:hidden btn-primary btn-sm">
                    + New
                </a>
            </div>

        </header>
        {{-- /topbar --}}

        {{-- ── SCROLLABLE CONTENT ─────────────────────────────────────── --}}
        <main class="flex-1 overflow-y-auto">

            {{-- Flash messages --}}
            @if(session('success') || session('error') || session('info') || session('warning'))
            <div class="px-4 sm:px-6 lg:px-8 pt-5">
                @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition
                     class="flex items-center gap-3 px-4 py-3 mb-4 rounded-xl
                            bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none"
                         stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                    <button @click="show = false"
                            class="ml-auto text-emerald-400 hover:text-emerald-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                             stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                @endif

                @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition
                     class="flex items-center gap-3 px-4 py-3 mb-4 rounded-xl
                            bg-red-50 border border-red-200 text-red-800 text-sm">
                    <svg class="w-5 h-5 text-red-500 shrink-0" fill="none"
                         stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0
                                 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                    <button @click="show = false"
                            class="ml-auto text-red-400 hover:text-red-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                             stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                @endif

                @if(session('warning'))
                <div x-data="{ show: true }" x-show="show" x-transition
                     class="flex items-center gap-3 px-4 py-3 mb-4 rounded-xl
                            bg-amber-50 border border-amber-200 text-amber-800 text-sm">
                    <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none"
                         stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374
                                 1.948 3.374h14.71c1.73 0 2.813-1.874
                                 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898
                                 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                    <span class="font-medium">{{ session('warning') }}</span>
                    <button @click="show = false"
                            class="ml-auto text-amber-400 hover:text-amber-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                             stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                @endif

                @if(session('info'))
                <div class="flex items-center gap-3 px-4 py-3 mb-4 rounded-xl
                            bg-blue-50 border border-blue-200 text-blue-800 text-sm">
                    <svg class="w-5 h-5 text-blue-500 shrink-0" fill="none"
                         stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708
                                 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0
                                 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                    </svg>
                    <span class="font-medium">{{ session('info') }}</span>
                </div>
                @endif
            </div>
            @endif

            {{-- Validation errors (global, shown at top of page) --}}
            @if($errors->any())
            <div class="px-4 sm:px-6 lg:px-8 pt-5">
                <div class="flex items-start gap-3 px-4 py-3 rounded-xl
                            bg-red-50 border border-red-200 text-red-800 text-sm">
                    <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none"
                         stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0
                                 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                    <div>
                        <p class="font-semibold mb-1">Please fix the following:</p>
                        <ul class="list-disc list-inside space-y-0.5 text-red-700">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            {{-- PAGE CONTENT --}}
            <div class="px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
                @yield('content')
            </div>

        </main>
        {{-- /main --}}

    </div>
    {{-- /main area --}}

</div>
{{-- /layout wrapper --}}

@stack('scripts')
</body>
</html>
