<div>
    <h2 class="text-lg font-bold text-gray-900">Update Password</h2>
    <p class="mt-1 text-sm text-gray-500">Use a long, random password to keep your account secure.</p>
</div>

<form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
    @csrf
    @method('put')

    {{-- Current Password --}}
    <div>
        <label for="update_password_current_password"
               class="block text-sm font-medium text-gray-700 mb-1.5">Current Password</label>
        <input id="update_password_current_password"
               name="current_password" type="password"
               autocomplete="current-password"
               class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm
                      text-gray-900 placeholder-gray-400
                      focus:ring-2 focus:ring-violet-500 focus:border-violet-500
                      outline-none transition-colors duration-150
                      {{ $errors->updatePassword->get('current_password') ? 'border-red-400 focus:ring-red-400 focus:border-red-400' : '' }}"
               placeholder="••••••••">
        @foreach($errors->updatePassword->get('current_password') as $message)
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
        @endforeach
    </div>

    {{-- New Password --}}
    <div>
        <label for="update_password_password"
               class="block text-sm font-medium text-gray-700 mb-1.5">New Password</label>
        <input id="update_password_password"
               name="password" type="password"
               autocomplete="new-password"
               class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm
                      text-gray-900 placeholder-gray-400
                      focus:ring-2 focus:ring-violet-500 focus:border-violet-500
                      outline-none transition-colors duration-150
                      {{ $errors->updatePassword->get('password') ? 'border-red-400 focus:ring-red-400 focus:border-red-400' : '' }}"
               placeholder="••••••••">
        @foreach($errors->updatePassword->get('password') as $message)
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
        @endforeach
    </div>

    {{-- Confirm Password --}}
    <div>
        <label for="update_password_password_confirmation"
               class="block text-sm font-medium text-gray-700 mb-1.5">Confirm New Password</label>
        <input id="update_password_password_confirmation"
               name="password_confirmation" type="password"
               autocomplete="new-password"
               class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm
                      text-gray-900 placeholder-gray-400
                      focus:ring-2 focus:ring-violet-500 focus:border-violet-500
                      outline-none transition-colors duration-150
                      {{ $errors->updatePassword->get('password_confirmation') ? 'border-red-400 focus:ring-red-400 focus:border-red-400' : '' }}"
               placeholder="••••••••">
        @foreach($errors->updatePassword->get('password_confirmation') as $message)
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
        @endforeach
    </div>

    <div class="flex items-center gap-4 pt-1">
        <button type="submit"
                class="px-6 py-2.5 text-sm font-semibold text-white rounded-xl
                       bg-gradient-to-r from-violet-600 to-indigo-600
                       hover:from-violet-700 hover:to-indigo-700
                       shadow-sm transition-all duration-150">
            Update Password
        </button>

        @if (session('status') === 'password-updated')
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
