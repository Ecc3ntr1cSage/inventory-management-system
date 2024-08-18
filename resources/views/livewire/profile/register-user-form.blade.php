<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new class extends Component
{
    public $name = '';
    public $email = '';
    public $role = '';
    public $password = '';
    public $password_confirmation = '';
    public $adminPassword = '';
    public $tempValidated = [];
    /**
     * Update the profile information for the currently authenticated user.
     */

     public function validateForm()
     {
        $this->tempValidated = $this->validate([
            'name' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:admin,staff'],
        ]);

        $this->dispatch('open-modal', 'register-user-confirmation');
     }

     public function register()
    {
        $this->validate([
            'adminPassword' => ['required', 'string', 'current_password'],
        ]);

        $validated = $this->tempValidated;

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
        ]);

        event(new Registered($user));

        Session::flash('message', 'New Account Created');
        $this->redirectIntended(default: route('profile', absolute: false), navigate: true);
    }

}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Register New User') }}
        </h2>
        <p class="mt-1 text-gray-600">
            {{ __('Exclusively for administrator to register new account.') }}
        </p>
    </header>
    <form wire:submit.prevent="register" class="mt-6 space-y-6">
        @csrf
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" class="block w-full mt-1" type="text" autofocus
                autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block w-full mt-1" type="text" autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="role" :value="__('Role')" />
            <select wire:model="role" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="" selected disabled>Select role</option>
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input wire:model="password" id="password" class="block w-full mt-1" type="password"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block w-full mt-1"
                type="password" name="password_confirmation" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        <div class="flex items-center gap-4">
            <x-primary-button type="button" wire:click="validateForm" target="validateForm" class="w-40 h-8">{{ __('Create Account') }}</x-primary-button>
        </div>
        <x-modal name="register-user-confirmation" :show="$errors->isNotEmpty()" focusable
            maxWidth="md">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Register User Confirmation?') }}
                </h2>
                <p class="mt-1 text-gray-600">
                    {{ __('Please enter your password to confirm administrator permission.') }}
                </p>
                <div class="mt-6">
                    <x-text-input wire:model="adminPassword" id="adminPassword" type="password"
                        class="block w-full mt-1" placeholder="{{ __('Password') }}" />
                    <x-input-error :messages="$errors->get('adminPassword')" class="mt-2" />
                </div>
                <div class="flex justify-end mt-6">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>
                    <x-primary-button target="register" class="w-24 ms-3">
                        {{ __('Confirm') }}
                    </x-primary-button>
                </div>
            </div>
        </x-modal>
    </form>
</section>
