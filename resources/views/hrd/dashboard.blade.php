@extends("layouts.hrd")

@section("content")
    @include("hrd.partials.app-page", [
        "appEyebrow" => "HRD",
        "appTitle" => "โปรแกรมพัฒนาบุคลากร",
        "appSubtitle" => "สำรวจ ลงทะเบียน และเช็คอินโปรแกรมการฝึกอบรม",
    ])

        <div class="flex flex-wrap gap-2">
            <a class="hrd-btn-secondary min-h-[44px] flex-1 sm:flex-none" href="{{ route("hrd.history") }}">
                <i class="fas fa-history text-blue-600"></i>
                ประวัติ
            </a>
            <a class="hrd-btn-primary min-h-[44px] flex-1 sm:flex-none" href="{{ route("hrd.user-guide") }}">
                <i class="fas fa-book-open"></i>
                คู่มือ
            </a>
        </div>

        @if ($ongoingProjects->count() > 0)
            <section class="hrd-hero-banner">
                <div class="border-b border-blue-700/50 px-4 py-4 sm:px-5">
                    <p class="text-[11px] font-semibold uppercase tracking-widest text-blue-200">Live</p>
                    <h2 class="mt-1 text-lg font-bold">เช็คอินได้ตอนนี้</h2>
                    <p class="mt-0.5 text-sm text-blue-100/90">ยืนยันการเข้าร่วมจากหน้านี้</p>
                </div>

                <div class="space-y-3 px-4 pb-4 sm:px-5 sm:pb-5">
                    @foreach ($ongoingProjects as $ongoingProject)
                        @foreach ($ongoingProject["sessions"] as $session)
                            <div class="hrd-user-card p-4 text-slate-800" id="session-card-{{ $ongoingProject["project"]->id }}-{{ $session["time"]->id }}">
                                <div class="mb-3">
                                    <p class="text-xs font-medium text-slate-500" id="date-title-{{ $ongoingProject["project"]->id }}-{{ $session["time"]->id }}">{{ $session["date"]->date_title }}</p>
                                    <h3 class="mt-0.5 text-base font-semibold text-slate-900" id="project-name-{{ $ongoingProject["project"]->id }}-{{ $session["time"]->id }}">{{ $ongoingProject["project"]->project_name }}</h3>
                                </div>

                                @if (! empty($session["attendanceRecord"]) && ($ongoingProject["project"]->project_seat_assign || $ongoingProject["project"]->project_group_assign))
                                    @include("hrd.partials.assignment-inline", [
                                        "userSeat" => $session["userSeat"] ?? null,
                                        "userGroup" => $session["userGroup"] ?? null,
                                        "showSeat" => $ongoingProject["project"]->project_seat_assign,
                                        "showGroup" => $ongoingProject["project"]->project_group_assign,
                                        "class" => "mb-3",
                                    ])
                                @endif

                                <dl class="mb-4 space-y-1.5 text-sm text-slate-600">
                                    @if ($session["date"]->date_location)
                                        <div class="flex gap-2" id="location-{{ $ongoingProject["project"]->id }}-{{ $session["time"]->id }}">
                                            <dt class="shrink-0 font-medium text-slate-500">สถานที่</dt>
                                            <dd>{{ $session["date"]->date_location }}</dd>
                                        </div>
                                    @endif
                                    <div class="flex gap-2" id="time-schedule-{{ $ongoingProject["project"]->id }}-{{ $session["time"]->id }}">
                                        <dt class="shrink-0 font-medium text-slate-500">เวลา</dt>
                                        <dd>{{ \Carbon\Carbon::parse($session["time"]->time_start)->format("H:i") }}–{{ \Carbon\Carbon::parse($session["time"]->time_end)->format("H:i") }}</dd>
                                    </div>
                                    <div class="flex gap-2 text-blue-700">
                                        <dt class="shrink-0 font-medium">เช็คอิน</dt>
                                        <dd>ตั้งแต่ {{ \Carbon\Carbon::parse($session["time"]->time_start)->subMinutes(30)->format("H:i") }}</dd>
                                    </div>
                                </dl>

                                @if ($session["canCheckIn"])
                                    <form class="checkin-form" id="checkin-form-{{ $ongoingProject["project"]->id }}-{{ $session["time"]->id }}" action="{{ $session["checkInRoute"] }}" method="{{ $session["checkInMethod"] }}">
                                        @csrf
                                        @foreach ($session["checkInData"] as $key => $value)
                                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                        @endforeach
                                        <button class="hrd-btn-primary min-h-[48px] w-full" id="checkin-btn-{{ $ongoingProject["project"]->id }}-{{ $session["time"]->id }}" type="submit">
                                            <i class="fas fa-user-check"></i>
                                            เช็คอินตอนนี้
                                        </button>
                                    </form>
                                @elseif ($session["hasAttended"] && $session["attendanceRecord"])
                                    <div class="rounded-xl border border-slate-200 bg-blue-50 px-4 py-3 text-center">
                                        <p class="text-sm font-medium text-blue-800"><i class="fas fa-check-circle mr-1"></i>เช็คอินแล้ว</p>
                                        <p class="text-xl font-bold text-slate-900">{{ \Carbon\Carbon::parse($session["attendanceRecord"]->attend_datetime)->format("H:i") }}</p>
                                        <p class="text-xs text-blue-700">{{ \Carbon\Carbon::parse($session["attendanceRecord"]->attend_datetime)->format("d M Y") }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </section>
        @endif

        <section class="hrd-user-card p-4 sm:p-5">
            <label class="sr-only" for="searchInput">ค้นหาโปรแกรม</label>
            <div class="hrd-search-wrap">
                <span class="hrd-search-wrap__icon" aria-hidden="true">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input class="hrd-input" id="searchInput" type="text" placeholder="ค้นหาชื่อโปรแกรม..." autocomplete="off" inputmode="search" spellcheck="false">
            </div>
            <p class="mt-2 text-xs text-slate-500">เลือกโปรแกรมเพื่อลงทะเบียน ดูตาราง และเช็คอิน</p>
        </section>

        <div class="space-y-3" id="projectsGrid">
            @forelse($projectsWithStates as $projectData)
                @php
                    $project = $projectData["project"];
                    $state = $projectData["registrationState"];
                @endphp
                <a class="project-list-item group block rounded-2xl focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2" data-name="{{ strtolower($project->project_name) }}" href="{{ route("hrd.projects.show", $project->id) }}">
                    <article class="project-card hrd-user-card overflow-hidden transition group-hover:shadow-md" data-type="{{ $project->project_type }}" data-name="{{ strtolower($project->project_name) }}">
                        <div class="border-b border-slate-100 p-4 sm:p-5">
                            <div class="flex flex-wrap items-center gap-2">
                                @if ($project->project_type === "attendance")
                                    <span class="rounded bg-violet-100 px-2 py-0.5 text-xs font-medium text-violet-800">ไม่ต้องลงทะเบียน</span>
                                @elseif($state["canRegister"])
                                    <span class="rounded bg-blue-100 px-2 py-0.5 text-xs font-semibold text-blue-800">เปิดรับลงทะเบียน</span>
                                @elseif($state["isUpcoming"])
                                    <span class="rounded bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-900">เร็วๆ นี้</span>
                                @elseif($state["isExpired"])
                                    <span class="rounded-lg bg-slate-200 px-2 py-0.5 text-xs font-medium text-slate-700">ปิดรับแล้ว</span>
                                @endif
                                @if ($state["attendanceStatus"])
                                    <span class="rounded-lg bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-800">{{ $state["attendanceStatus"] }}</span>
                                @endif
                            </div>
                            <h3 class="mt-3 text-lg font-bold text-slate-900">{{ $project->project_name }}</h3>
                            @if ($project->project_detail)
                                <p class="mt-2 line-clamp-2 text-sm text-slate-600">{{ $project->project_detail }}</p>
                            @endif
                        </div>
                        <div class="space-y-2 p-4 text-sm text-slate-600 sm:p-5">
                            @if ($project->project_type !== "attendance")
                                <p><span class="font-medium text-slate-500">ลงทะเบียน:</span> {{ $project->project_start_register->format("d M Y") }} – {{ $project->project_end_register->format("d M Y") }}</p>
                            @endif
                            @if ($project->dates->count() > 0)
                                <p><span class="font-medium text-slate-500">จัดอบรม:</span> {{ $project->dates->count() }} วัน</p>
                            @endif
                            <div class="flex flex-wrap gap-1.5 pt-1">
                                @if ($project->project_seat_assign)
                                        <span class="rounded-lg bg-emerald-50 px-2 py-0.5 text-xs text-emerald-800">จัดที่นั่ง</span>
                                @endif
                                @if ($project->project_group_assign)
                                        <span class="rounded-lg bg-violet-50 px-2 py-0.5 text-xs text-violet-800">จัดกลุ่ม</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center justify-between border-t border-slate-100 bg-slate-50/60 px-4 py-3 text-sm sm:px-5">
                            <span class="text-slate-600">ดูรายละเอียด</span>
                            <span class="font-semibold text-blue-700 group-hover:text-blue-800">เปิด <i class="fas fa-chevron-right ml-1 text-xs"></i></span>
                        </div>
                    </article>
                </a>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white py-14 text-center" id="emptyStateDefault">
                    <i class="fas fa-inbox text-3xl text-slate-300"></i>
                    <h3 class="mt-3 font-semibold text-slate-900">ไม่มีโปรแกรมที่ใช้งานได้</h3>
                    <p class="mt-1 text-sm text-slate-500">ยังไม่มีโปรแกรมที่เปิดให้ลงทะเบียน</p>
                </div>
            @endforelse
        </div>

        <div class="hidden rounded-2xl border border-dashed border-slate-300 bg-white py-14 text-center" id="emptyStateSearch">
            <i class="fas fa-search text-3xl text-slate-300"></i>
            <h3 class="mt-3 font-semibold text-slate-900">ไม่พบโปรแกรม</h3>
            <p class="mt-1 text-sm text-slate-500">ลองคำค้นหาอื่น</p>
        </div>

    @include("hrd.partials.app-page", ["appPageClose" => true])
@endsection

@section("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session("success"))
                Swal.fire({ icon: 'success', title: @json(session("success")), confirmButtonText: 'ตกลง', confirmButtonColor: '#2563eb' });
            @endif
            @if (session("error"))
                Swal.fire({ icon: 'error', title: @json(session("error")), confirmButtonText: 'ตกลง', confirmButtonColor: '#2563eb' });
            @endif
            @if (session("info"))
                Swal.fire({ icon: 'info', title: @json(session("info")), confirmButtonText: 'ตกลง', confirmButtonColor: '#2563eb' });
            @endif

            const searchInput = document.getElementById('searchInput');
            const listItems = document.querySelectorAll('.project-list-item');
            const emptySearch = document.getElementById('emptyStateSearch');

            function searchProjects() {
                const searchTerm = (searchInput?.value || '').toLowerCase().trim();
                let visible = 0;
                listItems.forEach(item => {
                    const match = !searchTerm || (item.dataset.name || '').includes(searchTerm);
                    item.classList.toggle('hidden', !match);
                    if (match) visible++;
                });
                if (emptySearch) {
                    emptySearch.classList.toggle('hidden', listItems.length === 0 || visible > 0 || !searchTerm);
                }
            }
            searchInput?.addEventListener('input', searchProjects);

            document.querySelectorAll('.checkin-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const matches = this.id.match(/checkin-form-(\d+)-(\d+)/);
                    if (!matches) return;
                    const [projectId, timeId] = [matches[1], matches[2]];
                    const projectName = document.getElementById(`project-name-${projectId}-${timeId}`)?.textContent || '';
                    const dateTitle = document.getElementById(`date-title-${projectId}-${timeId}`)?.textContent || '';
                    Swal.fire({
                        title: 'ยืนยันการเช็คอิน',
                        html: `<p class="text-sm text-left"><strong>${projectName}</strong><br>${dateTitle}</p>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#2563eb',
                        cancelButtonColor: '#71717a',
                        confirmButtonText: 'เช็คอิน',
                        cancelButtonText: 'ยกเลิก',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const btn = document.getElementById(`checkin-btn-${projectId}-${timeId}`);
                            if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> กำลังเช็คอิน...'; }
                            this.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
