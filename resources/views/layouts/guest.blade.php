<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <!-- Favicon Tab Browser -->
<link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SM Sport Center') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
        </style>
    </head>
    <body class="bg-[#111827] text-[#E5E7EB] antialiased selection:bg-[#22C55E] selection:text-black">
        <div class="min-h-screen flex flex-col justify-center items-center py-12 sm:px-6 lg:px-8 bg-[#111827] relative overflow-hidden">
            
            <!-- Glow Effect Background -->
            <div class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-[#22C55E]/10 blur-[130px] rounded-full pointer-events-none"></div>

            <!-- Logo Header -->
            <div class="sm:mx-auto sm:w-full sm:max-w-md text-center z-10 mb-6">
                <a href="/" class="inline-flex items-center space-x-2 font-black text-2xl tracking-wider text-white uppercase">
                    <img src="{{ asset('images/logo.png') }}" class="h-12 w-auto object-contain" alt="Logo SM Sport">
                    <span>SM <span class="text-[#22C55E]">SPORT</span></span>
                </a>
            </div>

            <!-- Card Form Container -->
            <div class="w-full sm:max-w-md px-6 py-8 bg-gray-900 border border-gray-800 shadow-2xl rounded-2xl z-10 backdrop-blur-sm">
                {{ $slot }}
            </div>

            <!-- Footer Text -->
            <div class="mt-8 text-center text-xs text-gray-500 uppercase tracking-widest font-semibold z-10">
                &copy; {{ date('Y') }} SM Sport Center. All Rights Reserved.
            </div>
        </div>
    </body>
</html>