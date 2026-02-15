<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-foreground">
        {{ $asset->name }} | {{ $asset->model }} | {{ $asset->registration_no }}
    </h2>
</x-slot>
<div class="max-w-5xl p-2 mx-auto my-4 space-y-3 md:p-0">
    <div class="flex items-center justify-center gap-2 mb-6">
        <a href="{{ route('asset.submission') }}" class="px-4 py-2 transition rounded-full hover:bg-accent"
            wire:navigate>
            {{ __('Senarai Permohonan') }}
        </a>
        <a href="{{ route('asset.listing') }}" class="px-4 py-2 text-primary-foreground bg-primary rounded-full" wire:navigate>
            {{ __('Senarai Aset') }}
        </a>
    </div>
    <div class="flex items-center justify-between">
        <a href="{{ route('asset.listing') }}" wire:navigate>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor"
                class="p-1 transition-all rounded-full w-7 h-7 hover:bg-accent hover:-translate-x-1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div class="flex items-center gap-3">
            <x-primary-button :disabled="!$selectedApplications" wire:click.prevent="exportPDF" target="exportPDF"
                class="h-8 w-36 disabled:opacity-60">
                Export to PDF
            </x-primary-button>
            @can('admin')
            <button x-on:click.prevent="$dispatch('open-modal', 'delete-asset-confirmation')"
                class="p-1 text-destructive-foreground bg-destructive rounded-md hover:bg-destructive/90">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
            </button>
            <x-modal name="delete-asset-confirmation" maxWidth="sm">
                <div class="p-6">
                    <h2 class="text-base font-medium text-foreground">
                        {{ __('Confirm Asset Deletion') }}
                    </h2>
                    <div class="flex items-center justify-end gap-2 mt-4">
                        <x-secondary-button x-on:click="$dispatch('close')">
                            {{ __('Cancel') }}
                        </x-secondary-button>
                        <x-primary-button wire:click.prevent="deleteAsset({{ $asset->id }})" class="w-24 h-8">
                            {{ __('Confirm') }}
                        </x-primary-button>
                    </div>
                </div>
            </x-modal>
            @endcan
        </div>
    </div>
    <div class="my-4 overflow-x-auto rounded-lg" x-data="checkboxes">
        <table class="w-full mb-4 text-xs text-left text-foreground rounded-lg table-auto">
            <thead class="text-xs font-medium uppercase bg-muted border-b-2 border-border">
                <tr>
                    <th class="px-2 py-2 tracking-wide">
                        <input type="checkbox" wire:model.live="selectAll"
                            class="text-primary bg-input border-input rounded focus:ring-ring focus:ring-2" />
                    </th>
                    <th class="px-2 py-2 tracking-wide ">
                        Tarikh (Pinjam - Pulang)
                    </th>
                    <th class="px-2 py-2 tracking-wide">
                        Nama Pemohon
                    </th>
                    <th class="px-2 py-2 tracking-wide text-center">
                        Jawatan/Bahagian
                    </th>
                    <th class="px-2 py-2 tracking-wide">
                        Tujuan
                    </th>
                    <th class="px-2 py-2 tracking-wide">
                        Tempat Digunakan
                    </th>
                    <th class="px-2 py-2 tracking-wide">
                        Pelulus
                    </th>
                    <th class="px-2 py-2 tracking-wide">
                        Penerima
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $application)
                <tr wire:key="{{ $application->id }}" class="transition hover:bg-accent">
                    <td class="px-2 py-2">
                        <input type="checkbox" wire:model.live="selectedApplications" value="{{ $application->id }}"
                            class="text-primary bg-input border-input rounded focus:ring-ring focus:ring-2" />
                    </td>
                    <td class="px-2 py-2">
                        {{ $application->date_issued }} - {{ $application->date_returned }}
                    </td>
                    <td class="px-2 py-2">
                        {{ $application->user->name }}
                    </td>
                    <td class="px-2 py-2 text-center">
                        {{ $application->position }}/{{ $application->department }}
                    </td>
                    <td class="px-2 py-2">
                        {{ $application->reason }}
                    </td>
                    <td class="px-2 py-2">
                        {{ $application->location }}
                    </td>
                    <td class="px-2 py-2">
                        {{ $application->handler }}
                    </td>
                    <td class="px-2 py-2">
                        {{ $application->receiver }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $applications->links() }}
    </div>
</div>
