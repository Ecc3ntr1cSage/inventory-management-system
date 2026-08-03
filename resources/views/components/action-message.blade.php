@props(['on'])

<div x-data="{ shown: false, timeout: null }"
     x-init="@this.on('{{ $on }}', () => { clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000); })"
     x-show.transition.out.opacity.duration.1500ms="shown"
     x-transition:leave.opacity.duration.1500ms
     style="display: none;"
    {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 text-sm font-medium text-primary']) }}>
    <i class="ph ph-check-circle text-base leading-none"></i>
    {{ $slot->isEmpty() ? 'Saved.' : $slot }}
</div>
