<div class="admin-manual-mockup pointer-events-none select-none" aria-hidden="true">
    <p class="mb-2 text-xs font-medium text-slate-500">ตัวอย่างหน้าจอ — การจัดการที่นั่ง (แอดมิน)</p>

    <div class="flex flex-wrap gap-2 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <button class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-3 py-2 text-sm font-semibold text-white" type="button" tabindex="-1">
            <i class="fas fa-sync-alt" aria-hidden="true"></i>รีเฟรช
        </button>
        <button class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-3 py-2 text-sm font-semibold text-white" type="button" tabindex="-1">
            <i class="fas fa-cogs" aria-hidden="true"></i>จัดอัตโนมัติ
        </button>
        <button class="inline-flex items-center gap-2 rounded-xl bg-violet-600 px-3 py-2 text-sm font-semibold text-white" type="button" tabindex="-1">
            <i class="fas fa-download" aria-hidden="true"></i>ส่งออก
        </button>
    </div>

    <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 bg-slate-50 px-4 py-3">
            <p class="text-sm font-semibold text-slate-900">วันอบรมที่ 1 · 09:00 – 12:00</p>
            <p class="text-xs text-slate-500">จัดที่นั่งแยกตามช่วงเวลานี้</p>
        </div>
        <div class="grid gap-4 p-4 md:grid-cols-2">
            <div>
                <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">ที่นั่งที่จัดแล้ว</p>
                <div class="rounded-lg bg-slate-50 p-3 text-sm text-slate-700">
                    <div class="flex items-center justify-between border-b border-slate-200 py-2">
                        <span><span class="font-semibold text-emerald-700">A-01</span> · 650001</span>
                        <span class="text-red-600"><i class="fas fa-times" aria-hidden="true"></i></span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span><span class="font-semibold text-emerald-700">A-02</span> · 650002</span>
                        <span class="text-red-600"><i class="fas fa-times" aria-hidden="true"></i></span>
                    </div>
                </div>
            </div>
            <div>
                <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">รอจัดที่นั่ง</p>
                <div class="rounded-lg bg-slate-50 p-3 text-sm text-slate-700">
                    <div class="flex items-center justify-between py-2">
                        <span>650003 · ลงทะเบียนแล้ว</span>
                        <span class="text-blue-600"><i class="fas fa-plus" aria-hidden="true"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t border-slate-100 px-4 py-3">
            <button class="rounded-lg bg-gradient-to-r from-red-500 to-red-600 px-3 py-2 text-xs font-semibold text-white" type="button" tabindex="-1">
                ล้างที่นั่งทั้งหมด (ช่วงนี้)
            </button>
        </div>
    </div>
</div>
