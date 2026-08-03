<div class="mx-auto max-w-5xl">
    <div class="mb-8 text-center">
        <span class="chip mb-4 bg-primary/10 text-primary">
            <i class="ph ph-hourglass text-sm leading-none"></i>
            Semakan Status
        </span>
        <h1 class="text-3xl font-bold leading-tight tracking-tight text-foreground sm:text-4xl">
            Permohonan Dalam Proses
        </h1>
        <p class="mx-auto mt-3 max-w-md text-muted-foreground">
            Senarai permohonan aset alih yang masih menunggu kelulusan.
        </p>
    </div>

    <div class="card-surface overflow-hidden">
        <div class="flex items-center justify-between border-b border-border px-5 py-4">
            <h3 class="text-sm font-bold tracking-tight text-foreground">Permohonan Terkini</h3>
            <span class="chip bg-muted text-muted-foreground">{{ $applications?->count() ?? 0 }} permohonan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-border text-[11px] font-bold uppercase tracking-widest text-muted-foreground">
                        <th class="px-5 py-3.5">Pemohon</th>
                        <th class="px-5 py-3.5">Butiran</th>
                        <th class="px-5 py-3.5">Tarikh</th>
                        <th class="px-5 py-3.5">Tujuan</th>
                        <th class="px-5 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($applications as $app)
                    <tr wire:key="{{ $app->id }}" class="transition-colors duration-150 hover:bg-muted/50">
                        <td class="px-5 py-3.5">
                            <p class="font-semibold text-foreground">{{ optional($app->user)->name ?? $app->guest_name }}</p>
                            <p class="text-xs text-muted-foreground">{{ $app->position }} / {{ $app->department }}</p>
                        </td>
                        <td class="max-w-[220px] truncate px-5 py-3.5 text-muted-foreground">{{ $app->description }}</td>
                        <td class="tnum px-5 py-3.5 text-muted-foreground">{{ $app->application_date }}</td>
                        <td class="max-w-[220px] truncate px-5 py-3.5 text-muted-foreground">{{ $app->reason }}</td>
                        <td class="px-5 py-3.5">
                            <span class="chip bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                                <i class="ph ph-hourglass text-sm leading-none"></i>
                                Dalam Proses
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-14 text-center">
                            <i class="ph ph-check-circle text-3xl leading-none text-muted-foreground/40"></i>
                            <p class="mt-3 text-sm text-muted-foreground">Tiada permohonan dalam proses</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
