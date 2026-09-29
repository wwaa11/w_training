@php
    $manualMockProject = (object) [
        'project_seat_assign' => true,
        'project_group_assign' => true,
    ];
@endphp

<div class="user-manual-mockup pointer-events-none select-none" aria-hidden="true">
    <p class="mb-2 text-xs font-medium text-slate-500">ตัวอย่างหน้าจอ — เซสชันและเช็คอิน (หลังลงทะเบียน)</p>

    <section class="hrd-panel overflow-hidden">
        <div class="border-b border-slate-200 bg-slate-50/80 px-4 py-3 sm:px-5">
            <h2 class="text-lg font-bold text-slate-900">เซสชันและเช็คอิน</h2>
            <p class="mt-0.5 text-sm text-slate-600">2 เซสชันที่ลงทะเบียน</p>
        </div>
        <div class="space-y-4 p-4 sm:p-5">
            <div>
                <p class="text-sm font-semibold text-slate-900">วันอบรมที่ 1</p>
                <p class="text-xs text-slate-500"><i class="fas fa-map-marker-alt mr-1 text-blue-600" aria-hidden="true"></i>ห้องประชุม A</p>
                <div class="mt-2 space-y-3">
                    <article class="rounded-2xl border border-slate-200 bg-slate-50/50 p-3 sm:p-4">
                        <div class="mb-3">
                            <span class="inline-flex items-center rounded-lg bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-800">
                                <i class="fas fa-circle text-[6px] mr-1 align-middle" aria-hidden="true"></i>เปิดเช็คอิน
                            </span>
                        </div>
                        @include("hrd.partials.checkin-session-schedule", [
                            "timeRange" => "09:00 - 12:00",
                            "checkinFromText" => "08:30",
                            "class" => "mb-3",
                        ])
                        <div class="mt-3">
                            @include("hrd.partials.assignment-inline", [
                                "project" => $manualMockProject,
                                "layout" => "full",
                                "hasAttended" => false,
                            ])
                        </div>
                        <button class="hrd-btn-primary mt-3 min-h-[48px] w-full py-3" type="button" tabindex="-1">
                            <i class="fas fa-user-check" aria-hidden="true"></i>
                            เช็คอินตอนนี้
                        </button>
                    </article>

                    <article class="rounded-2xl border border-slate-200 bg-slate-50/50 p-3 sm:p-4">
                        <div class="mb-3">
                            <span class="text-xs font-medium text-slate-500">รอช่วงเช็คอิน</span>
                        </div>
                        @include("hrd.partials.checkin-session-schedule", [
                            "timeRange" => "13:00 - 16:00",
                            "checkinFromText" => "12:30",
                            "class" => "mb-3",
                        ])
                        <div class="mt-3">
                            @include("hrd.partials.assignment-inline", [
                                "project" => $manualMockProject,
                                "layout" => "full",
                                "hasAttended" => false,
                            ])
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>
</div>
