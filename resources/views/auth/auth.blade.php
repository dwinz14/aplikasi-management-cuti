<x-guest-layout>
    <div x-data="authForm()" class="w-full max-w-md mx-auto">
        <!-- Tab Navigation -->
        <div class="flex mb-6 bg-gray-100 dark:bg-slate-700 rounded-lg p-1">
            <button @click="setMode('login')"
                :class="mode === 'login' ? 'bg-white dark:bg-slate-600 text-primary-600 dark:text-primary-400 shadow-sm' :
                    'text-gray-600 dark:text-gray-400'"
                class="flex-1 py-2 px-4 text-sm font-medium rounded-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
                Sign In
            </button>
            <button @click="setMode('register')"
                :class="mode === 'register' ? 'bg-white dark:bg-slate-600 text-primary-600 dark:text-primary-400 shadow-sm' :
                    'text-gray-600 dark:text-gray-400'"
                class="flex-1 py-2 px-4 text-sm font-medium rounded-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
                Sign Up
            </button>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Login Form -->
        <div x-show="mode === 'login'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-x-4"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform -translate-x-4" class="space-y-6">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">Selamat Datang</h2>
                <p class="text-gray-600 dark:text-gray-400">Silahkan Login Dengan Akun Anda</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- NIK -->
                <div>
                    <x-input-label for="nik" :value="__('NIK')" class="text-gray-700 dark:text-gray-300" />
                    <x-text-input id="nik"
                        class="block mt-1 w-full bg-gray-50 dark:bg-slate-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:border-primary-500 dark:focus:border-primary-400 focus:ring-primary-500 dark:focus:ring-primary-400 rounded-lg shadow-sm transition-colors duration-200"
                        type="text" name="nik" :value="old('nik')" required autofocus autocomplete="nik"
                        placeholder="Masukkan NIK..." />
                    <x-input-error :messages="$errors->get('nik')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" class="text-gray-700 dark:text-gray-300" />
                    <x-text-input id="password"
                        class="block mt-1 w-full bg-gray-50 dark:bg-slate-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:border-primary-500 dark:focus:border-primary-400 focus:ring-primary-500 dark:focus:ring-primary-400 rounded-lg shadow-sm transition-colors duration-200"
                        type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end">
                    <x-primary-button
                        class="w-full justify-center py-3 px-4 bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 focus:ring-primary-500 dark:focus:ring-primary-400 transition-all duration-200 rounded-lg shadow-sm">
                        {{ __('Sign In') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

        <!-- Register Form -->
        <div x-show="mode === 'register'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-x-4"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform -translate-x-4" class="space-y-6">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">Register Akun</h2>
                <p class="text-gray-600 dark:text-gray-400">Silahkan Isi Form Untuk Daftar Akun</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- NIK -->
                <div>
                    <x-input-label for="nik" :value="__('NIK')" class="text-gray-700 dark:text-gray-300" />
                    <x-text-input id="nik_register"
                        class="block mt-1 w-full bg-gray-50 dark:bg-slate-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:border-primary-500 dark:focus:border-primary-400 focus:ring-primary-500 dark:focus:ring-primary-400 rounded-lg shadow-sm transition-colors duration-200"
                        type="text" name="nik" :value="old('nik')" required autocomplete="nik"
                        placeholder="AP123456789" />
                    <x-input-error :messages="$errors->get('nik')" class="mt-2" />
                    <div id="nik-validation" class="mt-1 text-sm" style="display: none;"></div>
                </div>

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Nama')" class="text-gray-700 dark:text-gray-300" />
                    <x-text-input id="name"
                        class="block mt-1 w-full bg-gray-50 dark:bg-slate-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:border-primary-500 dark:focus:border-primary-400 focus:ring-primary-500 dark:focus:ring-primary-400 rounded-lg shadow-sm transition-colors duration-200"
                        type="text" name="name" :value="old('name')" required autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-gray-700 dark:text-gray-300" />
                    <x-text-input id="email"
                        class="block mt-1 w-full bg-gray-50 dark:bg-slate-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:border-primary-500 dark:focus:border-primary-400 focus:ring-primary-500 dark:focus:ring-primary-400 rounded-lg shadow-sm transition-colors duration-200"
                        type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Role -->
                <div>
                    <x-input-label for="role" :value="__('Role')" class="text-gray-700 dark:text-gray-300" />
                    <select id="role" name="role"
                        class="block mt-1 w-full bg-gray-50 dark:bg-slate-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 focus:border-primary-500 dark:focus:border-primary-400 focus:ring-primary-500 dark:focus:ring-primary-400 rounded-lg shadow-sm transition-colors duration-200">
                        <option value="">Pilih Role</option>
                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="kasie" {{ old('role') == 'kasie' ? 'selected' : '' }}>Kasie</option>
                        <option value="kabag" {{ old('role') == 'kabag' ? 'selected' : '' }}>Kabag</option>
                        <option value="hrd" {{ old('role') == 'hrd' ? 'selected' : '' }}>HRD</option>
                        <option value="direksi" {{ old('role') == 'direksi' ? 'selected' : '' }}>Direksi</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>

                <!-- Division -->
                <div>
                    <x-input-label for="division_id" :value="__('Divisi')" class="text-gray-700 dark:text-gray-300" />
                    <select id="division_id" name="division_id"
                        class="block mt-1 w-full bg-gray-50 dark:bg-slate-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 focus:border-primary-500 dark:focus:border-primary-400 focus:ring-primary-500 dark:focus:ring-primary-400 rounded-lg shadow-sm transition-colors duration-200">
                        <option value="">Pilih Divisi</option>
                        @foreach (\App\Models\Division::all() as $division)
                            <option value="{{ $division->id }}"
                                {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                {{ $division->nama_divisi }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('division_id')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" class="text-gray-700 dark:text-gray-300" />
                    <x-text-input id="password_register"
                        class="block mt-1 w-full bg-gray-50 dark:bg-slate-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:border-primary-500 dark:focus:border-primary-400 focus:ring-primary-500 dark:focus:ring-primary-400 rounded-lg shadow-sm transition-colors duration-200"
                        type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    <div id="password-validation" class="mt-1 text-sm" style="display: none;">
                        <div id="password-rule-1">Karakter pertama harus huruf besar</div>
                        <div id="password-rule-2">Minimal 8 karakter</div>
                        <div id="password-rule-3">Harus ada karakter angka</div>
                        <div id="password-rule-4">Harus ada karakter simbol</div>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')"
                        class="text-gray-700 dark:text-gray-300" />
                    <x-text-input id="password_confirmation"
                        class="block mt-1 w-full bg-gray-50 dark:bg-slate-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:border-primary-500 dark:focus:border-primary-400 focus:ring-primary-500 dark:focus:ring-primary-400 rounded-lg shadow-sm transition-colors duration-200"
                        type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end">
                    <x-primary-button
                        class="w-full justify-center py-3 px-4 bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 focus:ring-primary-500 dark:focus:ring-primary-400 transition-all duration-200 rounded-lg shadow-sm">
                        {{ __('Register') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function authForm() {
            return {
                mode: '{{ $mode ?? 'login' }}',
                setMode(newMode) {
                    this.mode = newMode;
                }
            }
        }

        // NIK validation for register
        document.addEventListener('DOMContentLoaded', function() {
            const nikInput = document.getElementById('nik_register');
            if (nikInput) {
                nikInput.addEventListener('input', function() {
                    const nik = this.value;
                    const validationDiv = document.getElementById('nik-validation');
                    if (nik.trim() === '') {
                        validationDiv.style.display = 'none';
                        return;
                    }
                    validationDiv.style.display = 'block';
                    if (/^AP\d{9}$/.test(nik)) {
                        validationDiv.textContent = 'NIK valid';
                        validationDiv.className = 'mt-1 text-sm text-green-600 dark:text-green-400';
                    } else {
                        validationDiv.textContent = 'NIK harus dimulai dengan "AP" diikuti 9 angka';
                        validationDiv.className = 'mt-1 text-sm text-red-600 dark:text-red-400';
                    }
                });
            }

            // Password validation for register
            const passwordInput = document.getElementById('password_register');
            if (passwordInput) {
                passwordInput.addEventListener('input', function() {
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
                        if (element) {
                            if (rule.regex.test(password)) {
                                element.className = 'text-green-600 dark:text-green-400';
                            } else {
                                element.className = 'text-red-600 dark:text-red-400';
                            }
                        }
                    });
                });
            }
        });
    </script>
</x-guest-layout>
