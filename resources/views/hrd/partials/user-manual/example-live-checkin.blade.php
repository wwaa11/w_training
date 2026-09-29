@php
    $manualMockProject = (object) [
        'project_seat_assign' => true,
        'project_group_assign' => true,
    ];
@endphp

<div class="user-manual-mockup pointer-events-none select-none" aria-hidden="true">
    <p class="mb-2 text-xs font-medium text-slate-500">ตัวอย่างหน้าจอ — แบบเดียวกับหน้าหลัก HRD (Live)</p>
    <section class="hrd-hero-banner">
        <div class="border-b border-blue-700/50 px-4 py-3 sm:px-5">
            <p class="text-[11px] font-semibold uppercase tracking-widest text-blue-200">Live</p>
            <h2 class="mt-0.5 text-lg font-bold">เช็คอินได้ตอนนี้</h2>
            <p class="mt-0.5 text-sm text-blue-100/90">ยืนยันการเข้าร่วมจากหน้านี้</p>
        </div>
        <div class="space-y-3 p-4 sm:p-5">
            <div class="hrd-user-card p-4 text-slate-800">
                <div class="mb-3">
                    <p class="text-xs font-medium text-slate-500">วันอบรมที่ 1</p>
                    <h3 class="mt-0.5 text-base font-semibold text-slate-900">การอบรมการใช้งานระบบ HRD</h3>
                </div>

                <div class="mb-3">
                    @include("hrd.partials.assignment-inline", [
                        "project" => $manualMockProject,
                        "layout" => "full",
                        "hasAttended" => false,
                        "userSeat" => null,
                        "userGroup" => null,
                    ])
                </div>

                @include("hrd.partials.checkin-session-schedule", [
                    "sessionStart" => "09:00",
                    "sessionEnd" => "12:00",
                    "checkinFrom" => "08:30",
                    "timeSubtitle" => "รอบเช้า",
                    "class" => "mb-4",
                ])

                <p class="mb-4 flex items-start gap-2 text-sm text-slate-600">
                    <i class="fas fa-map-marker-alt mt-0.5 shrink-0 text-blue-600" aria-hidden="true"></i>
                    <span><span class="font-medium text-slate-700">สถานที่</span> ห้องประชุม A</span>
                </p>

                <button class="hrd-btn-primary min-h-[48px] w-full" type="button" tabindex="-1">
                    <i class="fas fa-user-check" aria-hidden="true"></i>
                    เช็คอินตอนนี้
                </button>
            </div>
        </div>
    </section>
</div>
