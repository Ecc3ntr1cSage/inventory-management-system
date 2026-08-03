<x-slot name="header">
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                <i class="ph ph-arrows-left-right text-xl leading-none"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold leading-tight tracking-tight text-foreground">
                    {{ __('Senarai Aset Alih') }}
                </h2>
                <p class="text-sm text-muted-foreground">Ketersediaan aset alih semasa</p>
            </div>
        </div>
        @can('admin')
        <button x-on:click.prevent="$dispatch('open-modal', 'add-asset')"
            class="inline-flex h-10 cursor-pointer items-center justify-center gap-2 rounded-lg bg-primary px-4 text-sm font-semibold text-primary-foreground shadow-sm transition duration-200 hover:bg-primary/90">
            <i class="ph ph-plus text-base leading-none"></i>
            Aset Baharu
        </button>
        @endcan
    </div>
</x-slot>

<div class="mx-auto max-w-5xl" x-data="{ selected: 1 }">
    {{-- Segmented control --}}
    <div class="mb-6 inline-flex w-full rounded-xl border border-border bg-card p-1 shadow-sm sm:w-auto">
        <a href="{{ route('asset.submission') }}"
            class="flex-1 rounded-lg px-5 py-2.5 text-center text-sm font-semibold transition-all duration-150 sm:flex-none {{ request()->routeIs('asset.submission') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:text-foreground' }}"
            wire:navigate>
            Senarai Permohonan
        </a>
        <a href="{{ route('asset.listing') }}"
            class="flex-1 rounded-lg px-5 py-2.5 text-center text-sm font-semibold transition-all duration-150 sm:flex-none {{ request()->routeIs('asset.listing') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:text-foreground' }}"
            wire:navigate>
            Senarai Aset
        </a>
    </div>

    {{-- Add asset modal --}}
    @can('admin')
    <x-modal name="add-asset" maxWidth="md" focusable>
        <form wire:submit.prevent="addAsset" class="space-y-4 p-6 sm:p-8" enctype="multipart/form-data">
            @csrf
            <div>
                <h3 class="text-lg font-bold tracking-tight text-foreground">Tambah Aset Alih</h3>
                <p class="mt-1 text-sm text-muted-foreground">Daftar aset baharu ke dalam sistem.</p>
            </div>
            <div>
                <x-input-label for="asset_name" :value="__('Perihal Aset *')" />
                <x-text-input wire:model="asset_name" id="asset_name" class="mt-1 block w-full" type="text" />
                <x-input-error :messages="$errors->get('asset_name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="asset_model" :value="__('Jenama dan Model *')" />
                <x-text-input wire:model="asset_model" id="asset_model" class="mt-1 block w-full" type="text" />
                <x-input-error :messages="$errors->get('asset_model')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="registration_no" :value="__('No. Siri Pendaftaran *')" />
                <x-text-input wire:model="registration_no" id="registration_no" class="mt-1 block w-full" type="text" />
                <x-input-error :messages="$errors->get('registration_no')" class="mt-2" />
            </div>
            <div class="flex items-center justify-end gap-2 pt-2">
                <x-secondary-button x-on:click="$dispatch('close')">{{ __('Batal') }}</x-secondary-button>
                <x-primary-button target="addAsset">{{ __('Simpan') }}</x-primary-button>
            </div>
        </form>
    </x-modal>
    @endcan

    {{-- Available assets --}}
    <button type="button"
        x-on:click.prevent="selected !== 1 ? selected = 1 : selected = null"
        class="card-surface card-hover flex w-full items-center justify-between px-5 py-4 text-start">
        <span class="flex items-center gap-3">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                <i class="ph ph-check-circle text-lg leading-none"></i>
            </span>
            <span>
                <span class="block text-sm font-bold text-foreground">Aset Tersedia</span>
                <span class="block text-xs text-muted-foreground">{{ $available_assets->count() }} aset sedia untuk dipinjam</span>
            </span>
        </span>
        <span class="flex items-center gap-3">
            <span class="chip bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">{{ $available_assets->count() }}</span>
            <i class="ph ph-caret-down text-base leading-none text-muted-foreground" :class="selected == 1 && 'rotate-180'"></i>
        </span>
    </button>

    <div x-show="selected == 1" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="mt-3">
        <div class="card-surface overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-border text-[11px] font-bold uppercase tracking-widest text-muted-foreground">
                            <th class="px-5 py-3.5">Nama Aset</th>
                            <th class="px-5 py-3.5">Model</th>
                            <th class="px-5 py-3.5">No. Siri Pendaftaran</th>
                            <th class="px-5 py-3.5 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($available_assets as $asset)
                        <tr wire:key="{{ $asset->id }}" class="transition-colors duration-150 hover:bg-muted/50">
                            <td class="px-5 py-3.5 font-semibold text-foreground">{{ $asset->name }}</td>
                            <td class="px-5 py-3.5 text-muted-foreground">{{ $asset->model }}</td>
                            <td class="tnum px-5 py-3.5 font-mono text-xs text-muted-foreground">{{ $asset->registration_no }}</td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('asset.record', $asset->id) }}" wire:navigate
                                    class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold text-primary transition hover:bg-primary/10">
                                    Rekod
                                    <i class="ph ph-arrow-right text-sm leading-none"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center">
                                <p class="text-sm text-muted-foreground">Tiada aset tersedia</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Unavailable assets --}}
    <button type="button"
        x-on:click.prevent="selected !== 2 ? selected = 2 : selected = null"
        class="card-surface card-hover mt-4 flex w-full items-center justify-between px-5 py-4 text-start">
        <span class="flex items-center gap-3">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                <i class="ph ph-clock text-lg leading-none"></i>
            </span>
            <span>
                <span class="block text-sm font-bold text-foreground">Aset Sedang Dipinjam</span>
                <span class="block text-xs text-muted-foreground">{{ $unavailable_assets->count() }} aset sedang digunakan</span>
            </span>
        </span>
        <span class="flex items-center gap-3">
            <span class="chip bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">{{ $unavailable_assets->count() }}</span>
            <i class="ph ph-caret-down text-base leading-none text-muted-foreground" :class="selected == 2 && 'rotate-180'"></i>
        </span>
    </button>

    <div x-show="selected == 2" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="mt-3">
        <div class="card-surface overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-border text-[11px] font-bold uppercase tracking-widest text-muted-foreground">
                            <th class="px-5 py-3.5">Nama Aset</th>
                            <th class="px-5 py-3.5">Model</th>
                            <th class="px-5 py-3.5">No. Siri Pendaftaran</th>
                            <th class="px-5 py-3.5 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($unavailable_assets as $asset)
                        <tr wire:key="{{ $asset->id }}" class="transition-colors duration-150 hover:bg-muted/50">
                            <td class="px-5 py-3.5 font-semibold text-foreground">{{ $asset->name }}</td>
                            <td class="px-5 py-3.5 text-muted-foreground">{{ $asset->model }}</td>
                            <td class="tnum px-5 py-3.5 font-mono text-xs text-muted-foreground">{{ $asset->registration_no }}</td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('asset.record', $asset->id) }}" wire:navigate
                                    class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold text-primary transition hover:bg-primary/10">
                                    Rekod
                                    <i class="ph ph-arrow-right text-sm leading-none"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center">
                                <p class="text-sm text-muted-foreground">Tiada aset sedang dipinjam</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
