@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center gap-2.5 rounded-lg bg-primary px-3 py-2 text-sm font-medium text-primary-foreground shadow-sm transition duration-150'
            : 'inline-flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-medium text-sidebar-foreground/70 transition duration-150 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
