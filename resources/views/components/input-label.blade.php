@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-foreground']) }}>
    {{ $value ?? $slot }}
</label>
