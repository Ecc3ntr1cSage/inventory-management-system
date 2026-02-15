<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-foreground">
        {{ __('Senarai Inventori') }}
    </h2>
</x-slot>
<div class="max-w-xl p-2 mx-auto mt-4 md:p-0" x-data="{ isGrid: true }">
    <div x-data class="flex items-center justify-center gap-2 mb-6">
        <a href="{{ route('inventory.entry') }}" class="px-4 py-2 transition rounded-full hover:bg-accent"
            wire:navigate>
            {{ __('Kemasukan/Keluaran') }}
        </a>
        <a href="{{ route('inventory.listing') }}" class="px-4 py-2 text-primary-foreground bg-primary rounded-full" wire:navigate>
            {{ __('Senarai Inventori') }}
        </a>
    </div>
    <div class="flex items-center justify-between my-4">
        <x-text-input class="w-48 text-xs" placeholder="Cari stok" wire:model.live.debounce.500ms="search"
            type="text" />
        <div class="flex items-center gap-2">
            <button class="p-1 rounded-md hover:bg-accent">
                @if ($this->direction == 'desc')
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
            <button x-on:click="isGrid = !isGrid" class="p-1 rounded-md hover:bg-accent">
                <svg x-cloak x-show="isGrid" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
                <svg x-cloak x-show="!isGrid" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                </svg>
            </button>
        </div>
    </div>
    <div :class="isGrid ? 'grid md:grid-cols-3 grid-cols-2' : 'flex flex-col'" class="gap-3 mb-4">
        @foreach($stocks as $stock)
        <a href="{{ route('inventory.record', $stock->id) }}" wire:navigate
            class="p-3 text-xs transition bg-card rounded-md shadow-xl cursor-pointer hover:ring-2 hover:ring-ring">
            {{ $stock->name }}
        </a>
        @endforeach
    </div>
    {{ $stocks->links(data:['scrollTo' => false]) }}
</div>
