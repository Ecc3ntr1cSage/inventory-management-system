<x-slot name="header">
    <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
            <i class="ph ph-clipboard-text text-xl leading-none"></i>
        </div>
        <div>
            <h2 class="text-2xl font-bold leading-tight tracking-tight text-foreground">
                {{ __('Permohonan Peralatan IT') }}
            </h2>
            <p class="text-sm text-muted-foreground">Borang permohonan pergerakan/pinjaman aset alih</p>
        </div>
    </div>
</x-slot>

<div>
    @if($requestPending || $requestApproved)
    <div class="mx-auto max-w-5xl space-y-4">
        @if($requestApproved)
        <div class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-800/40 dark:bg-emerald-900/20 dark:text-emerald-300">
            <i class="ph ph-check-circle mt-0.5 text-lg leading-none"></i>
            <p>Permohonan anda telah diluluskan. Sila berkunjung ke Unit IT untuk mengambil peralatan.</p>
        </div>
        @endif

        <div class="card-surface overflow-hidden">
            <div class="flex items-center justify-between border-b border-border px-5 py-4">
                <h3 class="text-sm font-bold tracking-tight text-foreground">Sejarah Permohonan</h3>
                <span class="chip bg-muted text-muted-foreground">{{ $applications->total() }} rekod</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-border text-[11px] font-bold uppercase tracking-widest text-muted-foreground">
                            <th class="px-4 py-3.5">Tarikh Mohon</th>
                            <th class="px-4 py-3.5">Butiran Permohonan</th>
                            <th class="px-4 py-3.5">Butiran Aset</th>
                            <th class="px-4 py-3.5">Tujuan</th>
                            <th class="px-4 py-3.5">Lokasi Digunakan</th>
                            <th class="px-4 py-3.5 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach($applications as $application)
                        <tr wire:key="{{ $application->id }}" class="transition-colors duration-150 hover:bg-muted/50">
                            <td class="tnum px-4 py-3 text-muted-foreground">{{ $application->application_date }}</td>
                            <td class="px-4 py-3 font-medium text-foreground">{{ $application->description }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ $application->asset->name ?? '-' }}</td>
                            <td class="max-w-[180px] truncate px-4 py-3 text-muted-foreground">{{ $application->reason }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ $application->location }}</td>
                            <td class="px-4 py-3 text-right">
                                @if($application->status == 0)
                                <span class="chip bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">Pending</span>
                                @elseif($application->status == 1)
                                <span class="chip bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">Approved</span>
                                @elseif($application->status == 3)
                                <span class="chip bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-400">Completed</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div>{{ $applications->links() }}</div>
    </div>
    @else
    <div class="mx-auto max-w-2xl">
        <div class="mb-8 text-center">
            <h1 class="text-2xl font-bold leading-tight tracking-tight text-foreground sm:text-3xl">
                Borang permohonan pergerakan/pinjaman aset alih
            </h1>
            <p class="mx-auto mt-2 max-w-md text-sm text-muted-foreground">
                Isi butiran di bawah dan pasukan IT akan menyemak permohonan anda.
            </p>
        </div>

        <div class="card-surface p-6 sm:p-8">
            <form wire:submit.prevent="application" class="space-y-5" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <x-input-label for="name" :value="__('Nama Pemohon')" />
                        <x-text-input wire:model="name" id="name" class="mt-1 block w-full" type="text" disabled />
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
    @endif
</div>
