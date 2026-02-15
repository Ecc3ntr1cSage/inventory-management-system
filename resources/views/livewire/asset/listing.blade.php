<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-foreground">
        {{ __('Senarai Aset Alih') }}
    </h2>
</x-slot>
<div class="max-w-xl p-2 mx-auto mt-4 md:p-0" x-data="{ isGrid: true }">
    <div class="flex items-center justify-center gap-2 mb-6">
        <a href="{{ route('asset.submission') }}" class="px-4 py-2 transition rounded-full hover:bg-accent"
            wire:navigate>
            {{ __('Senarai Permohonan') }}
        </a>
        <a href="{{ route('asset.listing') }}" class="px-4 py-2 text-primary-foreground bg-primary rounded-full" wire:navigate>
            {{ __('Senarai Aset') }}
        </a>
    </div>
    <div class="flex items-center justify-end my-4">
        <div class="flex items-center gap-2">
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
            @can('admin')
            <button x-on:click.prevent="$dispatch('open-modal', 'add-asset')"
                class="p-1 transition bg-card rounded-md shadow-md hover:scale-110">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="h-6 p-1 rounded-md bg-w-6">
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
    <p>Aset Tersedia</p>
    <hr class="w-full h-1 mt-2 bg-primary" />
    <div :class="isGrid ? 'grid md:grid-cols-3 grid-cols-2' : 'flex flex-col'" class="gap-3 my-4">
        @foreach($available_assets as $asset)
        <a href="{{ route('asset.record', $asset->id) }}" wire:navigate
            class="p-3 text-xs transition bg-card rounded-md shadow-xl cursor-pointer hover:ring-2 hover:ring-ring">
            {{ $asset->name }}
        </a>
        @endforeach
    </div>
    <p>Aset Sedang Dipinjam</p>
    <hr class="w-full h-1 mt-2 bg-primary" />
    <div :class="isGrid ? 'grid md:grid-cols-3 grid-cols-2' : 'flex flex-col'" class="gap-3 my-4">
        @foreach($unavailable_assets as $asset)
        <a href="{{ route('asset.record', $asset->id) }}" wire:navigate
            class="p-3 text-xs transition bg-card rounded-md shadow-xl cursor-pointer hover:ring-2 hover:ring-ring">
            {{ $asset->name }}
        </a>
        @endforeach
    </div>
</div>
