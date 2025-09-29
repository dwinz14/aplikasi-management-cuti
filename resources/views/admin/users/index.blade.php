<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2
                    class="border-l-4 border-primary-700 pl-5 font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Master User') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola data akun karyawan.
                </p>
            </div>
        </div>
    </x-slot>
    <div class="flex justify-start mb-4">
        <a href="{{ route('admin.users.create') }}"
            class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah User
        </a>
    </div>
    <div class="space-y-4" x-data="{ showFilters: false, confirmDelete: false, confirmReset: false, selectedUser: null, selectedUserName: '' }">
        @if (session('success'))
            <div
                class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div
            class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200 dark:border-slate-700">
                <button @click="showFilters = !showFilters"
                    class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter & Cari
                    <svg class="w-4 h-4 ml-2 transition-transform" :class="showFilters ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>

            <div x-show="showFilters" x-transition
                class="px-4 py-4 border-b border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50">
                <form method="GET" action="{{ route('admin.users.index') }}"
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="relative">
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Nama</label>
                        <div class="relative">
                            <input type="text" name="search" placeholder="Cari nama user..."
                                value="{{ $search }}"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm pl-9 pr-3 py-2" />
                            {{-- <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg> --}}
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Role</label>
                        <select name="role"
                            class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm px-3 py-2">
                            <option value="">Semua Role</option>
                            @foreach (['super_admin', 'hrd', 'kabag', 'kasie', 'staff', 'direksi'] as $roleOption)
                                <option value="{{ $roleOption }}" {{ $roleOption == $role ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $roleOption)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Divisi</label>
                        <select name="division_id"
                            class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm px-3 py-2">
                            <option value="">Semua Divisi</option>
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}"
                                    {{ $division->id == $divisionId ? 'selected' : '' }}>
                                    {{ $division->nama_divisi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-primary-600 border border-transparent rounded-full font-medium text-xs text-white hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                        </button>
                        <a href="{{ route('admin.users.index') }}"
                            class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-slate-600 border border-gray-300 dark:border-slate-500 rounded-lg font-medium text-xs text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-slate-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                    <thead class="bg-gray-50 dark:bg-slate-700/50">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                #</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nama</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Email</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Role</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Divisi</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Sisa Cuti</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                        @forelse ($users as $index => $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/40 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $users->firstItem() + $index }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 font-medium">
                                    {{ $user->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if ($user->role === 'super_admin') bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400
                                        @elseif($user->role === 'hrd') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400
                                        @elseif($user->role === 'kabag') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400
                                        @elseif($user->role === 'kasie') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400
                                        @elseif($user->role === 'staff') bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-400
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $user->division?->nama_divisi ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $user->sisa_cuti }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="flex items-center space-x-2">
                                        {{-- Edit --}}
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        {{-- Hapus --}}
                                        <button
                                            @click="confirmDelete = true; selectedUser = {{ $user->id }}; selectedUserName = '{{ $user->name }}'"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                            title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>

                                        {{-- Reset Password --}}
                                        <button
                                            @click="confirmReset = true; selectedUser = {{ $user->id }}; selectedUserName = '{{ $user->name }}'"
                                            class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 transition-colors"
                                            title="Reset Password">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <p class="mt-2">Tidak ada data user ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Modal delete user -->
            <div x-show="confirmDelete"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md">
                    <h2 class="text-lg font-semibold mb-4">Delete User</h2>
                    <p class="mb-4">
                        Apakah Anda yakin ingin menghapus user
                        <span class="font-bold" x-text="selectedUserName"></span>?
                    </p>

                    <form x-bind:action="'{{ route('admin.users.destroy', ':id') }}'.replace(':id', selectedUser)"
                        method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="flex justify-end space-x-2">
                            <button type="button" @click="confirmDelete = false"
                                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-yellow-600 hover:bg-yellow-500 text-white rounded-md">
                                Hapus
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Reset Password -->
            <div x-show="confirmReset"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md">
                    <h2 class="text-lg font-semibold mb-4">Reset Password</h2>
                    <p class="mb-4">
                        Apakah Anda yakin ingin mereset password untuk user
                        <span class="font-bold" x-text="selectedUserName"></span>?
                    </p>

                    <form
                        x-bind:action="'{{ route('admin.users.resetPassword', ':id') }}'.replace(':id', selectedUser)"
                        method="POST">
                        @csrf
                        <div class="flex justify-end space-x-2">
                            <button type="button" @click="confirmReset = false"
                                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-yellow-600 hover:bg-yellow-500 text-white rounded-md">
                                Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if ($users->hasPages())
                <div class="px-4 py-3 bg-gray-50 dark:bg-slate-700/50 border-t border-gray-200 dark:border-slate-700">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
    <x-toast-notification />
</x-app-layout>
