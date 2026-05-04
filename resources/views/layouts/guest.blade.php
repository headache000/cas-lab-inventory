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

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0 bg-[#fafcff]">
        <!-- Header Logos -->
        <div class="flex items-center space-x-2 mb-6">
            <!-- University Logo (SSU) (Left) -->
            <div
                class="w-16 h-16 bg-white rounded-full border border-gray-200 shadow-sm overflow-hidden flex items-center justify-center p-1 z-0">
                <img src="{{ asset('images/ssu.jpg') }}" alt="Samar State University"
                    class="w-full h-full object-contain rounded-full"
                    onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'text-xs font-bold\'>SSU</span>';" />
            </div>

            <!-- Main System Logo (Middle) -->
            <div
                style="width: 96px; height: 96px; overflow: hidden; border-radius: 9999px; display: flex; align-items: center; justify-content: center; background: white;">
                <img src="{{ asset('images/lab-inventory.png') }}" alt="Lab Inventory System"
                    style="width: 100%; height: 100%; object-fit: contain; border-radius: 9999px;">
            </div>

            <!-- College Logo (CAS) (Right) -->
            <div
                class="w-16 h-16 bg-white rounded-full border border-gray-200 shadow-sm overflow-hidden flex items-center justify-center p-1 z-0">
                <img src="{{ asset('images/cas.jpg') }}" alt="College of Arts and Sciences"
                    class="w-full h-full object-contain rounded-full"
                    onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'text-xs font-bold\'>CAS</span>';" />
            </div>
        </div>

        <!-- Title -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Lab Inventory Management System</h1>
            <p class="text-xs font-semibold text-slate-400 tracking-widest uppercase mt-1 italic">College of Arts and
                Sciences</p>
        </div>

        <!-- Form Card -->
        <div
            class="w-full sm:max-w-md px-8 py-8 bg-white border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] rounded-2xl">
            {{ $slot }}
        </div>

        @if (isset($footer))
            <div class="mt-8 text-center">
                {{ $footer }}
            </div>
        @endif
    </div>
</body>

</html>