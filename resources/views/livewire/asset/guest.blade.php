<div class="mx-auto max-w-2xl">
    {{-- Hero --}}
    <div class="mb-8 text-center">
        <span class="chip mb-4 bg-primary/10 text-primary">
            <i class="ph ph-clipboard-text text-sm leading-none"></i>
            Borang Awam
        </span>
        <h1 class="text-3xl font-bold leading-tight tracking-tight text-foreground sm:text-4xl">
            Permohonan Peralatan IT
        </h1>
        <p class="mx-auto mt-3 max-w-md text-muted-foreground">
            Borang permohonan pergerakan atau pinjaman aset alih. Isi butiran di bawah dan pasukan IT akan menyemak permohonan anda.
        </p>
    </div>

    {{-- Form card --}}
    <div class="card-surface p-6 sm:p-8">
        <form wire:submit.prevent="application" class="space-y-5" enctype="multipart/form-data">
            @csrf
            <!-- honeypot field to deter bots -->
            <div style="display:none;">
                <x-text-input wire:model="website" id="website" type="text" autocomplete="off" />
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <x-input-label for="guest_name" :value="__('Nama Pemohon *')" />
                    <x-text-input wire:model="guest_name" id="guest_name" class="mt-1 block w-full" type="text" />
                    <x-input-error :messages="$errors->get('guest_name')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="guest_email" :value="__('Emel Pemohon *')" />
                    <x-text-input wire:model="guest_email" id="guest_email" class="mt-1 block w-full" type="email" />
                    <x-input-error :messages="$errors->get('guest_email')" class="mt-2" />
                </div>
                <div class="sm:col-span-2">
                    <x-input-label for="description" :value="__('Butir Permohonan *')" />
                    <x-text-input wire:model="description" id="description" class="mt-1 block w-full" type="text"
                        autofocus placeholder="Laptop + external hard disk" />
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="position" :value="__('Jawatan *')" />
                    <x-text-input wire:model="position" id="position" class="mt-1 block w-full" type="text" />
                    <x-input-error :messages="$errors->get('position')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="department" :value="__('Bahagian *')" />
                    <x-text-input wire:model="department" id="department" class="mt-1 block w-full" type="text" />
                    <x-input-error :messages="$errors->get('department')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="reason" :value="__('Tujuan *')" />
                    <x-text-input wire:model="reason" id="reason" class="mt-1 block w-full" type="text" />
                    <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="location" :value="__('Tempat Digunakan *')" />
                    <x-text-input wire:model="location" id="location" class="mt-1 block w-full" type="text" />
                    <x-input-error :messages="$errors->get('location')" class="mt-2" />
                </div>
            </div>

            <div class="pt-1">
                <x-primary-button class="w-full" target="application">
                    <i class="ph ph-paper-plane-tilt text-base leading-none"></i>
                    {{ __('Hantar') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
