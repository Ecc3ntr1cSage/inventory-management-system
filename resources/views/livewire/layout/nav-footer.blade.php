<div>
    {{-- User menu --}}
    @auth
    <div class="relative w-full" x-data="{ open: false }" @click.outside="open = false">
        <button type="button" @click="open = !open"
            class="flex w-full cursor-pointer items-center gap-2 rounded-lg px-1.5 py-1.5 text-start transition hover:bg-sidebar-accent">
            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-primary/15 text-[11px] font-bold text-primary">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </span>
            <span class="min-w-0 flex-1">
                <span class="block truncate text-xs font-medium text-sidebar-foreground" x-data="{}" x-text="$el.textContent">{{ auth()->user()->name }}</span>
                <span class="block text-[10px] text-sidebar-foreground/50">{{ ucfirst(auth()->user()->role) }}</span>
            </span>
            <i class="ph ph-caret-down text-sm leading-none text-sidebar-foreground/50" :class="open && 'rotate-180'"></i>
        </button>

        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="absolute bottom-full left-0 right-0 z-50 mb-2 overflow-hidden rounded-xl border border-border bg-popover shadow-lg">
            <a href="{{ route('profile') }}" wire:navigate
                class="flex items-center gap-2.5 px-3 py-2 text-sm text-popover-foreground transition hover:bg-accent">
                <i class="ph ph-user text-base leading-none"></i>
                Profil
            </a>
            <button wire:click="logout" type="button"
                class="flex w-full items-center gap-2.5 px-3 py-2.5 text-start text-sm text-destructive transition hover:bg-destructive/10">
                <i class="ph ph-sign-out text-base leading-none"></i>
                Log Keluar
            </button>
        </div>
    </div>
    @endauth
</div>
