<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-success/10 text-success">
                <i class="ph ph-house text-xl leading-none"></i>
            </div>
            <div>
                <p class="eyebrow mb-1">Pusat operasi</p>
                <h2 class="page-title">
                    {{ __('Menu Utama') }}
                </h2>
                <p class="mt-1 text-sm text-muted-foreground">Ringkasan stok dan aset alih semasa</p>
            </div>
        </div>
    </x-slot>

    <div>
        <livewire:dashboard.analytic />
    </div>
</x-app-layout>
