<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2
                    class="border-l-4 border-primary-700 pl-5 font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Tambah Kantor') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Tambahkan kantor baru ke dalam sistem.
                </p>
            </div>
            <a href="{{ route('admin.offices.index') }}"
                class="inline-flex items-center px-3 py-2 bg-gray-600 border border-transparent rounded-full font-medium text-xs text-white hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="space-y-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <form method="POST" action="{{ route('admin.offices.store') }}" class="space-y-6">
                @csrf

                <div>
                    <x-input-label for="nama_kantor" :value="__('Nama Kantor')" />
                    <x-text-input id="nama_kantor" name="nama_kantor" type="text" class="mt-1 block w-full"
                        :value="old('nama_kantor')" required autofocus autocomplete="nama_kantor" />
                    <x-input-error :messages="$errors->get('nama_kantor')" class="mt-2" />
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Masukkan nama kantor (hanya huruf dan spasi diperbolehkan).
                    </p>
                </div>

                <div class="flex items-center justify-end">
                    <x-primary-button>
                        {{ __('Simpan') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
