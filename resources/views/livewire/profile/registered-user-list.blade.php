<?php

use Livewire\Volt\Component;
use App\Models\User;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

new class extends Component
{
    use WithPagination, WithoutUrlPagination;

    public string $password = '';

    public function with(): array
    {
        return [
            'users' => User::orderBy('role')->paginate(10),
        ];
    }

    public function deleteUser($id)
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        User::destroy($id);

        Session::flash('message', 'User Deleted');
        $this->redirectIntended(default: route('profile', absolute: false), navigate: true);
    }

}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-foreground">
            {{ __('Registered Users') }}
        </h2>

        <p class="mt-1 text-muted-foreground">
            {{ __('Exclusively for administrator to view and manage registered accounts.') }}
        </p>
    </header>
    <div class="overflow-x-auto">
        <table class="w-full my-4 text-xs text-left text-foreground rounded-lg table-auto">
            <thead class="text-xs font-medium uppercase border-b-2 border-border bg-muted">
                <tr>
                    <th class="px-2 py-2 tracking-wide ">
                        Name
                    </th>
                    <th class="px-2 py-2 tracking-wide">
                        Email
                    </th>
                    <th class="px-2 py-2 tracking-wide text-center">
                        Role
                    </th>
                    <th rowspan="2" class="px-2 py-2 tracking-wide">
                        Date joined
                    </th>
                    <th rowspan="2" class="px-2 py-2 tracking-wide">

                    </th>
                </tr>
            </thead>
            <tbody class="bg-card">
                @foreach($users as $user)
                <tr wire:key="{{ $user->id }}" class="transition hover:bg-accent">
                    <td class="px-2 py-2">
                        {{ $user->name }}
                    </td>
                    <td class="px-2 py-2 truncate">
                        {{ $user->email }}
                    </td>
                    <td class="px-2 py-2 text-center">
                        {{ $user->role }}
                    </td>
                    <td class="px-2 py-2">
                        {{ $user->created_at }}
                    </td>
                    <td class="px-2 py-2">
                        @if(Auth::id() == $user->id)
                        @else
                        <button type="button"
                            x-on:click.prevent="$dispatch('open-modal', 'delete-user-confirmation-{{ $user->id }}')"
                            class="p-1 rounded-full hover:bg-destructive/50">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </button>
                        @endif
                        <x-modal name="delete-user-confirmation-{{ $user->id }}" :show="$errors->isNotEmpty()" focusable
                            maxWidth="md">
                            <form wire:submit="deleteUser({{ $user->id }})" class="p-6">
                                @csrf
                                <h2 class="text-lg font-medium text-foreground">
                                    {{ __('Delete User COnfirmation?') }}
                                </h2>

                                <p class="mt-1 text-muted-foreground">
                                    {{ __('Please enter your password to confirm administrator permission.') }}
                                </p>

                                <div class="mt-6">

                                    <x-text-input wire:model="password" id="password" name="password" type="password"
                                        class="block w-full mt-1" placeholder="{{ __('Password') }}" />

                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>

                                <div class="flex justify-end mt-6">
                                    <x-secondary-button x-on:click="$dispatch('close')">
                                        {{ __('Cancel') }}
                                    </x-secondary-button>

                                    <x-danger-button class="ms-3">
                                        {{ __('Delete Account') }}
                                    </x-danger-button>
                                </div>
                            </form>
                        </x-modal>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $users->links(data:['scrollTo' => false]) }}
    </div>

</section>
