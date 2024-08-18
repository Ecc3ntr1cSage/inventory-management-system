<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ __('Permohonan Peralatan IT') }}
    </h2>
</x-slot>
<div>
    @if($requestPending || $requestApproved)
    <div class="max-w-5xl p-2 mx-auto mt-4 md:p-0">
        <div class="px-2 pb-16">
            @if($requestApproved)
            <div class="w-full p-3 my-2 rounded-md bg-green-400/50">
                Permohonan anda telah diluluskan, sila berkunjung ke Unit IT untuk mengambil peralatan.
            </div>
            @endif
            <table class="w-full mb-4 text-xs text-left text-gray-800 border-separate table-auto border-spacing-y-2">
                <thead class="text-xs font-medium uppercase bg-gray-300">
                    <tr class="tracking-wide">
                        <th class="px-3 py-4 rounded-l-lg">
                            Tarikh Mohon
                        </th>
                        <th class="px-3 py-4">
                            Butiran Permohonan
                        </th>
                        <th class="px-3 py-4">
                            Butiran Aset
                        </th>
                        <th class="px-3 py-4">
                            Tujuan
                        </th>
                        <th class="px-3 py-4">
                            Lokasi Digunakan
                        </th>
                        <th class="px-3 py-4 rounded-r-lg">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $application)
                    <tr wire:key="{{ $application->id }}"
                        class="transition bg-gray-200 rounded-lg hover:ring-2 hover:ring-indigo-500 ring-offset-2">
                        <td class="p-3 rounded-l-lg">
                            {{ $application->application_date }}
                        </td>
                        <td class="p-3">
                            {{ $application->description }}
                        </td>
                        <td class="p-3">
                            @if(isset($application->asset->name))
                            {{ $application->asset->name }}
                            @endif
                        </td>
                        <td class="p-3">
                            {{ $application->reason }}
                        </td>
                        <td class="p-3">
                            {{ $application->location }}
                        </td>
                        <td class="p-3 text-center rounded-r-lg">
                            @if($application->status == 0)
                            <p class="p-1 rounded-md bg-orange-400/50">Pending</p>
                            @elseif($application->status == 1)
                            <p class="p-1 rounded-md bg-green-400/50">Approved</p>
                            @elseif($application->status == 3)
                            <p class="p-1 rounded-md bg-indigo-400/50">Completed</p>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $applications->links() }}
        </div>
    </div>
    @else
    <div class="max-w-xl p-2 mx-auto mt-4 md:p-0">
        <p class="text-lg font-medium tracking-wide text-center uppercase">Borang permohonan pergerakan/pinjaman aset
            alih
        </p>
        <form wire:submit.prevent="application" class="my-6 space-y-4" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-input-label for="name" :value="__('Nama Pemohon')" />
                    <x-text-input wire:model="name" id="name" class="block w-full mt-1" type="text" disabled />
                </div>
                <div>
                    <x-input-label for="description" :value="__('Butir Permohonan *')" />
                    <x-text-input wire:model="description" id="description" class="block w-full mt-1" type="text"
                        autofocus placeholder="Laptop + external hard disk" />
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="position" :value="__('Jawatan *')" />
                    <x-text-input wire:model="position" id="position" class="block w-full mt-1" type="text" />
                    <x-input-error :messages="$errors->get('position')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="department" :value="__('Bahagian *')" />
                    <x-text-input wire:model="department" id="department" class="block w-full mt-1" type="text" />
                    <x-input-error :messages="$errors->get('department')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="reason" :value="__('Tujuan *')" />
                    <x-text-input wire:model="reason" id="reason" class="block w-full mt-1" type="text" />
                    <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="location" :value="__('Tempat Digunakan *')" />
                    <x-text-input wire:model="location" id="location" class="block w-full mt-1" type="text" />
                    <x-input-error :messages="$errors->get('location')" class="mt-2" />
                </div>
            </div>
            <div>
                <x-primary-button class="w-full h-10" target="application">{{ __('Hantar') }}</x-primary-button>
            </div>
        </form>
    </div>
    @endif
</div>
