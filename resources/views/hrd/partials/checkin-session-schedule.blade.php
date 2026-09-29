{{--
    Session time + check-in window (full-width cards).
    Pass either $time (HrTime) or $sessionStart + $sessionEnd + $checkinFrom.
    Optional: $timeSubtitle, $class
--}}
@php
    if (isset($time) && $time) {
        $sessionStart = \Carbon\Carbon::parse($time->time_start)->format('H:i');
        $sessionEnd = \Carbon\Carbon::parse($time->time_end)->format('H:i');
        $checkinFromDisplay = $checkinFrom ?? \Carbon\Carbon::parse($time->time_start)->subMinutes(30)->format('H:i');
        $timeSubtitle = $timeSubtitle ?? ($time->time_title ?: null);
    } else {
        $sessionStart = $sessionStart ?? null;
        $sessionEnd = $sessionEnd ?? null;
        $checkinFromDisplay = $checkinFrom ?? $checkinFromText ?? null;
        $timeSubtitle = $timeSubtitle ?? null;
    }

    $sessionLabel = ($sessionStart && $sessionEnd)
        ? "{$sessionStart} – {$sessionEnd}"
        : ($timeRange ?? null);
@endphp

@if ($sessionLabel || $checkinFromDisplay)
    <div class="grid w-full grid-cols-1 gap-2 sm:grid-cols-2 {{ $class ?? '' }}">
        @if ($sessionLabel)
            <div class="flex min-h-[72px] w-full items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-700" aria-hidden="true">
                    <i class="fas fa-clock text-sm"></i>
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">เวลาจัด</p>
                    <p class="text-2xl font-extrabold leading-tight tracking-tight text-slate-900 sm:text-3xl">{{ $sessionLabel }}</p>
                    @if ($timeSubtitle)
                        <p class="mt-0.5 truncate text-xs font-medium text-slate-600">{{ $timeSubtitle }}</p>
                    @endif
                </div>
            </div>
        @endif
        @if ($checkinFromDisplay)
            <div class="flex min-h-[72px] w-full items-center gap-3 rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white" aria-hidden="true">
                    <i class="fas fa-sign-in-alt text-sm"></i>
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] font-semibold uppercase tracking-wide text-blue-800">เปิดเช็คอิน</p>
                    <p class="text-2xl font-extrabold leading-tight tracking-tight text-blue-950 sm:text-3xl">{{ $checkinFromDisplay }}</p>
                    <p class="mt-0.5 text-xs font-medium text-blue-800/90">เริ่ม 30 นาทีก่อนเวลาจัด</p>
                </div>
            </div>
        @endif
    </div>
@endif
