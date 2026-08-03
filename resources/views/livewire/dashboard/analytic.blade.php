<div class="space-y-6">
    {{-- Period filter --}}
    <div class="flex items-center justify-between">
        <p class="text-sm font-medium text-muted-foreground">
            Paparan tempoh:
            <span class="font-semibold text-foreground">
                {{ $days == -1 ? 'Semua masa' : 'Akhir ' . $days . ' hari' }}
            </span>
        </p>
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button type="button"
                    class="inline-flex items-center gap-2 rounded-lg border border-border bg-card px-3.5 py-2 text-sm font-medium text-foreground shadow-sm transition hover:bg-accent">
                    <i class="ph ph-funnel text-base leading-none text-muted-foreground"></i>
                    Tapis
                    <i class="ph ph-caret-down text-sm leading-none text-muted-foreground"></i>
                </button>
            </x-slot>
            <x-slot name="content">
                <button wire:click="sort(7)" class="w-full text-start">
                    <x-dropdown-link>Akhir 7 hari</x-dropdown-link>
                </button>
                <button wire:click="sort(30)" class="w-full text-start">
                    <x-dropdown-link>Akhir 30 hari</x-dropdown-link>
                </button>
                <button wire:click="sort(365)" class="w-full text-start">
                    <x-dropdown-link>Akhir setahun</x-dropdown-link>
                </button>
                <button wire:click="sort(-1)" class="w-full text-start">
                    <x-dropdown-link>Semua masa</x-dropdown-link>
                </button>
            </x-slot>
        </x-dropdown>
    </div>

    {{-- KPI cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="card-surface p-5">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-muted-foreground">Baki Stok</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary/10 text-primary">
                    <i class="ph ph-boxes text-lg leading-none"></i>
                </span>
            </div>
            <p class="tnum mt-3 text-3xl font-bold tracking-tight text-foreground">{{ number_format($stocks->sum('balance')) }}</p>
            <p class="mt-1 text-xs text-muted-foreground">{{ $stocks->count() }} jenis stok</p>
        </div>

        <div class="card-surface p-5">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-muted-foreground">Stok Terima</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                    <i class="ph ph-tray-arrow-down text-lg leading-none"></i>
                </span>
            </div>
            <p class="tnum mt-3 text-3xl font-bold tracking-tight text-foreground">{{ number_format($stock_received) }}</p>
            <p class="mt-1 text-xs text-muted-foreground">dalam tempoh dipilih</p>
        </div>

        <div class="card-surface p-5">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-muted-foreground">Stok Keluar</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                    <i class="ph ph-tray-arrow-up text-lg leading-none"></i>
                </span>
            </div>
            <p class="tnum mt-3 text-3xl font-bold tracking-tight text-foreground">{{ number_format($stock_issued) }}</p>
            <p class="mt-1 text-xs text-muted-foreground">dalam tempoh dipilih</p>
        </div>

        <div class="card-surface p-5">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-muted-foreground">Permohonan Aset</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-400">
                    <i class="ph ph-clipboard-text text-lg leading-none"></i>
                </span>
            </div>
            <p class="tnum mt-3 text-3xl font-bold tracking-tight text-foreground">{{ number_format($application_count) }}</p>
            <p class="mt-1 text-xs text-muted-foreground">dalam tempoh dipilih</p>
        </div>
    </div>

    {{-- Panels --}}
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
        {{-- Stock list --}}
        <div class="card-surface overflow-hidden">
            <div class="flex items-center justify-between border-b border-border px-5 py-4">
                <h3 class="text-sm font-bold tracking-tight text-foreground">Stok</h3>
                <span class="chip bg-primary/10 text-primary">{{ $stocks->sum('balance') }} unit</span>
            </div>
            <div class="max-h-96 divide-y divide-border overflow-y-auto">
                @forelse($stocks as $stock)
                <div class="flex items-center justify-between px-5 py-3 transition hover:bg-muted/50">
                    <p class="truncate pr-3 text-sm font-medium text-foreground">{{ $stock->name }}</p>
                    <p class="tnum shrink-0 text-sm font-semibold text-primary">{{ number_format($stock->balance) }}</p>
                </div>
                @empty
                <div class="px-5 py-12 text-center">
                    <i class="ph ph-boxes text-3xl leading-none text-muted-foreground/40"></i>
                    <p class="mt-3 text-sm text-muted-foreground">Tiada rekod stok</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Asset list --}}
        <div class="card-surface overflow-hidden">
            <div class="flex items-center justify-between border-b border-border px-5 py-4">
                <h3 class="text-sm font-bold tracking-tight text-foreground">Aset Alih</h3>
                <span class="chip bg-muted text-muted-foreground">{{ $assets->count() }} aset</span>
            </div>
            <div class="max-h-96 divide-y divide-border overflow-y-auto">
                @forelse($assets as $asset)
                <div class="flex items-center justify-between px-5 py-3 transition hover:bg-muted/50">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="h-2 w-2 shrink-0 rounded-full {{ $asset->is_available ? 'bg-emerald-500' : 'bg-destructive' }}"></span>
                        <p class="truncate text-sm font-medium text-foreground">{{ $asset->name }}</p>
                    </div>
                    <p class="tnum shrink-0 text-sm text-muted-foreground">{{ $asset->applications_count }} permohonan</p>
                </div>
                @empty
                <div class="px-5 py-12 text-center">
                    <i class="ph ph-arrows-left-right text-3xl leading-none text-muted-foreground/40"></i>
                    <p class="mt-3 text-sm text-muted-foreground">Tiada rekod aset</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
