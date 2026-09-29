@php
    $showHeading = $showHeading ?? false;
@endphp

@if ($showHeading)
    <h3 class="mb-3 text-lg font-semibold text-slate-900">
        <i class="fas fa-user mr-2 text-blue-600"></i>
        ข้อมูลผู้เข้าร่วม
    </h3>
@endif
<div class="grid grid-cols-1 gap-3 text-sm sm:grid-cols-2">
    <div>
        <span class="font-medium text-slate-700">รหัสพนักงาน:</span>
        <div class="text-slate-900">{{ auth()->user()->userid }}</div>
    </div>
    <div>
        <span class="font-medium text-slate-700">ชื่อ:</span>
        <div class="text-slate-900">{{ auth()->user()->name }}</div>
    </div>
    <div>
        <span class="font-medium text-slate-700">ตำแหน่ง:</span>
        <div class="text-slate-900">{{ auth()->user()->position ?? "ไม่ระบุ" }}</div>
    </div>
    <div>
        <span class="font-medium text-slate-700">แผนก:</span>
        <div class="text-slate-900">{{ auth()->user()->department ?? "ไม่ระบุ" }}</div>
    </div>
</div>