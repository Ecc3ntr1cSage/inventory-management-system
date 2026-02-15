<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center bg-primary
    border border-transparent rounded-md font-semibold text-xs text-primary-foreground uppercase tracking-widest hover:bg-primary/90
    focus:bg-primary/90 active:bg-primary/95 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2
    transition ease-in-out duration-150', 'wire:loading.attr' => 'disabled']) }}>
    <span wire:loading.remove @if (isset($target)) wire:target="{{ $target }}" @endif>
        {{ $slot }}
    </span>
    <span wire:loading @if (isset($target)) wire:target="{{ $target }}" @endif>
        <svg class="w-6 h-6 text-primary-foreground animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2">
            </circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0a12 12 0 00-12 12h2z"></path>
        </svg>
    </span>
</button>
