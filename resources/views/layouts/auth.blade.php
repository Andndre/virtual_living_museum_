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
    <div class="flex min-h-screen flex-col items-center bg-[#F0F2F5] pt-6 sm:justify-center sm:pt-0">

        <div class="w-full px-4 sm:max-w-sm">

            <div class="flex flex-col items-center">
                <a href="/">
                    <x-application-logo class="h-10 w-10" />
                </a>
                <p class="mt-2 text-sm text-gray-600">{{ config('app.name') }}</p>
            </div>

            <h1 class="my-6 text-4xl font-extrabold text-gray-800">
                {{ $title }}
            </h1>

            <div class="w-full">
                {{ $slot }}
            </div>
        </div>

        <div class="mt-10 w-full text-center text-sm text-gray-500 sm:max-w-sm">
            {{ $footer }}
        </div>
    </div>
</body>

</html>
