<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-foreground">
        {{ __('Senarai Inventori') }}
    </h2>
</x-slot>
<div class="max-w-3xl p-2 mx-auto mt-6 space-y-3 md:p-0">
        <div class="flex items-center justify-center p-1 mb-8 bg-muted rounded-xl w-fit mx-auto shadow-sm border border-border/50">
        <a href="{{ route('inventory.entry') }}" 
            class="px-6 py-2.5 text-sm font-semibold transition-all duration-200 rounded-lg {{ request()->routeIs('inventory.entry') ? 'bg-background text-primary shadow-sm border border-border/50' : 'text-muted-foreground hover:text-foreground hover:bg-background/50' }}" 
            wire:navigate>
            {{ __('Kemasukan/Keluaran') }}
        </a>
        <a href="{{ route('inventory.listing') }}" 
            class="px-6 py-2.5 text-sm font-semibold transition-all duration-200 rounded-lg {{ request()->routeIs('inventory.listing') ? 'bg-background text-primary shadow-sm border border-border/50' : 'text-muted-foreground hover:text-foreground hover:bg-background/50' }}"
            wire:navigate>
            {{ __('Senarai Inventori') }}
        </a>
    </div>
    <div class="flex items-center justify-between my-4">
        <x-text-input class="w-96 text-md" placeholder="Cari stok" wire:model.live.debounce.500ms="search"
            type="text" />
        <div class="flex items-center gap-2">
            <button class="p-1 rounded-md hover:bg-accent">
                @if ($direction == 'desc')
                <svg wire:click="sort('asc')" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 4.5h14.25M3 9h9.75M3 13.5h9.75m4.5-4.5v12m0 0-3.75-3.75M17.25 21 21 17.25" />
                </svg>
                @else
                <svg wire:click="sort('desc')" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 4.5h14.25M3 9h9.75M3 13.5h5.25m5.25-.75L17.25 9m0 0L21 12.75M17.25 9v12" />
                </svg>
                @endif
            </button>
        </div>
    </div>

    <div class="px-2 pb-16 overflow-x-auto">
        <table class="w-full mb-4 text-xs text-left text-foreground border-separate table-auto border-spacing-y-3">
            <thead>
                <tr class="text-[0.7rem] font-bold uppercase tracking-widest text-muted-foreground/80">
                    <th class="px-4 py-4">Nama Stok</th>
                    <th class="px-4 py-4">Kumpulan</th>
                    <th class="px-4 py-4">Lokasi</th>
                    <th class="px-4 py-4">Baki</th>
                    <th class="px-4 py-4 w-10"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($stocks as $stock)
                <tr wire:key="{{ $stock->id }}"
                    class="bg-card rounded-xl border border-border/50 hover:bg-muted/50 transition-colors duration-200 group">
                    <td class="p-4 rounded-l-xl border-y border-l border-border/50 font-medium">
                        {{ $stock->name }}
                    </td>
                    <td class="p-4 border-y border-border/50">
                        {{ $stock->group }}
                    </td>
                    <td class="p-4 border-y border-border/50">
                        {{ $stock->location }}
                    </td>
                    <td class="p-4 border-y border-border/50">
                        {{ $stock->balance }}
                    </td>
                    <td class="p-4 rounded-r-xl border-y border-r border-border/50 text-right">
                        <a href="{{ route('inventory.record', $stock->id) }}" wire:navigate
                            class="px-3 py-1 text-xs font-semibold text-primary-foreground bg-primary rounded-md hover:bg-primary/80 transition">
                            View
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $stocks->links(data:['scrollTo' => false]) }}
</div>
