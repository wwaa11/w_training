@php
    $project = $project ?? null;
    $groupEnabled = old('project_group_assign', $project?->project_group_assign ?? false);
    $groupMode = old('project_group_mode', $project?->project_group_mode ?? 'manual');
    $groupsAdminUrl = $project
        ? route('hrd.admin.projects.groups.index', $project->id)
        : null;
@endphp

<div class="mt-4 border-t border-slate-200 pt-3">
    <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-blue-700">ตัวเลือก</p>
    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
        <label class="flex cursor-pointer items-center gap-2 rounded-md border border-slate-200 bg-blue-50/50 px-3 py-2 text-sm text-slate-700">
            <input class="rounded border-slate-300 text-blue-600 focus:ring-blue-500" type="checkbox" name="project_seat_assign" value="1" @checked(old('project_seat_assign', $project?->project_seat_assign ?? false))>
            จัดที่นั่ง
        </label>
        <label class="flex cursor-pointer items-center gap-2 rounded-md border border-slate-200 bg-blue-50/50 px-3 py-2 text-sm text-slate-700">
            <input class="rounded border-slate-300 text-blue-600 focus:ring-blue-500" type="checkbox" name="project_register_today" value="1" @checked(old('project_register_today', $project?->project_register_today ?? true))>
            ลงทะเบียนในวันจัดอบรม
        </label>
    </div>

    <div @class([
        'mt-2 rounded-md border p-3 transition-colors',
        'border-slate-200 bg-blue-50/40' => $groupEnabled,
        'border-slate-200 bg-white' => ! $groupEnabled,
    ]) id="groupAssignCard">
        <label class="flex cursor-pointer items-center gap-2 text-sm font-medium text-slate-800">
            <input type="hidden" name="project_group_assign" value="0" id="projectGroupAssignOff" @disabled($groupEnabled)>
            <input class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500" type="checkbox" id="projectGroupAssign" name="project_group_assign" value="1" @checked($groupEnabled)>
            เปิดใช้งานการจัดกลุ่ม
        </label>

        <div @class(['mt-3 space-y-3 border-t border-slate-200/80 pt-3', 'hidden' => ! $groupEnabled]) id="groupModePanel" @if (! $groupEnabled) hidden @endif aria-hidden="{{ $groupEnabled ? 'false' : 'true' }}">
            <p class="text-xs font-semibold text-blue-800">โหมดจัดกลุ่ม</p>
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                <label class="flex cursor-pointer items-start gap-2 rounded-md border px-3 py-2 text-sm has-[:checked]:border-slate-1000 has-[:checked]:bg-blue-50 border-slate-200">
                    <input class="mt-1 text-blue-600 focus:ring-blue-500" type="radio" name="project_group_mode" value="manual" @checked($groupMode !== 'auto')>
                    <span>
                        <span class="font-medium text-slate-900">กำหนดเอง (Manual)</span>
                        <span class="mt-0.5 block text-xs text-slate-500">ผู้ดูแลจัดกลุ่มและย้ายสมาชิกเอง</span>
                    </span>
                </label>
                <label class="flex cursor-pointer items-start gap-2 rounded-md border px-3 py-2 text-sm has-[:checked]:border-slate-1000 has-[:checked]:bg-blue-50 border-slate-200">
                    <input class="mt-1 text-blue-600 focus:ring-blue-500" type="radio" name="project_group_mode" value="auto" @checked($groupMode === 'auto')>
                    <span>
                        <span class="font-medium text-slate-900">อัตโนมัติ (Auto)</span>
                        <span class="mt-0.5 block text-xs text-slate-500">กำหนดกลุ่มก่อน ระบบสุ่มจัดเมื่อลงทะเบียน</span>
                    </span>
                </label>
            </div>
            <div class="rounded-md border border-amber-200 bg-amber-50/80 px-3 py-2 text-xs text-amber-950">
                <p class="font-semibold"><i class="fas fa-info-circle mr-1 text-amber-700"></i>หลังบันทึกโปรเจกต์</p>
                <ul class="mt-1 list-inside list-disc space-y-0.5 text-amber-900/90">
                    <li><strong>Auto:</strong> สร้างรายการกลุ่ม (ชื่อ + จำนวนที่นั่ง) ในหน้า «จัดการกลุ่ม» ก่อนเปิดรับลงทะเบียน</li>
                    <li><strong>Manual:</strong> จัดกลุ่มผู้ลงทะเบียนในหน้า «จัดการกลุ่ม» หลังมีผู้สมัคร</li>
                </ul>
                @if ($groupsAdminUrl)
                    <a class="mt-2 inline-flex items-center gap-1 font-semibold text-blue-800 hover:text-slate-900" href="{{ $groupsAdminUrl }}">
                        ไปจัดการกลุ่ม <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                @else
                    <p class="mt-1 text-amber-800/80">เมื่อสร้างโปรเจกต์แล้ว เปิดเมนูโปรเจกต์ → <strong>จัดการกลุ่ม</strong></p>
                @endif
            </div>
        </div>
    </div>
</div>
