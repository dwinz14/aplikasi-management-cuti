<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2
                    class=" border-l-[5px] border-primary-700 pl-5 font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Profile User') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Setting Profile akun user anda.
                </p>
            </div>
        </div>
    </x-slot>


    <div class="space-y-4 shadow-lg max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6" x-data="{ activeTab: '{{ $errors->updatePassword->any() ? 'password' : ($errors->any() ? 'edit' : 'overview') }}' }">
                <!-- Tabs Navigation -->
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button @click="activeTab = 'overview'"
                            :class="activeTab === 'overview' ? 'border-indigo-500 text-indigo-600' :
                                'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                            {{ __('Profile Overview') }}
                        </button>
                        <button @click="activeTab = 'edit'"
                            :class="activeTab === 'edit' ? 'border-indigo-500 text-indigo-600' :
                                'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                            {{ __('Edit Profile') }}
                        </button>
                        <button @click="activeTab = 'password'"
                            :class="activeTab === 'password' ? 'border-indigo-500 text-indigo-600' :
                                'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                            {{ __('Change Password') }}
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="mt-6">
                    <!-- Profile Overview Tab -->
                    <div x-show="activeTab === 'overview'" x-transition>

                        <div
                            class="bg-gradient-to-r from-blue-500 to-purple-600 dark:from-blue-700 dark:to-purple-800 rounded-xl shadow-2xl p-6 text-white">

                            <div class="flex items-center justify-between mb-6">

                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <img class="h-16 w-16 rounded-full border-4 border-white/30 shadow-md"
                                            src="{{ $user->photo_path ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&color=fff' }}"
                                            alt="Foto {{ strtoupper($user->name) }}">
                                    </div>
                                    <div>
                                        <h3 class="text-2xl md:text-3xl font-bold">{{ strtoupper($user->name) }}</h3>
                                        <p class="text-lg font-medium text-blue-100 dark:text-blue-200">
                                            {{ strtoupper($user->position->nama_jabatan ?? 'N/A') }}
                                        </p>
                                        <div class="text-sm font-medium text-blue-100 dark:text-blue-200">
                                            Masa Kerja : {{ $user->masaKerjaTahunBulan() }}
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right flex-shrink-0 ml-4"> {{-- ml-4 ditambah agar tidak menempel di layar kecil --}}
                                    @if ($annualType && $user->masaKerjaTahun() >= $annualType->min_years)
                                        <span class="badge bg-success">Sisa Cuti Tahunan:
                                            {{ $annualBalance->remaining ?? 0 }} Hari</span>
                                    @else
                                        <span class="badge bg-danger">Cuti Tahunan: Belum Tersedia</span>
                                    @endif
                                </div>
                            </div>

                            <div class="border-t border-white/20 pt-6">
                                <h4 class="text-lg font-semibold mb-4">Detail Informasi</h4>

                                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 pt-0.5">
                                            <svg class="w-6 h-6 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-blue-100 dark:text-blue-200">NIK</dt>
                                            <dd class="text-base font-semibold">{{ strtoupper($user->nik) }}</dd>
                                        </div>
                                    </div>

                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 pt-0.5">
                                            <svg class="w-6 h-6 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z">
                                                </path>
                                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-blue-100 dark:text-blue-200">Alamat
                                                Email</dt>
                                            <dd class="text-base font-semibold">{{ $user->email }}</dd>
                                            {{-- Email tidak di-uppercase --}}
                                        </div>
                                    </div>

                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 pt-0.5">
                                            <svg class="w-6 h-6 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-blue-100 dark:text-blue-200">Divisi</dt>
                                            <dd class="text-base font-semibold">
                                                {{ strtoupper($user->division->nama_divisi ?? 'N/A') }}</dd>
                                        </div>
                                    </div>

                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 pt-0.5">
                                            <svg class="w-6 h-6 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M4 2a2 2 0 00-2 2v11a3 3 0 106 0V4a2 2 0 00-2-2H4zm1 14a1 1 0 100-2 1 1 0 000 2zm5-1.757l4.9-4.9a2 2 0 000-2.828L13.485 5.1a2 2 0 00-2.828 0L10 5.757v8.486zM16 18H9.071l6-6H16a2 2 0 012 2v2a2 2 0 01-2 2z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-blue-100 dark:text-blue-200">Lokasi
                                                Kantor</dt>
                                            <dd class="text-base font-semibold">
                                                {{ strtoupper($user->office->nama_kantor ?? 'N/A') }}
                                            </dd>
                                        </div>
                                    </div>

                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 pt-0.5">
                                            <svg class="w-6 h-6 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z"
                                                    clip-rule="evenodd"></path>
                                                <path
                                                    d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-blue-100 dark:text-blue-200">Role Sistem
                                            </dt>
                                            <dd class="text-base font-semibold">{{ strtoupper($user->role) }}</dd>
                                        </div>
                                    </div>

                                </dl>
                            </div>

                        </div>
                    </div>
                    <!-- Edit Profile Tab -->
                    <div x-show="activeTab === 'edit'" x-transition>
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    <!-- Change Password Tab -->
                    <div x-show="activeTab === 'password'" x-transition>
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>
        </div>

    </div>

    <x-toast-notification />
</x-app-layout>
