<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2
                    class="border-l-4 border-primary-700 pl-5 font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Edit User') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Perbarui informasi user.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div
            class="bg-white dark:bg-slate-800 shadow-xl hover:shadow-xl/30 hover:-translate-y-1 transition-all duration-300 rounded-xl p-6 md:p-8">
            <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="nik" value="NIK" />
                    <x-text-input id="nik" name="nik" type="text" value="{{ old('nik', $user->nik) }}"
                        placeholder="Masukkan NIK user..." required />
                    @error('nik')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <x-input-label for="name" value="Nama" />
                    <x-text-input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                        placeholder="Masukkan nama user..." required />
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" name="email" type="email" value="{{ old('email', $user->email) }}"
                        placeholder="Masukkan email user..." required />
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="role" value="Role" />
                    <select id="role" name="role"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm px-3 py-2"
                        required>
                        <option value="">-- Pilih Role --</option>
                        @foreach (['super_admin', 'hrd', 'kabag', 'kasie', 'staff', 'direksi'] as $roleOption)
                            <option value="{{ $roleOption }}"
                                {{ old('role', $user->role) == $roleOption ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $roleOption)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="division_id" value="Divisi" />
                    <select id="division_id" name="division_id"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm px-3 py-2">
                        <option value="">-- Pilih Divisi --</option>
                        @foreach ($divisions as $division)
                            <option value="{{ $division->id }}"
                                {{ old('division_id', $user->division_id) == $division->id ? 'selected' : '' }}>
                                {{ $division->nama_divisi }}
                            </option>
                        @endforeach
                    </select>
                    @error('division_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="sisa_cuti" value="Sisa Cuti" />
                    <x-text-input id="sisa_cuti" name="sisa_cuti" type="number"
                        value="{{ old('sisa_cuti', $user->sisa_cuti) }}" min="0" required />
                    @error('sisa_cuti')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div
                    class="bg-gray-50 dark:bg-slate-700/50 px-6 py-4 flex items-center justify-end space-x-3 rounded-lg">
                    <a href="{{ route('admin.users.index') }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-slate-600 border border-gray-300 dark:border-gray-500 rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:ring-offset-slate-800 disabled:opacity-25 transition ease-in-out duration-150">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-500 active:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:ring-offset-slate-800 transition ease-in-out duration-150">
                        Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
