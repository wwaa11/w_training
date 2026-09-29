@extends("layouts.hrd")

@section("content")
    @php
        $refreshToolbar = $availableCheckIns->count() > 0
            ? '<button type="button" onclick="refresh()" class="hrd-back-btn !h-10 !w-10" aria-label="รีเฟรช"><i class="fas fa-sync-alt"></i></button>'
            : "";
    @endphp
    @include("hrd.partials.app-page", [
        "appHeaderSticky" => true,
        "appBackUrl" => route("hrd.index"),
        "appTitle" => $project->project_name,
        "appSubtitle" => "รายละเอียดโปรแกรม",
        "appHeaderEnd" => $refreshToolbar,
        "appBottomClass" => ($registrationData["showRegisterForm"] ?? false) && ! ($project->isFull() ?? false) ? "pb-32" : "pb-24 md:pb-12",
    ])
        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                <p class="text-sm font-semibold">กรุณาแก้ไขข้อผิดพลาดต่อไปนี้</p>
                <ul class="mt-2 list-inside list-disc text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $registrationCount = $registrationData["userRegistrations"]->count();
            $focusMySessions = $userIsRegisteredForProject && $project->project_type !== "attendance";
            $addingMoreSessions = $focusMySessions && $registrationData["showRegisterForm"];
        @endphp

        @include("hrd.partials.project-detail-header", [
            "project" => $project,
            "registrationData" => $registrationData,
            "userIsRegisteredForProject" => $userIsRegisteredForProject,
            "registrationUserGroup" => $registrationUserGroup,
            "userSeatAssignments" => $userSeatAssignments,
        ])

        @if ($focusMySessions)
            @include("hrd.partials.user-sessions-hub", [
                "project" => $project,
                "scheduleView" => $scheduleView,
                "availableCheckIns" => $availableCheckIns,
                "registrationCount" => $registrationCount,
                "userSeatAssignments" => $userSeatAssignments,
            ])
        @endif

        <!-- Check-in (attendance projects or guests not in unified hub) -->
        @if (! $focusMySessions && $availableCheckIns->count() > 0)
            <section class="hrd-hero-banner">
                <div class="border-b border-blue-700/50 px-4 py-4 sm:px-5">
                    <p class="text-[11px] font-semibold uppercase tracking-widest text-blue-200">Live</p>
                    <h2 class="mt-1 text-xl font-bold">เช็คอินตอนนี้</h2>
                    <p class="mt-0.5 text-sm text-blue-100/90">เลือกเซสชันและยืนยันการเข้าร่วม</p>
                </div>
                <div class="space-y-3 p-4 sm:p-5">

                    @foreach ($availableCheckIns as $checkIn)
                        <div class="hrd-user-card p-4 text-slate-800" id="checkin-card-{{ $project->id }}-{{ $checkIn["time"]->id }}">
                            <div class="mb-3 min-w-0">
                                <h3 class="text-sm font-semibold text-slate-900" id="checkin-date-title-{{ $project->id }}-{{ $checkIn["time"]->id }}">{{ $checkIn["date"]->date_title }}</h3>
                                <p class="mt-0.5 text-xs text-slate-600" id="checkin-time-schedule-{{ $project->id }}-{{ $checkIn["time"]->id }}">
                                    {{ \Carbon\Carbon::parse($checkIn["time"]->time_start)->format("H:i") }}–{{ \Carbon\Carbon::parse($checkIn["time"]->time_end)->format("H:i") }}
                                </p>
                            </div>

                            <div class="mb-3 space-y-2">
                                @if ($checkIn["date"]->date_location)
                                    <div class="text-xs text-slate-600" id="checkin-location-{{ $project->id }}-{{ $checkIn["time"]->id }}">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        <span class="font-medium">สถานที่:</span> {{ $checkIn["date"]->date_location }}
                                    </div>
                                @endif
                                @if ($checkIn["note"])
                                    <div class="text-xs text-orange-600" id="checkin-note-{{ $project->id }}-{{ $checkIn["time"]->id }}">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        <span class="font-medium">รายละเอียด:</span> {{ $checkIn["note"] }}
                                    </div>
                                @endif
                                <div class="text-xs text-emerald-600">
                                    <i class="fas fa-sign-in-alt mr-1"></i>
                                    <span class="font-medium">เช็คอินได้ตั้งแต่:</span> {{ \Carbon\Carbon::parse($checkIn["time"]->time_start)->subMinutes(30)->format("H:i") }}
                                </div>
                            </div>

                            <!-- Check-in Button or Attended Status -->
                            @if ($checkIn["hasAttended"])
                                <!-- Already Attended -->
                                <div class="rounded-2xl border border-emerald-300 bg-gradient-to-r from-emerald-50 to-emerald-100 p-4">
                                    <div class="flex items-center justify-center">
                                        <i class="fas fa-check-circle mr-3 text-lg text-emerald-600"></i>
                                        <div class="text-center">
                                            <div class="text-sm font-medium text-emerald-700">เช็คอินแล้ว</div>
                                            <div class="text-base font-bold text-emerald-800">
                                                เมื่อ {{ \Carbon\Carbon::parse($checkIn["attendanceRecord"]->attend_datetime)->format("H:i") }}
                                            </div>
                                            <div class="text-xs text-emerald-600">
                                                {{ \Carbon\Carbon::parse($checkIn["attendanceRecord"]->attend_datetime)->format("d M Y") }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                @if ($project->links->count() > 0 && $checkIn["hasApprove"])
                                    <div class="mt-3 rounded-2xl border border-blue-300 bg-gradient-to-r from-blue-50 to-blue-100 p-4">
                                        <div class="mb-2 text-xs font-medium text-blue-700">
                                            <i class="fas fa-link mr-1"></i>
                                            ทรัพยากรสำหรับเซสชัน
                                        </div>
                                        <div class="">
                                            @foreach ($project->links as $link)
                                                @php
                                                    $linkAvailable = true;
                                                    if ($link->link_limit) {
                                                        $now = now();
                                                        $linkAvailable = (!$link->link_time_start || $now >= $link->link_time_start) && (!$link->link_time_end || $now <= $link->link_time_end);
                                                    }
                                                @endphp
                                                @if ($linkAvailable)
                                                    <a href="{{ $link->link_url }}" target="_blank">
                                                        <div class="mb-2 flex items-center justify-between rounded border border-slate-200 bg-blue-50 p-2">
                                                            <span class="text-xs font-medium text-blue-800">{{ $link->link_name }}</span>
                                                            <div class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800">
                                                                <i class="fas fa-external-link-alt mr-1"></i>
                                                                เปิด
                                                            </div>
                                                        </div>
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @elseif ($checkIn["canCheckIn"])
                                <!-- Can Check In -->
                                @if ($checkIn["projectType"] === "attendance")
                                    <form class="attendance-form-top" id="attendance-form-{{ $project->id }}-{{ $checkIn["time"]->id }}" action="{{ route("hrd.projects.attend.store", $project->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="time_id" value="{{ $checkIn["time"]->id }}">
                                        <button class="hrd-btn-primary min-h-[48px] w-full py-3.5" id="attendance-btn-{{ $project->id }}-{{ $checkIn["time"]->id }}" type="submit">
                                            <i class="fas fa-user-check mr-3 text-lg text-white"></i>
                                            <div class="text-center">
                                                <div class="text-sm font-medium text-blue-100">เช็คอินตอนนี้</div>
                                                <div class="text-base font-bold text-white">คลิกเพื่อยืนยันการเข้าร่วม</div>
                                            </div>
                                        </button>
                                    </form>
                                @else
                                    <form class="stamp-form-top" id="stamp-form-{{ $project->id }}-{{ $checkIn["userRegistration"]->id }}" action="{{ route("hrd.projects.stamp.store", [$project->id, $checkIn["userRegistration"]->id]) }}" method="POST">
                                        @csrf
                                        <button class="hrd-btn-primary min-h-[48px] w-full py-3.5" id="stamp-btn-{{ $project->id }}-{{ $checkIn["userRegistration"]->id }}" type="submit">
                                            <i class="fas fa-stamp mr-3 text-lg text-white"></i>
                                            <div class="text-center">
                                                <div class="text-sm font-medium text-blue-100">เช็คอินตอนนี้</div>
                                                <div class="text-base font-bold text-white">คลิกเพื่อยืนยันการเข้าร่วม</div>
                                            </div>
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Registration Section -->
        @if ($registrationData["showRegisterForm"])
            <section class="hrd-panel p-4 sm:p-6" id="registration">
                @if ($project->isFull())
                    <!-- Project Full Notice in Registration -->
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 sm:p-4">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle mr-2 mt-1 text-red-500 sm:mr-3"></i>
                            <div>
                                <h3 class="font-medium text-red-800">ไม่สามารถลงทะเบียนได้</h3>
                                <p class="mt-1 text-sm text-red-700">
                                    โปรเจกต์นี้เต็มแล้ว ไม่สามารถลงทะเบียนเพิ่มเติมได้ ทุกช่วงเวลามีผู้ลงทะเบียนครบแล้ว
                                </p>
                                <div class="mt-2 text-xs text-red-600">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    จำนวนผู้ลงทะเบียนทั้งหมด: {{ $project->activeAttends->count() }} คน
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    @if ($registrationData["showSameDayNotice"])
                        <!-- Same-day Registration Info -->
                        <div class="mb-4 rounded-lg border border-yellow-200 bg-yellow-50 p-3 sm:p-4">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle mr-2 mt-1 text-yellow-500"></i>
                                <div>
                                    <h3 class="font-medium text-yellow-800">หมายเหตุการลงทะเบียน</h3>
                                    <p class="mt-1 text-sm text-yellow-700">
                                        ไม่สามารถลงทะเบียนในวันเดียวกันได้ เซสชันของวันนี้ไม่สามารถลงทะเบียนได้ แต่คุณสามารถลงทะเบียนสำหรับวันที่ในอนาคตได้
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($registrationData["canReselect"] && $registrationData["userRegistrations"]->count() > 0)
                        <!-- Reselection Notice -->
                        <div class="mb-4 rounded-lg border border-orange-200 bg-orange-50 p-3 sm:p-4">
                            <div class="flex items-start">
                                <i class="fas fa-edit mr-2 mt-1 text-orange-500"></i>
                                <div>
                                    <h3 class="font-medium text-orange-800">สามารถเลือกใหม่ได้</h3>
                                    <p class="mt-1 text-sm text-orange-700">
                                        คุณลงทะเบียนสำหรับ {{ $registrationData["userRegistrations"]->count() }} เซสชันแล้ว
                                        คุณสามารถล้างการลงทะเบียนปัจจุบันและเลือกช่วงเวลาใหม่ได้
                                    </p>
                                </div>
                            </div>
                            <form class="reselect-form mt-3" id="reselect-form-{{ $project->id }}" action="{{ route("hrd.projects.reselect", $project->id) }}" method="POST">
                                @csrf
                                @method("DELETE")
                                <button class="group relative inline-flex items-center overflow-hidden rounded-lg bg-gradient-to-r from-orange-500 to-orange-600 px-4 py-2 text-white shadow-sm transition-all duration-300 hover:from-orange-600 hover:to-orange-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 active:scale-95" id="reselect-btn-{{ $project->id }}" type="submit">
                                    <div class="absolute inset-0 bg-gradient-to-r from-orange-400 to-orange-500 opacity-0 transition-opacity duration-300 group-hover:opacity-20"></div>
                                    <i class="fas fa-redo mr-2 text-lg"></i>
                                    <span class="text-sm font-semibold">ล้างการลงทะเบียนและเลือกใหม่</span>
                                    <i class="fas fa-arrow-right ml-2 transition-transform duration-300 group-hover:translate-x-1"></i>
                                </button>
                            </form>
                        </div>
                    @endif

                    <div>
                        @include("hrd.partials.section-title", [
                            "icon" => $addingMoreSessions ? "fas fa-plus-circle" : "fas fa-user-plus",
                            "title" => $addingMoreSessions
                                ? "ลงทะเบียนเซสชันเพิ่ม"
                                : ($registrationData["canReselect"] && $registrationCount > 0 ? "เลือกเซสชันใหม่" : "ลงทะเบียนเข้าร่วม"),
                            "subtitle" => $addingMoreSessions
                                ? "เปิดรายการด้านล่างเพื่อเลือกเซสชันที่ยังไม่ลงทะเบียน"
                                : "เลือกวันและช่วงเวลา จากนั้นยืนยันด้านล่าง",
                        ])

                        <form id="registrationForm" action="{{ route("hrd.projects.register.store", $project->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="project_type" value="{{ $project->project_type }}">

                            @if ($addingMoreSessions)
                                @component("hrd.partials.collapsible-panel", [
                                    "title" => "เลือกเซสชันเพิ่ม",
                                    "subtitle" => "แสดงเฉพาะช่วงที่ยังลงทะเบียนไม่ได้",
                                    "icon" => "fas fa-calendar-plus",
                                    "open" => false,
                                ])
                                    @if ($project->project_type !== "attendance")
                                        @include("hrd.partials.registration-summary", [
                                            "project" => $project,
                                            "registrationData" => $registrationData,
                                        ])
                                    @endif

                                    @include("hrd.partials.registration-slot-picker", [
                                        "registrationTimeSlots" => $registrationTimeSlots,
                                        "project" => $project,
                                        "registrationUserGroup" => $registrationUserGroup,
                                        "onlyAvailable" => true,
                                    ])
                                @endcomponent

                            @else
                                @if ($project->project_type !== "attendance")
                                    @include("hrd.partials.registration-summary", [
                                        "project" => $project,
                                        "registrationData" => $registrationData,
                                    ])
                                @endif

                                <div class="mb-4 rounded-2xl border border-slate-200 bg-slate-50/80 p-4">
                                    @include("hrd.partials.registration-participant-info", [
                                        "project" => $project,
                                        "userIsRegisteredForProject" => $userIsRegisteredForProject,
                                        "userSeatAssignments" => $userSeatAssignments,
                                        "registrationUserGroup" => $registrationUserGroup,
                                        "showHeading" => true,
                                    ])
                                </div>

                                @include("hrd.partials.registration-slot-picker", [
                                    "registrationTimeSlots" => $registrationTimeSlots,
                                    "project" => $project,
                                    "registrationUserGroup" => $registrationUserGroup,
                                    "onlyAvailable" => $registrationCount > 0 && ! $registrationData["canReselect"],
                                ])
                            @endif

                            @php
                                $hasSelectableSlots = $registrationTimeSlots->contains(function ($dateSlot) use ($addingMoreSessions) {
                                    $slots = collect($dateSlot["slots"]);
                                    if ($addingMoreSessions) {
                                        $slots = $slots->filter(fn ($s) => ! $s["userRegistered"]);
                                    }
                                    return $slots->isNotEmpty();
                                });
                            @endphp

                            @if ($hasSelectableSlots)
                                <div class="hrd-reg-actions-spacer" aria-hidden="true"></div>
                                <div class="hrd-reg-actions-bar">
                                    <div class="hrd-reg-actions-bar__inner">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">สรุปการเลือก</p>
                                            <p class="truncate text-sm font-semibold text-slate-900" id="registrationSelectionSummary">ยังไม่ได้เลือกเซสชัน</p>
                                        </div>
                                        <button class="hrd-btn-primary hrd-reg-submit shrink-0 min-h-[44px] px-5" type="submit">
                                            <i class="fas fa-user-plus"></i>
                                            <span>{{ $addingMoreSessions ? "เพิ่มเซสชัน" : "ลงทะเบียน" }}</span>
                                        </button>
                                    </div>
                                </div>
                            @elseif ($addingMoreSessions)
                                <p class="mt-4 rounded-md border border-dashed border-slate-200 bg-slate-50/60 px-4 py-3 text-center text-sm text-slate-600">
                                    ไม่มีเซสชันเพิ่มที่เปิดให้ลงทะเบียนในขณะนี้
                                </p>
                            @endif
                        </form>
                    </div>
                @endif
            </section>
        @endif

        <!-- Schedule (full program or other sessions when already registered) -->
        @php
            $showScheduleSection = $registrationData["userRegistrations"]->count() > 0 || $project->project_type === "attendance";
            $hasOtherSessions = false;
            if ($focusMySessions) {
                foreach ($scheduleView as $d) {
                    foreach ($d["times"] as $t) {
                        if (! $t["userRegistered"] && ! $t["hasAttended"]) {
                            $hasOtherSessions = true;
                            break 2;
                        }
                    }
                }
            }
        @endphp

        @if ($showScheduleSection && (! $focusMySessions || $hasOtherSessions || $project->project_type === "attendance"))
            @if ($focusMySessions && $hasOtherSessions)
                @component("hrd.partials.collapsible-panel", [
                    "title" => "เซสชันอื่นในโปรแกรม",
                    "subtitle" => "ดูตารางที่ยังไม่ได้ลงทะเบียน",
                    "icon" => "fas fa-calendar-alt",
                    "open" => false,
                ])
                    @include("hrd.partials.schedule-program-list", [
                        "scheduleView" => $scheduleView,
                        "project" => $project,
                        "onlyOthers" => true,
                    ])
                @endcomponent
            @else
                <section class="hrd-panel p-4 sm:p-6">
                    <h2 class="mb-4 text-lg font-semibold text-slate-900 sm:text-xl">
                        <i class="fas fa-calendar-check mr-2 text-blue-600"></i>
                        @if ($project->project_type === "attendance")
                            ตารางการเข้าร่วมโปรแกรม
                        @else
                            การลงทะเบียนและตารางการเข้าร่วม
                        @endif
                    </h2>

                    @if ($project->isFull())
                        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 sm:p-4">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle mr-2 mt-1 text-red-500 sm:mr-3"></i>
                                <div>
                                    <h3 class="font-medium text-red-800">โปรเจกต์เต็มแล้ว</h3>
                                    <p class="mt-1 text-sm text-red-700">ขออภัย โปรเจกต์นี้เต็มแล้ว ไม่สามารถลงทะเบียนเพิ่มเติมได้</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @include("hrd.partials.schedule-program-list", [
                        "scheduleView" => $scheduleView,
                        "project" => $project,
                        "onlyOthers" => false,
                    ])
                </section>
            @endif
        @endif

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

            const form = document.getElementById('registrationForm');
            const registrationSummaryEl = document.getElementById('registrationSelectionSummary');

            function updateRegistrationSelectionSummary() {
                if (!form || !registrationSummaryEl) {
                    return;
                }
                const selectedTimes = form.querySelectorAll('input[name="time_ids[]"]:checked');
                if (selectedTimes.length === 0) {
                    registrationSummaryEl.textContent = 'ยังไม่ได้เลือกเซสชัน';
                    return;
                }
                if (selectedTimes.length === 1) {
                    const slotCard = selectedTimes[0].closest('.js-registration-slot');
                    const timeRange = slotCard?.querySelector('.text-base.font-semibold')?.textContent?.trim();
                    registrationSummaryEl.textContent = timeRange ? `เลือก: ${timeRange}` : 'เลือกแล้ว 1 เซสชัน';
                    return;
                }
                registrationSummaryEl.textContent = `เลือกแล้ว ${selectedTimes.length} เซสชัน`;
            }

            if (form) {
                form.addEventListener('change', function(e) {
                    if (e.target.matches('input[name="time_ids[]"]')) {
                        updateRegistrationSelectionSummary();
                    }
                });
                updateRegistrationSelectionSummary();

                // Handle single vs multiple selection for single type projects
                const projectType = document.querySelector('input[name="project_type"]');

                if (projectType && projectType.value === 'single') {
                    const radioButtons = document.querySelectorAll('input[name="time_ids[]"][type="radio"]');

                    radioButtons.forEach(radio => {
                        radio.addEventListener('change', function() {
                            // Uncheck all other radio buttons when one is selected
                            radioButtons.forEach(otherRadio => {
                                if (otherRadio !== this) {
                                    otherRadio.checked = false;
                                }
                            });
                            updateRegistrationSelectionSummary();
                        });
                    });
                }

                // Form submission with confirmation
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const selectedTimes = document.querySelectorAll('input[name="time_ids[]"]:checked');

                    if (selectedTimes.length === 0) {
                        Swal.fire({
                            title: 'ไม่มีการเลือก',
                            text: 'กรุณาเลือกช่วงเวลาอย่างน้อยหนึ่งช่วง',
                            icon: 'warning',
                            confirmButtonText: 'ตกลง',
                            confirmButtonColor: '#f59e0b'
                        });
                        return;
                    }

                    // Check if any selected time slots are already registered
                    let hasRegisteredSlots = false;
                    selectedTimes.forEach(input => {
                        if (input.closest('label[data-slot-registered]')) {
                            hasRegisteredSlots = true;
                        }
                    });

                    if (hasRegisteredSlots) {
                        Swal.fire({
                            title: 'ไม่สามารถลงทะเบียนได้',
                            text: 'คุณได้ลงทะเบียนในบางช่วงเวลาแล้ว กรุณาเลือกช่วงเวลาอื่น',
                            icon: 'warning',
                            confirmButtonText: 'ตกลง',
                            confirmButtonColor: '#f59e0b'
                        });
                        return;
                    }

                    // Build confirmation message
                    let selectionText = '';
                    selectedTimes.forEach((input, index) => {
                        const slotCard = input.closest('.js-registration-slot');
                        const timeRange = slotCard?.querySelector('.text-base.font-semibold')?.textContent?.trim() || 'เซสชัน';
                        const timeTitle = slotCard?.querySelector('.text-sm.text-slate-600')?.textContent?.trim();
                        const label = timeTitle ? `${timeRange} · ${timeTitle}` : timeRange;
                        selectionText += `<div class="text-left mb-1">${index + 1}. ${label}</div>`;
                    });

                    Swal.fire({
                        title: 'ยืนยันการลงทะเบียน',
                        html: `
                            <div class="text-left">
                                <p class="mb-3"><strong>โปรแกรม:</strong> {{ $project->project_name }}</p>
                                <p class="mb-2"><strong>เซสชันที่เลือก:</strong></p>
                                ${selectionText}
                            </div>
                            <p class="mt-4 text-sm text-slate-600">คุณแน่ใจหรือไม่ที่จะลงทะเบียนสำหรับ ${selectedTimes.length > 1 ? 'เซสชันเหล่านี้' : 'เซสชันนี้'}?</p>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3b82f6',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'ใช่, ลงทะเบียน',
                        cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            }

            // Handle attendance form submissions
            const attendanceForms = document.querySelectorAll('.attendance-form');
            attendanceForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Get session details with better error handling
                    const sessionCard = this.closest('.border-slate-100, .border-slate-200, .border-green-200');
                    if (!sessionCard) {
                        console.error('Could not find session card for attendance form');
                        return;
                    }

                    const timeSlot = sessionCard.querySelector('.font-medium')?.textContent || 'Unknown Session';
                    const timeElement = sessionCard.querySelector('.fa-clock')?.parentNode;
                    const timeSchedule = timeElement ? timeElement.textContent.trim() : '';

                    Swal.fire({
                        title: 'ยืนยันการเช็คอิน',
                        html: `
                            <div class="text-left">
                                <p class="mb-3"><strong>โปรแกรม:</strong> {{ $project->project_name }}</p>
                                <p class="mb-2"><strong>เซสชัน:</strong> ${timeSlot}</p>
                                ${timeSchedule ? `<p class="mb-3"><strong>เวลา:</strong> ${timeSchedule}</p>` : ''}
                            </div>
                            <p class="mt-4 text-sm text-slate-600">คุณแน่ใจหรือไม่ที่จะเช็คอินสำหรับเซสชันนี้?</p>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3b82f6',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'ใช่, เช็คอิน',
                        cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });

            // Handle top attendance form submissions (for attendance projects)
            const topAttendanceForms = document.querySelectorAll('.attendance-form-top');
            topAttendanceForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Get form ID to extract project and time IDs
                    const formId = this.id;
                    const matches = formId.match(/attendance-form-(\d+)-(\d+)/);

                    if (!matches) {
                        console.error('Could not parse attendance form ID:', formId);
                        return;
                    }

                    const projectId = matches[1];
                    const timeId = matches[2];

                    // Use ID-based selectors for better performance and reliability
                    const projectName = @json($project->project_name);
                    const dateTitle = document.getElementById(`checkin-date-title-${projectId}-${timeId}`)?.textContent?.trim() || '';
                    const locationElement = document.getElementById(`checkin-location-${projectId}-${timeId}`);
                    const location = locationElement ? locationElement.textContent.replace('สถานที่:', '').trim() : '';
                    const timeScheduleElement = document.getElementById(`checkin-time-schedule-${projectId}-${timeId}`);
                    const timeSchedule = timeScheduleElement ? timeScheduleElement.textContent.replace('เวลา:', '').trim() : '';

                    Swal.fire({
                        title: 'ยืนยันการเช็คอิน',
                        html: `
                            <div class="text-left">
                                <p class="mb-3"><strong>โปรแกรม:</strong> ${projectName}</p>
                                ${dateTitle ? `<p class="mb-2"><strong>วันที่:</strong> ${dateTitle}</p>` : ''}
                                ${location ? `<p class="mb-2"><strong>สถานที่:</strong> ${location}</p>` : ''}
                                ${timeSchedule ? `<p class="mb-3"><strong>เวลา:</strong> ${timeSchedule}</p>` : ''}
                            </div>
                            <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-3">
                                <p class="text-sm text-emerald-700">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    การเช็คอินจะบันทึกเวลาที่คุณเข้าร่วมโปรแกรม
                                </p>
                            </div>
                            <p class="mt-4 text-sm text-slate-600">คุณแน่ใจหรือไม่ที่จะเช็คอินสำหรับเซสชันนี้?</p>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#2563eb',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'ใช่, เช็คอิน',
                        cancelButtonText: 'ยกเลิก',
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return new Promise((resolve) => {
                                setTimeout(() => {
                                    resolve();
                                }, 1000);
                            });
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading state on button using ID
                            const buttonId = `attendance-btn-${projectId}-${timeId}`;
                            const button = document.getElementById(buttonId);
                            if (button) {
                                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>กำลังเช็คอิน...';
                                button.disabled = true;
                            }

                            this.submit();
                        }
                    });
                });
            });

            // Handle top stamp form submissions (for registered projects)
            const topStampForms = document.querySelectorAll('.stamp-form-top');
            topStampForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Get form ID to extract project and registration IDs
                    const formId = this.id;
                    const matches = formId.match(/stamp-form-(\d+)-(\d+)/);

                    if (!matches) {
                        console.error('Could not parse stamp form ID:', formId);
                        return;
                    }

                    const projectId = matches[1];
                    const registrationId = matches[2];
                    const projectName = @json($project->project_name);

                    const sessionCard = this.closest('[id^="session-hub-"], [id^="checkin-card-"]');
                    let dateTitle = '';
                    let timeSchedule = '';
                    let location = '';
                    if (sessionCard?.id.startsWith('session-hub-')) {
                        const dateHeading = sessionCard.closest('div')?.querySelector('p.text-sm.font-semibold');
                        dateTitle = dateHeading?.textContent?.trim() || '';
                        timeSchedule = sessionCard.querySelector('p.text-sm.font-semibold')?.textContent?.trim() || '';
                        const loc = sessionCard.closest('div')?.querySelector('.fa-map-marker-alt')?.parentNode;
                        location = loc ? loc.textContent.replace(/\s+/g, ' ').trim() : '';
                    } else if (sessionCard) {
                        const timeIdMatch = sessionCard.id.match(/checkin-card-\d+-(\d+)/);
                        const timeId = timeIdMatch ? timeIdMatch[1] : '';
                        dateTitle = document.getElementById(`checkin-date-title-${projectId}-${timeId}`)?.textContent?.trim() || '';
                        const locationElement = document.getElementById(`checkin-location-${projectId}-${timeId}`);
                        location = locationElement ? locationElement.textContent.replace('สถานที่:', '').trim() : '';
                        const timeScheduleElement = document.getElementById(`checkin-time-schedule-${projectId}-${timeId}`);
                        timeSchedule = timeScheduleElement ? timeScheduleElement.textContent.trim() : '';
                    }

                    Swal.fire({
                        title: 'ยืนยันการเช็คอิน',
                        html: `
                            <div class="text-left">
                                <p class="mb-3"><strong>โปรแกรม:</strong> ${projectName}</p>
                                ${dateTitle ? `<p class="mb-2"><strong>วันที่:</strong> ${dateTitle}</p>` : ''}
                                ${location ? `<p class="mb-2"><strong>สถานที่:</strong> ${location}</p>` : ''}
                                ${timeSchedule ? `<p class="mb-3"><strong>เวลา:</strong> ${timeSchedule}</p>` : ''}
                            </div>
                            <div class="mt-4 p-3 bg-blue-50 border border-slate-200 rounded-lg">
                                <p class="text-sm text-blue-700">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    การเช็คอินจะบันทึกเวลาที่คุณเข้าร่วมโปรแกรมที่ลงทะเบียนแล้ว
                                </p>
                            </div>
                            <p class="mt-4 text-sm text-slate-600">คุณแน่ใจหรือไม่ที่จะเช็คอินสำหรับเซสชันที่ลงทะเบียนแล้วนี้?</p>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3b82f6',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'ใช่, เช็คอิน',
                        cancelButtonText: 'ยกเลิก',
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return new Promise((resolve) => {
                                setTimeout(() => {
                                    resolve();
                                }, 1000);
                            });
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading state on button using ID
                            const buttonId = `stamp-btn-${projectId}-${registrationId}`;
                            const button = document.getElementById(buttonId);
                            if (button) {
                                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>กำลังเช็คอิน...';
                                button.disabled = true;
                            }

                            this.submit();
                        }
                    });
                });
            });



            // Handle reselect form submissions
            const reselectForm = document.getElementById('reselect-form-{{ $project->id }}');
            if (reselectForm) {
                reselectForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'ล้างการลงทะเบียน?',
                        html: `
                            <div class="text-left">
                                <p class="mb-3"><strong>โปรแกรม:</strong> {{ $project->project_name }}</p>
                                <p class="mb-3 text-orange-600"><strong>คำเตือน:</strong> การดำเนินการนี้จะลบการลงทะเบียนปัจจุบันของคุณ</p>
                            </div>
                            <p class="mt-4 text-sm text-slate-600">หลังจากล้างแล้ว คุณสามารถลงทะเบียนใหม่ด้วยการเลือกช่วงเวลาใหม่ คุณแน่ใจหรือไม่ที่จะดำเนินการต่อ?</p>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#f59e0b',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'ใช่, ล้างการลงทะเบียน',
                        cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            }

            // Handle unregister trigger buttons (new design)
            const unregisterTriggers = document.querySelectorAll('[id^="unregister-btn-{{ $project->id }}-"]');
            unregisterTriggers.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    const registrationId = this.getAttribute('data-registration-id');
                    const timeSlotContainer = this.closest('.js-unregister-slot');

                    if (!timeSlotContainer) {
                        console.error('Could not find time slot container for unregister trigger');
                        return;
                    }

                    const timeLabel = timeSlotContainer.querySelector('p.text-sm.font-semibold, h4.text-sm.font-medium, h3.text-sm.font-semibold');
                    const sessionTitle = timeLabel ? timeLabel.textContent.trim() : 'เซสชัน';
                    const sessionTimeText = sessionTitle;

                    Swal.fire({
                        title: 'ยืนยันการยกเลิกการลงทะเบียน',
                        html: `
                            <div class="text-left">
                                <p class="mb-3"><strong>โปรแกรม:</strong> {{ $project->project_name }}</p>
                                <p class="mb-2"><strong>เซสชัน:</strong> ${sessionTitle}</p>
                                ${sessionTimeText ? `<p class="mb-3"><strong>เวลา:</strong> ${sessionTimeText}</p>` : ''}
                            </div>
                            <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-700">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <strong>คำเตือน:</strong> การดำเนินการนี้จะยกเลิกการลงทะเบียนของคุณสำหรับเซสชันนี้
                                </p>
                            </div>
                            <p class="mt-4 text-sm text-slate-600">คุณแน่ใจหรือไม่ที่จะดำเนินการต่อ?</p>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'ใช่, ยกเลิกการลงทะเบียน',
                        cancelButtonText: 'ไม่, เก็บไว้',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Create and submit the form
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ route("hrd.projects.unregister", [$project->id, ":registrationId"]) }}'.replace(':registrationId', registrationId);

                            const csrfToken = document.createElement('input');
                            csrfToken.type = 'hidden';
                            csrfToken.name = '_token';
                            csrfToken.value = '{{ csrf_token() }}';

                            const methodField = document.createElement('input');
                            methodField.type = 'hidden';
                            methodField.name = '_method';
                            methodField.value = 'DELETE';

                            form.appendChild(csrfToken);
                            form.appendChild(methodField);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });
        });

        function refresh() {
            location.reload();
        }
    </script>
@endsection
