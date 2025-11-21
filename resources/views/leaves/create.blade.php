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
                <form method="POST" action="{{ route('cuti.store') }}" enctype="multipart/form-data" class="space-y-2">
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
                    <div id="form-fields"
                        class="space-y-4 transition-opacity duration-500 {{ $errors->any() ? '' : 'hidden opacity-0' }}">
                        <!-- Main Grid: Left - Pengganti/Atasan, Right - Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Left Column: Pengganti and Atasan -->
                            <div class="space-y-3">
                                @if ($requiresReplacement)
                                    <div class="w-3/4">
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
                                            return [
                                                'id' => $u->id,
                                                'name' => strtoupper($u->name . ' (' . $u->role . ')'),
                                            ];
                                        })"
                                            :selected="old('pengganti_id')" placeholder="-- Pilih Pengganti --" />
                                        @error('pengganti_id')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400"> Anda harus memilih
                                                pengganti</p>
                                        @enderror
                                    </div>
                                @endif

                                @if ($requiresAtasan)
                                    <div class="w-3/4">
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
                                            return [
                                                'id' => $u->id,
                                                'name' => strtoupper($u->name . ' (' . $u->role . ')'),
                                            ];
                                        })"
                                            :selected="old('atasan_id')" placeholder="-- Pilih Atasan --" />
                                        @error('atasan_id')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">Anda harus memilih atasan
                                                langsung</p>
                                        @enderror
                                    </div>
                                @endif
                            </div>

                            <!-- Right Column: Dates -->
                            <div class="space-y-3">
                                <div class="w-3/4">
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
                                    <p id="date-note" class="mt-1 text-sm text-amber-600 hidden">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                                            </path>
                                        </svg>
                                        Pengajuan cuti minimal 1 minggu sebelum tanggal cuti.
                                    </p>
                                    @error('start_date')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">Anda harus memilih tanggal
                                            mulai cuti</p>
                                    @enderror
                                </div>

                                <div class="w-3/4">
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
                                    <input type="date" id="end_date" name="end_date"
                                        value="{{ old('end_date') }}" min="{{ date('Y-m-d') }}" disabled
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                                    @error('end_date')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">Anda harus memilih tanggal
                                            selesai cuti</p>
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
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">Anda harus mengisi alasan cuti</p>
                            @enderror
                        </div>

                        <!-- Bukti Gambar (Opsional, kecuali cuti sakit) -->
                        <div id="proof-image-section" class="hidden">
                            <label for="proof_image"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                <svg class="w-4 h-4 inline mr-1 text-primary-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Bukti Gambar <span id="proof-required" class="text-red-500 hidden">*</span>
                            </label>
                            <input type="file" id="proof_image" name="proof_image" accept="image/*"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                            <!-- Tombol Preview -->
                            <button type="button" id="preview-image-btn"
                                class="mt-2 hidden text-primary-600 hover:text-primary-800 text-sm font-medium flex items-center"
                                onclick="openImagePreview()">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat Gambar
                            </button>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Format: JPG, PNG, GIF. Maksimal
                                2MB.</p>
                            @error('proof_image')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">Anda harus menyertakan bukti surat
                                    dokter</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('cuti.index') }}"
                                class="inline-flex items-center justify-center px-4 py-2 bg-red-400 dark:bg-slate-600 border border-gray-300 dark:border-gray-500 rounded-full font-semibold text-xs text-slate-50 dark:text-gray-200 uppercase tracking-widest shadow-sm hover:bg-rose-300 dark:hover:bg-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:ring-offset-slate-800 transition ease-in-out duration-150">
                                Batal
                            </a>

                            <x-primary-button>
                                {{ __('Simpan') }}
                            </x-primary-button>
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

    <!-- Modal Preview Gambar -->
    <div id="imagePreviewModal" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-slate-800 p-4 rounded-lg shadow-lg max-w-lg w-full relative">
            <button onclick="closeImagePreview()" class="absolute top-2 right-2 text-gray-600 hover:text-gray-900">
                ✖
            </button>
            <img id="imagePreview" src="" alt="Preview" class="w-full rounded-md">
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const leaveTypeSelect = document.getElementById('leave_type_id');
            const formFields = document.getElementById('form-fields');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            // Date constants
            const today = new Date().toISOString().split('T')[0];
            const yesterdayDate = new Date();
            yesterdayDate.setDate(yesterdayDate.getDate() - 1);
            const yesterday = yesterdayDate.toISOString().split('T')[0];

            let isSickLeave = false;

            // Utility functions
            function isWeekend(date) {
                const day = date.getDay();
                return day === 0 || day === 6; // 0 = Sunday, 6 = Saturday
            }

            function countWeekdays(start, end) {
                let count = 0;
                const current = new Date(start);
                while (current <= end) {
                    if (!isWeekend(current)) {
                        count++;
                    }
                    current.setDate(current.getDate() + 1);
                }
                return count;
            }

            function validateDateInput(input) {
                const value = input.value;
                if (value) {
                    const date = new Date(value);
                    if (isWeekend(date)) {
                        alert(
                            'Tidak dapat memilih hari Sabtu atau Minggu. Silakan pilih hari kerja (Senin-Jumat).'
                        );
                        input.value = '';
                        return false;
                    }
                }
                return true;
            }

            // Initially hide form fields
            formFields.classList.add('hidden');

            // Initially disable end date
            endDateInput.disabled = true;

            // Initially hide date note
            document.getElementById('date-note').classList.add('hidden');

            // Handle leave type selection
            leaveTypeSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const leaveTypeName = (selectedOption?.text || '').toLowerCase();

                const proofSection = document.getElementById('proof-image-section');
                const proofRequired = document.getElementById('proof-required');
                const dateNote = document.getElementById('date-note');

                const isSickWithLetter = leaveTypeName.includes('izin sakit dengan surat dokter');
                const isSickWithoutLetter = leaveTypeName.includes('izin sakit tanpa surat dokter');
                isSickLeave = isSickWithLetter || isSickWithoutLetter; // Update global variable

                // Show form fields smoothly
                if (this.value) {
                    formFields.classList.remove('hidden');
                    setTimeout(() => formFields.classList.remove('opacity-0'), 10);
                } else {
                    formFields.classList.add('opacity-0');
                    setTimeout(() => formFields.classList.add('hidden'), 500);
                    return;
                }

                // RULE: Sick with doctor letter → proof required, without letter → no proof needed
                if (isSickWithLetter) {
                    proofSection.classList.remove('hidden');
                    proofRequired.classList.remove('hidden');
                    dateNote.classList.add('hidden');
                } else if (isSickWithoutLetter) {
                    proofSection.classList.add('hidden');
                    proofRequired.classList.add('hidden');
                    dateNote.classList.add('hidden');
                } else {
                    proofSection.classList.add('hidden');
                    proofRequired.classList.add('hidden');
                    dateNote.classList.remove('hidden');
                }

                // Apply date rules
                applyDateRules(isSickLeave);

                // Refresh dependent fields
                startDateInput.dispatchEvent(new Event('change'));
            });

            /**
             * Apply min/max rules for sick leave or normal leave
             */
            function applyDateRules(isSickLeave) {
                if (isSickLeave) {
                    // Sick → past dates allowed for start, end max is yesterday
                    startDateInput.min = '';
                    startDateInput.max = yesterday;
                    endDateInput.min = ''; // allow past dates, overridden by start date
                    endDateInput.max = yesterday;
                } else {
                    // Normal leave → min 7 days from today
                    const minDate = new Date();
                    minDate.setDate(minDate.getDate() + 7);

                    const minDateStr = minDate.toISOString().split('T')[0];

                    startDateInput.min = minDateStr;
                    startDateInput.max = '';
                    endDateInput.min = minDateStr;
                    endDateInput.max = '';
                }
            }


            // Handle date changes
            startDateInput.addEventListener('change', function() {
                if (!validateDateInput(this)) return;

                if (isSickLeave && this.value > yesterday) {
                    alert('Tanggal mulai untuk jenis cuti sakit tidak boleh hari ini atau di masa depan.');
                    this.value = yesterday;
                }

                const startValue = this.value;
                if (startValue) {
                    endDateInput.disabled = false;
                    endDateInput.min = startValue;
                    if (isSickLeave) {
                        endDateInput.max = yesterday;
                    } else {
                        endDateInput.max = '';
                    }
                } else {
                    endDateInput.disabled = true;
                    endDateInput.min = today;
                    endDateInput.max = '';
                }
                calculateDays();
            });

            endDateInput.addEventListener('change', function() {
                if (!validateDateInput(this)) return;

                if (isSickLeave && this.value > yesterday) {
                    alert(
                        'Tanggal selesai untuk jenis cuti sakit tidak boleh hari ini atau di masa depan.'
                    );
                    this.value = yesterday;
                }
                calculateDays();
            });

            function calculateDays() {
                const startValue = startDateInput.value;
                const endValue = endDateInput.value;
                const preview = document.getElementById('duration-preview');
                const totalDays = document.getElementById('total-days');

                if (startValue && endValue) {
                    const start = new Date(startValue);
                    const end = new Date(endValue);

                    if (end >= start) {
                        const weekdays = countWeekdays(start, end);
                        totalDays.textContent = weekdays;
                        preview.classList.remove('hidden');
                    } else {
                        preview.classList.add('hidden');
                    }
                } else {
                    preview.classList.add('hidden');
                }
            }

            // If there are errors or old input, show the form fields
            if (leaveTypeSelect.value) {
                leaveTypeSelect.dispatchEvent(new Event('change'));
            }
        });

        // preview gambar
        const proofInput = document.getElementById('proof_image');
        const previewBtn = document.getElementById('preview-image-btn');
        let previewURL = "";

        proofInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                previewURL = URL.createObjectURL(this.files[0]);
                previewBtn.classList.remove('hidden');
            } else {
                previewBtn.classList.add('hidden');
            }
        });

        function openImagePreview() {
            document.getElementById('imagePreview').src = previewURL;
            document.getElementById('imagePreviewModal').classList.remove('hidden');
        }

        function closeImagePreview() {
            document.getElementById('imagePreviewModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
