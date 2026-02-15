<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-foreground">
        {{ __('Senarai Aset Alih') }}
    </h2>
</x-slot>
<div class="max-w-5xl p-2 mx-auto my-4 space-y-3 md:p-0" x-data="{ selected:1 }">
    <div class="flex items-center justify-center p-1 mb-8 bg-muted rounded-xl w-fit mx-auto shadow-sm border border-border/50">
        <a href="{{ route('asset.submission') }}" 
            class="px-6 py-2.5 text-sm font-semibold transition-all duration-200 rounded-lg {{ request()->routeIs('asset.submission') ? 'bg-background text-primary shadow-sm border border-border/50' : 'text-muted-foreground hover:text-foreground hover:bg-background/50' }}" 
            wire:navigate>
            {{ __('Senarai Permohonan') }}
        </a>
        <a href="{{ route('asset.listing') }}" 
            class="px-6 py-2.5 text-sm font-semibold transition-all duration-200 rounded-lg {{ request()->routeIs('asset.listing') ? 'bg-background text-primary shadow-sm border border-border/50' : 'text-muted-foreground hover:text-foreground hover:bg-background/50' }}"
            wire:navigate>
            {{ __('Senarai Aset') }}
        </a>
    </div>

    <div class="flex items-center justify-end my-4">
        <div class="flex items-center gap-2">
            @can('admin')
            <button x-on:click.prevent="$dispatch('open-modal', 'add-asset')"
                class="p-2 transition bg-card rounded-md shadow-md hover:scale-110">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </button>
            @endcan
            <x-modal name="add-asset" maxWidth="md" focusable>
                <form wire:submit.prevent="addAsset" class="p-6 my-4 space-y-2" enctype="multipart/form-data">
                    @csrf
                    <p class="text-xl font-medium">Tambah Aset Alih</p>
                    <div>
                        <x-input-label for="asset_name" :value="__('Perihal Aset *')" />
                        <x-text-input wire:model="asset_name" id="asset_name" class="block w-full mt-1" type="text" />
                        <x-input-error :messages="$errors->get('asset_name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="asset_model" :value="__('Jenama dan Model *')" />
                        <x-text-input wire:model="asset_model" id="asset_model" class="block w-full mt-1" type="text" />
                        <x-input-error :messages="$errors->get('asset_model')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="registration_no" :value="__('No. Siri Pendaftaran *')" />
                        <x-text-input wire:model="registration_no" id="registration_no" class="block w-full mt-1" type="text" />
                        <x-input-error :messages="$errors->get('registration_no')" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-end gap-2">
                        <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                        <x-primary-button class="w-24 h-8" target="addAsset">{{ __('Submit') }}</x-primary-button>
                    </div>
                </form>
            </x-modal>
        </div>
    </div>

    <button class="flex items-center justify-between w-full px-8 py-4 text-lg font-bold tracking-tight transition-all duration-300 rounded-xl group"
        x-on:click.prevent="selected !== 1 ? selected = 1 : selected = null"
        :class="selected == 1 ? 'bg-primary text-primary-foreground shadow-lg shadow-primary/20 scale-[1.01]' : 'bg-card text-foreground hover:bg-muted border border-border shadow-sm'">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg bg-current/10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span>Aset Tersedia</span>
            <span class="px-2.5 py-0.5 text-xs font-black rounded-full transition-colors" 
                :class="selected == 1 ? 'bg-primary-foreground text-primary' : 'bg-primary text-primary-foreground'">
                {{ $available_assets->count() }}
            </span>
        </div>
        <svg class="w-6 h-6 transition-transform duration-300" :class="selected == 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    <div class="relative transition-all duration-300 ease-in-out" 
        :class="selected == 1 ? 'max-h-[5000px] opacity-100' : 'max-h-0 opacity-0 overflow-hidden'">
        <div class="px-2 pb-16 overflow-x-auto">
            <table class="w-full mb-4 text-xs text-left text-foreground border-separate table-auto border-spacing-y-3">
                <thead>
                    <tr class="text-[0.7rem] font-bold uppercase tracking-widest text-muted-foreground/80">
                        <th class="px-4 py-4">Nama Aset</th>
                        <th class="px-4 py-4">Model</th>
                        <th class="px-4 py-4">No. Siri Pendaftaran</th>
                        <th class="px-4 py-4 w-10"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($available_assets as $asset)
                    <tr wire:key="{{ $asset->id }}"
                        class="bg-card rounded-xl border border-border/50 hover:bg-muted/50 transition-colors duration-200 group">
                        <td class="p-4 rounded-l-xl border-y border-l border-border/50 font-medium">
                            {{ $asset->name }}
                        </td>
                        <td class="p-4 border-y border-border/50">
                            {{ $asset->model }}
                        </td>
                        <td class="p-4 border-y border-border/50">
                            {{ $asset->registration_no }}
                        </td>
                        <td class="p-4 rounded-r-xl border-y border-r border-border/50 text-right">
                            <a href="{{ route('asset.record', $asset->id) }}" wire:navigate
                                class="px-3 py-1 text-xs font-semibold text-primary-foreground bg-primary rounded-md hover:bg-primary/80 transition">
                                View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <button class="flex items-center justify-between w-full px-8 py-4 text-lg font-bold tracking-tight transition-all duration-300 rounded-xl group"
        x-on:click.prevent="selected !== 2 ? selected = 2 : selected = null"
        :class="selected == 2 ? 'bg-primary text-primary-foreground shadow-lg shadow-primary/20 scale-[1.01]' : 'bg-card text-foreground hover:bg-muted border border-border shadow-sm'">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg bg-current/10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span>Aset Sedang Dipinjam</span>
            <span class="px-2.5 py-0.5 text-xs font-black rounded-full transition-colors" 
                :class="selected == 2 ? 'bg-primary-foreground text-primary' : 'bg-primary text-primary-foreground'">
                {{ $unavailable_assets->count() }}
            </span>
        </div>
        <svg class="w-6 h-6 transition-transform duration-300" :class="selected == 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    <div class="relative transition-all duration-300 ease-in-out" 
        :class="selected == 2 ? 'max-h-[5000px] opacity-100' : 'max-h-0 opacity-0 overflow-hidden'">
        <div class="px-2 pb-16 overflow-x-auto">
            <table class="w-full mb-4 text-xs text-left text-foreground border-separate table-auto border-spacing-y-3">
                <thead>
                    <tr class="text-[0.7rem] font-bold uppercase tracking-widest text-muted-foreground/80">
                        <th class="px-4 py-4">Nama Aset</th>
                        <th class="px-4 py-4">Model</th>
                        <th class="px-4 py-4">No. Siri Pendaftaran</th>
                        <th class="px-4 py-4 w-10"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($unavailable_assets as $asset)
                    <tr wire:key="{{ $asset->id }}"
                        class="bg-card rounded-xl border border-border/50 hover:bg-muted/50 transition-colors duration-200 group">
                        <td class="p-4 rounded-l-xl border-y border-l border-border/50 font-medium">
                            {{ $asset->name }}
                        </td>
                        <td class="p-4 border-y border-border/50">
                            {{ $asset->model }}
                        </td>
                        <td class="p-4 border-y border-border/50">
                            {{ $asset->registration_no }}
                        </td>
                        <td class="p-4 rounded-r-xl border-y border-r border-border/50 text-right">
                            <a href="{{ route('asset.record', $asset->id) }}" wire:navigate
                                class="px-3 py-1 text-xs font-semibold text-primary-foreground bg-primary rounded-md hover:bg-primary/80 transition">
                                View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
