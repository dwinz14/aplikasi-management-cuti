<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2
                    class="border-l-[5px] border-primary-700 pl-5 font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ now()->format('l, d F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Enhanced Welcome Section -->
        <div class="relative overflow-hidden bg-gradient-to-r from-primary-600 to-primary-800 rounded-2xl shadow-xl">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="relative p-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">
                            Selamat Datang Kembali, {{ auth()->user()->name }}! 👋
                        </h1>
                        <p class="text-primary-100 text-lg">
                            Berikut adalah ringkasan aktivitas dan status cuti Anda hari ini
                        </p>
                        <div class="mt-4 flex items-center space-x-4">
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg px-3 py-1">
                                <span class="text-sm font-medium">{{ auth()->user()->role }}</span>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg px-3 py-1">
                                <span class="text-sm">
                                    {{ auth()->user()->division->nama_divisi ?? 'Division' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div
                            class="w-24 h-24 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        @if (auth()->user()->role !== 'super_admin')
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Aksi Cepat</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('cuti.create') }}"
                        class="flex items-center p-4 bg-primary-50 hover:bg-primary-100 dark:bg-primary-900/20 dark:hover:bg-primary-900/30 rounded-lg transition-all duration-200 group">
                        <div class="bg-primary-500 p-2 rounded-lg mr-3 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-primary-900 dark:text-primary-100">Ajukan Cuti</p>
                            <p class="text-sm text-primary-600 dark:text-primary-300">Buat pengajuan baru</p>
                        </div>
                    </a>

                    <a href="{{ route('cuti.index') }}"
                        class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/30 rounded-lg transition-all duration-200 group">
                        <div class="bg-blue-500 p-2 rounded-lg mr-3 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-blue-900 dark:text-blue-100">Riwayat Cuti</p>
                            <p class="text-sm text-blue-600 dark:text-blue-300">Lihat pengajuan Anda</p>
                        </div>
                    </a>

                    @if (auth()->user()->role !== 'super_admin' && auth()->user()->role !== 'hrd')
                        <a href="{{ route('approval.index') }}"
                            class="flex items-center p-4 bg-green-50 hover:bg-green-100 dark:bg-green-900/20 dark:hover:bg-green-900/30 rounded-lg transition-all duration-200 group">
                            <div class="bg-green-500 p-2 rounded-lg mr-3 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-green-900 dark:text-green-100">Persetujuan</p>
                                <p class="text-sm text-green-600 dark:text-green-300">Kelola approval</p>
                            </div>
                        </a>
                    @endif

                    @if (auth()->user()->role !== 'super_admin' && auth()->user()->role !== 'hrd')
                        <a href="{{ route('approval.history') }}"
                            class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 dark:bg-purple-900/20 dark:hover:bg-purple-900/30 rounded-lg transition-all duration-200 group">
                            <div class="bg-purple-500 p-2 rounded-lg mr-3 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-purple-900 dark:text-purple-100">Riwayat Approval</p>
                                <p class="text-sm text-purple-600 dark:text-purple-300">Lihat history</p>
                            </div>
                        </a>
                    @endif
                </div>
            </div>
        @endif
        @if (auth()->user()->role !== 'super_admin')
            <div>
                <h3 class="text-xl font-semibold text-gray-500 dark:text-gray-500 mb-6">Ringkasan Cuti Anda</h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">

                    {{-- Enhanced stat cards with progress bars and better styling --}}

                    <!-- Remaining Leave Card -->
                    <div
                        class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-green-500">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sisa Cuti Tahunan</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $sisaCuti }} <span
                                        class="text-lg text-gray-500">Hari</span></p>
                            </div>
                            <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-full">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full"
                                style="width: {{ $sisaCuti > 0 ? min(($sisaCuti / 12) * 100, 100) : 0 }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">dari total cuti tahun ini</p>
                    </div>

                    <!-- Used Leave Card -->
                    <div
                        class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Cuti Sudah Digunakan</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $cutiDigunakan }}
                                    <span class="text-lg text-gray-500">Hari</span>
                                </p>
                            </div>
                            <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-full">
                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full"
                                style="width: {{ $cutiDigunakan > 0 ? min(($cutiDigunakan / 12) * 100, 100) : 0 }}%">
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">dari total cuti yang disetujui</p>
                    </div>

                    <!-- Pending Approvals Card -->
                    <div
                        class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-yellow-500">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Menunggu Persetujuan
                                </p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ $menungguPersetujuan }}
                                    <span class="text-lg text-gray-500">Pengajuan</span>
                                </p>
                            </div>
                            <div class="bg-yellow-100 dark:bg-yellow-900/30 p-3 rounded-full">
                                <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        @if ($menungguPersetujuan > 0)
                            <div
                                class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-3 mt-4">
                                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                    <span class="font-medium">Status:</span> Ada pengajuan yang sedang diproses
                                </p>
                            </div>
                        @else
                            <div
                                class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3 mt-4">
                                <p class="text-sm text-green-800 dark:text-green-200">
                                    <span class="font-medium">Status:</span> Semua pengajuan telah diproses
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        <!-- Recent Activity Section -->
        {{-- <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Aktivitas Terbaru</h3>
                <a href="{{ route('cuti.index') }}"
                    class="text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 text-sm font-medium">
                    Lihat Semua →
                </a>
            </div>

            <div class="space-y-4">
                <!-- Placeholder for recent activities - you can enhance this with real data -->
                <div class="flex items-center p-4 bg-gray-50 dark:bg-slate-700 rounded-lg">
                    <div class="bg-primary-100 dark:bg-primary-900/30 p-2 rounded-full mr-4">
                        <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Dashboard diperbarui</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Sekarang menampilkan data real cuti Anda
                        </p>
                    </div>
                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ now()->format('d/m/Y') }}</span>
                </div>

                <div class="flex items-center p-4 bg-gray-50 dark:bg-slate-700 rounded-lg">
                    <div class="bg-green-100 dark:bg-green-900/30 p-2 rounded-full mr-4">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Sistem persetujuan aktif</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Pengajuan cuti dapat diproses dengan cepat
                        </p>
                    </div>
                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ now()->format('d/m/Y') }}</span>
                </div>
            </div>
        </div> --}}
    </div>
</x-app-layout>
