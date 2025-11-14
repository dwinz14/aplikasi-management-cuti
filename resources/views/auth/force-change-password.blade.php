<x-app-layout>
    <!-- Force Password Change Modal -->
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true">
            </div>

            <!-- Modal panel -->
            <div
                class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-stone-200"
                                id="modal-title">
                                {{ __('Keamanan Akun') }}
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    {{ __('Untuk melanjutkan, Anda diharuskan mengubah password Anda. Pastikan password baru Anda kuat dan aman.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-slate-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form method="post" action="{{ route('password.update') }}" class="w-full">
                        @csrf
                        @method('put')

                        <div class="space-y-4">
                            <!-- Current Password -->
                            <div>
                                <x-input-label for="current_password" :value="__('Password Saat Ini')" />
                                <x-text-input id="current_password" name="current_password" type="password"
                                    class="mt-1 block w-full" autocomplete="current-password"
                                    placeholder="Masukkan password saat ini" />
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                            </div>

                            <!-- New Password -->
                            <div>
                                <x-input-label for="password" :value="__('Password Baru')" />
                                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"
                                    autocomplete="new-password" placeholder="Masukkan password baru" />
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                                <div id="password-validation" class="mt-1 text-sm" style="display: none;">
                                    <div id="password-rule-1">Karakter pertama harus huruf besar</div>
                                    <div id="password-rule-2">Minimal 8 karakter</div>
                                    <div id="password-rule-3">Harus ada karakter angka</div>
                                    <div id="password-rule-4">Harus ada karakter simbol</div>
                                </div>
                            </div>

                            <!-- Confirm New Password -->
                            <div>
                                <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" />
                                <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                                    class="mt-1 block w-full" autocomplete="new-password"
                                    placeholder="Konfirmasi password baru" />
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-5 sm:mt-4 flex justify-center">
                            <x-primary-button class="w-full sm:w-auto text-center">
                                {{ __('Perbarui Password') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Prevent closing the modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                e.preventDefault();
            }
        });

        // Prevent clicking outside the modal to close it
        document.querySelector('.fixed.inset-0.bg-gray-500').addEventListener('click', function(e) {
            e.stopPropagation();
        });

        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const validationDiv = document.getElementById('password-validation');
            if (password.trim() === '') {
                validationDiv.style.display = 'none';
                return;
            }
            validationDiv.style.display = 'block';
            const rules = [{
                    id: 'password-rule-1',
                    regex: /^[A-Z]/,
                    message: 'Karakter pertama harus huruf besar'
                },
                {
                    id: 'password-rule-2',
                    regex: /.{8,}/,
                    message: 'Minimal 8 karakter'
                },
                {
                    id: 'password-rule-3',
                    regex: /\d/,
                    message: 'Harus ada karakter angka'
                },
                {
                    id: 'password-rule-4',
                    regex: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/,
                    message: 'Harus ada karakter simbol'
                }
            ];

            rules.forEach(rule => {
                const element = document.getElementById(rule.id);
                if (rule.regex.test(password)) {
                    element.className = 'text-green-600';
                } else {
                    element.className = 'text-red-600';
                }
            });
        });
    </script>
</x-app-layout>
