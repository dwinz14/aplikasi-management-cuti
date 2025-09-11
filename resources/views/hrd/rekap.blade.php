<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2
                    class=" border-l-[5px] border-primary-700 pl-5 font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Rekapitulasi Cuti Karyawan') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Ringkasan data cuti seluruh karyawan perusahaan.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div
            class="bg-white dark:bg-slate-800 shadow-xl hover:shadow-xl/30 hover:-translate-y-1 transition-all duration-300 rounded-xl p-6">
            <form method="GET" action="{{ route('hrd.rekap.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label for="division_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Divisi</label>
                        <select id="division_id" name="division_id"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            <option value="">-- Semua Divisi --</option>
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}" @selected($divisionId == $division->id)>
                                    {{ $division->nama_divisi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="start_date"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai</label>
                        <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal
                            Selesai</label>
                        <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    </div>

                    <div class="flex items-end space-x-3">
                        <button type="submit"
                            class="inline-flex items-center justify-center w-full px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-500 active:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:ring-offset-slate-800 transition ease-in-out duration-150">
                            Filter
                        </button>
                        <a href="{{ route('hrd.rekap.index') }}"
                            class="inline-flex items-center justify-center w-full px-4 py-2 bg-white dark:bg-slate-700 border border-gray-300 dark:border-gray-600 rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:ring-offset-slate-800 transition ease-in-out duration-150">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div
            class="bg-white dark:bg-slate-800 shadow-xl hover:shadow-xl/30 hover:-translate-y-1 transition-all duration-300 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-slate-700/50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nama</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Divisi</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tanggal Cuti</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Alasan</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Approver Terakhir</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($leaves as $leave)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition duration-150">
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $leave->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $leave->user->division->nama_divisi ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($leave->start_date)->isoFormat('D MMM YYYY') }} -
                                    {{ \Carbon\Carbon::parse($leave->end_date)->isoFormat('D MMM YYYY') }}
                                    <span class="text-xs">({{ $leave->total_hari }} hari)</span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $leave->alasan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @php
                                        $statusClass = '';
                                        if (strtolower($leave->status_final) === 'approved') {
                                            $statusClass = 'bg-status-success-bg text-status-success-text';
                                        } elseif (strtolower($leave->status_final) === 'pending') {
                                            $statusClass = 'bg-status-warning-bg text-status-warning-text';
                                        } elseif (strtolower($leave->status_final) === 'rejected') {
                                            $statusClass = 'bg-status-danger-bg text-status-danger-text';
                                        }
                                    @endphp
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ ucfirst($leave->status_final) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    @php
                                        $lastApproval = $leave->approvalHistories()->latest()->first();
                                    @endphp
                                    {{ $lastApproval ? $lastApproval->approver->name : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center w-full">
                                        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5 4.5l-2.25-2.25m0 0l2.25-2.25m-2.25 2.25l2.25 2.25M12 18.75l-2.25-2.25m2.25 2.25l2.25-2.25m-2.25 2.25l-2.25-2.25M6.34 7.5h11.32a2.25 2.25 0 012.25 2.25v7.5a2.25 2.25 0 01-2.25 2.25H6.34a2.25 2.25 0 01-2.25-2.25v-7.5a2.25 2.25 0 012.25-2.25z" />
                                        </svg>
                                        <p class="text-lg font-semibold mb-1">Data tidak ditemukan</p>
                                        <p class="text-sm">Tidak ada data cuti yang ditampilkan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($leaves->hasPages())
                <div class="bg-white dark:bg-slate-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                    {{ $leaves->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
