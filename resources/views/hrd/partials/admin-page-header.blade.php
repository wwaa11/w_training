{{-- $backUrl, $title, $subtitle (optional), $headerActions (optional HTML) --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex items-start gap-4">
        @if (! empty($backUrl))
            <a class="hrd-back-btn" href="{{ $backUrl }}" aria-label="กลับ">
                <i class="fas fa-arrow-left"></i>
            </a>
        @endif
        <div class="min-w-0">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">{{ $title }}</h1>
            @if (! empty($subtitle))
                <p class="mt-1 text-slate-600">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    @if (! empty($headerActions))
        <div class="flex flex-wrap items-center gap-2">{!! $headerActions !!}</div>
    @endif
</div>
