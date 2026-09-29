<div class="hrd-ov-export-layout">
    <div class="hrd-ov-export-block">
        <p class="hrd-ov-export-block__title"><i class="fas fa-user-check"></i> ผู้ลงทะเบียน</p>
        <a class="hrd-ov-export-card hrd-ov-export-card--all group" href="{{ route('hrd.admin.export.excel.all_date', $project->id) }}">
            <div class="hrd-ov-export-card__stripe" aria-hidden="true"></div>
            <div class="hrd-ov-export-card__head">
                <span class="hrd-ov-export-card__icon"><i class="fas fa-users"></i></span>
                <span class="hrd-ov-export-card__format">Excel</span>
            </div>
            <div class="hrd-ov-export-card__body">
                <p class="hrd-ov-export-card__title">รายงานผู้ลงทะเบียนทั้งหมด</p>
                <p class="hrd-ov-export-card__desc">ทุกวันและทุกช่วงเวลาในโปรเจกต์</p>
            </div>
            <div class="hrd-ov-export-card__foot">
                <span>ดาวน์โหลด</span>
                <i class="fas fa-arrow-down transition-transform group-hover:translate-y-0.5"></i>
            </div>
        </a>
    </div>

    <div class="hrd-ov-export-block">
        <p class="hrd-ov-export-block__title"><i class="fas fa-chalkboard-teacher"></i> วิทยากร</p>
        <a class="hrd-ov-export-card hrd-ov-export-card--lectures group" href="{{ route('hrd.admin.export.excel.lectures', $project->id) }}">
            <div class="hrd-ov-export-card__stripe" aria-hidden="true"></div>
            <div class="hrd-ov-export-card__head">
                <span class="hrd-ov-export-card__icon"><i class="fas fa-chalkboard-teacher"></i></span>
                <span class="hrd-ov-export-card__format">Excel</span>
            </div>
            <div class="hrd-ov-export-card__body">
                <p class="hrd-ov-export-card__title">รายชื่อวิทยากรทั้งหมด</p>
                <p class="hrd-ov-export-card__desc">รวมทุกวันที่ในโปรเจกต์</p>
            </div>
            <div class="hrd-ov-export-card__foot">
                <span>ดาวน์โหลด</span>
                <i class="fas fa-arrow-down transition-transform group-hover:translate-y-0.5"></i>
            </div>
        </a>
    </div>

    <div class="hrd-ov-export-block hrd-ov-export-block--wide space-y-2">
        <p class="hrd-ov-export-block__title"><i class="fas fa-building"></i> ฟอร์มและระบบภายนอก</p>
        <div class="hrd-ov-export-grid hrd-ov-export-grid--2">
            <a class="hrd-ov-export-card hrd-ov-export-card--dbd group" href="{{ route('hrd.admin.export.excel.dbd', $project->id) }}">
                <div class="hrd-ov-export-card__stripe" aria-hidden="true"></div>
                <div class="hrd-ov-export-card__head">
                    <span class="hrd-ov-export-card__icon"><i class="fas fa-file-excel"></i></span>
                    <span class="hrd-ov-export-card__format">Excel</span>
                </div>
                <div class="hrd-ov-export-card__body">
                    <p class="hrd-ov-export-card__title">แบบฟอร์มกรมพัฒน์ (DBD)</p>
                    <p class="hrd-ov-export-card__desc">รูปแบบรายงานตามที่กรมพัฒน์กำหนด</p>
                </div>
                <div class="hrd-ov-export-card__foot">
                    <span>ดาวน์โหลด</span>
                    <i class="fas fa-arrow-down transition-transform group-hover:translate-y-0.5"></i>
                </div>
            </a>

            @if ($project->dms_id !== null)
                <a class="hrd-ov-export-card hrd-ov-export-card--dms group" href="https://pr9web.praram9.com/dms/training/download-pdf/{{ $project->dms_id }}" target="_blank" rel="noopener">
                    <div class="hrd-ov-export-card__stripe" aria-hidden="true"></div>
                    <div class="hrd-ov-export-card__head">
                        <span class="hrd-ov-export-card__icon"><i class="fas fa-file-pdf"></i></span>
                        <span class="hrd-ov-export-card__format">PDF</span>
                    </div>
                    <div class="hrd-ov-export-card__body">
                        <p class="hrd-ov-export-card__title">ใบบันทึกฝึกอบรมภาคอิสระ</p>
                        <p class="hrd-ov-export-card__desc">เปิดจากระบบ DMS</p>
                    </div>
                    <div class="hrd-ov-export-card__foot">
                        <span>เปิดลิงก์</span>
                        <i class="fas fa-external-link-alt"></i>
                    </div>
                </a>
            @endif
        </div>

        <div class="hrd-ov-onebook-panel">
            <div class="hrd-ov-onebook-panel__top">
                <div class="flex min-w-0 items-start gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-violet-200 text-violet-800"><i class="fas fa-book"></i></span>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-violet-950">รายงาน Onebook</p>
                        <p class="mt-0.5 text-xs text-violet-800/80">ดาวน์โหลด Excel และตั้งค่าเวลาพักก่อนส่งออก</p>
                    </div>
                </div>
                <a class="hrd-ov-onebook-download shrink-0" href="{{ route('hrd.admin.export.excel.onebook', $project->id) }}">
                    <i class="fas fa-download"></i> ดาวน์โหลด Excel
                </a>
            </div>
            <div class="hrd-ov-onebook-panel__settings">
                <form class="flex flex-wrap items-end gap-3" action="{{ route('hrd.admin.export.hours.onebook') }}" method="POST">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                    <div class="min-w-[10rem] flex-1 sm:max-w-xs">
                        <label class="mb-1 block text-xs font-semibold text-violet-900" for="onebookSkipHours">
                            Break time (ชั่วโมง) <span class="text-red-500">*</span>
                        </label>
                        <div class="flex rounded-md border border-violet-200 bg-violet-50/50 focus-within:border-violet-400 focus-within:ring-2 focus-within:ring-violet-200">
                            <input class="hrd-af-input !border-0 !bg-transparent !shadow-none focus:!ring-0" id="onebookSkipHours" min="0" type="number" name="input" value="{{ $project->onebook?->skip_hours }}" placeholder="0">
                            <span class="flex items-center px-3 text-xs font-medium text-violet-700">hrs</span>
                        </div>
                    </div>
                    <button class="inline-flex items-center gap-2 rounded-md border border-violet-300 bg-white px-4 py-2 text-sm font-semibold text-violet-800 hover:bg-violet-50" type="submit">
                        <i class="fas fa-save text-violet-600"></i> บันทึกค่า Break
                    </button>
                </form>
                <p class="mt-2 flex items-start gap-1.5 text-[11px] leading-snug text-violet-900/70">
                    <i class="fas fa-info-circle mt-0.5 shrink-0 text-violet-500"></i>
                    ระบบจะหักชั่วโมงนี้จากเวลาฝึกอบรมเมื่อสร้างรายงาน Onebook
                </p>
            </div>
        </div>
    </div>
</div>
