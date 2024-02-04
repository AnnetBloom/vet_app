<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Vet App') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-mon-yellow-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-mon-yellow-200 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <footer class=" max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 text-sm leading-6">
                <br><br><br>
                <div class="pt-10 pb-28 border-t border-white sm:flex justify-between text-slate-500 dark:border-white">
                    
                    <div class="mb-6 sm:mb-0 sm:flex">
                        <p>Copyright © <!-- -->{{ date('Y') }}<!-- --> Vet App Inc.</p>
                        <p class="sm:ml-4 sm:pl-4 sm:border-l sm:border-white dark:sm:border-white">
                            <a class="hover:text-white dark:hover:text-white" href="#">Учебное приложение</a>
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
