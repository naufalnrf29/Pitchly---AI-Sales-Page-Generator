<div>
    <h2 class="text-lg font-bold text-gray-900">Profile Information</h2>
    <p class="mt-1 text-sm text-gray-500">Update your account name and email address.</p>
</div>

<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
    @csrf
    @method('patch')

    {{-- Name --}}
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Name</label>
        <input id="name" name="name" type="text"
               value="{{ old('name', $user->name) }}"
               required autofocus autocomplete="name"
               class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm
                      text-gray-900 placeholder-gray-400
                      focus:ring-2 focus:ring-violet-500 focus:border-violet-500
                      outline-none transition-colors duration-150
                      {{ $errors->get('name') ? 'border-red-400 focus:ring-red-400 focus:border-red-400' : '' }}">
        @foreach($errors->get('name') as $message)
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
        @endforeach
    </div>

    {{-- Email --}}
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email address</label>
        <input id="email" name="email" type="email"
               value="{{ old('email', $user->email) }}"
               required autocomplete="username"
               class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm
                      text-gray-900 placeholder-gray-400
                      focus:ring-2 focus:ring-violet-500 focus:border-violet-500
                      outline-none transition-colors duration-150
                      {{ $errors->get('email') ? 'border-red-400 focus:ring-red-400 focus:border-red-400' : '' }}">
        @foreach($errors->get('email') as $message)
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
        @endforeach

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-2 p-3 rounded-xl bg-amber-50 border border-amber-200">
                <p class="text-sm text-amber-800">
                    Your email address is unverified.
                    <button form="send-verification"
                            class="underline font-medium hover:text-amber-900 transition-colors">
                        Click here to re-send the verification email.
                    </button>
                </p>
                @if (session('status') === 'verification-link-sent')
                    <p class="mt-1.5 text-sm font-medium text-emerald-700">
                        A new verification link has been sent to your email address.
                    </p>
                @endif
            </div>
        @endif
    </div>

    <div class="flex items-center gap-4 pt-1">
        <button type="submit"
                class="px-6 py-2.5 text-sm font-semibold text-white rounded-xl
                       bg-gradient-to-r from-violet-600 to-indigo-600
                       hover:from-violet-700 hover:to-indigo-700
                       shadow-sm transition-all duration-150">
            Save Changes
        </button>

        @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }"
               x-show="show"
               x-transition
               x-init="setTimeout(() => show = false, 2000)"
               class="text-sm text-emerald-600 font-medium">
                Saved.
            </p>
        @endif
    </div>
</form>
