<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#F0F2F5]">
            
            <div class="w-full sm:max-w-sm px-4">
                
                <div class="flex flex-col items-center">
                    <a href="/">
                        <x-application-logo class="w-10 h-10" />
                    </a>
                    <p class="mt-2 text-sm text-gray-600">Virtual Living Museum</p>
                </div>

                <h1 class="text-4xl font-extrabold text-gray-800 my-6">
                    {{ $title }}
                </h1>

                <div class="w-full">
                    {{ $slot }}
                </div>
            </div>

            <div class="w-full sm:max-w-sm mt-10 text-center text-sm text-gray-500">
                {{ $footer }}
            </div>
        </div>
    </body>
</html>