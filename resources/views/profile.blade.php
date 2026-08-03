<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                <i class="ph ph-user text-xl leading-none"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold leading-tight tracking-tight text-foreground">
                    {{ __('Profile') }}
                </h2>
                <p class="text-sm text-muted-foreground">Urus maklumat akaun dan keselamatan</p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl space-y-6">
        <div class="card-surface p-6 sm:p-8">
            <div class="max-w-xl">
                <livewire:profile.update-profile-information-form />
            </div>
        </div>

        @can('admin')
        <div class="card-surface p-6 sm:p-8">
            <div class="max-w-xl">
                <livewire:profile.register-user-form />
            </div>
        </div>

        <div class="card-surface p-6 sm:p-8">
            <div class="max-w-xl">
                <header>
                    <h2 class="text-lg font-bold tracking-tight text-foreground">
                        {{ __('Registered Users') }}
                    </h2>

                    <p class="mt-1 text-sm text-muted-foreground">
                        {{ __('View and manage registered accounts.') }}
                    </p>
                </header>

                <div class="mt-4">
                    <x-primary-button class="px-4" x-data="" x-on:click.prevent="$dispatch('open-modal', 'registered-users-list')">
                        <i class="ph ph-users text-base leading-none"></i>
                        {{ __('View Registered Users') }}
                    </x-primary-button>
                </div>
            </div>
        </div>

        <x-modal name="registered-users-list" maxWidth="2xl">
            <div class="p-6 sm:p-8">
                <livewire:profile.registered-user-list />
            </div>
        </x-modal>
        @endcan

        <div class="card-surface p-6 sm:p-8">
            <div class="max-w-xl">
                <livewire:profile.update-password-form />
            </div>
        </div>

        <div class="card-surface border-destructive/20 p-6 sm:p-8">
            <div class="max-w-xl">
                <livewire:profile.delete-user-form />
            </div>
        </div>
    </div>
</x-app-layout>
