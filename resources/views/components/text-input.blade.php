@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-border focus:border-ring focus:ring-ring rounded-md shadow-sm placeholder:text-sm']) !!}>
