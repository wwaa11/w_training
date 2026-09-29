<div class="admin-manual-mockup pointer-events-none select-none" aria-hidden="true">
    <p class="mb-2 text-xs font-medium text-slate-500">ตัวอย่างหน้าจอ — จัดการกลุ่ม (แอดมิน)</p>

    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
        <h2 class="text-base font-semibold text-slate-900">
            <i class="fas fa-sliders-h mr-2 text-blue-600" aria-hidden="true"></i>
            โหมดการจัดกลุ่ม
        </h2>
        <p class="mt-1 text-sm text-slate-600">กลุ่มแยกตามวันและช่วงเวลาลงทะเบียน</p>
        <div class="mt-4 grid gap-3 md:grid-cols-2">
            <div class="rounded-xl border-2 border-slate-200 p-4">
                <div class="flex items-start gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
                        <i class="fas fa-user-cog" aria-hidden="true"></i>
                    </span>
                    <div>
                        <p class="font-semibold text-slate-900">กำหนดเอง (Manual)</p>
                        <p class="mt-1 text-xs text-slate-600">มอบหมายสมาชิก / นำเข้า Excel</p>
                    </div>
                </div>
            </div>
            <div class="rounded-xl border-2 border-violet-500 bg-violet-50/50 p-4 ring-2 ring-violet-200">
                <div class="flex items-start gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-violet-100 text-violet-700">
                        <i class="fas fa-magic" aria-hidden="true"></i>
                    </span>
                    <div>
                        <p class="font-semibold text-slate-900">อัตโนมัติ (Auto)</p>
                        <p class="mt-1 text-xs text-slate-600">จัดเมื่อลงทะเบียนในรอบนั้น</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="mt-4 overflow-hidden rounded-2xl border-2 border-blue-200 bg-white shadow-sm">
        <div class="flex flex-wrap items-center gap-3 border-b border-blue-100 bg-blue-50/80 px-4 py-3">
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">1</span>
            <h2 class="text-base font-semibold text-slate-900">เลือกวันและช่วงเวลา</h2>
        </div>
        <div class="p-4">
            <label class="mb-2 block text-sm font-medium text-slate-700">ช่วงที่ต้องการจัดกลุ่ม</label>
            <select class="w-full max-w-2xl rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800" disabled tabindex="-1">
                <option>วันอบรมที่ 1 · 09:00 – 12:00</option>
            </select>
            <div class="mt-3 flex flex-wrap gap-2 text-sm">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-3 py-1 font-medium text-blue-900">
                    <i class="fas fa-user-check text-xs" aria-hidden="true"></i> ลงทะเบียน 24
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 font-medium text-emerald-900">
                    <i class="fas fa-users text-xs" aria-hidden="true"></i> จัดกลุ่มแล้ว 20
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-violet-100 px-3 py-1 font-medium text-violet-900">
                    <i class="fas fa-layer-group text-xs" aria-hidden="true"></i> กลุ่ม 4
                </span>
            </div>
        </div>
    </section>

    <div class="mt-4 grid gap-4 lg:grid-cols-2">
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50 px-4 py-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-600 text-sm font-bold text-white">2</span>
                <h2 class="text-sm font-semibold text-slate-900">สร้างกลุ่มในช่วงนี้</h2>
            </div>
            <div class="p-4">
                <button class="w-full rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white" type="button" tabindex="-1">
                    <i class="fas fa-plus mr-2" aria-hidden="true"></i>สร้างกลุ่ม
                </button>
            </div>
        </section>
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50 px-4 py-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-violet-600 text-sm font-bold text-white">3</span>
                <h2 class="text-sm font-semibold text-slate-900">จัดกลุ่มอัตโนมัติ</h2>
            </div>
            <div class="p-4">
                <button class="w-full rounded-xl bg-violet-600 px-4 py-2.5 text-sm font-medium text-white" type="button" tabindex="-1">
                    <i class="fas fa-dice mr-2" aria-hidden="true"></i>สุ่มจัดกลุ่มใหม่ทั้งช่วง
                </button>
            </div>
        </section>
    </div>
</div>
