<x-guest-layout>

    {{-- Header --}}
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Create your account</h2>
        <p class="mt-1 text-sm text-gray-500">Free to start — no credit card required</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- Name --}}
        <div>
            <label for="name" class="label">Full name</label>
            <input id="name" name="name" type="text"
                   value="{{ old('name') }}"
                   required autofocus autocomplete="name"
                   class="input {{ $errors->has('name') ? 'input-error' : '' }}"
                   placeholder="Your name">
            @error('name')
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="label">Email address</label>
            <input id="email" name="email" type="email"
                   value="{{ old('email') }}"
                   required autocomplete="username"
                   class="input {{ $errors->has('email') ? 'input-error' : '' }}"
                   placeholder="you@example.com">
            @error('email')
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="label">Password</label>
            <input id="password" name="password" type="password"
                   required autocomplete="new-password"
                   class="input {{ $errors->has('password') ? 'input-error' : '' }}"
                   placeholder="Min. 8 characters">
            @error('password')
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm password --}}
        <div>
            <label for="password_confirmation" class="label">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password"
                   required autocomplete="new-password"
                   class="input {{ $errors->has('password_confirmation') ? 'input-error' : '' }}"
                   placeholder="Repeat your password">
            @error('password_confirmation')
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-primary w-full py-3 text-base">
            Create account
        </button>

        {{-- Login link --}}
        <p class="text-center text-sm text-gray-500">
            Already have an account?
            <a href="{{ route('login') }}"
               class="text-violet-600 hover:text-violet-700 font-semibold transition-colors">
                Sign in
            </a>
        </p>

    </form>

</x-guest-layout>
