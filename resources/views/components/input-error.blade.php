@props(['messages'])

@if ($messages)
    <ul role="alert" {{ $attributes->merge(['class' => 'mt-2 space-y-1 text-xs font-medium text-destructive']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-start gap-1.5">
                <i class="ph ph-warning-circle mt-0.5 text-sm leading-none"></i>
                <span>{{ $message }}</span>
            </li>
        @endforeach
    </ul>
@endif
