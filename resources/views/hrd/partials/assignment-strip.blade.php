@php
    $hasCheckedIn = ($registrationData['userRegistrations'] ?? collect())
        ->contains(fn ($registration) => (bool) $registration->attend_datetime);
    $seatFeature = $project->project_seat_assign;
    $groupFeature = $project->project_group_assign;
@endphp

@if ($seatFeature || $groupFeature)
    <div class="mt-4 border-t border-slate-200 pt-4">
        @include('hrd.partials.assignment-inline', [
            'project' => $project,
            'hasAttended' => $hasCheckedIn,
            'userSeat' => $hasCheckedIn && $userSeatAssignments->isNotEmpty()
                ? (object) ['seat_number' => $userSeatAssignments->pluck('seat_number')->unique()->first()]
                : null,
            'userGroup' => $registrationUserGroup,
            'layout' => 'full',
        ])
    </div>
@endif
