<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-foreground">
        {{ $stock->name }}
    </h2>
</x-slot>
<div class="max-w-5xl p-2 mx-auto my-4 space-y-3 md:p-0">
    <div class="w-fit">
        <a href="{{ route('inventory.listing') }}" wire:navigate>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor"
                class="p-1 transition-all rounded-full w-7 h-7 hover:bg-accent hover:-translate-x-1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
    </div>
    <div class="flex items-center justify-between">
        <p class="my-2">Perihal Stok: {{ $stock->name }}</p>
        <x-danger-button x-on:click.prevent="$dispatch('open-modal', 'delete-stock-confirmation')">
            Delete
        </x-danger-button>
        <x-modal name="delete-stock-confirmation" maxWidth="sm">
            <div class="p-6">
                <h2 class="text-base font-medium text-foreground">
                    {{ __('Confirm Stock Deletion') }}
                </h2>
                <div class="flex items-center justify-end gap-2 mt-4">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>
                    <x-primary-button wire:click.prevent="deleteStock({{ $stock->id }})" class="w-24 h-8">
                        {{ __('Confirm') }}
                    </x-primary-button>
                </div>
            </div>
        </x-modal>
    </div>
    <div class="flex items-center gap-2">
        <select wire:model.live="perPage"
            class="text-xs transition bg-muted border-none rounded-md focus:ring-2 focus:ring-offset-1 focus:ring-ring">
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            <option value="">Max</option>
        </select>
        <x-text-input class="w-48 text-xs" placeholder="Cari no rujukan" wire:model.live.debounce.500ms="search"
            type="text" />
        @if(count($indexes) > 0)
        <button wire:click.prevent="exportPDF"
            class="p-1 transition bg-muted rounded-md hover:ring-2 hover:ring-ring">
            <span class="p-1 font-medium tracking-wide">PDF</span>
        </button>
        @endif
    </div>
    <div class="my-4 overflow-x-auto rounded-lg">
        <table class="w-full mb-4 text-xs text-left text-foreground rounded-lg table-auto">
            <thead class="text-xs font-medium uppercase bg-muted border-b-2 border-border">
                <tr>
                    <th rowspan="2" class="px-2 py-2 tracking-wide ">
                        <div class="flex">
                            Tarikh
                            <span>
                                @if ($this->direction == 'desc')
                                <svg wire:click="sort('asc')" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="w-6 h-4 cursor-pointer hover:text-primary">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 17.25L12 21m0 0l-3.75-3.75M12 21V3" />
                                </svg>
                                @else
                                <svg wire:click="sort('desc')" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="w-6 h-4 cursor-pointer hover:text-primary">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 6.75L12 3m0 0l3.75 3.75M12 3v18" />
                                </svg>
                                @endif
                            </span>
                        </div>
                    </th>
                    <th rowspan="2" class="px-2 py-2 tracking-wide">
                        No rujukan BTB/BPPS
                    </th>
                    <th colspan="4" class="px-2 py-2 tracking-wide text-center">
                        Kuantiti
                    </th>
                    <th rowspan="2" class="px-2 py-2 tracking-wide text-center">
                        Nama
                    </th>
                    <th rowspan="2" class="px-2 py-2 tracking-wide">

                    </th>
                </tr>
                <tr class="text-center">
                    <th class="px-2 py-2 tracking-wide">
                        Terima
                    </th>
                    <th class="px-2 py-2 tracking-wide">
                        Seunit (RM)
                    </th>
                    <th class="px-2 py-2 tracking-wide">
                        Keluar
                    </th>
                    <th class="px-2 py-2 tracking-wide">
                        Baki
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($indexes as $index)
                <tr wire:loading.class="opacity-50" wire:key="{{ $index->id }}" class="transition hover:bg-accent">
                    <td class="px-2 py-2">
                        {{ $index->date }}
                    </td>
                    <td class="px-2 py-2">
                        {{ $index->reference_no }}
                    </td>
                    <td class="px-2 py-2 text-center">
                        {{ $index->in_quantity }}
                    </td>
                    <td class="px-2 py-2 text-center">
                        {{ $index->unit_price }}
                    </td>
                    <td class="px-2 py-2 text-center">
                        {{ $index->out_quantity }}
                    </td>
                    <td class="px-2 py-2 text-center">
                        {{ $index->balance }}
                    </td>
                    <td class="px-2 py-2">
                        {{ $index->name }}
                    </td>
                    <td class="px-2 py-2">
                        @can('admin')
                        <button type="button"
                            x-on:click.prevent="$dispatch('open-modal', 'delete-index-confirmation-{{ $index->id }}')"
                            class="p-1 rounded-full hover:bg-destructive/50">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </button>
                        <x-modal name="delete-index-confirmation-{{ $index->id }}" maxWidth="sm">
                            <div class="p-6">
                                <h2 class="text-base font-medium text-foreground">
                                    {{ __('Confirm Index Deletion') }}
                                </h2>
                                <div class="flex items-center justify-end gap-2 mt-4">
                                    <x-secondary-button x-on:click="$dispatch('close')">
                                        {{ __('Cancel') }}
                                    </x-secondary-button>
                                    <x-primary-button wire:click.prevent="deleteIndex({{ $index->id }})"
                                        class="w-24 h-8">
                                        {{ __('Confirm') }}
                                    </x-primary-button>
                                </div>
                            </div>
                        </x-modal>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $indexes->links(data:['scrollTo' => false]) }}
    </div>
</div>
