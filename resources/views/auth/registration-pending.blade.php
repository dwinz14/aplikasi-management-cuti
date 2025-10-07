<x-guest-layout>

    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Pendaftaran berhasil! Akun Anda sedang menunggu persetujuan dari admin. Silahkan menghubungi admin untuk konfirmasi aktivasi akun anda .') }}
    </div>

    <div class="flex items-center justify-between mt-4">
        <a href="{{ route('login') }}"
            class="text-sm text-gray-600 dark:text-gray-400 underline hover:text-gray-900 dark:hover:text-gray-100">
            {{ __('Kembali ke Login') }}
        </a>
    </div>
</x-guest-layout>
