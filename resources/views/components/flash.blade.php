@if (session()->has('message'))
    <div x-cloak x-data="{ open: true }"
        class="fixed inset-x-0 top-4 z-50 mx-auto w-fit max-w-sm px-4"
        role="alert"
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-init="() => { setTimeout(() => { open = false }, 3500); }">
        <div class="flex items-start gap-3 rounded-xl border border-border bg-card px-4 py-3 shadow-lg">
            <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary">
                <i class="ph ph-check text-sm leading-none"></i>
            </span>
            <p class="text-sm font-medium text-card-foreground">{{ session('message') }}</p>
            <button type="button" @click="open = false" class="ml-auto -mr-1 mt-0.5 text-muted-foreground transition hover:text-foreground" aria-label="Tutup">
                <i class="ph ph-x text-base leading-none"></i>
            </button>
        </div>
    </div>
@endif
