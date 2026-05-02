<x-guest-layout>

    {{-- Header --}}
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Welcome back</h2>
        <p class="mt-1 text-sm text-gray-500">Sign in to your Pitchly account</p>
    </div>

    {{-- Session status --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="label">Email address</label>
            <input id="email" name="email" type="email"
                   value="{{ old('email') }}"
                   required autofocus autocomplete="username"
                   class="input {{ $errors->has('email') ? 'input-error' : '' }}"
                   placeholder="you@example.com">
            @error('email')
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="label mb-0">Password</label>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-xs text-violet-600 hover:text-violet-700 font-medium transition-colors">
                    Forgot password?
                </a>
                @endif
            </div>
            <input id="password" name="password" type="password"
                   required autocomplete="current-password"
                   class="input {{ $errors->has('password') ? 'input-error' : '' }}"
                   placeholder="••••••••">
            @error('password')
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember me --}}
        <div class="flex items-center gap-2.5">
            <input id="remember_me" name="remember" type="checkbox"
                   class="w-4 h-4 rounded border-gray-300 text-violet-600
                          focus:ring-violet-500 focus:ring-offset-0 transition">
            <label for="remember_me" class="text-sm text-gray-600 select-none cursor-pointer">
                Keep me signed in
            </label>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-primary w-full py-3 text-base">
            Sign in
        </button>

        {{-- Register link --}}
        <p class="text-center text-sm text-gray-500">
            Don't have an account?
            <a href="{{ route('register') }}"
               class="text-violet-600 hover:text-violet-700 font-semibold transition-colors">
                Create one free
            </a>
        </p>

    </form>

</x-guest-layout>
