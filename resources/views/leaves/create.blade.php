<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2
                    class=" border-l-[5px] border-primary-700 pl-5 font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Ajukan Cuti') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Isi formulir di bawah ini untuk mengajukan cuti Anda.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="flex justify-center bg-gray-50 dark:bg-gray-900 py-2 px-4 sm:px-6 lg:px-8 drop-shadow-xl/50">
        <div class="w-full max-w-5x2">
            <div
                class="bg-white dark:bg-slate-800 shadow-xl hover:shadow-2xl transition-all duration-300 rounded-xl p-4 sm:p-6 lg:p-8">
                <form method="POST" action="{{ route('cuti.store') }}" class="space-y-2">
                    @csrf

                    <!-- Jenis Cuti Selector -->
                    <div
                        class="bg-gradient-to-r from-primary-50 to-primary-100 dark:from-slate-700 dark:to-slate-600 p-3 rounded-lg">
                        <label for="leave_type_id"
                            class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-1">
                            <svg class="w-4 h-4 inline mr-1 text-primary-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Pilih Jenis Cuti
                        </label>
                        <select id="leave_type_id" name="leave_type_id"
                            class="block w-full px-3 py-2 bg-white dark:bg-slate-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm font-medium">
                            <option value="">-- Pilih Jenis Cuti --</option>
                            @foreach ($leaveTypes as $type)
                                <option value="{{ $type->id }}" @selected(old('leave_type_id') == $type->id)
                                    data-quota="{{ $type->quota }}" data-gender="{{ $type->gender }}">
                                    {{ strtoupper($type->name) }}
                                    @if ($type->quota > 0 && isset($userLeaveBalances[$type->id]))
                                        (Sisa: {{ $userLeaveBalances[$type->id]->remaining }} hari)
                                    @elseif ($type->quota > 0)
                                        (Kuota: {{ $type->quota }} hari)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('leave_type_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Fields (Hidden initially) -->
                    <div id="form-fields" class="hidden space-y-4 opacity-0 transition-opacity duration-500">
                        <!-- Main Grid: Left - Pengganti/Atasan, Right - Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Left Column: Pengganti and Atasan -->
                            <div class="space-y-3">
                                @if ($requiresReplacement)
                                    <div class="w-1/2">
                                        <label for="pengganti_id"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            <svg class="w-4 h-4 inline mr-1 text-primary-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                            Pengganti
                                        </label>
                                        <x-select-dropdown name="pengganti_id" label="" :options="$penggantiList->map(function ($u) {
                                            return ['id' => $u->id, 'name' => $u->name . ' (' . $u->role . ')'];
                                        })"
                                            :selected="old('pengganti_id')" placeholder="-- Pilih Pengganti --" />
                                        @error('pengganti_id')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif

                                @if ($requiresAtasan)
                                    <div>
                                        <label for="atasan_id"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            <svg class="w-4 h-4 inline mr-1 text-primary-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Atasan Langsung
                                        </label>
                                        <x-select-dropdown name="atasan_id" label="" :options="$atasanList->map(function ($u) {
                                            return ['id' => $u->id, 'name' => $u->name . ' (' . $u->role . ')'];
                                        })"
                                            :selected="old('atasan_id')" placeholder="-- Pilih Atasan --" />
                                        @error('atasan_id')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                            </div>

                            <!-- Right Column: Dates -->
                            <div class="space-y-3">
                                <div>
                                    <label for="start_date"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        <svg class="w-4 h-4 inline mr-1 text-primary-600" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Tanggal Mulai
                                    </label>
                                    <input type="date" id="start_date" name="start_date"
                                        value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                                    @error('start_date')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="end_date"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        <svg class="w-4 h-4 inline mr-1 text-primary-600" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Tanggal Selesai
                                    </label>
                                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}"
                                        min="{{ date('Y-m-d') }}"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                                    @error('end_date')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div id="duration-preview"
                                    class="text-sm text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-slate-700 p-2 rounded-md hidden">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Durasi cuti: <span id="total-days" class="font-semibold text-primary-600"></span>
                                    hari
                                </div>
                            </div>
                        </div>

                        <!-- Alasan -->
                        <div>
                            <label for="alasan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <svg class="w-4 h-4 inline mr-1 text-primary-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                Alasan Cuti
                            </label>
                            <textarea id="alasan" name="alasan" rows="3" placeholder="Jelaskan alasan cuti Anda secara detail..."
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm resize-none">{{ old('alasan') }}</textarea>
                            @error('alasan')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="bg-gray-50 dark:bg-slate-700/50 px-4 py-3 flex items-center justify-end space-x-3 rounded-lg">
                            <a href="{{ route('cuti.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-white dark:bg-slate-600 border border-gray-300 dark:border-gray-500 rounded-md font-medium text-sm text-gray-700 dark:text-gray-200 uppercase tracking-wide shadow-sm hover:bg-gray-50 dark:hover:bg-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition duration-200">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-medium text-sm text-white uppercase tracking-wide hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition duration-200 shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Kirim Pengajuan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal untuk error sisa cuti -->
    @if ($errors->has('msg'))
        <div x-data="{ showModal: true }" x-show="showModal" x-transition
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-slate-800 p-4 rounded-lg shadow-lg max-w-md w-full mx-4">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                    <h3 class="text-lg font-semibold text-red-600 dark:text-red-400">Peringatan</h3>
                </div>
                <p class="mt-4 text-gray-700 dark:text-gray-300">{{ $errors->first('msg') }}</p>
                <div class="mt-6 flex justify-end">
                    <button @click="showModal = false"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition ease-in-out duration-150">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const leaveTypeSelect = document.getElementById('leave_type_id');
            const formFields = document.getElementById('form-fields');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            // Initially hide form fields
            formFields.classList.add('hidden');

            // Set minimum dates
            const today = new Date().toISOString().split('T')[0];
            startDateInput.min = today;
            endDateInput.min = today;

            // Handle leave type selection
            leaveTypeSelect.addEventListener('change', function() {
                if (this.value) {
                    formFields.classList.remove('hidden');
                    setTimeout(() => {
                        formFields.classList.remove('opacity-0');
                    }, 10);
                } else {
                    formFields.classList.add('opacity-0');
                    setTimeout(() => {
                        formFields.classList.add('hidden');
                    }, 500);
                }
            });

            // Handle date changes
            startDateInput.addEventListener('change', function() {
                const startValue = this.value;
                endDateInput.min = startValue;
                calculateDays();
            });

            endDateInput.addEventListener('change', calculateDays);

            function calculateDays() {
                const start = new Date(startDateInput.value);
                const end = new Date(endDateInput.value);
                const preview = document.getElementById('duration-preview');
                const totalDays = document.getElementById('total-days');

                if (start && end && end >= start) {
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    totalDays.textContent = diffDays;
                    preview.classList.remove('hidden');
                } else {
                    preview.classList.add('hidden');
                }
            }
        });
    </script>
</x-app-layout>
