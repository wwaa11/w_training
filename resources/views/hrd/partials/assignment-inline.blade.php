@php
    $seatLabel = null;
    if (isset($userSeat) && $userSeat) {
        $seatLabel = $userSeat->seat_number;
    } elseif (! empty($seatNumber)) {
        $seatLabel = $seatNumber;
    }
    $showSeat = ($showSeat ?? true) && filled($seatLabel);
    $showGroup = ($showGroup ?? true) && isset($userGroup) && $userGroup;
@endphp

@if ($showSeat || $showGroup)
    <div class="flex flex-wrap gap-2 {{ $class ?? '' }}">
        @if ($showSeat)
            <div class="inline-flex min-h-[40px] items-center gap-2 rounded-xl border border-emerald-300 bg-emerald-50 px-3 py-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-600 text-white">
                    <i class="fas fa-chair text-sm" aria-hidden="true"></i>
                </span>
                <div>
                    <p class="text-[10px] font-semibold uppercase leading-none text-emerald-800">ที่นั่ง</p>
                    <p class="text-lg font-extrabold leading-tight text-emerald-950">{{ $seatLabel }}</p>
                </div>
            </div>
        @endif
        @if ($showGroup)
            <div class="inline-flex min-h-[40px] items-center gap-2 rounded-xl border border-violet-300 bg-violet-50 px-3 py-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-600 text-white">
                    <i class="fas fa-users text-sm" aria-hidden="true"></i>
                </span>
                <div>
                    <p class="text-[10px] font-semibold uppercase leading-none text-violet-800">กลุ่ม</p>
                    <p class="text-lg font-extrabold leading-tight text-violet-950">{{ $userGroup->group }}</p>
                </div>
            </div>
        @endif
    </div>
@endif
