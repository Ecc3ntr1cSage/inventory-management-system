<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                <i class="ph ph-house text-xl leading-none"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold leading-tight tracking-tight text-foreground">
                    {{ __('Menu Utama') }}
                </h2>
                <p class="text-sm text-muted-foreground">Ringkasan stok dan aset alih</p>
            </div>
        </div>
    </x-slot>

    <div>
        <livewire:dashboard.analytic />
    </div>
</x-app-layout>
