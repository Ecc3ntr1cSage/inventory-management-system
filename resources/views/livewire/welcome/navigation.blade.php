<nav class="-mx-3 flex flex-1 justify-end">
    @auth
        <a
            href="{{ url('/dashboard') }}"
            class="rounded-md px-3 py-2 text-foreground ring-1 ring-transparent transition hover:text-foreground/70 focus:outline-none focus-visible:ring-ring"
        >
            Dashboard
        </a>
    @else
        <a
            href="{{ route('login') }}"
            class="rounded-md px-3 py-2 text-foreground ring-1 ring-transparent transition hover:text-foreground/70 focus:outline-none focus-visible:ring-ring"
        >
            Log in
        </a>

    @endauth
</nav>
