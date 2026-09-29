@php
    $hasGroup = $project->project_group_assign && $registrationUserGroup;
    $hasSeats = $project->project_seat_assign && $userSeatAssignments->isNotEmpty();
    $uniqueSeatNumbers = $hasSeats ? $userSeatAssignments->pluck('seat_number')->unique()->values() : collect();
@endphp

@if ($project->project_group_assign || $project->project_seat_assign)
    <div class="mt-4 flex flex-wrap gap-2 border-t border-slate-200 pt-4">
        @if ($project->project_group_assign)
            <div class="inline-flex min-h-[40px] items-center gap-2 rounded-xl border border-violet-200 bg-violet-50 px-3 py-2 text-sm">
                <i class="fas fa-users text-violet-700" aria-hidden="true"></i>
                <span class="text-slate-600">กลุ่ม</span>
                <span class="font-bold text-violet-950">{{ $hasGroup ? $registrationUserGroup->group : 'รอจัด' }}</span>
            </div>
        @endif
        @if ($project->project_seat_assign)
            <div class="inline-flex min-h-[40px] items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm">
                <i class="fas fa-chair text-emerald-700" aria-hidden="true"></i>
                <span class="text-slate-600">ที่นั่ง</span>
                @if ($hasSeats && $uniqueSeatNumbers->count() === 1)
                    <span class="font-bold text-emerald-950">{{ $uniqueSeatNumbers->first() }}</span>
                @elseif ($hasSeats)
                    <span class="font-medium text-emerald-950">ดูรายละเอียดตามเซสชัน</span>
                @else
                    <span class="font-medium text-slate-600">รอจัด</span>
                @endif
            </div>
        @endif
    </div>
@endif
