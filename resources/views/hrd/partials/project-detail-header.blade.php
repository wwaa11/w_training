<section class="hrd-panel p-4 sm:p-6">
    <div class="flex flex-wrap items-center gap-2">
        @if ($registrationData["statusBadge"])
            <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-900">
                <i class="{{ $registrationData["statusBadge"]["icon"] }} mr-1.5"></i>
                {{ $registrationData["statusBadge"]["text"] }}
            </span>
        @endif
        @if (! $userIsRegisteredForProject && $project->project_seat_assign)
            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs text-emerald-800"><i class="fas fa-chair mr-1"></i>จัดที่นั่ง</span>
        @endif
        @if (! $userIsRegisteredForProject && $project->project_group_assign)
            <span class="inline-flex items-center rounded-full bg-violet-50 px-2.5 py-1 text-xs text-violet-800"><i class="fas fa-users mr-1"></i>จัดกลุ่ม</span>
        @endif
    </div>

    <h1 class="mt-4 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">{{ $project->project_name }}</h1>

    @if ($project->project_detail)
        <p class="mt-3 text-sm leading-relaxed text-slate-600 sm:text-base">{{ $project->project_detail }}</p>
    @endif

    @if ($userIsRegisteredForProject && ($showAssignmentStrip ?? true))
        @include('hrd.partials.assignment-strip', [
            'project' => $project,
            'registrationData' => $registrationData,
            'registrationUserGroup' => $registrationUserGroup,
            'userSeatAssignments' => $userSeatAssignments,
        ])
    @endif
</section>
