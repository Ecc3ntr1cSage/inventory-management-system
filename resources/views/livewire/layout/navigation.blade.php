<?php

use App\Livewire\Actions\Logout;
use Livewire\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="lg:fixed lg:inset-y-0 lg:left-0 lg:z-40 lg:flex lg:w-64 lg:flex-col lg:border-r lg:border-sidebar-border lg:bg-sidebar">
    {{-- Mobile top bar --}}
    <div class="flex h-16 items-center justify-between border-b border-sidebar-border bg-sidebar px-4 lg:hidden">
        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2.5">
            <x-application-logo class="h-8 w-8 text-primary" />
            <span class="text-sm font-bold tracking-tight text-sidebar-foreground">{{ config('app.name', 'InvMS') }}</span>
        </a>
        <button type="button" @click="open = true" class="inline-flex h-10 w-10 cursor-pointer items-center justify-center rounded-lg text-sidebar-foreground/70 transition hover:bg-sidebar-accent hover:text-sidebar-accent-foreground" aria-label="Buka menu navigasi">
            <i class="ph ph-list text-xl leading-none"></i>
        </button>
    </div>

    {{-- Mobile drawer overlay --}}
    <div x-show="open" x-cloak x-transition.opacity class="fixed inset-0 z-50 bg-foreground/40 backdrop-blur-sm lg:hidden" @click="open = false"></div>

    {{-- Mobile drawer --}}
    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-sidebar-border bg-sidebar lg:hidden">
        <div class="flex h-16 items-center justify-between border-b border-sidebar-border px-4">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2.5">
                <x-application-logo class="h-8 w-8 text-primary" />
                <span class="text-sm font-bold tracking-tight text-sidebar-foreground">{{ config('app.name', 'InvMS') }}</span>
            </a>
            <button type="button" @click="open = false" class="inline-flex h-10 w-10 cursor-pointer items-center justify-center rounded-lg text-sidebar-foreground/70 transition hover:bg-sidebar-accent" aria-label="Tutup menu navigasi">
                <i class="ph ph-x text-xl leading-none"></i>
            </button>
        </div>

        <div class="flex-1 space-y-1 overflow-y-auto p-3">
            @include('livewire.layout.nav-items')
        </div>

        <div class="border-t border-sidebar-border p-3">
            @include('livewire.layout.nav-footer')
        </div>
    </div>

    {{-- Desktop sidebar --}}
    <div class="hidden flex-1 flex-col lg:flex">
        <div class="flex h-16 items-center gap-2.5 border-b border-sidebar-border px-5">
            <x-application-logo class="h-8 w-8 text-primary" />
            <span class="flex flex-col">
                <span class="text-sm font-bold leading-none tracking-tight text-sidebar-foreground">{{ config('app.name', 'InvMS') }}</span>
                <span class="mt-1 font-mono text-[10px] uppercase tracking-widest text-sidebar-foreground/45">Sistem Inventori &amp; Aset</span>
            </span>
        </div>

        <div class="flex-1 space-y-1 overflow-y-auto p-3">
            @include('livewire.layout.nav-items')
        </div>

        <div class="border-t border-sidebar-border p-3">
            @include('livewire.layout.nav-footer')
        </div>
    </div>
</nav>
