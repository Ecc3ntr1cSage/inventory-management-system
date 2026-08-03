<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'InvMS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=montserrat:400,500,600,700|merriweather:400,700|ubuntu-mono:400,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-sm antialiased selection:bg-success/20 selection:text-foreground scroll-smooth">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-lg focus:bg-card focus:px-4 focus:py-3 focus:text-sm focus:font-semibold focus:text-foreground focus:shadow-lg">Langkau ke kandungan</a>
    <div class="min-h-[100dvh] bg-background">
        <livewire:layout.navigation />
        <x-flash />

        <!-- Page Content -->
        <main id="main-content" tabindex="-1" class="px-4 pb-12 pt-6 sm:px-6 lg:pl-72 lg:pr-8">
            <div class="mx-auto w-full max-w-7xl">
                <!-- Page Heading -->
                @if (isset($header))
                <header class="mb-6">
                    {{ $header }}
                </header>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>
</body>

</html>
