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

<body class="font-sans text-sm antialiased selection:bg-success/20 selection:text-foreground">
    <div class="grid min-h-[100dvh] bg-background lg:grid-cols-2">
        {{-- Brand panel --}}
        <div class="relative hidden overflow-hidden bg-sidebar text-sidebar-foreground lg:flex lg:flex-col lg:justify-between lg:p-12">
            {{-- subtle warm glow from the accent --}}
            <div class="pointer-events-none absolute -right-32 -top-32 h-96 w-96 rounded-full bg-primary/20 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-40 -left-24 h-96 w-96 rounded-full bg-accent/20 blur-3xl"></div>

            <div class="relative flex items-center gap-3">
                <x-application-logo class="h-10 w-10 text-primary" />
                <div>
                    <p class="text-lg font-bold leading-none tracking-tight">{{ config('app.name', 'InvMS') }}</p>
                    <p class="mt-1 text-xs text-background/60">Sistem Inventori &amp; Aset Alih</p>
                </div>
            </div>

            <div class="relative max-w-md">
                <h1 class="font-mono text-4xl font-semibold leading-[1.1] tracking-[-0.05em]">
                    Inventori yang jelas, <span class="text-success">operasi yang lancar</span>.
                </h1>
                <p class="mt-4 max-w-sm leading-relaxed text-sidebar-foreground/65">
                    Rekod stok, permohonan pinjaman aset dan sejarah pergerakan peralatan, semuanya di satu tempat.
                </p>
            </div>

            <p class="relative text-xs text-sidebar-foreground/45">
                Sistem dalaman &middot; Kementerian Kesihatan Malaysia
            </p>
        </div>

        {{-- Form panel --}}
        <div class="flex items-center justify-center px-4 py-10 sm:px-8">
            <div class="w-full max-w-md">
                <div class="mb-8 flex items-center gap-3 lg:hidden">
                    <x-application-logo class="h-9 w-9 text-primary" />
                    <div>
                        <p class="font-bold leading-none tracking-tight text-foreground">{{ config('app.name', 'InvMS') }}</p>
                        <p class="mt-1 text-xs text-muted-foreground">Sistem Inventori &amp; Aset Alih</p>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-border bg-card shadow-lg">
                    <div class="p-6 sm:p-8">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
