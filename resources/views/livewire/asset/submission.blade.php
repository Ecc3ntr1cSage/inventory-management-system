<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ __('Pergerakan/Pinjaman Aset Alih') }}
    </h2>
</x-slot>
<div class="max-w-5xl p-2 mx-auto my-4 space-y-3 md:p-0" x-data="{ selected:1 }">
    <div class="flex items-center justify-center gap-2 mb-6">
        <a href="{{ route('asset.submission') }}" class="px-4 py-2 text-white bg-gray-800 rounded-full" wire:navigate>
            {{ __('Senarai Permohonan') }}
        </a>
        <a href="{{ route('asset.listing') }}" class="px-4 py-2 transition rounded-full hover:bg-gray-300"
            wire:navigate>
            {{ __('Senarai Aset') }}
        </a>
    </div>
    <button class="w-full px-8 py-3 text-xl font-medium tracking-wide bg-gray-300 rounded-md"
        x-on:click.prevent="selected !== 1 ? selected = 1 : selected = null"
        x-bind:class="selected == 1 ? 'transition bg-[radial-gradient(ellipse_at_bottom,_var(--tw-gradient-stops))] from-indigo-100 via-gray-300 to-gray-300 ring-2 ring-gray-500 ring-offset-2 ' : ''">
        Permohonan Aktif ({{ $pending_applications->count() }})
    </button>
    <div class="relative overflow-hidden transition-all duration-700 max-h-0" style="" x-ref="container1"
        x-bind:style="selected == 1 ? 'max-height: ' + $refs.container1.scrollHeight + 'px' : ''">
        <div class="px-2 pb-16 overflow-x-auto rounded-lg">
            <table class="w-full mb-4 text-xs text-left text-gray-800 border-separate table-auto border-spacing-y-2">
                <thead class="text-xs font-medium uppercase bg-gray-300">
                    <tr class="tracking-wide">
                        <th class="px-3 py-4 rounded-l-lg">
                            Tarikh Mohon
                        </th>
                        <th class="px-3 py-4">
                            Nama
                        </th>
                        <th class="px-3 py-4">
                            Butiran Permohonan
                        </th>
                        <th class="px-3 py-4">
                            Jawatan/Jabatan
                        </th>
                        <th class="px-3 py-4">
                            Tujuan
                        </th>
                        <th class="px-3 py-4">
                            Lokasi Digunakan
                        </th>
                        <th class="px-3 py-4 rounded-r-lg">

                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pending_applications as $application)
                    <tr wire:key="{{ $application->id }}"
                        class="transition bg-gray-200 rounded-lg hover:ring-2 hover:ring-indigo-500 ring-offset-2">
                        <td class="p-3 rounded-l-lg">
                            {{ $application->application_date }}
                        </td>
                        <td class="p-3">
                            {{ $application->user->name }}
                        </td>
                        <td class="p-3">
                            {{ $application->description }}
                        </td>
                        <td class="p-3">
                            {{ $application->position }}/{{ $application->department }}
                        </td>
                        <td class="p-3">
                            {{ $application->reason }}
                        </td>
                        <td class="p-3">
                            {{ $application->location }}
                        </td>
                        <td class="p-3 rounded-r-lg">
                            <x-dropdown align="right" width="40">
                                <x-slot name="trigger">
                                    <button class="px-1 py-0.5 transition rounded-md hover:bg-indigo-400/40">
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
                                    <div class="relative text-black" x-data="select({{ $assets }})"
                                        @click.away="close()">
                                        <p class="block mb-1 font-medium text-gray-700">Pilih Aset *</p>
                                        <div class="flex items-center gap-2">
                                            <input type="text" x-model="selectedkey" name="selectfield" id="selectfield"
                                                class="hidden">
                                            <div class="relative inline-block w-full rounded-md shadow-sm"
                                                @click="toggle(); $nextTick(() => $refs.filterinput.focus());">
                                                <button type="button" autofocus
                                                    class="relative z-0 w-full py-2 pl-3 pr-10 text-left transition duration-150 ease-in-out bg-white border-2 border-gray-300 rounded-md cursor-default focus:border-indigo-400 sm:leading-5">
                                                    <span class="block truncate"
                                                        x-text="selectedlabel ?? 'Please Select'"></span>
                                                    <span
                                                        class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                        <svg class="w-5 h-5 text-gray-400" viewBox="0 0 20 20"
                                                            fill="none" stroke="currentColor">
                                                            <path d="M7 7l3-3 3 3m0 6l-3 3-3-3" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                        <x-input-error :messages="$errors->get('asset_id')" class="mt-2" />
                                        <div x-show="state"
                                            class="absolute w-full p-2 mt-1 bg-white rounded-md shadow-lg z-100">
                                            <input type="text"
                                                class="w-full px-2 py-1 mb-1 border border-gray-400 rounded-md"
                                                x-model="filter" x-ref="filterinput">
                                            <ul
                                                class="py-1 overflow-auto leading-6 rounded-md shadow-xs max-h-60 focus:outline-none sm:leading-5">
                                                <template x-for="(value, key) in getlist()" :key="key">
                                                    <li @click="select(value, key)"
                                                        :class="{'bg-gray-100': isselected(key)}"
                                                        class="relative py-1 pl-3 mb-1 text-gray-900 rounded-md cursor-pointer select-none pr-9 hover:bg-gray-100">
                                                        <span x-text="value" class="block font-normal truncate"></span>
                                                        <span x-show="isselected(key)"
                                                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-700">
                                                            <svg class="w-4 h-4" viewBox="0 0 20 20"
                                                                fill="currentColor">
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
    <button class="w-full px-8 py-3 text-xl font-medium tracking-wide bg-gray-300 rounded-md"
        x-on:click.prevent="selected !== 2 ? selected = 2 : selected = null"
        x-bind:class="selected == 2 ? 'transition bg-[radial-gradient(ellipse_at_bottom,_var(--tw-gradient-stops))] from-indigo-100 via-gray-300 to-gray-300 ring-2 ring-gray-500 ring-offset-2 ' : ''">
        Permohonan Lulus ({{ $approved_applications->count() }})
    </button>
    <div class="relative overflow-hidden transition-all duration-700 max-h-0" style="" x-ref="container2"
        x-bind:style="selected == 2 ? 'max-height: ' + $refs.container2.scrollHeight + 'px' : ''">
        <div class="px-2 pb-16 overflow-x-auto rounded-lg">
            <table class="w-full mb-4 text-xs text-left text-gray-800 border-separate table-auto border-spacing-y-2">
                <thead class="text-xs font-medium uppercase bg-gray-300 border-b-2">
                    <tr class="tracking-wide">
                        <th class="px-3 py-4 rounded-l-lg">
                            Tarikh Terima
                        </th>
                        <th class="px-3 py-4">
                            Nama
                        </th>
                        <th class="px-3 py-4">
                            Keterangan Aset
                        </th>
                        <th class="px-3 py-4">
                            Jawatan/Jabatan
                        </th>
                        <th class="px-3 py-4">
                            Tujuan
                        </th>
                        <th class="px-3 py-4">
                            Lokasi Digunakan
                        </th>
                        <th class="px-3 py-4">
                            Pelulus
                        </th>
                        <th class="px-3 py-4 rounded-r-lg">

                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($approved_applications as $application)
                    <tr wire:key="{{ $application->id }}"
                        class="transition bg-gray-200 rounded-lg hover:ring-2 hover:ring-indigo-500 ring-offset-2">
                        <td class="p-3 rounded-l-lg">
                            {{ $application->date_issued }}
                        </td>
                        <td class="p-3">
                            {{ $application->user->name }}
                        </td>
                        <td class="p-3">
                            {{ $application->asset->name }}
                        </td>
                        <td class="p-3">
                            {{ $application->position }}/{{ $application->department }}
                        </td>
                        <td class="p-3">
                            {{ $application->reason }}
                        </td>
                        <td class="p-3">
                            {{ $application->location }}
                        </td>
                        <td class="p-3">
                            {{ $application->handler }}
                        </td>
                        <td class="p-3 rounded-r-lg">
                            <x-dropdown align="right" width="40">
                                <x-slot name="trigger">
                                    <button class="px-1 py-0.5 transition rounded-md hover:bg-indigo-400/40">
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
