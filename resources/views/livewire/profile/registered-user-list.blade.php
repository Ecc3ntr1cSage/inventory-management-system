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
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-bold tracking-tight text-foreground">Senarai Pengguna Berdaftar</h3>
        <span class="chip bg-muted text-muted-foreground">{{ $users->total() }} pengguna</span>
    </div>
    <div class="overflow-x-auto">
        <table class="mt-4 w-full text-left text-sm">
            <thead class="border-b border-border text-[11px] font-bold uppercase tracking-widest text-muted-foreground">
                <tr>
                    <th class="px-3 py-3 tracking-wide">Name</th>
                    <th class="px-3 py-3 tracking-wide">Email</th>
                    <th class="px-3 py-3 text-center tracking-wide">Role</th>
                    <th class="px-3 py-3 tracking-wide">Date joined</th>
                    <th class="px-3 py-3 tracking-wide"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @foreach($users as $user)
                <tr wire:key="{{ $user->id }}" class="transition hover:bg-muted/50">
                    <td class="px-3 py-3 font-semibold text-foreground">
                        {{ $user->name }}
                    </td>
                    <td class="truncate px-3 py-3 text-muted-foreground">
                        {{ $user->email }}
                    </td>
                    <td class="px-3 py-3 text-center">
                        <span class="chip bg-muted text-muted-foreground">{{ $user->role }}</span>
                    </td>
                    <td class="px-3 py-3 text-muted-foreground">
                        {{ $user->created_at }}
                    </td>
                    <td class="px-2 py-2">
                        @if(Auth::id() == $user->id)
                        @else
                        <button type="button"
                            x-on:click.prevent="$dispatch('open-modal', 'delete-user-confirmation-{{ $user->id }}')"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-muted-foreground transition hover:bg-destructive/10 hover:text-destructive">
                            <i class="ph ph-trash text-base leading-none"></i>
                        </button>
                        @endif
                        <x-modal name="delete-user-confirmation-{{ $user->id }}" :show="$errors->isNotEmpty()" focusable
                            maxWidth="md">
                            <form wire:submit="deleteUser({{ $user->id }})" class="p-6">
                                @csrf
                                <h2 class="text-lg font-bold tracking-tight text-foreground">
                                    {{ __('Delete User Confirmation?') }}
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
