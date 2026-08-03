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

<body class="min-h-[100dvh] bg-background font-sans text-sm antialiased selection:bg-success/20 selection:text-foreground">
    <div class="flex min-h-[100dvh] flex-col">
        {{-- Public header --}}
        <header class="sticky top-0 z-30 border-b border-border bg-card/80 backdrop-blur">
            <div class="mx-auto flex h-16 w-full max-w-5xl items-center justify-between px-4 sm:px-6">
                <a href="{{ route('guest.request') }}" wire:navigate class="flex items-center gap-3">
                    <x-application-logo class="h-8 w-8 text-primary" />
                    <span class="flex flex-col">
                        <span class="text-sm font-bold leading-none tracking-tight text-foreground">{{ config('app.name', 'InvMS') }}</span>
                        <span class="mt-0.5 text-[11px] text-muted-foreground">Sistem Inventori &amp; Aset Alih</span>
                    </span>
                </a>

                <nav class="flex items-center gap-1">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('dashboard') }}" wire:navigate
                                class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-muted-foreground transition hover:bg-accent hover:text-accent-foreground">
                                <i class="ph ph-gauge text-base leading-none"></i>
                                Menu Utama
                            </a>
                        @else
                            <a href="{{ route('login') }}" wire:navigate
                                class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-muted-foreground transition hover:bg-accent hover:text-accent-foreground">
                                <i class="ph ph-sign-in text-base leading-none"></i>
                                Log Masuk
                            </a>
                        @endauth
                    @endif
                </nav>
            </div>
        </header>

        {{-- Content --}}
        <main id="main-content" tabindex="-1" class="flex-1 px-4 py-8 sm:px-6">
            <div class="mx-auto w-full max-w-5xl">
                {{ $slot }}
            </div>
        </main>

        {{-- Footer --}}
        <footer class="border-t border-border py-6">
            <p class="text-center text-xs text-muted-foreground">
                {{ config('app.name', 'InvMS') }} &middot; Sistem Inventori &amp; Aset Alih
            </p>
        </footer>
    </div>
</body>

</html>
