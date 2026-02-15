<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-foreground">
        {{ __('Borang Kemasukan/Keluaran Stor Am') }}
    </h2>
</x-slot>
<div class="max-w-md p-2 mx-auto mt-4 md:p-0">
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
    <form wire:submit.prevent="entry" class="my-6 space-y-4" enctype="multipart/form-data">
        @csrf
        <div class="relative text-foreground" x-data="select({{ $stocks }})" @click.away="close()">
            <p class="block mb-1 font-medium text-foreground">Perihal Stok *</p>
            <div class="flex items-center gap-2">
                <input type="hidden" id="selectfield" wire:model="stock_id">
                <div class="relative inline-block w-full rounded-md shadow-sm"
                    @click="toggle(); $nextTick(() => $refs.filterinput.focus());">
                    <button type="button" autofocus
                        class="relative z-0 w-full py-2 pl-3 pr-10 text-left transition duration-150 ease-in-out bg-card border border-input rounded-md cursor-default focus:outline-none focus:shadow-outline-blue focus:border-ring sm:leading-5">
                        <span class="block truncate" x-text="selectedlabel ?? 'Please Select'"></span>

                        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                            <svg class="w-5 h-5 text-muted-foreground" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                                <path d="M7 7l3-3 3 3m0 6l-3 3-3-3" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </span>
                    </button>
                </div>
                @can('admin')
                <button type="button" x-on:click.prevent="$dispatch('open-modal', 'add-stock')"
                    class="p-1 transition bg-card rounded-md shadow-md hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="h-6 p-1 rounded-md bg-w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </button>
                @endcan
            </div>
            <x-input-error :messages="$errors->get('stock_id')" class="mt-2" />
            <div x-show="state" class="absolute z-10 w-full p-2 mt-1 bg-card rounded-md shadow-lg">
                <input type="text" class="w-full px-2 py-1 mb-1 border border-input rounded-md" x-model="filter"
                    x-ref="filterinput">
                <ul class="py-1 overflow-auto leading-6 rounded-md shadow-xs max-h-60 focus:outline-none sm:leading-5">
                    <template x-for="(value, key) in getlist()" :key="key">
                        <li @click="select(value, key)" :class="{'bg-accent': isselected(key)}"
                            class="relative py-1 pl-3 mb-1 text-foreground rounded-md cursor-pointer select-none pr-9 hover:bg-accent">
                            <span x-text="value" class="block font-normal truncate"></span>
                                <span x-show="isselected(key)"
                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-foreground">
                                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
        <div>
            <x-input-label for="reference_no" :value="__('No Rujukan BTB/BPPS *')" />
            <x-text-input wire:model="reference_no" id="reference_no" class="block w-full mt-1" type="text" />
            <x-input-error :messages="$errors->get('reference_no')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="date" :value="__('Tarikh *')" />
            <x-text-input wire:model="date" id="date" class="block w-full mt-1" type="date" />
            <x-input-error :messages="$errors->get('date')" class="mt-2" />
        </div>
        @can('admin')
        <div>
            <x-input-label for="in_quantity" :value="__('Kuantiti Terima *')" />
            <x-text-input wire:model.blur="in_quantity" id="in_quantity" class="block w-full mt-1" type="text" />
            <x-input-error :messages="$errors->get('in_quantity')" class="mt-2" />
        </div>
        @endcan
        @can('staff')
        <div>
            <x-input-label for="out_quantity" :value="__('Kuantiti Keluar *')" />
            <x-text-input wire:model.blur="out_quantity" id="out_quantity" class="block w-full mt-1" type="text" />
            <x-input-error :messages="$errors->get('out_quantity')" class="mt-2" />
        </div>
        @endcan
        <div class="flex items-center justify-center">
            <x-primary-button class="w-full h-10" target="entry">{{ __('Submit') }}</x-primary-button>
        </div>
    </form>
    <x-modal name="add-stock" maxWidth="md" focusable>
        <form wire:submit.prevent="addStock" class="p-6 my-4 space-y-2" enctype="multipart/form-data">
            <p class="text-xl font-medium">Tambah Stok</p>
            <div>
                <x-input-label for="stock_name" :value="__('Perihal Stok *')" />
                <x-text-input wire:model="stock_name" id="stock_name" class="block w-full mt-1" type="text" />
                <x-input-error :messages="$errors->get('stock_name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="stock_group" :value="__('Kumpulan Stok')" />
                <x-text-input wire:model="stock_group" id="stock_group" class="block w-full mt-1" type="text" />
                <x-input-error :messages="$errors->get('stock_group')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="stock_location" :value="__('Lokasi Stok')" />
                <x-text-input wire:model="stock_location" id="stock_location" class="block w-full mt-1" type="text" />
                <x-input-error :messages="$errors->get('stock_location')" class="mt-2" />
            </div>
            <div class="flex items-center justify-end gap-2">
                <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                <x-primary-button class="h-8 w-28" target="addStock">{{ __('Add Stock') }}</x-primary-button>
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
