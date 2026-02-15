<div class="grid grid-cols-1 gap-6 p-2 text-foreground md:grid-cols-2 md:p-0">
    <div class="p-6 overflow-y-scroll bg-card rounded-md shadow-md max-h-96">
        <div class="flex items-center justify-between text-base font-medium tracking-wide">
            <p>Stok</p>
            <p class="px-2 bg-muted rounded-md">{{ $stocks->sum('balance') }}</p>
        </div>
        <hr class="w-full h-0.5 mx-auto my-2 bg-primary/20 rounded">
        @foreach($stocks as $stock)
        <div class="flex items-center justify-between mt-2">
            <p class="font-medium tracking-wide">{{ $stock->name }}:</p>
            <p class="px-2 text-center bg-muted rounded-md">{{ $stock->balance }}</p>
        </div>
        @endforeach
    </div>
    <div class="p-6 overflow-y-scroll bg-card rounded-md shadow-md max-h-96">
        <div class="flex items-center justify-between text-base font-medium tracking-wide">
            <p>Aset Alih</p>
            <p>Status</p>
        </div>
        <hr class="w-full h-0.5 mx-auto my-2 bg-primary/20 rounded">
        @foreach($assets as $asset)
        <div class="flex items-center justify-between mt-2">
            <p class="font-medium tracking-wide">{{ $asset->name }}</p>
            <div class="flex items-center space-x-2">
                <p class="p-1 rounded-full @if($asset->is_available) bg-green-500 @else bg-red-500 @endif"></p>
                <p class="">({{ $asset->applications_count }})</p>
            </div>
        </div>
        @endforeach
    </div>
    <div class="w-full p-6 mx-auto bg-card rounded-md shadow-md md:col-span-2 md:w-96" x-data="{ open: false }">
        <x-dropdown align="left" width="40">
            <x-slot name="trigger">
                <button type="button"
                    class="flex items-center gap-4 px-4 py-1 transition border-2 rounded-full border-border hover:border-primary focus:ring-2 focus:border-ring focus:ring-ring">
                    Filter by - {{ $days == -1 ? 'All Time' : 'Last ' . $days . ' Days' }}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
            </x-slot>
            <x-slot name="content">
                <button wire:click="sort(7)" class="w-full text-start">
                    <x-dropdown-link>
                        Last week
                    </x-dropdown-link>
                </button>
                <button wire:click="sort(30)" class="w-full text-start">
                    <x-dropdown-link>
                        Last month
                    </x-dropdown-link>
                </button>
                <button wire:click="sort(365)" class="w-full text-start">
                    <x-dropdown-link>
                        Last year
                    </x-dropdown-link>
                </button>
                <button wire:click="sort(-1)" class="w-full text-start">
                    <x-dropdown-link>
                        All Time
                    </x-dropdown-link>
                </button>
            </x-slot>
        </x-dropdown>
        <div class="flex flex-col gap-3 mt-4">
            <p class="font-medium tracking-wide">Stok Terima:<span class="float-right px-2 bg-muted rounded-md">{{
                    $stock_received }}</span></p>
            <p class="font-medium tracking-wide">Stok Keluar:<span class="float-right px-2 bg-muted rounded-md">{{
                    $stock_issued }}</span></p>
            <p class="font-medium tracking-wide">Jumlah Permohonan Aset:<span
                    class="float-right px-2 bg-muted rounded-md">{{ $application_count }}</span></p>
        </div>
    </div>
</div>
