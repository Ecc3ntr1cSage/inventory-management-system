<x-slot name="header">
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('inventory.listing') }}" wire:navigate
                class="flex h-10 w-10 items-center justify-center rounded-lg border border-border bg-card text-muted-foreground shadow-sm transition hover:bg-accent hover:text-accent-foreground"
                aria-label="Kembali ke senarai">
                <i class="ph ph-arrow-left text-lg leading-none"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold leading-tight tracking-tight text-foreground">
                    {{ $stock->name }}
                </h2>
                <p class="text-sm text-muted-foreground">Sejarah pergerakan stok</p>
            </div>
        </div>
        <x-danger-button x-on:click.prevent="$dispatch('open-modal', 'delete-stock-confirmation')">
            <i class="ph ph-trash text-base leading-none"></i>
            Delete
        </x-danger-button>
    </div>
</x-slot>

<div class="mx-auto max-w-6xl">
    {{-- Stock summary --}}
    <div class="card-surface mb-5 flex flex-wrap items-center justify-between gap-4 px-5 py-4">
        <div class="flex items-center gap-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                <i class="ph ph-boxes text-lg leading-none"></i>
            </span>
            <div>
                <p class="text-sm font-semibold text-foreground">{{ $stock->name }}</p>
                <p class="text-xs text-muted-foreground">Perihal Stok</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="chip bg-primary/10 text-primary">
                Baki: <span class="tnum font-bold">{{ number_format($stock->balance) }}</span> unit
            </span>
        </div>
    </div>

    {{-- Delete stock modal --}}
    <x-modal name="delete-stock-confirmation" maxWidth="sm">
        <div class="p-6 sm:p-8">
            <h2 class="text-lg font-bold tracking-tight text-foreground">
                {{ __('Confirm Stock Deletion') }}
            </h2>
            <p class="mt-1 text-sm text-muted-foreground">Tindakan ini tidak boleh dibatalkan.</p>
            <div class="mt-6 flex items-center justify-end gap-2">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>
                <x-danger-button wire:click.prevent="deleteStock({{ $stock->id }})">
                    {{ __('Confirm') }}
                </x-danger-button>
            </div>
        </div>
    </x-modal>

    {{-- Toolbar --}}
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-2">
            <select wire:model.live="perPage"
                class="h-10 rounded-lg border border-input bg-card px-3 text-sm text-foreground shadow-sm transition focus:border-ring focus:outline-none focus:ring-2 focus:ring-ring/30">
                <option value="10">10 / muka</option>
                <option value="20">20 / muka</option>
                <option value="30">30 / muka</option>
                <option value="">Semua</option>
            </select>
            <div class="relative">
                <i class="ph ph-magnifying-glass pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-base leading-none text-muted-foreground"></i>
                <x-text-input class="w-full pl-10 pr-3.5 sm:w-56" placeholder="Cari no rujukan" wire:model.live.debounce.500ms="search" type="text" />
            </div>
        </div>
        @if(count($indexes) > 0)
        <button wire:click.prevent="exportPDF"
            class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-border bg-card px-3.5 text-sm font-semibold text-foreground shadow-sm transition hover:bg-accent">
            <i class="ph ph-file-pdf text-base leading-none text-destructive"></i>
            PDF
        </button>
        @endif
    </div>

    {{-- History table --}}
    <div class="card-surface overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-[11px] font-bold uppercase tracking-widest text-muted-foreground">
                    <tr class="border-b border-border bg-muted/40">
                        <th rowspan="2" class="px-4 py-3">
                            <span class="inline-flex items-center gap-1">
                                Tarikh
                                @if ($this->direction == 'desc')
                                <button wire:click="sort('asc')" class="text-primary"><i class="ph ph-caret-down text-xs leading-none"></i></button>
                                @else
                                <button wire:click="sort('desc')" class="text-primary"><i class="ph ph-caret-up text-xs leading-none"></i></button>
                                @endif
                            </span>
                        </th>
                        <th rowspan="2" class="px-4 py-3">No rujukan BTB/BPPS</th>
                        <th colspan="4" class="border-b border-border px-4 py-3 text-center">Kuantiti</th>
                        <th rowspan="2" class="px-4 py-3">Nama</th>
                        <th rowspan="2" class="px-4 py-3"></th>
                    </tr>
                    <tr>
                        <th class="px-4 py-2.5 text-center">Terima</th>
                        <th class="px-4 py-2.5 text-center">Seunit (RM)</th>
                        <th class="px-4 py-2.5 text-center">Keluar</th>
                        <th class="px-4 py-2.5 text-center">Baki</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($indexes as $index)
                    <tr wire:loading.class="opacity-50" wire:key="{{ $index->id }}" class="transition hover:bg-muted/40">
                        <td class="tnum px-4 py-3">{{ $index->date }}</td>
                        <td class="px-4 py-3 font-medium text-foreground">{{ $index->reference_no }}</td>
                        <td class="tnum px-4 py-3 text-center font-semibold text-emerald-600 dark:text-emerald-400">{{ $index->in_quantity }}</td>
                        <td class="tnum px-4 py-3 text-center text-muted-foreground">{{ $index->unit_price }}</td>
                        <td class="tnum px-4 py-3 text-center font-semibold text-orange-600 dark:text-orange-400">{{ $index->out_quantity }}</td>
                        <td class="tnum px-4 py-3 text-center font-bold text-foreground">{{ $index->balance }}</td>
                        <td class="px-4 py-3">{{ $index->name }}</td>
                        <td class="px-4 py-3 text-right">
                            @can('admin')
                            <button type="button"
                                x-on:click.prevent="$dispatch('open-modal', 'delete-index-confirmation-{{ $index->id }}')"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-muted-foreground transition hover:bg-destructive/10 hover:text-destructive"
                                aria-label="Padam rekod">
                                <i class="ph ph-trash text-base leading-none"></i>
                            </button>
                            <x-modal name="delete-index-confirmation-{{ $index->id }}" maxWidth="sm">
                                <div class="p-6 sm:p-8">
                                    <h2 class="text-lg font-bold tracking-tight text-foreground">
                                        {{ __('Confirm Index Deletion') }}
                                    </h2>
                                    <p class="mt-1 text-sm text-muted-foreground">Tindakan ini tidak boleh dibatalkan.</p>
                                    <div class="mt-6 flex items-center justify-end gap-2">
                                        <x-secondary-button x-on:click="$dispatch('close')">
                                            {{ __('Cancel') }}
                                        </x-secondary-button>
                                        <x-danger-button wire:click.prevent="deleteIndex({{ $index->id }})">
                                            {{ __('Confirm') }}
                                        </x-danger-button>
                                    </div>
                                </div>
                            </x-modal>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-14 text-center">
                            <i class="ph ph-clipboard-text text-3xl leading-none text-muted-foreground/40"></i>
                            <p class="mt-3 text-sm text-muted-foreground">Tiada rekod dijumpai</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $indexes->links(data:['scrollTo' => false]) }}
    </div>
</div>
