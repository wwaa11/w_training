@php
    $sectionNumber = $sectionNumber ?? null;
    $collapsible = $collapsible ?? false;
    $defaultOpen = $defaultOpen ?? true;
@endphp

@if ($collapsible)
    <details
        class="hrd-af-section hrd-af-section--collapsible js-ov-page-section hrd-card overflow-hidden"
        @if (! empty($sectionId)) id="{{ $sectionId }}" @endif
        @if ($defaultOpen) open @endif
    >
        <summary class="flex cursor-pointer list-none items-start gap-3 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white px-4 py-3 [&::-webkit-details-marker]:hidden">
            @if ($sectionNumber)
                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-blue-600 text-xs font-bold text-white">{{ $sectionNumber }}</span>
            @endif
            <div class="min-w-0 flex-1 text-left">
                <h2 class="text-sm font-semibold text-slate-900">{{ $title }}</h2>
                @if (! empty($description))
                    <p class="mt-0.5 text-xs text-slate-600">{{ $description }}</p>
                @endif
            </div>
            @if (! empty($headerEnd))
                <div class="shrink-0" onclick="event.preventDefault(); event.stopPropagation();">{!! $headerEnd !!}</div>
            @endif
            <i class="js-section-chevron fas fa-chevron-down mt-1 shrink-0 text-xs text-blue-600 transition-transform"></i>
        </summary>
        <div class="p-4">
            {{ $slot }}
        </div>
    </details>
@else
    <section class="hrd-af-section hrd-card overflow-hidden" @if (! empty($sectionId)) id="{{ $sectionId }}" @endif>
        <div class="flex items-start gap-3 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white px-4 py-3">
            @if ($sectionNumber)
                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-blue-600 text-xs font-bold text-white">{{ $sectionNumber }}</span>
            @endif
            <div class="min-w-0 flex-1">
                <h2 class="text-sm font-semibold text-slate-900">{{ $title }}</h2>
                @if (! empty($description))
                    <p class="mt-0.5 text-xs text-slate-600">{{ $description }}</p>
                @endif
            </div>
            @if (! empty($headerEnd))
                <div class="shrink-0">{!! $headerEnd !!}</div>
            @endif
        </div>
        <div class="p-4">
            {{ $slot }}
        </div>
    </section>
@endif
