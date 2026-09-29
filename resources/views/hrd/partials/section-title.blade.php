<div class="mb-4 flex items-center gap-3">
    @if (! empty($icon))
        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-600 text-white">
            <i class="{{ $icon }} text-sm" aria-hidden="true"></i>
        </span>
    @endif
    <div>
        @if (! empty($eyebrow))
            <p class="text-[11px] font-semibold uppercase tracking-widest text-blue-600">{{ $eyebrow }}</p>
        @endif
        <h2 class="text-lg font-bold tracking-tight text-slate-900 sm:text-xl">{{ $title }}</h2>
        @if (! empty($subtitle))
            <p class="mt-0.5 text-sm text-slate-600">{{ $subtitle }}</p>
        @endif
    </div>
</div>
