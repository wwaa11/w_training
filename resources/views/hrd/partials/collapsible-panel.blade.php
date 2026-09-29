@php
    $open = $open ?? false;
    $panelId = $panelId ?? 'panel-' . uniqid();
@endphp

<details
    id="{{ $panelId }}"
    @class([
        'group hrd-panel',
        $class ?? '',
    ])
    @if ($open) open @endif
>
    <summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-4 py-3.5 text-left marker:content-none sm:px-5 [&::-webkit-details-marker]:hidden">
        <div class="flex min-w-0 flex-1 items-center gap-3">
            @if (! empty($icon))
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
                    <i class="{{ $icon }} text-sm" aria-hidden="true"></i>
                </span>
            @endif
            <div class="min-w-0">
                <p class="font-semibold text-slate-900">{{ $title }}</p>
                @if (! empty($subtitle))
                    <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
        <i class="fas fa-chevron-down shrink-0 text-sm text-blue-600 transition group-open:rotate-180" aria-hidden="true"></i>
    </summary>
    <div class="border-t border-slate-100 px-4 py-4 sm:px-5 sm:py-5">
        {{ $slot }}
    </div>
</details>
