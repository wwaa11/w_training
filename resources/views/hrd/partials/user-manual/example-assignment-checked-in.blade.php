@php
    $manualMockProject = (object) [
        'project_seat_assign' => true,
        'project_group_assign' => true,
    ];
    $manualMockSeat = (object) ['seat_number' => 'A-12'];
    $manualMockGroup = (object) ['group' => 'กลุ่ม B'];
@endphp

<div class="user-manual-mockup pointer-events-none select-none" aria-hidden="true">
    <p class="mb-2 text-xs font-medium text-slate-500">ตัวอย่างหน้าจอ — หลังเช็คอิน (กลุ่ม / ที่นั่ง)</p>
    <div class="hrd-user-card p-4 text-slate-800">
        <div class="mb-3 flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center rounded-lg bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">
                <i class="fas fa-check-circle mr-1" aria-hidden="true"></i>เช็คอินแล้ว
            </span>
        </div>
        @include("hrd.partials.checkin-session-schedule", [
            "sessionStart" => "09:00",
            "sessionEnd" => "12:00",
            "checkinFrom" => "08:30",
            "class" => "mb-3",
        ])
        @include("hrd.partials.assignment-inline", [
            "project" => $manualMockProject,
            "layout" => "full",
            "hasAttended" => true,
            "userSeat" => $manualMockSeat,
            "userGroup" => $manualMockGroup,
        ])
        <div class="mt-3 rounded-2xl border border-emerald-300 bg-gradient-to-r from-emerald-50 to-emerald-100 p-4 text-center">
            <p class="text-sm font-medium text-emerald-700">เช็คอินแล้ว</p>
            <p class="text-base font-bold text-emerald-800">เมื่อ 09:05</p>
            <p class="text-xs text-emerald-600">29 ก.ย. 2026</p>
        </div>
    </div>
</div>
