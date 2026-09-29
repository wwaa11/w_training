@php
    $layout = $layout ?? 'inline';
    $hasAttended = (bool) ($hasAttended ?? true);
    $seatFeature = $showSeatFeature ?? ($project->project_seat_assign ?? false);
    $groupFeature = $showGroupFeature ?? ($project->project_group_assign ?? false);

    $seatLabel = null;
    if (isset($userSeat) && $userSeat) {
        $seatLabel = $userSeat->seat_number;
    } elseif (! empty($seatNumber)) {
        $seatLabel = $seatNumber;
    }

    $groupLabel = (isset($userGroup) && $userGroup) ? $userGroup->group : null;

    $showSeatValue = $seatFeature && $hasAttended;
    $showGroupValue = $groupFeature && $hasAttended;
    $showPending = ($seatFeature || $groupFeature) && ! $hasAttended;

    $pendingLabel = match (true) {
        $seatFeature && $groupFeature => 'กลุ่มและที่นั่ง',
        $groupFeature => 'กลุ่ม',
        default => 'ที่นั่ง',
    };

    $gridClass = $layout === 'full'
        ? 'grid w-full grid-cols-1 gap-3' . (($seatFeature && $groupFeature) ? ' sm:grid-cols-2' : '')
        : 'flex flex-wrap gap-2';
    $cardClass = $layout === 'full'
        ? 'flex w-full min-h-[72px] items-center gap-3 rounded-2xl border px-4 py-3'
        : 'inline-flex min-h-[40px] items-center gap-2 rounded-xl border px-3 py-2';
    $iconBoxClass = $layout === 'full' ? 'h-11 w-11 rounded-xl' : 'h-8 w-8 rounded-lg';
    $valueTextClass = $layout === 'full' ? 'text-2xl font-extrabold leading-tight sm:text-3xl' : 'text-lg font-extrabold leading-tight';
@endphp

@if ($showPending)
    <div class="w-full rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3.5 text-sm text-slate-700 {{ $class ?? '' }}" role="status">
        <p class="flex items-start gap-2">
            <i class="fas fa-lock mt-0.5 shrink-0 text-slate-400" aria-hidden="true"></i>
            <span>กรุณาเช็คอินก่อนเพื่อดู{{ $pendingLabel }}ของคุณ</span>
        </p>
    </div>
@elseif ($showSeatValue || $showGroupValue)
    <div class="{{ $gridClass }} {{ $class ?? '' }}">
        @if ($showGroupValue)
            <div class="{{ $cardClass }} border-violet-300 bg-violet-50 {{ $layout === 'full' && ! $seatFeature ? 'sm:col-span-2' : '' }}">
                <span class="flex {{ $iconBoxClass }} shrink-0 items-center justify-center bg-violet-600 text-white">
                    <i class="fas fa-users text-sm" aria-hidden="true"></i>
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] font-semibold uppercase tracking-wide text-violet-800">กลุ่ม</p>
                    @if ($groupLabel)
                        <p class="{{ $valueTextClass }} text-violet-950">{{ $groupLabel }}</p>
                    @else
                        <p class="mt-0.5 text-sm font-medium text-violet-800">รอจัดกลุ่ม</p>
                    @endif
                </div>
            </div>
        @endif
        @if ($showSeatValue)
            <div class="{{ $cardClass }} border-emerald-300 bg-emerald-50 {{ $layout === 'full' && ! $groupFeature ? 'sm:col-span-2' : '' }}">
                <span class="flex {{ $iconBoxClass }} shrink-0 items-center justify-center bg-emerald-600 text-white">
                    <i class="fas fa-chair text-sm" aria-hidden="true"></i>
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] font-semibold uppercase tracking-wide text-emerald-800">ที่นั่ง</p>
                    @if (filled($seatLabel))
                        <p class="{{ $valueTextClass }} text-emerald-950">{{ $seatLabel }}</p>
                    @else
                        <p class="mt-0.5 text-sm font-medium text-emerald-800">รอจัดที่นั่ง</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endif
