<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2
                    class="border-l-[5px] border-primary-700 pl-5 font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Kuota Cuti Karyawan') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">
                    Setting sisa cuti seluruh karyawan perusahaan.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-4">
        {{-- Notifikasi --}}
        @if (session('success'))
            <div
                class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3 mb-4 animate-fade-in">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-gray-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Reset Semua --}}
        <div
            class="bg-white dark:bg-slate-800 shadow-md rounded-lg p-4 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-200">
            <div class="flex items-center mb-3">
                <svg class="w-4 h-4 text-primary-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                    </path>
                </svg>
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-200">Reset Kuota Cuti</h3>
            </div>

            <form action="{{ route('hrd.quota.reset') }}" method="POST"
                class="flex flex-col sm:flex-row items-center gap-3">
                @csrf
                <div class="flex items-center gap-2 flex-1">
                    <label for="default_quota" class="text-sm text-gray-600 dark:text-gray-300 whitespace-nowrap">
                        Kuota Default
                    </label>
                    <input type="number" id="default_quota" name="default_quota" value="12" min="0"
                        class="w-20 px-2 py-1.5 border border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-primary-500 transition">
                    <span class="text-xs text-gray-500 dark:text-gray-400">hari</span>
                </div>

                <button type="submit"
                    class="inline-flex items-center px-3 py-1.5 bg-primary-600 hover:bg-primary-700 rounded-md text-sm font-medium text-white shadow-sm transition">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    Reset
                </button>
            </form>
        </div>

        {{-- Tabel Kuota --}}
        <div
            class="bg-white dark:bg-slate-800 hover:shadow-xl/30 hover:-translate-y-1 transition-all duration-300 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-slate-700/50">
                        <tr>
                            <th scope="col"
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Nama
                            </th>
                            <th scope="col"
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                                Divisi
                            </th>
                            <th scope="col"
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Role
                            </th>
                            <th scope="col"
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Sisa Cuti
                            </th>
                            <th scope="col"
                                class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition duration-150">
                                <td
                                    class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-100">
                                    {{ $user->name }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $user->division->nama_divisi ?? '-' }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $user->role }}
                                </td>
                                <td
                                    class="px-4 py-2 whitespace-nowrap text-sm font-semibold text-primary-600 dark:text-primary-400">
                                    {{ $user->sisa_cuti }} hari
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-right text-sm font-medium">
                                    <form action="{{ route('hrd.quota.update', $user) }}" method="POST"
                                        class="flex items-center justify-end gap-2">
                                        @csrf
                                        <input type="number" name="sisa_cuti" value="{{ $user->sisa_cuti }}"
                                            min="0"
                                            class="block w-16 px-2 py-1 border border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 rounded text-sm focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition">
                                        <button type="submit"
                                            class="inline-flex items-center justify-center px-2 py-1 bg-primary-600 border border-transparent rounded font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-500 active:bg-primary-700 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:ring-offset-1 dark:ring-offset-slate-800 transition ease-in-out duration-150">
                                            Update
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center w-full">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                        <p class="text-base font-semibold mb-1 text-gray-800 dark:text-gray-100">Tidak
                                            ada data karyawan</p>
                                        <p class="text-sm">Belum ada karyawan yang terdaftar dalam sistem.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
