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

    <div class="space-y-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                                class="bg-gradient-to-r from-blue-500 to-purple-600 dark:from-blue-700 dark:to-purple-800 rounded-lg p-6 text-white">
                                <h3 class="text-2xl font-bold mb-6">{{ __('Profile Akun Anda') }}</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    <div
                                        class="flex items-center space-x-3 bg-white dark:bg-gray-800 bg-opacity-10 dark:bg-opacity-20 rounded-lg p-4">
                                        <svg class="w-8 h-8 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-blue-100 dark:text-blue-200">
                                                {{ __('Nama') }}</p>
                                            <p class="text-lg font-semibold">{{ $user->name }}</p>
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-center space-x-3 bg-white dark:bg-gray-800 bg-opacity-10 dark:bg-opacity-20 rounded-lg p-4">
                                        <svg class="w-8 h-8 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z">
                                            </path>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-blue-100 dark:text-blue-200">
                                                {{ __('Email') }}</p>
                                            <p class="text-lg font-semibold">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-center space-x-3 bg-white dark:bg-gray-800 bg-opacity-10 dark:bg-opacity-20 rounded-lg p-4">
                                        <svg class="w-8 h-8 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-blue-100 dark:text-blue-200">
                                                {{ __('NIK') }}</p>
                                            <p class="text-lg font-semibold">{{ $user->nik }}</p>
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-center space-x-3 bg-white dark:bg-gray-800 bg-opacity-10 dark:bg-opacity-20 rounded-lg p-4">
                                        <svg class="w-8 h-8 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z"
                                                clip-rule="evenodd"></path>
                                            <path
                                                d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z">
                                            </path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-blue-100 dark:text-blue-200">
                                                {{ __('Role') }}</p>
                                            <p class="text-lg font-semibold">{{ ucfirst($user->role) }}</p>
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-center space-x-3 bg-white dark:bg-gray-800 bg-opacity-10 dark:bg-opacity-20 rounded-lg p-4">
                                        <svg class="w-8 h-8 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-blue-100 dark:text-blue-200">
                                                {{ __('Divisi') }}</p>
                                            <p class="text-lg font-semibold">
                                                {{ $user->division->nama_divisi ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-center space-x-3 bg-white dark:bg-gray-800 bg-opacity-10 dark:bg-opacity-20 rounded-lg p-4">
                                        <svg class="w-8 h-8 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-blue-100 dark:text-blue-200">
                                                {{ __('Sisa Cuti Tahunan') }}
                                            </p>
                                            <p class="text-lg font-semibold">{{ $user->sisa_cuti }} days</p>
                                        </div>
                                    </div>
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
    </div>
    <x-toast-notification />
</x-app-layout>
