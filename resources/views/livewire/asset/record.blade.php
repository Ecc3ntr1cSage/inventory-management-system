<x-slot name="header">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('asset.listing') }}" wire:navigate
                class="flex h-10 w-10 items-center justify-center rounded-lg border border-border bg-card text-muted-foreground shadow-sm transition hover:bg-accent hover:text-accent-foreground"
                aria-label="Kembali ke senarai">
                <i class="ph ph-arrow-left text-lg leading-none"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold leading-tight tracking-tight text-foreground">
                    {{ $asset->name }}
                </h2>
                <p class="text-sm text-muted-foreground">{{ $asset->model }} &middot; {{ $asset->registration_no }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <x-primary-button :disabled="!$selectedApplications" wire:click.prevent="exportPDF" target="exportPDF" class="disabled:pointer-events-none disabled:opacity-50">
                <i class="ph ph-file-pdf text-base leading-none"></i>
                Export to PDF
            </x-primary-button>
            @can('admin')
            <button x-on:click.prevent="$dispatch('open-modal', 'delete-asset-confirmation')"
                class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-destructive/10 text-destructive transition hover:bg-destructive hover:text-destructive-foreground"
                aria-label="Padam aset">
                <i class="ph ph-trash text-lg leading-none"></i>
            </button>
            @endcan
        </div>
    </div>
</x-slot>

<div class="mx-auto max-w-6xl">
    {{-- Delete asset modal --}}
    @can('admin')
    <x-modal name="delete-asset-confirmation" maxWidth="sm">
        <div class="p-6 sm:p-8">
            <h2 class="text-lg font-bold tracking-tight text-foreground">
                {{ __('Confirm Asset Deletion') }}
            </h2>
            <p class="mt-1 text-sm text-muted-foreground">Tindakan ini tidak boleh dibatalkan.</p>
            <div class="mt-6 flex items-center justify-end gap-2">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>
                <x-danger-button wire:click.prevent="deleteAsset({{ $asset->id }})">
                    {{ __('Confirm') }}
                </x-danger-button>
            </div>
        </div>
    </x-modal>
    @endcan

    {{-- History table --}}
    <div class="card-surface overflow-hidden">
        <div class="flex items-center justify-between border-b border-border px-5 py-4">
            <h3 class="text-sm font-bold tracking-tight text-foreground">Sejarah Pinjaman</h3>
            <span class="chip bg-muted text-muted-foreground">{{ $applications->total() }} rekod</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-border text-[11px] font-bold uppercase tracking-widest text-muted-foreground">
                        <th class="w-12 px-4 py-3.5">
                            <input type="checkbox" wire:model.live="selectAll"
                                class="h-4 w-4 rounded border-border bg-card text-primary focus:ring-ring/40" />
                        </th>
                        <th class="px-4 py-3.5">Tarikh (Pinjam - Pulang)</th>
                        <th class="px-4 py-3.5">Nama Pemohon</th>
                        <th class="px-4 py-3.5">Jawatan/Bahagian</th>
                        <th class="px-4 py-3.5">Tujuan</th>
                        <th class="px-4 py-3.5">Tempat Digunakan</th>
                        <th class="px-4 py-3.5">Pelulus</th>
                        <th class="px-4 py-3.5">Penerima</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($applications as $application)
                    <tr wire:key="{{ $application->id }}" class="transition-colors duration-150 hover:bg-muted/50">
                        <td class="px-4 py-3">
                            <input type="checkbox" wire:model.live="selectedApplications" value="{{ $application->id }}"
                                class="h-4 w-4 rounded border-border bg-card text-primary focus:ring-ring/40" />
                        </td>
                        <td class="tnum whitespace-nowrap px-4 py-3 text-muted-foreground">
                            {{ $application->date_issued }} - {{ $application->date_returned }}
                        </td>
                        <td class="px-4 py-3 font-semibold text-foreground">{{ $application->user->name ?? $application->guest_name }}</td>
                        <td class="px-4 py-3 text-muted-foreground">{{ $application->position }}/{{ $application->department }}</td>
                        <td class="max-w-[200px] truncate px-4 py-3 text-muted-foreground">{{ $application->reason }}</td>
                        <td class="px-4 py-3 text-muted-foreground">{{ $application->location }}</td>
                        <td class="px-4 py-3 text-muted-foreground">{{ $application->handler }}</td>
                        <td class="px-4 py-3 text-muted-foreground">{{ $application->receiver }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-14 text-center">
                            <i class="ph ph-clipboard-text text-3xl leading-none text-muted-foreground/40"></i>
                            <p class="mt-3 text-sm text-muted-foreground">Tiada rekod pinjaman</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $applications->links() }}
    </div>
</div>
