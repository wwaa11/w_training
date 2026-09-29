@php
    $userIsRegistered = $userIsRegisteredForProject ?? (($registrationData['userRegistrations']->count() ?? 0) > 0);
    $hasGroup = $project->project_group_assign && $registrationUserGroup;
    $hasSeats = $project->project_seat_assign && $userSeatAssignments->isNotEmpty();
    $showsSeatFeature = $project->project_seat_assign;
    $showsGroupFeature = $project->project_group_assign;
@endphp

@if ($userIsRegistered && ($showsSeatFeature || $showsGroupFeature))
    <section class="hrd-panel overflow-hidden bg-gradient-to-br from-blue-50/40 via-white to-violet-50/30" aria-label="ข้อมูลที่นั่งและกลุ่ม">
        <div class="border-b border-slate-200 bg-blue-100/60 px-4 py-3 sm:px-5">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white">
                    <i class="fas fa-bell text-sm" aria-hidden="true"></i>
                </span>
                <div class="min-w-0">
                    <h2 class="text-base font-bold text-slate-900 sm:text-lg">ที่นั่งและกลุ่มของคุณ</h2>
                    <p class="text-xs text-blue-800/80 sm:text-sm">ตรวจสอบก่อนเข้าร่วมหรือเช็คอิน</p>
                </div>
            </div>
        </div>

        <div class="grid gap-3 p-4 sm:grid-cols-2 sm:p-5">
            @if ($showsGroupFeature)
                <div class="@if (! $showsSeatFeature) sm:col-span-2 @endif rounded-2xl border {{ $hasGroup ? 'border-violet-300 bg-violet-50' : 'border-dashed border-slate-200 bg-white' }} p-4">
                    <div class="flex items-center gap-2 text-violet-800">
                        <i class="fas fa-users" aria-hidden="true"></i>
                        <span class="text-xs font-semibold uppercase tracking-wide">กลุ่ม</span>
                    </div>
                    @if ($hasGroup)
                        <p class="mt-2 text-2xl font-extrabold tracking-tight text-violet-950 sm:text-3xl">{{ $registrationUserGroup->group }}</p>
                        <p class="mt-1 text-sm font-medium text-violet-700">ใช้กลุ่มนี้เมื่อเข้าร่วมกิจกรรม</p>
                    @else
                        <p class="mt-2 text-sm font-medium text-slate-600">ระบบจะแจ้งกลุ่มเมื่อมีการจัดแล้ว</p>
                    @endif
                </div>
            @endif

            @if ($showsSeatFeature)
                <div class="@if (! $showsGroupFeature) sm:col-span-2 @endif rounded-2xl border {{ $hasSeats ? 'border-emerald-300 bg-emerald-50' : 'border-dashed border-slate-200 bg-white' }} p-4">
                    <div class="flex items-center gap-2 text-emerald-800">
                        <i class="fas fa-chair" aria-hidden="true"></i>
                        <span class="text-xs font-semibold uppercase tracking-wide">ที่นั่ง</span>
                    </div>
                    @if ($hasSeats)
                        @php
                            $uniqueSeatNumbers = $userSeatAssignments->pluck('seat_number')->unique()->values();
                        @endphp
                        @if ($uniqueSeatNumbers->count() === 1 && $userSeatAssignments->count() === 1)
                            <p class="mt-2 text-2xl font-extrabold tracking-tight text-emerald-950 sm:text-3xl">{{ $uniqueSeatNumbers->first() }}</p>
                            <p class="mt-1 text-sm text-emerald-800">{{ $userSeatAssignments->first()['date_title'] }}</p>
                        @elseif ($uniqueSeatNumbers->count() === 1)
                            <p class="mt-2 text-2xl font-extrabold tracking-tight text-emerald-950 sm:text-3xl">{{ $uniqueSeatNumbers->first() }}</p>
                            <p class="mt-1 text-sm text-emerald-800">ทุกเซสชันที่ลงทะเบียน</p>
                        @else
                            <ul class="mt-2 space-y-2">
                                @foreach ($userSeatAssignments as $seatRow)
                                    <li class="flex items-center justify-between gap-2 rounded-xl border border-emerald-200 bg-white px-3 py-2 text-sm">
                                        <span class="min-w-0 truncate text-slate-700">{{ $seatRow['date_title'] }} · {{ $seatRow['time_label'] }}</span>
                                        <span class="shrink-0 text-lg font-bold text-emerald-950">{{ $seatRow['seat_number'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    @else
                        <p class="mt-2 text-sm font-medium text-slate-600">ที่นั่งจะแสดงเมื่อระบบจัดให้แล้ว</p>
                    @endif
                </div>
            @endif
        </div>
    </section>
@endif
