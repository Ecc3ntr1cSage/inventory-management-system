<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
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
}; ?>

<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold tracking-tight text-foreground">Log Masuk</h1>
        <p class="mt-1 text-sm text-muted-foreground">Sila masukkan kelayakan anda untuk meneruskan.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

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

        <div class="flex items-center justify-between pt-1">
            @if (Route::has('password.request'))
            {{-- <a
                class="text-muted-foreground underline rounded-md hover:text-foreground focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-ring"
                href="{{ route('password.request') }}" wire:navigate>
                {{ __('Forgot your password?') }}
            </a>--}}
            @endif

            <a class="text-sm font-medium text-primary underline-offset-4 transition hover:underline"
                href="{{ route('register') }}" wire:navigate>
                {{ __('Daftar Akaun') }}
            </a>

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
