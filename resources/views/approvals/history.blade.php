<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2
                    class=" border-l-[5px] border-primary-700 pl-5 font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Riwayat Persetujuan Cuti') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Riwayat semua persetujuan cuti yang telah diproses.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div
            class="bg-white dark:bg-slate-800 shadow-xl hover:shadow-xl/30 hover:-translate-y-1 transition-all duration-300 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-slate-700/50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Pemohon</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Periode Cuti</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Alasan</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Diproses pada</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($histories as $history)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $history->leave->user->name }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $history->leave->user->role }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($history->leave->start_date)->isoFormat('D MMM YYYY') }} -
                                    {{ \Carbon\Carbon::parse($history->leave->end_date)->isoFormat('D MMM YYYY') }}
                                    <span class="text-xs">({{ $history->leave->total_hari }} hari)</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $history->leave->alasan }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @php
                                        $statusClass = '';
                                        if (strtolower($history->status) === 'approved') {
                                            $statusClass = 'bg-status-success-bg text-status-success-text';
                                        } elseif (strtolower($history->status) === 'rejected') {
                                            $statusClass = 'bg-status-danger-bg text-status-danger-text';
                                        }
                                    @endphp
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ $history->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $history->created_at->isoFormat('D MMMM YYYY') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center w-full">
                                        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.03 1.125 0 1.131.094 1.976 1.057 1.976 2.192v1.392M16.5 21v-1.392c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.125 0c-1.131.094-1.976 1.057-1.976 2.192V21M15.75 9.75h1.5a3 3 0 013 3v1.5a3 3 0 01-3 3h-1.5a3 3 0 01-3-3v-1.5a3 3 0 013-3zM12 9.75h-1.5a3 3 0 00-3 3v1.5a3 3 0 003 3h1.5a3 3 0 003-3v-1.5a3 3 0 00-3-3zM4.5 9.75h1.5a3 3 0 013 3v1.5a3 3 0 01-3 3h-1.5a3 3 0 01-3-3v-1.5a3 3 0 013-3z" />
                                        </svg>
                                        <p class="text-lg font-semibold mb-1">Belum ada riwayat persetujuan</p>
                                        <p class="text-sm">Data akan muncul di sini setelah Anda memproses pengajuan.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($histories->hasPages())
                <div class="bg-white dark:bg-slate-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                    {{ $histories->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
