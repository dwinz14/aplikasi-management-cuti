<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 dark:text-gray-100 antialiased dark:bg-slate-900 transition-colors duration-300">
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-slate-900 transition-colors duration-300">
        <div class="mb-4">
            <a href="/">
                <x-application-logo
                    class="w-20 h-20 fill-current text-gray-500 dark:text-gray-400 transition-colors duration-300" />
            </a>
        </div>
        <div class="md-2">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">PORTAL-CUTI</h2>
        </div>

        <div
            class="w-full sm:max-w-md mt-4 px-6 py-4 bg-white dark:bg-slate-800 shadow-md dark:shadow-xl overflow-hidden sm:rounded-lg border border-gray-200 dark:border-gray-700">
            {{ $slot }}
        </div>

        <!-- Theme Toggle for Guest Pages -->
        <div class="mt-6">
            <x-theme-toggle size="w-6 h-6" />
        </div>
    </div>
</body>

</html>
