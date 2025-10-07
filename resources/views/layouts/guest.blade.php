<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        if (localStorage.getItem('dark-mode') === 'true' || (!('dark-mode' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <title>{{ config('app.name', 'ACC') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex">
        <div
            class="hidden lg:flex w-1/2 items-center justify-center bg-gradient-to-br from-primary-600 to-primary-800 p-12 text-white relative overflow-hidden">
            <div class="absolute -top-1/4 -left-1/4 w-96 h-96 bg-primary-500/30 rounded-full animate-pulse"></div>
            <div
                class="absolute -bottom-1/4 -right-1/4 w-96 h-96 bg-primary-700/30 rounded-full animate-pulse delay-75">
            </div>
            <div class="relative z-10 text-center">
                <a href="/">
                    <x-application-logo
                        class="w-24 h-24 mx-auto mb-6 text-gray-500 dark:text-gray-400 transition-colors duration-300" />
                </a>
                <h1 class="text-4xl font-bold mb-3">PORTAL-CUTI</h1>
                <p class="text-primary-200">Website Cuti Karyawan PT BPR Artha Pamenang.</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-100 dark:bg-slate-900 p-4 sm:p-8 md:p-12">
            <!-- Theme Toggle -->
            <div class="absolute top-4 right-4">
                <x-theme-toggle />
            </div>
            <div class="w-full max-w-md">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>
