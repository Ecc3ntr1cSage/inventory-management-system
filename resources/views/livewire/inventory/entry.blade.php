<x-slot name="header">
    <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
            <i class="ph ph-arrows-down-up text-xl leading-none"></i>
        </div>
        <div>
            <h2 class="text-2xl font-bold leading-tight tracking-tight text-foreground">
                {{ __('Borang Kemasukan/Keluaran Stor Am') }}
            </h2>
            <p class="text-sm text-muted-foreground">Rekod pergerakan stok masuk dan keluar</p>
        </div>
    </div>
</x-slot>

<div class="mx-auto max-w-2xl">
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

    {{-- Form card --}}
    <div class="card-surface p-6 sm:p-8">
        <form wire:submit.prevent="entry" class="space-y-5" enctype="multipart/form-data">
            @csrf

            {{-- Stock select (custom Alpine) --}}
            <div class="relative" x-data="select({{ $stocks }})" @click.away="close()">
                <x-input-label for="selectfield" :value="__('Perihal Stok *')" />
                <input type="hidden" id="selectfield" wire:model="stock_id">
                <div class="flex items-center gap-2">
                    <div class="relative w-full" @click="toggle(); $nextTick(() => $refs.filterinput.focus());">
                        <button type="button"
                            class="flex h-10 w-full items-center justify-between rounded-lg border border-input bg-card px-3.5 text-sm text-foreground shadow-sm transition focus:border-ring focus:outline-none focus:ring-2 focus:ring-ring/30">
                            <span class="block truncate" :class="selectedlabel ? 'text-foreground' : 'text-muted-foreground'" x-text="selectedlabel ?? 'Sila pilih stok...'"></span>
                            <i class="ph ph-caret-down ml-2 shrink-0 text-sm leading-none text-muted-foreground" :class="state && 'rotate-180'"></i>
                        </button>
                    </div>
                    @can('admin')
                    <button type="button" x-on:click.prevent="$dispatch('open-modal', 'add-stock')"
                        class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-border bg-card text-muted-foreground shadow-sm transition hover:bg-accent hover:text-accent-foreground"
                        aria-label="Tambah stok baharu">
                        <i class="ph ph-plus text-lg leading-none"></i>
                    </button>
                    @endcan
                </div>
                <x-input-error :messages="$errors->get('stock_id')" class="mt-2" />

                {{-- Dropdown list --}}
                <div x-show="state" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="absolute z-20 mt-1.5 w-full rounded-xl border border-border bg-popover p-1.5 shadow-lg">
                    <div class="relative mb-1.5">
                        <i class="ph ph-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm leading-none text-muted-foreground"></i>
                        <input type="text"
                            class="h-9 w-full rounded-lg border border-input bg-card pl-8 pr-3 text-sm text-foreground shadow-sm transition placeholder:text-muted-foreground/70 focus:border-ring focus:outline-none focus:ring-2 focus:ring-ring/30"
                            placeholder="Cari stok..." x-model="filter" x-ref="filterinput">
                    </div>
                    <ul class="max-h-60 overflow-auto">
                        <template x-for="(value, key) in getlist()" :key="key">
                            <li @click="select(value, key)"
                                :class="{ 'bg-primary text-primary-foreground': isselected(key) }"
                                class="relative cursor-pointer select-none rounded-lg px-3 py-2 text-sm text-foreground transition hover:bg-accent hover:text-accent-foreground">
                                <span x-text="value" class="block truncate font-medium"></span>
                                <span x-show="isselected(key)" class="absolute inset-y-0 right-3 flex items-center">
                                    <i class="ph ph-check text-base leading-none"></i>
                                </span>
                            </li>
                        </template>
                        <li x-show="Object.keys(getlist()).length === 0" class="px-3 py-6 text-center text-sm text-muted-foreground">
                            Tiada stok dijumpai
                        </li>
                    </ul>
                </div>
            </div>

            <div>
                <x-input-label for="reference_no" :value="__('No Rujukan BTB/BPPS *')" />
                <x-text-input wire:model="reference_no" id="reference_no" class="mt-1 block w-full" type="text" />
                <x-input-error :messages="$errors->get('reference_no')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="date" :value="__('Tarikh *')" />
                <x-text-input wire:model="date" id="date" class="mt-1 block w-full" type="date" />
                <x-input-error :messages="$errors->get('date')" class="mt-2" />
            </div>

            @can('admin')
            <div>
                <x-input-label for="in_quantity" :value="__('Kuantiti Terima *')" />
                <x-text-input wire:model.blur="in_quantity" id="in_quantity" class="mt-1 block w-full" type="text" />
                <x-input-error :messages="$errors->get('in_quantity')" class="mt-2" />
            </div>
            @endcan

            @can('staff')
            <div>
                <x-input-label for="out_quantity" :value="__('Kuantiti Keluar *')" />
                <x-text-input wire:model.blur="out_quantity" id="out_quantity" class="mt-1 block w-full" type="text" />
                <x-input-error :messages="$errors->get('out_quantity')" class="mt-2" />
            </div>
            @endcan

            <div class="pt-1">
                <x-primary-button class="w-full" target="entry">
                    <i class="ph ph-check text-base leading-none"></i>
                    {{ __('Simpan') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    {{-- Add stock modal --}}
    <x-modal name="add-stock" maxWidth="md" focusable>
        <form wire:submit.prevent="addStock" class="space-y-4 p-6 sm:p-8">
            <div>
                <h3 class="text-lg font-bold tracking-tight text-foreground">Tambah Stok</h3>
                <p class="mt-1 text-sm text-muted-foreground">Rekod stok baharu ke dalam stor am.</p>
            </div>
            <div>
                <x-input-label for="stock_name" :value="__('Perihal Stok *')" />
                <x-text-input wire:model="stock_name" id="stock_name" class="mt-1 block w-full" type="text" />
                <x-input-error :messages="$errors->get('stock_name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="stock_group" :value="__('Kumpulan Stok')" />
                <x-text-input wire:model="stock_group" id="stock_group" class="mt-1 block w-full" type="text" />
                <x-input-error :messages="$errors->get('stock_group')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="stock_location" :value="__('Lokasi Stok')" />
                <x-text-input wire:model="stock_location" id="stock_location" class="mt-1 block w-full" type="text" />
                <x-input-error :messages="$errors->get('stock_location')" class="mt-2" />
            </div>
            <div class="flex items-center justify-end gap-2 pt-2">
                <x-secondary-button x-on:click="$dispatch('close')">{{ __('Batal') }}</x-secondary-button>
                <x-primary-button target="addStock">{{ __('Tambah Stok') }}</x-primary-button>
            </div>
        </form>
    </x-modal>
</div>

<script>
    function select(stocks) {
        return {
            state: false,
            filter: '',
            list: stocks,
            selectedkey: null,
            selectedlabel: null,
            toggle: function() {
                this.state = !this.state;
                this.filter = '';
            },
            close: function() {
                this.state = false;
            },
            select: function(value, key) {
                if (this.selectedkey == key) {
                    this.selectedlabel = null;
                    this.selectedkey = null;
                    // update hidden wire:model input and dispatch input event for Livewire
                    const input = document.getElementById('selectfield');
                    if (input) {
                        input.value = '';
                        input.dispatchEvent(new Event('input'));
                    }
                } else {
                    this.selectedlabel = value;
                    this.selectedkey = key;
                    this.state = false;
                    const input = document.getElementById('selectfield');
                    if (input) {
                        input.value = key;
                        input.dispatchEvent(new Event('input'));
                    }
                }
            },
            isselected: function(key) {
                return this.selectedkey == key;
            },
            getlist: function() {
                if (this.filter == '') {
                    return this.list;
                }
                var filtered = Object.entries(this.list).filter(([key, value]) => value.toLowerCase().includes(this.filter.toLowerCase()));
                var result = Object.fromEntries(filtered);
                return result;
            }
        };
    }
</script>
