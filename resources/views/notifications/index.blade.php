<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2
                    class=" border-l-[5px] border-primary-700 pl-5 font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Daftar Notifikasi') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Lihat semua notifikasi Anda.
                </p>
            </div>
            <div class="flex items-center space-x-2">
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                    {{ $notifications->where('is_read', false)->count() }} Belum Dibaca
                </span>
                @if ($notifications->where('is_read', false)->count() > 0)
                    <button onclick="markAllAsRead()" type="button"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-xs font-medium rounded-full shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Tandai Semua Dibaca
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Notifications List -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden">
                @forelse($notifications as $notification)
                    <div
                        class="notification-item border-b border-gray-200 dark:border-gray-700 last:border-b-0 transition-all duration-200 hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ !$notification->is_read ? 'bg-blue-50/30 dark:bg-blue-900/10' : '' }}">
                        <div class="p-6">
                            <div class="flex items-start space-x-4">
                                <!-- Icon -->
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-12 h-12 rounded-full flex items-center justify-center {{ !$notification->is_read ? 'bg-gradient-to-br from-blue-500 to-blue-600' : 'bg-gray-300 dark:bg-gray-600' }}">
                                        @if (!$notification->is_read)
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 pr-4">
                                            <div class="flex items-center space-x-2 mb-1">
                                                <h4
                                                    class="text-base font-semibold text-gray-900 dark:text-gray-100 {{ $notification->is_read ? 'opacity-75' : '' }}">
                                                    {{ $notification->title }}
                                                </h4>
                                                @if (!$notification->is_read)
                                                    <span class="flex h-2 w-2">
                                                        <span
                                                            class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-blue-400 opacity-75"></span>
                                                        <span
                                                            class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                                    </span>
                                                @endif
                                            </div>

                                            <p
                                                class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed {{ $notification->is_read ? 'opacity-75' : '' }}">
                                                {{ $notification->message }}
                                            </p>

                                            <div class="flex items-center mt-3 space-x-4">
                                                <span
                                                    class="inline-flex items-center text-xs text-gray-500 dark:text-gray-500">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </span>

                                                @if ($notification->is_read)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        Sudah Dibaca
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Action Button -->
                                        @if (!$notification->is_read)
                                            <button onclick="markAsRead({{ $notification->id }})" type="button"
                                                class="flex-shrink-0 inline-flex items-center px-3 py-2 border border-transparent text-xs leading-4 font-medium rounded-full text-white bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm hover:shadow transition-all duration-200">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Tandai Dibaca
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-16 px-4">
                        <div
                            class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                            Tidak Ada Notifikasi
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 text-center max-w-sm">
                            Anda tidak memiliki notifikasi saat ini. Notifikasi baru akan muncul di sini.
                        </p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($notifications->hasPages())
                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay"
        class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl">
            <div class="flex items-center space-x-3">
                <svg class="animate-spin h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <span class="text-gray-700 dark:text-gray-300 font-medium">Memproses...</span>
            </div>
        </div>
    </div>

    <script>
        // Show loading overlay
        function showLoading() {
            document.getElementById('loadingOverlay').classList.remove('hidden');
        }

        // Hide loading overlay
        function hideLoading() {
            document.getElementById('loadingOverlay').classList.add('hidden');
        }

        // Show toast notification
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 ${
                type === 'success'
                    ? 'bg-green-500 text-white'
                    : 'bg-red-500 text-white'
            }`;
            toast.innerHTML = `
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">${message}</span>
                </div>
            `;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Mark single notification as read
        function markAsRead(notificationId) {
            showLoading();

            fetch(`/notifications/${notificationId}/read`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        showToast('Notifikasi berhasil ditandai sebagai dibaca');
                        setTimeout(() => location.reload(), 800);
                    } else {
                        showToast('Terjadi kesalahan, silakan coba lagi', 'error');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan, silakan coba lagi', 'error');
                });
        }

        // Mark all notifications as read
        function markAllAsRead() {
            if (!confirm('Tandai semua notifikasi sebagai dibaca?')) {
                return;
            }

            showLoading();

            fetch('/notifications/mark-all-read', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        showToast('Semua notifikasi berhasil ditandai sebagai dibaca');
                        setTimeout(() => location.reload(), 800);
                    } else {
                        showToast('Terjadi kesalahan, silakan coba lagi', 'error');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan, silakan coba lagi', 'error');
                });
        }
    </script>

    <style>
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .notification-item {
            animation: slideInRight 0.3s ease-out;
        }
    </style>
</x-app-layout>
