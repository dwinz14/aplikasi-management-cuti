<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2
                    class="border-l-4 border-primary-700 pl-5 font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Manajemen Kuota Cuti') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola kuota cuti karyawan dengan mudah dan efisien.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-4" x-data="{ showFilter: false, showReset: false }">
        {{-- Filter & Search --}}
        <div class="bg-white dark:bg-slate-800 shadow-lg rounded-xl p-4 transition-all">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Filter & Pencarian</h3>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        Gunakan filter untuk mencari data spesifik
                    </span>
                    <button @click="showFilter = !showFilter"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <svg class="w-4 h-4 ml-2 transition-transform" :class="showFilter ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="gap-2" x-show="showFilter" x-collapse>
                <form method="GET" action="{{ route('hrd.quota.index') }}">
                    <input type="hidden" name="leave_type_id" value="{{ $leaveTypeId }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                        {{-- Search --}}
                        <div>
                            <label for="search"
                                class="block text-xs font-medium mb-1 text-gray-700 dark:text-gray-300">Cari
                                Nama</label>
                            <input type="text" id="search" name="search" value="{{ $search }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-xs"
                                placeholder="Ketik nama karyawan...">
                        </div>

                        {{-- Position --}}
                        <div>
                            <label for="position_id"
                                class="block text-xs font-medium mb-1 text-gray-700 dark:text-gray-300">Jabatan</label>
                            <select id="position_id" name="position_id"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-xs">
                                <option value="">-- Semua Jabatan --</option>
                                @foreach ($positions as $position)
                                    <option value="{{ $position->id }}"
                                        {{ $positionId == $position->id ? 'selected' : '' }}>
                                        {{ strtoupper($position->nama_jabatan) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Office --}}
                        <div>
                            <label for="office_id"
                                class="block text-xs font-medium mb-1 text-gray-700 dark:text-gray-300">Kantor</label>
                            <select id="office_id" name="office_id"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-xs">
                                <option value="">-- Semua Kantor --</option>
                                @foreach ($offices as $office)
                                    <option value="{{ $office->id }}"
                                        {{ $officeId == $office->id ? 'selected' : '' }}>
                                        {{ strtoupper($office->nama_kantor) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Role --}}
                        <div>
                            <label for="role"
                                class="block text-xs font-medium mb-1 text-gray-700 dark:text-gray-300">Role</label>
                            <select id="role" name="role"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-xs">
                                <option value="">-- Semua Role --</option>
                                <option value="staff" {{ $role == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="kasie" {{ $role == 'kasie' ? 'selected' : '' }}>Kasie</option>
                                <option value="kabag-pincab" {{ $role == 'kabag-pincab' ? 'selected' : '' }}>
                                    kabag-pincab</option>
                                <option value="hrd" {{ $role == 'hrd' ? 'selected' : '' }}>HRD</option>
                            </select>
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-col md:flex-row gap-2 items-stretch justify-start">
                            <button type="submit"
                                class="inline-flex items-center justify-center px-3 py-1.5 bg-primary-600 rounded-md text-white font-medium text-xs hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 transition">
                                Terapkan
                            </button>
                            <a href="{{ route('hrd.quota.index', ['leave_type_id' => $leaveTypeId]) }}"
                                class="inline-flex items-center justify-center px-3 py-1.5 bg-gray-100 dark:bg-slate-700 rounded-md text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-slate-600 transition">
                                ↺ Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Reset Kuota --}}
        <div class="bg-white dark:bg-slate-800 shadow-lg rounded-xl p-4">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Pengaturan Kuota Cuti</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Reset kuota cuti untuk semua karyawan atau per
                        jabatan</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-amber-600 dark:text-amber-400">
                        Aksi ini tidak dapat dibatalkan
                    </span>
                    <button @click="showReset = !showReset"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <svg class="w-4 h-4 ml-2 transition-transform" :class="showReset ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4" x-show="showReset" x-collapse>
                {{-- Settings --}}
                <div class="p-3 rounded-lg border border-blue-300 dark:border-blue-700 bg-blue-50 dark:bg-blue-900/20">
                    <h4 class="text-xs font-medium text-blue-700 dark:text-blue-200 mb-2">Pengaturan Sistem</h4>
                    <form action="{{ route('hrd.quota.settings') }}" method="POST">
                        @csrf
                        <div class="space-y-2">
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="auto_generate_leave_balances" value="1"
                                        {{ $autoGenerate ? 'checked' : '' }}
                                        class="rounded border-gray-300 dark:border-gray-600 dark:bg-slate-700 text-primary-600 focus:ring-primary-500">
                                    <span class="ml-2 text-xs text-gray-700 dark:text-gray-300">Auto buat saldo cuti
                                        untuk user baru</span>
                                </label>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-700 dark:text-gray-300 mb-1">Kuota default</label>
                                <input type="number" name="default_annual_leave_quota" value="{{ $defaultQuota }}"
                                    min="0"
                                    class="w-full rounded-md border-blue-300 dark:border-blue-600 dark:bg-blue-900/50 dark:text-blue-200 text-xs focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <button type="submit"
                                class="w-full px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                                Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Semua --}}
                <div class="p-3 rounded-lg border border-red-300 dark:border-red-700 bg-red-50 dark:bg-red-900/20">
                    <h4 class="text-xs font-medium text-red-700 dark:text-red-200 mb-1">Reset Semua Karyawan</h4>
                    <form action="{{ route('hrd.quota.reset') }}" method="POST" x-data
                        @submit.prevent="if(confirm('Apakah yakin reset semua kuota?')) $el.submit()">
                        @csrf
                        <input type="hidden" name="leave_type_id" value="{{ $leaveTypeId }}">
                        <div class="flex gap-2">
                            <input type="number" name="default_quota" value="{{ $defaultQuota }}" min="0"
                                class="flex-1 rounded-md border-red-300 dark:border-red-600 dark:bg-red-900/50 dark:text-red-200 text-xs focus:border-red-500 focus:ring-red-500">
                            <button type="submit"
                                class="px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-md hover:bg-red-700 focus:ring-2 focus:ring-red-500">
                                Reset Semua
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Per Jabatan --}}
                <div
                    class="p-3 rounded-lg border border-yellow-300 dark:border-yellow-700 bg-yellow-50 dark:bg-yellow-900/20">
                    <h4 class="text-xs font-medium text-yellow-700 dark:text-yellow-200 mb-1">Reset per Jabatan</h4>
                    <form action="{{ route('hrd.quota.resetPosition') }}" method="POST" x-data
                        @submit.prevent="if(confirm('Apakah yakin reset kuota jabatan ini?')) $el.submit()">
                        @csrf
                        <input type="hidden" name="leave_type_id" value="{{ $leaveTypeId }}">
                        <div class="space-y-2">
                            <select name="position_id" required
                                class="w-full rounded-md border-yellow-300 dark:border-yellow-600 dark:bg-yellow-900/70 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-xs">
                                <option value="">-- Pilih Jabatan --</option>
                                @foreach ($positions as $position)
                                    <option value="{{ $position->id }}">{{ strtoupper($position->nama_jabatan) }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="flex gap-2">
                                <input type="number" name="default_quota" value="{{ $defaultQuota }}"
                                    min="0"
                                    class="flex-1 rounded-md border-yellow-300 dark:border-yellow-600 dark:bg-yellow-900/50 dark:text-yellow-200 text-xs focus:border-yellow-500 focus:ring-yellow-500">
                                <button type="submit"
                                    class="px-3 py-1.5 bg-yellow-600 text-white text-xs font-medium rounded-md hover:bg-yellow-700 focus:ring-2 focus:ring-yellow-500">
                                    Reset Jabatan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Tabel Kuota --}}
        <div class="bg-white dark:bg-slate-800 shadow-lg rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <div class="flex items-center px-4 py-2 gap-4">
                    <form method="GET" action="{{ route('hrd.quota.index') }}" class="flex items-center gap-2">
                        <label for="leave_type_id" class="text-sm font-medium text-gray-700 dark:text-gray-300">Jenis
                            Cuti:</label>
                        <select id="leave_type_id" name="leave_type_id" onchange="this.form.submit()"
                            class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                            @foreach ($leaveTypes as $leaveType)
                                <option value="{{ $leaveType->id }}"
                                    {{ $leaveTypeId == $leaveType->id ? 'selected' : '' }}>
                                    {{ strtoupper($leaveType->name) }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="overflow-x-auto bg-white dark:bg-slate-800 rounded-lg shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead
                            class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-slate-700 dark:to-slate-700/80">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                    Nama Karyawan
                                </th>
                                <th scope="col"
                                    class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                    Role
                                </th>
                                <th scope="col"
                                    class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                    Jabatan
                                </th>
                                <th scope="col"
                                    class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                    Kantor
                                </th>
                                <th scope="col"
                                    class="px-6 py-3.5 text-center text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                    Alokasi
                                </th>
                                <th scope="col"
                                    class="px-6 py-3.5 text-center text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                    Terpakai
                                </th>
                                <th scope="col"
                                    class="px-6 py-3.5 text-center text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                    Sisa
                                </th>
                                <th scope="col"
                                    class="px-6 py-3.5 text-center text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($userLeaveBalances as $balance)
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center">
                                                <span class="text-white font-semibold text-sm">
                                                    {{ strtoupper(substr($balance->user->name, 0, 2)) }}
                                                </span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ Str::title($balance->user->name) }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                            {{ strtoupper($balance->user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-700 dark:text-gray-300 font-medium">
                                            {{ strtoupper($balance->user->position->nama_jabatan ?? '-') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-700 dark:text-gray-300 font-medium">
                                            {{ strtoupper($balance->user->office->nama_kantor ?? '-') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <div class="inline-flex flex-col items-center">
                                            <span class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                                {{ $balance->total_quota }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">hari</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <div class="inline-flex flex-col items-center">
                                            <span class="text-lg font-bold text-orange-600 dark:text-orange-400">
                                                {{ $balance->used }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">hari</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <div class="inline-flex flex-col items-center">
                                            <span class="text-lg font-bold text-green-600 dark:text-green-400">
                                                {{ $balance->remaining }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">hari</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form
                                            action="{{ route('hrd.quota.update', [$balance->user, $balance->leaveType]) }}"
                                            method="POST" class="flex items-center justify-center gap-2">
                                            @csrf
                                            @method('POST')
                                            <div class="relative">
                                                <input type="number" name="remaining"
                                                    value="{{ $balance->remaining }}" min="0"
                                                    class="w-20 px-3 py-2 text-xs font-medium text-center rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-50 transition-all"
                                                    placeholder="0" />
                                            </div>
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1 bg-primary-600 hover:bg-primary-700 text-white text-xs font-medium rounded-full shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Update
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mb-4"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">
                                                Tidak ada data karyawan ditemukan
                                            </p>
                                            <p class="text-gray-400 dark:text-gray-500 text-xs mt-1">
                                                Data akan muncul di sini setelah ditambahkan
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($userLeaveBalances->hasPages())
                <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-700">{{ $userLeaveBalances->links() }}
                </div>
            @endif
        </div>
    </div>
    <x-toast-notification />
</x-app-layout>
