@php
    $capacity = $capacity ?? [];
    $tone = $capacity['statusTone'] ?? 'neutral';
    $barClass = match ($tone) {
        'full' => 'bg-red-500',
        'low' => 'bg-amber-500',
        'ok' => 'bg-blue-500',
        default => 'bg-slate-300',
    };
    $textClass = match ($tone) {
        'full' => 'text-red-700',
        'low' => 'text-amber-800',
        'ok' => 'text-blue-800',
        default => 'text-slate-600',
    };
@endphp

<div class="mt-2">
    <div class="flex items-center justify-between gap-2 text-xs">
        <span class="font-medium {{ $textClass }}">
            <i class="fas fa-users mr-1 opacity-70" aria-hidden="true"></i>
            {{ $capacity['statusLabel'] ?? '' }}
        </span>
        @if (! empty($capacity['isLimited']) && isset($capacity['percentFilled']))
            <span class="tabular-nums text-slate-500">{{ $capacity['percentFilled'] }}%</span>
        @endif
    </div>
    @if (! empty($capacity['isLimited']) && isset($capacity['percentFilled']))
        <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-slate-200">
            <div class="{{ $barClass }} h-full rounded-full transition-all" style="width: {{ $capacity['percentFilled'] }}%"></div>
        </div>
    @endif
</div>
