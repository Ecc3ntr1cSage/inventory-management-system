@props(['value'])

<label {{ $attributes->merge(['class' => 'mb-1.5 block text-sm font-medium text-foreground']) }}>
    {{ $value ?? $slot }}
</label>
