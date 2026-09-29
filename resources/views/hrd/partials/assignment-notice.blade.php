@php
    $userIsRegistered = $userIsRegisteredForProject ?? (($registrationData['userRegistrations']->count() ?? 0) > 0);
    $hasCheckedIn = ($registrationData['userRegistrations'] ?? collect())
        ->contains(fn ($registration) => (bool) $registration->attend_datetime);
    $seatFeature = $project->project_seat_assign;
    $groupFeature = $project->project_group_assign;
@endphp

@if ($userIsRegistered && ($seatFeature || $groupFeature))
    <section class="hrd-panel overflow-hidden bg-gradient-to-br from-blue-50/40 via-white to-violet-50/30" aria-label="ข้อมูลที่นั่งและกลุ่ม">
        <div class="border-b border-slate-200 bg-blue-100/60 px-4 py-3 sm:px-5">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white">
                    <i class="fas fa-bell text-sm" aria-hidden="true"></i>
                </span>
                <div class="min-w-0">
                    <h2 class="text-base font-bold text-slate-900 sm:text-lg">ที่นั่งและกลุ่มของคุณ</h2>
                    <p class="text-xs text-blue-800/80 sm:text-sm">
                        @if ($hasCheckedIn)
                            แสดงหลังเช็คอินแล้ว
                        @else
                            เช็คอินก่อนเพื่อดูรายละเอียด
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-5">
            @include('hrd.partials.assignment-inline', [
                'project' => $project,
                'hasAttended' => $hasCheckedIn,
                'userGroup' => $registrationUserGroup,
                'userSeat' => $userSeatAssignments->isNotEmpty()
                    ? (object) ['seat_number' => $userSeatAssignments->pluck('seat_number')->unique()->first()]
                    : null,
                'layout' => 'full',
            ])
        </div>
    </section>
@endif
