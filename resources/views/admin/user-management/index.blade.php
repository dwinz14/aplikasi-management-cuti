<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2
                    class="border-l-4 border-primary-700 pl-5 font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('User Management') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola aktivitas user dan persetujuan registrasi.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Feature Selector -->
        <div
            class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200 dark:border-slate-700">
                <label for="feature-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih
                    Fitur</label>
                <select id="feature-select"
                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md dark:bg-slate-700 dark:border-slate-600 dark:text-gray-100">
                    <option value="user-activity" selected>User Activity</option>
                    <option value="registration-approvals">
                        Registration Approvals
                        @if (isset($pendingCount) && $pendingCount > 0)
                            ({{ $pendingCount }})
                        @endif
                    </option>
                </select>
            </div>

            <!-- User Activity Content -->
            <div id="user-activity-content" class="feature-content">
                @include('admin.user-management.partials.user-activity')
            </div>

            <!-- Registration Approvals Content -->
            <div id="registration-approvals-content" class="feature-content hidden">
                @include('admin.user-management.partials.registration-approvals')
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const featureSelect = document.getElementById('feature-select');
            const featureContents = document.querySelectorAll('.feature-content');

            featureSelect.addEventListener('change', function() {
                const selectedFeature = this.value;

                // Hide all feature contents
                featureContents.forEach(content => {
                    content.classList.add('hidden');
                });

                // Show selected feature content
                document.getElementById(selectedFeature + '-content').classList.remove('hidden');
            });
        });
    </script>
</x-app-layout>
