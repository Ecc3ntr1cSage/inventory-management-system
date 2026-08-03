<x-slot name="header">
    <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
            <i class="ph ph-boxes text-xl leading-none"></i>
        </div>
        <div>
            <h2 class="text-2xl font-bold leading-tight tracking-tight text-foreground">
                {{ __('Senarai Inventori') }}
            </h2>
            <p class="text-sm text-muted-foreground">Semak baki dan lokasi stok semasa</p>
        </div>
    </div>
</x-slot>

<div class="mx-auto max-w-5xl">
    {{-- Segmented control --}}
    <div class="mb-6 inline-flex w-full rounded-xl border border-border bg-card p-1 shadow-sm sm:w-auto">
        <a href="{{ route('inventory.entry') }}"
            class="flex-1 rounded-lg px-5 py-2.5 text-center text-sm font-semibold transition-all duration-150 sm:flex-none {{ request()->routeIs('inventory.entry') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:text-foreground' }}"
            wire:navigate>
            Kemasukan/Keluaran
        </a>
        <a href="{{ route('inventory.listing') }}"
            class="flex-1 rounded-lg px-5 py-2.5 text-center text-sm font-semibold transition-all duration-150 sm:flex-none {{ request()->routeIs('inventory.listing') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:text-foreground' }}"
            wire:navigate>
            Senarai Inventori
        </a>
    </div>

    {{-- Toolbar --}}
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="relative w-full sm:max-w-xs">
            <i class="ph ph-magnifying-glass pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-base leading-none text-muted-foreground"></i>
            <x-text-input class="w-full pl-10 pr-3.5" placeholder="Cari stok" wire:model.live.debounce.500ms="search" type="text" />
        </div>
        <button wire:click="sort('{{ $direction == 'asc' ? 'desc' : 'asc' }}')"
            class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-border bg-card px-3.5 text-sm font-medium text-foreground shadow-sm transition hover:bg-accent">
            <i class="ph {{ $direction == 'asc' ? 'ph-sort-ascending' : 'ph-sort-descending' }} text-base leading-none"></i>
            {{ $direction == 'asc' ? 'A-Z' : 'Z-A' }}
        </button>
    </div>

    {{-- Table --}}
    <div class="card-surface overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-border text-[11px] font-bold uppercase tracking-widest text-muted-foreground">
                        <th class="px-5 py-3.5">Nama Stok</th>
                        <th class="px-5 py-3.5">Kumpulan</th>
                        <th class="px-5 py-3.5">Lokasi</th>
                        <th class="px-5 py-3.5 text-right">Baki</th>
                        <th class="px-5 py-3.5 text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($stocks as $stock)
                    <tr wire:key="{{ $stock->id }}" class="group transition-colors duration-150 hover:bg-muted/50">
                        <td class="px-5 py-3.5 font-semibold text-foreground">{{ $stock->name }}</td>
                        <td class="px-5 py-3.5 text-muted-foreground">{{ $stock->group ?? '-' }}</td>
                        <td class="px-5 py-3.5 text-muted-foreground">{{ $stock->location ?? '-' }}</td>
                        <td class="tnum px-5 py-3.5 text-right">
                            <span class="chip {{ $stock->balance > 0 ? 'bg-primary/10 text-primary' : 'bg-muted text-muted-foreground' }}">
                                {{ number_format($stock->balance) }} unit
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('inventory.record', $stock->id) }}" wire:navigate
                                class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold text-primary transition hover:bg-primary/10">
                                Rekod
                                <i class="ph ph-arrow-right text-sm leading-none"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-14 text-center">
                            <i class="ph ph-magnifying-glass text-3xl leading-none text-muted-foreground/40"></i>
                            <p class="mt-3 text-sm text-muted-foreground">Tiada stok dijumpai</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $stocks->links(data:['scrollTo' => false]) }}
    </div>
</div>
