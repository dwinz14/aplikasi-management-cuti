<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2
                    class=" border-l-[5px] border-primary-700 pl-5 font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Master Data Divisi') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola Data Divisi Perusahaan.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="flex justify-start mb-4">
            <a href="{{ route('admin.divisions.create') }}"
                class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah Divisi
            </a>
        </div>
        <div
            class="bg-white dark:bg-slate-800 shadow-xl hover:shadow-xl/30 hover:-translate-y-1 transition-all duration-300 rounded-xl p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-slate-700/50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                No
                            </th>
                            <th
                                class="px-6 py-3 text-left font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Nama Divisi
                            </th>
                            <th
                                class="px-6 py-3 text-left font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($divisions as $index => $division)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/40">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                    {{ $division->nama_divisi }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.divisions.edit', $division->id) }}"
                                        class="text-blue-600 hover:underline mr-2">Edit</a>
                                    <form action="{{ route('admin.divisions.destroy', $division->id) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Yakin ingin menghapus divisi ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @if ($divisions->isEmpty())
                            <tr>
                                <td colspan="3" class="text-center py-4">Tidak ada data divisi.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <x-toast-notification />
</x-app-layout>
