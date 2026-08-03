<x-slot name="header">
    <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
            <i class="ph ph-list-checks text-xl leading-none"></i>
        </div>
        <div>
            <h2 class="text-2xl font-bold leading-tight tracking-tight text-foreground">
                {{ __('Pergerakan/Pinjaman Aset Alih') }}
            </h2>
            <p class="text-sm text-muted-foreground">Kelulusan dan pengurusan permohonan aset</p>
        </div>
    </div>
</x-slot>

<div class="mx-auto max-w-6xl" x-data="{ selected: 1 }">
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

    {{-- Pending applications --}}
    <button type="button"
        x-on:click.prevent="selected !== 1 ? selected = 1 : selected = null"
        class="card-surface card-hover flex w-full items-center justify-between px-5 py-4 text-start">
        <span class="flex items-center gap-3">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                <i class="ph ph-hourglass text-lg leading-none"></i>
            </span>
            <span>
                <span class="block text-sm font-bold text-foreground">Permohonan Aktif</span>
                <span class="block text-xs text-muted-foreground">Menunggu kelulusan</span>
            </span>
        </span>
        <span class="flex items-center gap-3">
            <span class="chip bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">{{ $pending_applications->count() }}</span>
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
                            <th class="px-4 py-3.5">Tarikh Mohon</th>
                            <th class="px-4 py-3.5">Nama</th>
                            <th class="px-4 py-3.5">Butiran Permohonan</th>
                            <th class="px-4 py-3.5">Jawatan/Jabatan</th>
                            <th class="px-4 py-3.5">Tujuan</th>
                            <th class="px-4 py-3.5">Lokasi Digunakan</th>
                            <th class="px-4 py-3.5 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($pending_applications as $application)
                        <tr wire:key="{{ $application->id }}" class="transition-colors duration-150 hover:bg-muted/50">
                            <td class="tnum whitespace-nowrap px-4 py-3 text-muted-foreground">{{ $application->application_date }}</td>
                            <td class="px-4 py-3 font-semibold text-foreground">{{ $application->user->name ?? $application->guest_name }}</td>
                            <td class="max-w-[180px] truncate px-4 py-3 font-medium text-foreground">{{ $application->description }}</td>
                            <td class="px-4 py-3">
                                <span class="chip bg-muted text-muted-foreground">{{ $application->position }}</span>
                                <span class="text-xs text-muted-foreground">{{ $application->department }}</span>
                            </td>
                            <td class="max-w-[180px] truncate px-4 py-3 text-muted-foreground">{{ $application->reason }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ $application->location }}</td>
                            <td class="px-4 py-3 text-right">
                                <x-dropdown align="right" width="40">
                                    <x-slot name="trigger">
                                        <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-muted-foreground transition hover:bg-accent hover:text-accent-foreground">
                                            <i class="ph ph-dots-three-vertical text-lg leading-none"></i>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <button x-on:click.prevent="$dispatch('open-modal', 'approve-application-{{ $application->id }}')" class="w-full">
                                            <x-dropdown-link>
                                                <span class="inline-flex items-center gap-2">
                                                    <i class="ph ph-check text-base leading-none text-emerald-600"></i>
                                                    Approve
                                                </span>
                                            </x-dropdown-link>
                                        </button>
                                    </x-slot>
                                </x-dropdown>

                                {{-- Approve modal with asset picker --}}
                                <x-modal name="approve-application-{{ $application->id }}" maxWidth="md" focusable>
                                    <form wire:submit.prevent="approve({{ $application->id }})" class="space-y-4 p-6 sm:p-8" enctype="multipart/form-data">
                                        @csrf
                                        <div>
                                            <h3 class="text-lg font-bold tracking-tight text-foreground">Luluskan Permohonan</h3>
                                            <p class="mt-1 text-sm text-muted-foreground">
                                                {{ $application->user->name ?? $application->guest_name }} &middot; {{ $application->description }}
                                            </p>
                                        </div>
                                        <div class="relative" x-data="select({{ $assets }})" @click.away="close()" wire:ignore>
                                            <x-input-label :value="__('Pilih Aset *')" />
                                            <div class="flex items-center gap-2">
                                                <input type="text" x-model="selectedkey" name="selectfield" id="selectfield-{{ $application->id }}" class="hidden">
                                                <div class="relative w-full" @click="toggle(); $nextTick(() => $refs.filterinput.focus());">
                                                    <button type="button"
                                                        class="flex h-10 w-full items-center justify-between rounded-lg border border-input bg-card px-3.5 text-sm text-foreground shadow-sm transition focus:border-ring focus:outline-none focus:ring-2 focus:ring-ring/30">
                                                        <span class="block truncate" :class="selectedlabel ? 'text-foreground' : 'text-muted-foreground'" x-text="selectedlabel ?? 'Sila pilih aset...'"></span>
                                                        <i class="ph ph-caret-down ml-2 shrink-0 text-sm leading-none text-muted-foreground" :class="state && 'rotate-180'"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <x-input-error :messages="$errors->get('asset_id')" class="mt-2" />
                                            <div x-show="state" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                                class="absolute z-20 mt-1.5 w-full rounded-xl border border-border bg-popover p-1.5 shadow-lg">
                                                <div class="relative mb-1.5">
                                                    <i class="ph ph-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm leading-none text-muted-foreground"></i>
                                                    <input type="text"
                                                        class="h-9 w-full rounded-lg border border-input bg-card pl-8 pr-3 text-sm text-foreground shadow-sm transition placeholder:text-muted-foreground/70 focus:border-ring focus:outline-none focus:ring-2 focus:ring-ring/30"
                                                        placeholder="Cari aset..." x-model="filter" x-ref="filterinput">
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
                                                        Tiada aset dijumpai
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-end gap-2 pt-2">
                                            <x-secondary-button x-on:click="$dispatch('close')">{{ __('Batal') }}</x-secondary-button>
                                            <x-primary-button>{{ __('Lulus') }}</x-primary-button>
                                        </div>
                                    </form>
                                </x-modal>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-5 py-14 text-center">
                                <i class="ph ph-check-circle text-3xl leading-none text-muted-foreground/40"></i>
                                <p class="mt-3 text-sm text-muted-foreground">Tiada permohonan aktif</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Approved applications --}}
    <button type="button"
        x-on:click.prevent="selected !== 2 ? selected = 2 : selected = null"
        class="card-surface card-hover mt-4 flex w-full items-center justify-between px-5 py-4 text-start">
        <span class="flex items-center gap-3">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                <i class="ph ph-check-circle text-lg leading-none"></i>
            </span>
            <span>
                <span class="block text-sm font-bold text-foreground">Permohonan Lulus</span>
                <span class="block text-xs text-muted-foreground">Sedang dipinjam</span>
            </span>
        </span>
        <span class="flex items-center gap-3">
            <span class="chip bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">{{ $approved_applications->count() }}</span>
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
                            <th class="px-4 py-3.5">Tarikh Terima</th>
                            <th class="px-4 py-3.5">Nama</th>
                            <th class="px-4 py-3.5">Keterangan Aset</th>
                            <th class="px-4 py-3.5">Jawatan/Jabatan</th>
                            <th class="px-4 py-3.5">Tujuan</th>
                            <th class="px-4 py-3.5">Lokasi Digunakan</th>
                            <th class="px-4 py-3.5">Pelulus</th>
                            <th class="px-4 py-3.5 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($approved_applications as $application)
                        <tr wire:key="{{ $application->id }}" class="transition-colors duration-150 hover:bg-muted/50">
                            <td class="tnum whitespace-nowrap px-4 py-3 text-muted-foreground">{{ $application->date_issued }}</td>
                            <td class="px-4 py-3 font-semibold text-foreground">{{ $application->user->name ?? $application->guest_name }}</td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-primary">{{ $application->asset->name }}</p>
                                <p class="font-mono text-[11px] uppercase text-muted-foreground">{{ $application->asset->tag_number ?? '' }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="chip bg-muted text-muted-foreground">{{ $application->position }}</span>
                                <span class="text-xs text-muted-foreground">{{ $application->department }}</span>
                            </td>
                            <td class="max-w-[160px] truncate px-4 py-3 text-muted-foreground">{{ $application->reason }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ $application->location }}</td>
                            <td class="px-4 py-3">
                                <span class="chip bg-muted text-muted-foreground">
                                    <i class="ph ph-user text-sm leading-none"></i>
                                    {{ $application->handler }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <x-dropdown align="right" width="40">
                                    <x-slot name="trigger">
                                        <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-muted-foreground transition hover:bg-accent hover:text-accent-foreground">
                                            <i class="ph ph-dots-three-vertical text-lg leading-none"></i>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <button wire:click="receive({{ $application->id }})" class="w-full">
                                            <x-dropdown-link>
                                                <span class="inline-flex items-center gap-2">
                                                    <i class="ph ph-arrow-down-left text-base leading-none text-emerald-600"></i>
                                                    Receive
                                                </span>
                                            </x-dropdown-link>
                                        </button>
                                        <button wire:click="revert({{ $application->id }})" class="w-full">
                                            <x-dropdown-link>
                                                <span class="inline-flex items-center gap-2">
                                                    <i class="ph ph-arrow-counter-clockwise text-base leading-none text-orange-600"></i>
                                                    Revert
                                                </span>
                                            </x-dropdown-link>
                                        </button>
                                    </x-slot>
                                </x-dropdown>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-5 py-14 text-center">
                                <i class="ph ph-clipboard-text text-3xl leading-none text-muted-foreground/40"></i>
                                <p class="mt-3 text-sm text-muted-foreground">Tiada permohonan lulus</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function select(assets) {
        return {
            state: false,
            filter: '',
            list: assets,
            selectedkey: @entangle('asset_id'),
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
                } else {
                    this.selectedlabel = value;
                    this.selectedkey = key;
                    this.state = false;
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
