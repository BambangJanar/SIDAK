<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Tracking Disposisi') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900">
        <div class="flex h-screen bg-gray-50 dark:bg-gray-900" x-data="{ isSideMenuOpen: false }">
            
            @include('layouts.navigation')

            <div class="flex flex-col flex-1 w-full overflow-hidden">
                
                @include('layouts.topbar')

                <main class="h-full overflow-y-auto">
                    
                    @isset($header)
                        <div class="container px-6 mx-auto mt-6">
                            <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
                                {{ $header }}
                            </h2>
                        </div>
                    @endisset

                    <div class="container px-6 mx-auto grid py-6">
                        {{ $slot }}
                    </div>
                </main>

            </div>
        </div>
    </body>
</html>