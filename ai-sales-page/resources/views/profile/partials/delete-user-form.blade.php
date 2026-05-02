<div x-data="{ confirmOpen: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }">

    <div>
        <h2 class="text-lg font-bold text-gray-900">Delete Account</h2>
        <p class="mt-1 text-sm text-gray-500">
            Once your account is deleted, all data will be permanently removed and cannot be recovered.
        </p>
    </div>

    <div class="mt-5">
        <button type="button"
                @click="confirmOpen = true"
                class="px-5 py-2.5 text-sm font-semibold text-red-600 rounded-xl
                       border border-red-200 bg-red-50
                       hover:bg-red-100 hover:border-red-300
                       transition-all duration-150">
            Delete Account
        </button>
    </div>

    {{-- Confirmation Modal --}}
    <div x-show="confirmOpen"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm"
             @click="confirmOpen = false"></div>

        {{-- Modal card --}}
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">

            {{-- Icon --}}
            <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0
                             2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898
                             0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
            </div>

            <h2 class="text-lg font-bold text-gray-900">Delete your account?</h2>
            <p class="mt-1.5 text-sm text-gray-500">
                This action is permanent. All your pages and data will be deleted immediately.
                Please enter your password to confirm.
            </p>

            <form method="post" action="{{ route('profile.destroy') }}" class="mt-5 space-y-4">
                @csrf
                @method('delete')

                <div>
                    <label for="delete_password"
                           class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input id="delete_password"
                           name="password" type="password"
                           placeholder="Enter your password"
                           class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm
                                  text-gray-900 placeholder-gray-400
                                  focus:ring-2 focus:ring-red-400 focus:border-red-400
                                  outline-none transition-colors duration-150
                                  {{ $errors->userDeletion->get('password') ? 'border-red-400' : '' }}">
                    @foreach($errors->userDeletion->get('password') as $message)
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @endforeach
                </div>

                <div class="flex justify-end gap-3 pt-1">
                    <button type="button"
                            @click="confirmOpen = false"
                            class="px-5 py-2.5 text-sm font-semibold text-gray-700 rounded-xl
                                   border border-gray-200 hover:bg-gray-50
                                   transition-all duration-150">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 text-sm font-semibold text-white rounded-xl
                                   bg-red-600 hover:bg-red-700
                                   transition-all duration-150">
                        Delete Account
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
