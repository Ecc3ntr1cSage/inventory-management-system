<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <div class="mb-6">
        <span class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-primary/10 text-primary">
            <i class="ph ph-envelope-simple text-2xl leading-none"></i>
        </span>
        <h1 class="text-2xl font-bold tracking-tight text-foreground">Sahkan Emel Anda</h1>
        <p class="mt-1 text-sm text-muted-foreground">
            Terima kasih kerana mendaftar! Sebelum bermula, sahkan emel anda dengan klik pautan yang kami hantar. Tidak terima emel? Kami akan hantar semula.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 flex items-start gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-800/40 dark:bg-emerald-900/20 dark:text-emerald-300">
            <i class="ph ph-check-circle mt-0.5 text-base leading-none"></i>
            <span>{{ __('A new verification link has been sent to the email address you provided during registration.') }}</span>
        </div>
    @endif

    <div class="flex items-center justify-between gap-3 pt-1">
        <x-primary-button class="h-10" wire:click="sendVerification">
            {{ __('Resend Verification Email') }}
        </x-primary-button>

        <button wire:click="logout" type="submit"
            class="inline-flex items-center gap-2 text-sm font-medium text-muted-foreground underline-offset-4 transition hover:text-foreground hover:underline focus:outline-none">
            <i class="ph ph-sign-out text-base leading-none"></i>
            {{ __('Log Out') }}
        </button>
    </div>
</div>
