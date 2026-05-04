<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Lab Inventory Management  System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="font-sans antialiased text-slate-800 bg-slate-50">
    <!-- Sidebar Navigation -->
    <div class="fixed top-0 left-0 bottom-0 z-50" style="width: 16rem;">
        @include('layouts.navigation')
    </div>

    <!-- Main Content Area -->
    <div class="flex flex-col min-h-screen" style="margin-left: 16rem;">
        <!-- Top Header -->
        @isset($header)
            <header class="bg-white border-b border-gray-100 flex items-center justify-between px-8 py-5 sticky top-0 z-40">
                <div class="flex flex-col">
                    {{ $header }}
                </div>

                <!-- Global Access / Settings -->
                <div class="flex items-center space-x-6">
                    <span
                        class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-semibold tracking-wide bg-gray-100 text-gray-700 uppercase">
                        @if(Auth::user()->role === 'admin')
                            {{ Auth::user()->laboratory->name ?? 'Local Access' }}
                        @else
                            @if(isset($selectedLab) && $selectedLab)
                                {{ $selectedLab->name }}
                            @else
                                Global Access
                            @endif
                        @endif
                    </span>
                </div>
            </header>
        @endisset

        <main class="p-8 flex-1">
            {{ $slot }}
        </main>
    </div>
</body>

</html>