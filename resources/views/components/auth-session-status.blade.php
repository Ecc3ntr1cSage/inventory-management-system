@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'flex items-start gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-800/40 dark:bg-emerald-900/20 dark:text-emerald-300']) }}>
        <i class="ph ph-check-circle mt-0.5 text-base leading-none"></i>
        <span>{{ $status }}</span>
    </div>
@endif
