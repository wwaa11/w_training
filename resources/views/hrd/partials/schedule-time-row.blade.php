@php
    $compact = $compact ?? false;
    $showCapacity = $showCapacity ?? ! $compact;
    $showUnregister = $showUnregister ?? true;
    $showAssignment = $showAssignment ?? false;
    $showStatusBadges = $showStatusBadges ?? true;
@endphp

<div @class([
    'js-unregister-slot rounded-2xl border p-3 sm:p-4',
    $t['hasAttended'] ? 'border-emerald-200 bg-emerald-50/80' : ($t['userRegistered'] ? 'border-blue-200 bg-blue-50/50' : 'border-slate-200 bg-slate-50'),
])>
    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0 flex-1">
            <div class="flex flex-wrap items-center gap-2">
                <p class="text-sm font-semibold text-slate-900">{{ $t['timeRange'] }}</p>
                @if ($showStatusBadges)
                    @if ($t['hasAttended'])
                        <span class="inline-flex items-center rounded-lg bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-800">
                            <i class="fas fa-check-circle mr-1"></i>เข้าร่วมแล้ว
                        </span>
                    @elseif ($t['userRegistered'])
                        <span class="inline-flex items-center rounded-lg bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-900">
                            <i class="fas fa-user-check mr-1"></i>ลงทะเบียนแล้ว
                        </span>
                    @endif
                @endif
            </div>

            @if (! $compact && ! empty($t['timeDetail']))
                <p class="mt-1 text-xs text-slate-500">{{ $t['timeDetail'] }}</p>
            @endif

            @if ($showCapacity && ! empty($t['timeLimit']) && ! empty($t['capacity']))
                <div class="mt-2 max-w-xs">
                    @include('hrd.partials.registration-capacity', ['capacity' => $t['capacity']])
                </div>
            @endif

            @if (! $compact)
                <p class="mt-2 text-xs text-blue-700">
                    <i class="fas fa-sign-in-alt mr-1"></i>เช็คอินได้ตั้งแต่ {{ $t['checkinFromText'] }}
                </p>
            @endif
        </div>
    </div>

    @if ($showAssignment && ($t['userRegistered'] || $t['hasAttended']) && ($project->project_seat_assign || $project->project_group_assign))
        <div class="mt-3">
            @include('hrd.partials.assignment-inline', [
                'project' => $project,
                'layout' => 'full',
                'hasAttended' => (bool) ($t['hasAttended'] ?? false),
                'userSeat' => $t['userSeat'] ?? null,
                'userGroup' => $t['userGroup'] ?? null,
            ])
        </div>
    @endif

    @if (! $compact && ($t['registeredAtText'] || $t['attendedAtText']))
        <div class="mt-2 border-t border-slate-200/80 pt-2 text-xs text-slate-600">
            @if ($t['attendedAtText'])
                <i class="fas fa-check-circle mr-1 text-emerald-600"></i>เช็คอินเมื่อ {{ $t['attendedAtText'] }}
            @elseif ($t['registeredAtText'])
                <i class="fas fa-user-check mr-1 text-blue-600"></i>ลงทะเบียนเมื่อ {{ $t['registeredAtText'] }}
            @endif
        </div>
    @endif

    @if ($showUnregister && $t['userRegistrationId'] && ! $t['hasAttended'])
        @php $canUnregister = $project->project_register_today || ! $d['dateIsToday']; @endphp
        @if ($canUnregister)
            <div class="mt-3">
                <button class="unregister-trigger min-h-[44px] w-full rounded-xl border border-red-200 bg-white px-4 py-2.5 text-sm font-medium text-red-700 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" id="unregister-btn-{{ $project->id }}-{{ $t['userRegistrationId'] }}" data-registration-id="{{ $t['userRegistrationId'] }}" type="button">
                    <i class="fas fa-user-times mr-2"></i>ยกเลิกการลงทะเบียน
                </button>
            </div>
        @endif
    @endif
</div>
