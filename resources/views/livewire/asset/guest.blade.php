<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-foreground">
        {{ __('Permohonan Peralatan IT') }}
    </h2>
</x-slot>
<div>
    <div class="max-w-xl p-2 mx-auto mt-4 md:p-0">
        <p class="text-lg font-medium tracking-wide text-center uppercase">Borang permohonan pergerakan/pinjaman aset alih
        </p>
        <form wire:submit.prevent="application" class="my-6 space-y-4" enctype="multipart/form-data">
            @csrf
            <!-- honeypot field to deter bots -->
            <div style="display:none;">
                <x-text-input wire:model="website" id="website" type="text" autocomplete="off" />
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-input-label for="guest_name" :value="__('Nama Pemohon *')" />
                    <x-text-input wire:model="guest_name" id="guest_name" class="block w-full mt-1" type="text" />
                    <x-input-error :messages="$errors->get('guest_name')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="guest_email" :value="__('Emel Pemohon *')" />
                    <x-text-input wire:model="guest_email" id="guest_email" class="block w-full mt-1" type="email" />
                    <x-input-error :messages="$errors->get('guest_email')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="description" :value="__('Butir Permohonan *')" />
                    <x-text-input wire:model="description" id="description" class="block w-full mt-1" type="text"
                        autofocus placeholder="Laptop + external hard disk" />
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="position" :value="__('Jawatan *')" />
                    <x-text-input wire:model="position" id="position" class="block w-full mt-1" type="text" />
                    <x-input-error :messages="$errors->get('position')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="department" :value="__('Bahagian *')" />
                    <x-text-input wire:model="department" id="department" class="block w-full mt-1" type="text" />
                    <x-input-error :messages="$errors->get('department')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="reason" :value="__('Tujuan *')" />
                    <x-text-input wire:model="reason" id="reason" class="block w-full mt-1" type="text" />
                    <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="location" :value="__('Tempat Digunakan *')" />
                    <x-text-input wire:model="location" id="location" class="block w-full mt-1" type="text" />
                    <x-input-error :messages="$errors->get('location')" class="mt-2" />
                </div>
            </div>
            <div>
                <x-primary-button class="w-full h-10" target="application">{{ __('Hantar') }}</x-primary-button>
            </div>
        </form>
    </div>
</div>
