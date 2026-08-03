<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'InvMS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=geist:400,500,600,700&family=geist-mono:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-sm antialiased selection:bg-primary/25 selection:text-primary-foreground scroll-smooth">
    <div class="min-h-[100dvh] bg-background">
        <livewire:layout.navigation />
        <x-flash />

        <!-- Page Content -->
        <main class="px-4 pb-12 pt-6 sm:px-6 lg:pl-72 lg:pr-8">
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
