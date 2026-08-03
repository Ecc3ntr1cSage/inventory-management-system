@php
$items = [];

if (auth()->user() && auth()->user()->role !== 'user') {
    $items[] = [
        'label' => 'Menu Utama',
        'icon' => 'ph-house',
        'href' => route('dashboard'),
        'active' => request()->routeIs('dashboard'),
    ];
    $items[] = [
        'label' => 'Inventori',
        'icon' => 'ph-boxes',
        'href' => route('inventory.entry'),
        'active' => request()->routeIs('inventory.entry', 'inventory.listing', 'inventory.record'),
    ];
    $items[] = [
        'label' => 'Aset Alih',
        'icon' => 'ph-arrows-left-right',
        'href' => route('asset.submission'),
        'active' => request()->routeIs('asset.submission', 'asset.listing', 'asset.record'),
    ];
}

if (auth()->user() && auth()->user()->role === 'user') {
    $items[] = [
        'label' => 'Borang Permohonan',
        'icon' => 'ph-clipboard-text',
        'href' => route('asset.request'),
        'active' => request()->routeIs('asset.request'),
    ];
}
@endphp

@foreach ($items as $item)
<a href="{{ $item['href'] }}" wire:navigate
    @class([
        'group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150',
        'bg-primary text-primary-foreground shadow-sm' => $item['active'],
        'text-sidebar-foreground/70 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground' => ! $item['active'],
    ])>
    <i class="ph {{ $item['icon'] }} text-lg leading-none"></i>
    <span>{{ $item['label'] }}</span>
</a>
@endforeach
