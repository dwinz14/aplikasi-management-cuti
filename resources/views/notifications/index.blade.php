<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Notifikasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Daftar Notifikasi</h3>
                        <button onclick="markAllAsRead()"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Tandai Semua Dibaca
                        </button>
                    </div>

                    @forelse($notifications as $notification)
                        <div
                            class="border-b border-gray-200 dark:border-gray-700 py-4 {{ $notification->is_read ? 'bg-gray-50 dark:bg-gray-700' : 'bg-white dark:bg-gray-800' }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4
                                        class="font-semibold text-gray-900 dark:text-gray-100 {{ $notification->is_read ? 'text-gray-600' : '' }}">
                                        {{ $notification->title }}
                                    </h4>
                                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                                        {{ $notification->message }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                @if (!$notification->is_read)
                                    <button onclick="markAsRead({{ $notification->id }})"
                                        class="ml-4 bg-green-500 hover:bg-green-700 text-white text-sm py-1 px-3 rounded">
                                        Tandai Dibaca
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">Tidak ada notifikasi</p>
                        </div>
                    @endforelse

                    <div class="mt-6">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function markAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/read`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        }

        function markAllAsRead() {
            fetch('/notifications/mark-all-read', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        }
    </script>
</x-app-layout>
