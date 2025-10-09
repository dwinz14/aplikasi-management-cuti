<x-guest-layout>
    <div x-data="authForm()" class="w-full space-y-4">
        <!-- Mobile Title -->
        <div class="text-center lg:hidden mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">PORTAL-CUTI</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">PT BPR Artha Pamenang</p>
        </div>

        <!-- Tab Navigation - Modern Pills -->
        <div class="flex gap-2 p-1.5 bg-primary-600 dark:bg-slate-800 rounded-xl">
            <button @click="setMode('login')"
                :class="mode === 'login' ? 'bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm' :
                    'text-gray-600 dark:text-gray-400 hover:text-white dark:hover:text-white'"
                class="flex-1 py-2.5 px-4 text-sm font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500/50">
                Sign In
            </button>
            <button @click="setMode('register')"
                :class="mode === 'register' ? 'bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm' :
                    'text-gray-600 dark:text-gray-400 hover:text-white dark:hover:text-white'"
                class="flex-1 py-2.5 px-4 text-sm font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500/50">
                Sign Up
            </button>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Login Form -->
        <div x-show="mode === 'login'" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95" class="space-y-6">

            <div class="text-center">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-2">Selamat Datang</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">Silahkan login dengan akun Anda</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- NIK -->
                <div>
                    <x-input-label for="nik" :value="__('NIK')"
                        class="mb-2 text-gray-800 dark:text-gray-200 font-medium" />
                    <x-text-input id="nik"
                        class="block w-full px-4 py-3 bg-white dark:bg-slate-800 border-2 border-gray-200 dark:border-slate-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-primary-500 dark:focus:border-primary-400 focus:ring-2 focus:ring-primary-500/20 dark:focus:ring-primary-400/20 rounded-xl transition-all duration-200"
                        type="text" name="nik" :value="old('nik')" required autofocus autocomplete="nik"
                        placeholder="Masukkan NIK..." />
                    <x-input-error :messages="$errors->get('nik')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')"
                        class="mb-2 text-gray-800 dark:text-gray-200 font-medium" />
                    <x-text-input id="password"
                        class="block w-full px-4 py-3 bg-white dark:bg-slate-800 border-2 border-gray-200 dark:border-slate-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-primary-500 dark:focus:border-primary-400 focus:ring-2 focus:ring-primary-500/20 dark:focus:ring-primary-400/20 rounded-xl transition-all duration-200"
                        type="password" name="password" required autocomplete="current-password"
                        placeholder="Masukkan password..." />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <x-primary-button
                    class="w-full justify-center py-3.5 px-4 bg-primary-600 hover:bg-primary-700 active:bg-primary-800 dark:bg-primary-500 dark:hover:bg-primary-600 focus:ring-4 focus:ring-primary-500/30 dark:focus:ring-primary-400/30 transition-all duration-200 rounded-xl shadow-lg shadow-primary-500/20 font-semibold drop-shadow-lg drop-shadow-indigo-500/50">
                    {{ __('Sign In') }}
                </x-primary-button>
            </form>
        </div>

        <!-- Register Form - Multi-step for better UX -->
        <div x-show="mode === 'register'" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95" x-data="{ step: 1, maxStep: 2 }" class="space-y-6">

            <div class="text-center">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-2">Buat Akun Baru</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">Lengkapi data diri untuk registrasi</p>
            </div>

            <!-- Progress Indicator -->
            <div class="flex items-center justify-center gap-2">
                <div :class="step >= 1 ? 'bg-primary-600 dark:bg-primary-500' : 'bg-gray-300 dark:bg-slate-700'"
                    class="h-2 flex-1 rounded-full transition-all duration-300"></div>
                <div :class="step >= 2 ? 'bg-primary-600 dark:bg-primary-500' : 'bg-gray-300 dark:bg-slate-700'"
                    class="h-2 flex-1 rounded-full transition-all duration-300"></div>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Step 1: Personal Info -->
                <div x-show="step === 1" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="space-y-5">

                    <!-- NIK -->
                    <div>
                        <x-input-label for="nik_register" :value="__('NIK')"
                            class="mb-2 text-gray-800 dark:text-gray-200 font-medium" />
                        <x-text-input id="nik_register"
                            class="block w-full px-4 py-3 bg-white dark:bg-slate-800 border-2 border-gray-200 dark:border-slate-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-primary-500 dark:focus:border-primary-400 focus:ring-2 focus:ring-primary-500/20 dark:focus:ring-primary-400/20 rounded-xl transition-all duration-200"
                            type="text" name="nik" :value="old('nik')" required autocomplete="nik"
                            placeholder="AP123456789" />
                        <x-input-error :messages="$errors->get('nik')" class="mt-2" />
                        <div id="nik-validation" class="mt-2 text-sm font-medium" style="display: none;"></div>
                    </div>

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Nama Lengkap')"
                            class="mb-2 text-gray-800 dark:text-gray-200 font-medium" />
                        <x-text-input id="name"
                            class="block w-full px-4 py-3 bg-white dark:bg-slate-800 border-2 border-gray-200 dark:border-slate-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-primary-500 dark:focus:border-primary-400 focus:ring-2 focus:ring-primary-500/20 dark:focus:ring-primary-400/20 rounded-xl transition-all duration-200"
                            type="text" name="name" :value="old('name')" required autocomplete="name"
                            placeholder="Nama sesuai KTP" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Email')"
                            class="mb-2 text-gray-800 dark:text-gray-200 font-medium" />
                        <x-text-input id="email"
                            class="block w-full px-4 py-3 bg-white dark:bg-slate-800 border-2 border-gray-200 dark:border-slate-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-primary-500 dark:focus:border-primary-400 focus:ring-2 focus:ring-primary-500/20 dark:focus:ring-primary-400/20 rounded-xl transition-all duration-200"
                            type="email" name="email" :value="old('email')" required autocomplete="username"
                            placeholder="email@example.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <button type="button" @click="step = 2"
                        class="w-full py-3.5 px-4 bg-primary-600 hover:bg-primary-700 active:bg-primary-800 dark:bg-primary-500 dark:hover:bg-primary-600 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-primary-500/20 focus:outline-none focus:ring-4 focus:ring-primary-500/30">
                        Lanjutkan
                    </button>
                </div>

                <!-- Step 2: Work Info & Password -->
                <div x-show="step === 2" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="space-y-5">

                    <!-- Role & Division in Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Role -->
                        <div>
                            <x-input-label for="role" :value="__('Role')"
                                class="mb-2 text-gray-800 dark:text-gray-200 font-medium" />
                            <select id="role" name="role" required
                                class="block w-full px-4 py-3 bg-white dark:bg-slate-800 border-2 border-gray-200 dark:border-slate-700 text-gray-900 dark:text-gray-100 focus:border-primary-500 dark:focus:border-primary-400 focus:ring-2 focus:ring-primary-500/20 dark:focus:ring-primary-400/20 rounded-xl transition-all duration-200">
                                <option value="">Pilih Role</option>
                                <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="kasie" {{ old('role') == 'kasie' ? 'selected' : '' }}>Kasie</option>
                                <option value="kabag" {{ old('role') == 'kabag' ? 'selected' : '' }}>Kabag</option>
                                <option value="hrd" {{ old('role') == 'hrd' ? 'selected' : '' }}>HRD</option>
                                <option value="direksi" {{ old('role') == 'direksi' ? 'selected' : '' }}>Direksi
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <!-- Division -->
                        <div>
                            <x-input-label for="division_id" :value="__('Divisi')"
                                class="mb-2 text-gray-800 dark:text-gray-200 font-medium" />
                            <select id="division_id" name="division_id" required
                                class="block w-full px-4 py-3 bg-white dark:bg-slate-800 border-2 border-gray-200 dark:border-slate-700 text-gray-900 dark:text-gray-100 focus:border-primary-500 dark:focus:border-primary-400 focus:ring-2 focus:ring-primary-500/20 dark:focus:ring-primary-400/20 rounded-xl transition-all duration-200">
                                <option value="">Pilih Divisi</option>
                                @foreach (\App\Models\Division::all() as $division)
                                    <option value="{{ $division->id }}"
                                        {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                        {{ $division->nama_divisi }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('division_id')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password_register" :value="__('Password')"
                            class="mb-2 text-gray-800 dark:text-gray-200 font-medium" />
                        <x-text-input id="password_register"
                            class="block w-full px-4 py-3 bg-white dark:bg-slate-800 border-2 border-gray-200 dark:border-slate-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-primary-500 dark:focus:border-primary-400 focus:ring-2 focus:ring-primary-500/20 dark:focus:ring-primary-400/20 rounded-xl transition-all duration-200"
                            type="password" name="password" required autocomplete="new-password"
                            placeholder="Minimal 8 karakter" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />

                        <!-- Password Strength Indicator -->
                        <div id="password-validation"
                            class="mt-3 p-3 bg-gray-50 dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-700"
                            style="display: none;">
                            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Persyaratan
                                Password:</p>
                            <div class="space-y-1.5">
                                <div id="password-rule-1" class="flex items-center gap-2 text-xs">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Huruf besar di awal</span>
                                </div>
                                <div id="password-rule-2" class="flex items-center gap-2 text-xs">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Minimal 8 karakter</span>
                                </div>
                                <div id="password-rule-3" class="flex items-center gap-2 text-xs">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Mengandung angka</span>
                                </div>
                                <div id="password-rule-4" class="flex items-center gap-2 text-xs">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Mengandung simbol (!@#$%...)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')"
                            class="mb-2 text-gray-800 dark:text-gray-200 font-medium" />
                        <x-text-input id="password_confirmation"
                            class="block w-full px-4 py-3 bg-white dark:bg-slate-800 border-2 border-gray-200 dark:border-slate-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-primary-500 dark:focus:border-primary-400 focus:ring-2 focus:ring-primary-500/20 dark:focus:ring-primary-400/20 rounded-xl transition-all duration-200"
                            type="password" name="password_confirmation" required autocomplete="new-password"
                            placeholder="Ketik ulang password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <button type="button" @click="step = 1"
                            class="px-6 py-3.5 bg-gray-200 hover:bg-gray-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-800 dark:text-gray-200 font-semibold rounded-xl transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-gray-300/50 dark:focus:ring-slate-500/50">
                            Kembali
                        </button>
                        <x-primary-button
                            class="flex-1 justify-center py-3.5 px-4 bg-primary-600 hover:bg-primary-700 active:bg-primary-800 dark:bg-primary-500 dark:hover:bg-primary-600 focus:ring-4 focus:ring-primary-500/30 dark:focus:ring-primary-400/30 transition-all duration-200 rounded-xl shadow-lg shadow-primary-500/20 font-semibold">
                            {{ __('Daftar Sekarang') }}
                        </x-primary-button>
                    </div>
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

        document.addEventListener('DOMContentLoaded', function() {
            // NIK validation with better UX
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
                        validationDiv.innerHTML =
                            '<span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>NIK valid</span>';
                        validationDiv.className =
                            'mt-2 text-sm font-medium text-green-600 dark:text-green-400';
                    } else {
                        validationDiv.innerHTML =
                            '<span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>Format: AP + 9 digit angka</span>';
                        validationDiv.className = 'mt-2 text-sm font-medium text-red-600 dark:text-red-400';
                    }
                });
            }

            // Password validation with better visual feedback
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
                            valid: false
                        },
                        {
                            id: 'password-rule-2',
                            regex: /.{8,}/,
                            valid: false
                        },
                        {
                            id: 'password-rule-3',
                            regex: /\d/,
                            valid: false
                        },
                        {
                            id: 'password-rule-4',
                            regex: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/,
                            valid: false
                        }
                    ];

                    rules.forEach(rule => {
                        const element = document.getElementById(rule.id);
                        if (element) {
                            const isValid = rule.regex.test(password);
                            if (isValid) {
                                element.className =
                                    'flex items-center gap-2 text-xs text-green-600 dark:text-green-400 font-medium';
                            } else {
                                element.className =
                                    'flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400';
                            }
                        }
                    });
                });
            }
        });
    </script>
</x-guest-layout>
