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

                        {{-- Division --}}
                        <div>
                            <label for="division_id"
                                class="block text-xs font-medium mb-1 text-gray-700 dark:text-gray-300">Divisi</label>
                            <select id="division_id" name="division_id"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-xs">
                                <option value="">-- Semua Divisi --</option>
                                @foreach ($divisions as $division)
                                    <option value="{{ $division->id }}"
                                        {{ $divisionId == $division->id ? 'selected' : '' }}>
                                        {{ strtoupper($division->nama_divisi) }}
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
                                <option value="kabag" {{ $role == 'kabag' ? 'selected' : '' }}>kabag</option>
                                <option value="hrd" {{ $role == 'hrd' ? 'selected' : '' }}>HRD</option>
                            </select>
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-col md:flex-row gap-2 items-stretch justify-end">
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
                        divisi</p>
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

                {{-- Per Divisi --}}
                <div
                    class="p-3 rounded-lg border border-yellow-300 dark:border-yellow-700 bg-yellow-50 dark:bg-yellow-900/20">
                    <h4 class="text-xs font-medium text-yellow-700 dark:text-yellow-200 mb-1">Reset per Divisi</h4>
                    <form action="{{ route('hrd.quota.resetDivision') }}" method="POST" x-data
                        @submit.prevent="if(confirm('Apakah yakin reset kuota divisi ini?')) $el.submit()">
                        @csrf
                        <input type="hidden" name="leave_type_id" value="{{ $leaveTypeId }}">
                        <div class="space-y-2">
                            <select name="division_id" required
                                class="w-full rounded-md border-yellow-300 dark:border-yellow-600 dark:bg-yellow-900/70 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-xs">
                                <option value="">-- Pilih Divisi --</option>
                                @foreach ($divisions as $division)
                                    <option value="{{ $division->id }}">{{ strtoupper($division->nama_divisi) }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="flex gap-2">
                                <input type="number" name="default_quota" value="{{ $defaultQuota }}"
                                    min="0"
                                    class="flex-1 rounded-md border-yellow-300 dark:border-yellow-600 dark:bg-yellow-900/50 dark:text-yellow-200 text-xs focus:border-yellow-500 focus:ring-yellow-500">
                                <button type="submit"
                                    class="px-3 py-1.5 bg-yellow-600 text-white text-xs font-medium rounded-md hover:bg-yellow-700 focus:ring-2 focus:ring-yellow-500">
                                    Reset Divisi
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
                                    {{ $leaveType->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-xs table-fixed">
                    <thead class="bg-gray-50 dark:bg-slate-700/50">
                        <tr class="text-gray-600 dark:text-gray-300 font-semibold uppercase tracking-wider">
                            <th class="px-4 py-2 text-left w-1/5">Nama</th>
                            <th class="px-4 py-2 text-left w-1/5">Divisi</th>
                            <th class="px-4 py-2 text-left w-1/6">Role</th>
                            <th class="px-4 py-2 text-center w-1/12">Alokasi</th>
                            <th class="px-4 py-2 text-center w-1/12">Terpakai</th>
                            <th class="px-4 py-2 text-center w-1/12">Sisa</th>
                            <th class="px-4 py-2 text-center w-1/6">Aksi</th>
                        </tr>
                    </thead>


                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($userLeaveBalances as $balance)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/40 transition duration-150">
                                <td class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100 break-words">
                                    {{ Str::title($balance->user->name) }}
                                </td>
                                <td class="px-4 py-2 text-gray-500 dark:text-gray-400 break-words">
                                    {{ strtoupper($balance->user->division->nama_divisi ?? '-') }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <span
                                        class="px-2 py-0.5 text-[10px] font-bold rounded-full inline-flex items-center justify-center
@if ($balance->user->role == 'staff') bg-blue-100 text-blue-700 dark:bg-blue-600 dark:text-white
@elseif($balance->user->role == 'kasie') bg-green-100 text-green-700 dark:bg-green-600 dark:text-white
@elseif($balance->user->role == 'kabag') bg-purple-100 text-purple-700 dark:bg-purple-600 dark:text-white
@else bg-gray-100 text-gray-700 dark:bg-gray-600 dark:text-white @endif">
                                        {{ ucfirst($balance->user->role) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-center font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $balance->total_quota }} hari</td>
                                <td class="px-4 py-2 text-center font-semibold text-orange-600 dark:text-orange-400">
                                    {{ $balance->used }} hari</td>
                                <td class="px-4 py-2 text-center font-semibold text-green-600 dark:text-green-400">
                                    {{ $balance->remaining }} hari</td>
                                <td class="px-3 py-2">
                                    <form
                                        action="{{ route('hrd.quota.update', [$balance->user, $balance->leaveType]) }}"
                                        method="POST" class="flex items-center justify-center gap-2">
                                        @csrf
                                        @method('POST')
                                        <input type="number" name="remaining" value="{{ $balance->remaining }}"
                                            min="0"
                                            class="w-16 rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 text-xs focus:border-primary-500 focus:ring-primary-500" />
                                        <button type="submit"
                                            class="px-3 py-1 bg-primary-600 text-white text-xs rounded-md hover:bg-primary-500 hover:shadow focus:ring-2 focus:ring-primary-500 transition-all">
                                            Update
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada data karyawan ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($userLeaveBalances->hasPages())
                <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-700">{{ $userLeaveBalances->links() }}
                </div>
            @endif
        </div>
    </div>
    <x-toast-notification />
</x-app-layout>
