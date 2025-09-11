<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2
                    class=" border-l-[5px] border-primary-700 pl-5 font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Persetujuan Cuti') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Daftar pengajuan cuti yang memerlukan persetujuan dari Anda.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="space-y-4">
            @forelse ($approvals as $approval)
                <div
                    class="bg-white dark:bg-slate-800 shadow-xl hover:shadow-xl/30 hover:-translate-y-1 transition-all duration-300 rounded-xl overflow-hidden">
                    <div class="p-5">
                        <div class="flex flex-col md:flex-row md:justify-between">
                            <div class="mb-4 md:mb-0">
                                <p class="text-sm font-semibold text-primary-600 dark:text-primary-400">
                                    {{ $approval->leave->user->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $approval->leave->user->role }}
                                </p>
                                <p class="mt-2 text-sm font-medium text-gray-800 dark:text-gray-100">
                                    <span
                                        class="font-bold">{{ \Carbon\Carbon::parse($approval->leave->start_date)->isoFormat('D MMM YYYY') }}</span>
                                    s/d
                                    <span
                                        class="font-bold">{{ \Carbon\Carbon::parse($approval->leave->end_date)->isoFormat('D MMM YYYY') }}</span>
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Total:
                                    {{ $approval->leave->total_hari }} hari</p>
                            </div>

                            <div class="md:w-1/2 mb-4 md:mb-0">
                                <p class="text-sm text-gray-500 dark:text-gray-400 italic">Alasan :
                                    "{{ $approval->leave->alasan }}"</p>
                            </div>

                            <div class="flex items-center space-x-3 self-end md:self-center">
                                <form action="{{ route('approval.reject', $approval) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:ring-offset-slate-800 transition ease-in-out duration-150"
                                        onclick="return confirm('Apakah Anda yakin ingin menolak pengajuan ini?');">
                                        Tolak
                                    </button>
                                </form>
                                <form action="{{ route('approval.approve', $approval) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:ring-offset-slate-800 transition ease-in-out duration-150">
                                        Setujui
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div
                    class="bg-white dark:bg-slate-800 shadow-xl hover:shadow-xl/30 hover:-translate-y-1 transition-all duration-300 rounded-xl overflow-hidden">
                    <div class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                        <div class="flex flex-col items-center justify-center w-full">
                            <svg class="w-16 h-16 text-green-400 dark:text-green-500 mb-4"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-lg font-semibold mb-1 text-gray-800 dark:text-gray-100">Tidak Ada Pengajuan
                                Persetujuan</p>
                            <p class="text-sm">Semua pengajuan cuti telah berhasil diproses.
                            </p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
