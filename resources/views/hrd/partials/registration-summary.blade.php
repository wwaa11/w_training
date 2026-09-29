<div class="mb-5 rounded-2xl border border-slate-200 bg-blue-50/40 p-4 text-sm text-slate-700">
    <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">สรุปการลงทะเบียน</p>
    <dl class="mt-3 grid gap-3 sm:grid-cols-2">
        @if ($project->project_type !== 'attendance')
            <div>
                <dt class="text-xs text-slate-500">ช่วงเปิดรับ</dt>
                <dd class="mt-0.5 font-medium text-slate-900">
                    {{ $project->project_start_register->format('d M Y, H:i') }}
                    <span class="text-slate-400">→</span>
                    {{ $project->project_end_register->format('d M Y, H:i') }}
                </dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500">รูปแบบ</dt>
                <dd class="mt-0.5 font-medium text-slate-900">
                    @if ($project->project_type === 'single')
                        เลือกได้ 1 เซสชัน
                    @else
                        เลือกได้หลายเซสชัน
                    @endif
                </dd>
            </div>
        @endif
        <div class="sm:col-span-2">
            @if ($project->project_register_today)
                <span class="inline-flex rounded bg-white px-2 py-0.5 text-xs text-slate-600 ring-1 ring-slate-200">ลงทะเบียนได้ในวันจัดอบรม</span>
            @else
                <span class="inline-flex rounded bg-white px-2 py-0.5 text-xs text-slate-600 ring-1 ring-slate-200">ไม่รับลงทะเบียนในวันจัดอบรม</span>
            @endif
        </div>
    </dl>
    <p class="mt-3 text-xs text-slate-600">
        @if ($project->project_type === 'single')
            เลือกเซสชันเดียวแล้วกด «ลงทะเบียน» ด้านล่าง
        @elseif ($project->project_type === 'multiple')
            เลือกทุกเซสชันที่ต้องการเข้าร่วม (ติ๊กหลายรายการได้)
        @endif
        @if ($project->project_type === 'multiple' && $registrationData['userRegistrations']->count() > 0)
            · สามารถเพิ่มเซสชันใหม่โดยไม่ต้องยกเลิกเดิม
        @endif
    </p>
</div>
