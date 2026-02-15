<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-foreground">
        {{ __('Pergerakan/Pinjaman Aset Alih') }}
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

    <button class="flex items-center justify-between w-full px-8 py-4 text-lg font-bold tracking-tight transition-all duration-300 rounded-xl group"
        x-on:click.prevent="selected !== 1 ? selected = 1 : selected = null"
        :class="selected == 1 ? 'bg-primary text-primary-foreground shadow-lg shadow-primary/20 scale-[1.01]' : 'bg-card text-foreground hover:bg-muted border border-border shadow-sm'">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg bg-current/10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span>Permohonan Aktif</span>
            <span class="px-2.5 py-0.5 text-xs font-black rounded-full transition-colors" 
                :class="selected == 1 ? 'bg-primary-foreground text-primary' : 'bg-primary text-primary-foreground'">
                {{ $pending_applications->count() }}
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
                        <th class="px-4 py-4">Tarikh Mohon</th>
                        <th class="px-4 py-4">Nama</th>
                        <th class="px-4 py-4">Butiran Permohonan</th>
                        <th class="px-4 py-4">Jawatan/Jabatan</th>
                        <th class="px-4 py-4">Tujuan</th>
                        <th class="px-4 py-4">Lokasi Digunakan</th>
                        <th class="px-4 py-4 w-10"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pending_applications as $application)
                    <tr wire:key="{{ $application->id }}"
                        class="bg-card rounded-xl border border-border/50 hover:bg-muted/50 transition-colors duration-200 group">
                        <td class="p-4 rounded-l-xl border-y border-l border-border/50 font-medium">
                            {{ $application->application_date }}
                        </td>
                        <td class="p-4 border-y border-border/50">
                            <div class="font-bold text-foreground">{{ $application->user->name ?? $application->guest_name }}</div>
                        </td>
                        <td class="p-4 border-y border-border/50 font-medium">
                            {{ $application->description }}
                        </td>
                        <td class="p-4 border-y border-border/50">
                            <span class="px-2 py-1 rounded bg-muted text-[10px] font-bold uppercase tracking-tighter">{{ $application->position }}</span>
                            <span class="text-muted-foreground">/</span>
                            <span class="text-muted-foreground font-medium">{{ $application->department }}</span>
                        </td>
                        <td class="p-4 border-y border-border/50 text-muted-foreground leading-relaxed">
                            {{ $application->reason }}
                        </td>
                        <td class="p-4 border-y border-border/50">
                            <div class="flex items-center gap-1.5 font-semibold text-foreground">
                                <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $application->location }}
                            </div>
                        </td>
                        <td class="p-4 rounded-r-xl border-y border-r border-border/50 text-right">
                            <x-dropdown align="right" width="40">
                                <x-slot name="trigger">
                                    <button class="px-1 py-0.5 transition rounded-md hover:bg-accent">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                        </svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <button
                                        x-on:click.prevent="$dispatch('open-modal', 'approve-application-{{ $application->id }}')"
                                        class="w-full">
                                        <x-dropdown-link>
                                            Approve
                                        </x-dropdown-link>
                                    </button>
                                </x-slot>
                            </x-dropdown>
                            <x-modal name="approve-application-{{ $application->id }}" maxWidth="md" focusable>
                                <form wire:submit.prevent="approve({{ $application->id }})" class="p-6 my-4 space-y-2"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="relative text-foreground text-left" x-data="select({{ $assets }})"
                                        @click.away="close()" wire:ignore>
                                        <p class="block mb-2 text-sm font-semibold text-foreground">Pilih Aset *</p>
                                        <div class="flex items-center gap-2">
                                            <input type="text" x-model="selectedkey" name="selectfield" id="selectfield"
                                                class="hidden">
                                            <div class="relative inline-block w-full"
                                                @click="toggle(); $nextTick(() => $refs.filterinput.focus());">
                                                <button type="button"
                                                    class="relative z-0 w-full py-3 pl-4 pr-10 text-left transition-all duration-200 bg-background border-2 border-border rounded-lg cursor-pointer hover:border-ring hover:shadow-md focus:border-ring focus:ring-2 focus:ring-ring/20 focus:outline-none">
                                                    <span class="block truncate font-medium"
                                                        :class="selectedlabel ? 'text-foreground' : 'text-muted-foreground'"
                                                        x-text="selectedlabel ?? 'Sila pilih aset...'"></span>
                                                    <span
                                                        class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none transition-transform duration-200"
                                                        :class="state ? 'rotate-180' : ''">
                                                        <svg class="w-5 h-5 text-muted-foreground" viewBox="0 0 20 20"
                                                            fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                        <x-input-error :messages="$errors->get('asset_id')" class="mt-2" />
                                        <div x-show="state"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 -translate-y-1"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100 translate-y-0"
                                            x-transition:leave-end="opacity-0 -translate-y-1"
                                            class="absolute w-full mt-2 bg-background rounded-lg shadow-xl border-2 border-border z-50 overflow-hidden">
                                            <div class="p-3 border-b border-border bg-muted/30">
                                                <div class="relative">
                                                    <input type="text"
                                                        class="w-full pl-10 pr-4 py-2.5 text-sm border-2 border-border rounded-lg focus:border-ring focus:ring-2 focus:ring-ring/20 focus:outline-none bg-background transition-all duration-200"
                                                        placeholder="Cari aset..."
                                                        x-model="filter" x-ref="filterinput">
                                                    <svg class="w-5 h-5 text-muted-foreground absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <ul
                                                class="py-2 px-2 overflow-auto max-h-60 focus:outline-none scrollbar-thin">
                                                <template x-for="(value, key) in getlist()" :key="key">
                                                    <li @click="select(value, key)"
                                                        :class="{'bg-primary text-primary-foreground shadow-sm': isselected(key)}"
                                                        class="relative py-2.5 pl-4 pr-12 mb-1 text-foreground rounded-md cursor-pointer select-none transition-all duration-150 hover:bg-muted group/item">
                                                        <span x-text="value" class="block font-medium truncate text-sm transition-transform duration-150 group-hover/item:translate-x-1"></span>
                                                        <span x-show="isselected(key)"
                                                            class="absolute inset-y-0 right-0 flex items-center pr-4">
                                                            <svg class="w-5 h-5" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    </li>
                                                </template>
                                                <li x-show="Object.keys(getlist()).length === 0" class="py-8 text-center text-muted-foreground text-sm">
                                                    Tiada aset dijumpai
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-end gap-2 mt-4">
                                        <x-secondary-button x-on:click="$dispatch('close')">
                                            {{ __('Cancel') }}
                                        </x-secondary-button>
                                        <x-primary-button class="w-24 h-8">
                                            {{ __('Submit') }}
                                        </x-primary-button>
                                    </div>
                                </form>
                            </x-modal>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span>Permohonan Lulus</span>
            <span class="px-2.5 py-0.5 text-xs font-black rounded-full transition-colors" 
                :class="selected == 2 ? 'bg-primary-foreground text-primary' : 'bg-primary text-primary-foreground'">
                {{ $approved_applications->count() }}
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
                        <th class="px-4 py-4">Tarikh Terima</th>
                        <th class="px-4 py-4">Nama</th>
                        <th class="px-4 py-4">Keterangan Aset</th>
                        <th class="px-4 py-4">Jawatan/Jabatan</th>
                        <th class="px-4 py-4">Tujuan</th>
                        <th class="px-4 py-4">Lokasi Digunakan</th>
                        <th class="px-4 py-4 text-center">Pelulus</th>
                        <th class="px-4 py-4 w-10"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($approved_applications as $application)
                    <tr wire:key="{{ $application->id }}"
                        class="bg-card rounded-xl border border-border/50 hover:bg-muted/50 transition-colors duration-200 group">
                        <td class="p-4 rounded-l-xl border-y border-l border-border/50 font-medium">
                            <div class="flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-green-500 shadow-sm shadow-green-200"></div>
                                {{ $application->date_issued }}
                            </div>
                        </td>
                        <td class="p-4 border-y border-border/50">
                            <div class="font-bold text-foreground">{{ $application->user->name ?? $application->guest_name }}</div>
                        </td>
                        <td class="p-4 border-y border-border/50">
                            <div class="flex flex-col">
                                <span class="font-bold text-primary">{{ $application->asset->name }}</span>
                                <span class="text-[10px] text-muted-foreground font-mono uppercase">{{ $application->asset->tag_number ?? '' }}</span>
                            </div>
                        </td>
                        <td class="p-4 border-y border-border/50">
                            <span class="px-2 py-1 rounded bg-muted text-[10px] font-bold uppercase tracking-tighter">{{ $application->position }}</span>
                            <span class="text-muted-foreground">/</span>
                            <span class="text-muted-foreground font-medium">{{ $application->department }}</span>
                        </td>
                        <td class="p-4 border-y border-border/50 text-muted-foreground leading-relaxed">
                            {{ $application->reason }}
                        </td>
                        <td class="p-4 border-y border-border/50">
                            <div class="flex items-center gap-1.5 font-semibold text-foreground">
                                <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $application->location }}
                            </div>
                        </td>
                        <td class="p-4 border-y border-border/50 text-center">
                            <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-muted/50 border border-border/50 text-[10px] font-bold text-muted-foreground uppercase">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                {{ $application->handler }}
                            </div>
                        </td>
                        <td class="p-4 rounded-r-xl border-y border-r border-border/50 text-right">
                            <x-dropdown align="right" width="40">
                                <x-slot name="trigger">
                                    <button class="px-1 py-0.5 transition rounded-md hover:bg-accent">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                        </svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <button wire:click="receive({{ $application->id }})" class="w-full">
                                        <x-dropdown-link>
                                            Receive
                                        </x-dropdown-link>
                                    </button>
                                    <button wire:click="revert({{ $application->id }})" class="w-full">
                                        <x-dropdown-link>
                                            Revert
                                        </x-dropdown-link>
                                    </button>
                                </x-slot>
                            </x-dropdown>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
