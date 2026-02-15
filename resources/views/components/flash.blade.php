@if (session()->has('message'))
    <div x-cloak
        class="fixed z-50 inset-x-0 top-2 w-fit mx-auto p-[3px] text-white transition rounded-md bg-gradient-to-tr from-orange-600 via-rose-600 to-orange-600"
        role="alert" x-data="{ open: true }" x-bind:class="open ? '' : 'opacity-0'" x-init="() => { setTimeout(() => { open = false }, 3000); }"
        x-delay="1000">
        <div class="p-4 bg-muted rounded-sm">
            <span class="inline-block mr-5 text-xl align-middle">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 text-muted-foreground">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.640 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
            </span>
            <span class="inline-block mr-8 text-foreground align-middle">
                {{ session('message') }}
            </span>
        </div>
    </div>
@endif
