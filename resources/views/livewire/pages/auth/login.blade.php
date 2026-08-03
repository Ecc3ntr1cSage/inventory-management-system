<?php

use App\Livewire\Forms\LoginForm;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    public function loginAsDemo(string $role): void
    {
        $account = User::DEMO_ACCOUNTS[$role] ?? null;

        if (! $account) {
            throw ValidationException::withMessages([
                'form.email' => 'Akaun demo tidak sah.',
            ]);
        }

        $this->form->email = $account['email'];
        $this->form->password = User::DEMO_PASSWORD;

        $this->login();
    }
}; ?>

<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold tracking-tight text-foreground">Log Masuk</h1>
        <p class="mt-1 text-sm text-muted-foreground">Sila masukkan kelayakan anda untuk meneruskan.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <section aria-labelledby="demo-accounts-heading" class="mb-6">
        <div class="flex items-center justify-between">
            <h2 id="demo-accounts-heading" class="text-xs font-bold uppercase tracking-widest text-muted-foreground">
                Akses demo
            </h2>
            <span class="text-xs text-muted-foreground/70">Klik untuk masuk</span>
        </div>

        <div class="mt-3 grid gap-2 sm:grid-cols-3">
            @foreach (User::DEMO_ACCOUNTS as $role => $account)
                <button type="button" wire:click="loginAsDemo('{{ $role }}')" wire:loading.attr="disabled"
                    wire:target="loginAsDemo" aria-label="Log masuk sebagai {{ $account['name'] }}"
                    class="group rounded-lg border border-border bg-muted/30 p-3 text-left transition hover:border-primary/50 hover:bg-primary/5 focus-visible:border-ring active:scale-[0.98] disabled:cursor-wait disabled:opacity-60">
                    <span class="flex items-center justify-between gap-2">
                        <span class="text-sm font-semibold text-foreground">
                            {{ match ($role) {
                                User::ROLE_ADMIN => 'Pentadbir',
                                User::ROLE_STAFF => 'Staf',
                                default => 'Pengguna',
                            } }}
                        </span>
                        <i class="ph {{ match ($role) {
                            User::ROLE_ADMIN => 'ph-shield-check',
                            User::ROLE_STAFF => 'ph-warehouse',
                            default => 'ph-user',
                        } }} text-base text-primary transition group-hover:translate-x-0.5"></i>
                    </span>
                    <span class="mt-1 block truncate text-xs text-muted-foreground">{{ $account['name'] }}</span>
                    <span class="mt-2 block truncate font-mono text-[10px] text-muted-foreground/70">{{ $account['email'] }}</span>
                </button>
            @endforeach
        </div>
    </section>

    <form wire:submit="login" class="space-y-5">
        @csrf
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="form.email" id="email" class="mt-1 block w-full" type="text" name="email"
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input wire:model="form.password" id="password" class="mt-1 block w-full" type="password"
                name="password" autocomplete="current-password" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        {{-- <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox"
                    class="text-ring border-border rounded shadow-sm focus:ring-ring" name="remember">
                <span class="text-muted-foreground ms-2">{{ __('Remember me') }}</span>
            </label>
        </div> --}}

        <div class="flex items-center justify-end pt-1">
            @if (Route::has('password.request'))
            {{-- <a
                class="text-muted-foreground underline rounded-md hover:text-foreground focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-ring"
                href="{{ route('password.request') }}" wire:navigate>
                {{ __('Forgot your password?') }}
            </a>--}}
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <div class="border-t border-border pt-4">
            <a href="{{ url('guest/request') }}" wire:navigate
                class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-primary/30 px-4 py-2.5 text-sm font-semibold text-primary shadow-sm transition hover:bg-primary/5">
                <i class="ph ph-clipboard-text text-base leading-none"></i>
                {{ __('Buat Permohonan') }}
            </a>
        </div>
    </form>
</div>
