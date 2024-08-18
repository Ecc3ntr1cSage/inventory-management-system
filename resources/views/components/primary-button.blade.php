<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center bg-gray-800
    border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
    focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
    transition ease-in-out duration-150', 'wire:loading.attr' => 'disabled']) }}>
    <span wire:loading.remove @if (isset($target)) wire:target="{{ $target }}" @endif>
        {{ $slot }}
    </span>
    <span wire:loading @if (isset($target)) wire:target="{{ $target }}" @endif>
        <svg class="w-6 h-6 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2">
            </circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0a12 12 0 00-12 12h2z"></path>
        </svg>
    </span>
</button>
