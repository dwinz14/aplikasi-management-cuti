<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2
                    class="border-l-4 border-primary-700 pl-5 font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Persetujuan Cuti') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Tinjau dan proses pengajuan cuti yang masuk.
                </p>
            </div>
            <div class="mt-2 sm:mt-0 flex items-center space-x-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                <span
                    class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-primary-100 dark:bg-primary-900/50 text-primary-600 dark:text-primary-300">
                    {{ $approvals->count() }}
                </span>
                <span>
                    Menunggu Tindakan
                </span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-4">
        @forelse ($approvals as $approval)
            <div x-data="{ open: false }"
                class="bg-white dark:bg-slate-800 rounded-xl shadow-md transition-all duration-300">
                <div @click="open = !open"
                    class="flex items-center p-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700/50 rounded-xl">
                    <div class="flex-shrink-0 mr-4">
                        <img class="h-10 w-10 rounded-full"
                            src="https://ui-avatars.com/api/?name={{ urlencode($approval->leave->user->name) }}&background=random"
                            alt="">
                    </div>
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                {{ $approval->leave->user->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $approval->leave->user->role }}</p>
                        </div>
                        <div class="hidden md:block">
                            <p class="text-sm text-gray-800 dark:text-gray-200 truncate"
                                title="{{ $approval->leave->alasan }}">
                                {{ $approval->leave->alasan }}
                            </p>
                        </div>
                        <div class="hidden md:flex items-center text-sm text-gray-500 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0H21" />
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($approval->leave->start_date)->isoFormat('D MMM') }} -
                                {{ \Carbon\Carbon::parse($approval->leave->end_date)->isoFormat('D MMM') }}</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-300"
                            :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <div x-show="open" x-collapse>
                    <div class="px-5 pb-5 pt-2 border-t border-gray-200 dark:border-slate-700">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <div class="mb-4 sm:mb-0">
                                <h4 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase mb-2">Detail
                                    Pengajuan</h4>
                                <div class="space-y-1 text-sm text-gray-700 dark:text-gray-300">
                                    <p><span class="font-medium">Tanggal:</span>
                                        {{ \Carbon\Carbon::parse($approval->leave->start_date)->isoFormat('dddd, D MMMM YYYY') }}
                                        s/d
                                        {{ \Carbon\Carbon::parse($approval->leave->end_date)->isoFormat('dddd, D MMMM YYYY') }}
                                    </p>
                                    <p><span class="font-medium">Total:</span> {{ $approval->leave->total_hari }} hari
                                    </p>
                                    <p class="italic"><span class="font-medium not-italic">Alasan:</span>
                                        "{{ $approval->leave->alasan }}"</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3 self-end sm:self-center">
                                <form action="{{ route('approval.reject', $approval) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        class="text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                                        onclick="return confirm('Apakah Anda yakin ingin menolak pengajuan ini?');">
                                        Tolak
                                    </button>
                                </form>
                                <form action="{{ route('approval.approve', $approval) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        class="text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                        Setujui
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16 px-6 bg-white dark:bg-slate-800 rounded-xl shadow-md">
                <div class="flex flex-col items-center">
                    <svg class="w-16 h-16 text-green-400 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-lg font-semibold text-gray-800 dark:text-gray-100">Inbox Persetujuan Kosong</p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kerja bagus! Semua pengajuan telah
                        diproses.</p>
                </div>
            </div>
        @endforelse
    </div>
</x-app-layout>
