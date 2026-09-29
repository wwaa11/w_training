{{-- Shared compact markup helpers for create/edit project JS. Set $withRecordIds before include on edit. --}}
<script>
    window.HRD_PROJECT_FORM_WITH_IDS = @json($withRecordIds ?? false);

    window.hrdAfInputClass = 'hrd-af-input';
    window.hrdAfLabelClass = 'hrd-af-label';

    window.hrdToggleGroupModePanel = function(enabled) {
        const panel = document.getElementById('groupModePanel');
        const card = document.getElementById('groupAssignCard');
        const offField = document.getElementById('projectGroupAssignOff');
        if (panel) {
            panel.classList.toggle('hidden', !enabled);
            panel.hidden = !enabled;
            panel.setAttribute('aria-hidden', enabled ? 'false' : 'true');
        }
        if (card) {
            card.classList.toggle('border-slate-200', enabled);
            card.classList.toggle('bg-blue-50/40', enabled);
            card.classList.toggle('border-slate-200', !enabled);
            card.classList.toggle('bg-white', !enabled);
        }
        if (offField) offField.disabled = enabled;
        panel?.querySelectorAll('input[name="project_group_mode"]').forEach((el) => {
            el.disabled = !enabled;
        });
    };

    function hrdInitGroupAssignToggle() {
        const cb = document.getElementById('projectGroupAssign');
        if (!cb || cb.dataset.hrdGroupBound === '1') return;
        cb.dataset.hrdGroupBound = '1';
        cb.addEventListener('change', function() {
            window.hrdToggleGroupModePanel(this.checked);
        });
        window.hrdToggleGroupModePanel(cb.checked);
    }
    hrdInitGroupAssignToggle();
    document.addEventListener('DOMContentLoaded', hrdInitGroupAssignToggle);

    window.hrdSyncDateSummary = function(dateIndex) {
        const item = document.querySelector(`.date-item[data-date-index="${dateIndex}"]`);
        if (!item) return;
        const title = item.querySelector(`#dateTitle${dateIndex}`)?.value?.trim();
        const dt = item.querySelector(`#dateDateTime${dateIndex}`)?.value;
        const times = item.querySelectorAll('.time-item').length;
        const label = item.querySelector('.js-date-summary-label');
        const countEl = item.querySelector('.js-date-time-count');
        let text = title || 'ยังไม่ได้ตั้งชื่อวัน';
        if (dt) {
            text = `${dt} — ${text}`;
        }
        if (label) label.textContent = text;
        if (countEl) countEl.textContent = `${times} ช่วงเวลา`;
    };

    window.hrdSyncTimeSummary = function(dateIndex, timeIndex) {
        const item = document.querySelector(`#timesContainer${dateIndex} .time-item[data-time-index="${timeIndex}"]`);
        if (!item) return;
        const start = item.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_start]"]`)?.value;
        const end = item.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_end]"]`)?.value;
        const title = item.querySelector(`#timeTitle_${dateIndex}_${timeIndex}`)?.value?.trim();
        const el = item.querySelector('.js-time-summary');
        if (!el) return;
        const range = start && end ? `${start}–${end}` : 'กำหนดเวลา';
        el.textContent = title ? `${range} · ${title}` : range;
    };

    window.hrdExpandAllDates = function() {
        document.querySelectorAll('.hrd-date-collapse').forEach((el) => { el.open = true; });
    };

    window.hrdCollapseAllDates = function() {
        document.querySelectorAll('.hrd-date-collapse').forEach((el) => { el.open = false; });
    };

    window.buildDateCardHtml = function(dateIndex, existingDateId, openByDefault) {
        const idField = (window.HRD_PROJECT_FORM_WITH_IDS && existingDateId)
            ? `<input type="hidden" name="dates[${dateIndex}][id]" value="${existingDateId}">`
            : '';
        const openAttr = openByDefault ? 'open' : '';
        return `
        <details class="date-item hrd-date-collapse rounded-md border border-slate-200 bg-white" data-date-index="${dateIndex}" ${openAttr}>
            <summary class="flex items-center gap-2 border-b border-slate-100 bg-slate-50/80 px-3 py-2.5">
                <span class="js-date-badge-num flex h-6 w-6 shrink-0 items-center justify-center rounded bg-blue-600 text-xs font-bold text-white">${dateIndex + 1}</span>
                <div class="min-w-0 flex-1 text-left">
                    <p class="js-date-summary-label truncate text-sm font-semibold text-slate-900">วันที่ ${dateIndex + 1}</p>
                    <p class="js-date-time-count text-xs text-slate-500">0 ช่วงเวลา</p>
                </div>
                <i class="js-date-chevron fas fa-chevron-down shrink-0 text-xs text-blue-600 transition-transform"></i>
                <button type="button" onclick="event.preventDefault(); event.stopPropagation(); removeDate(${dateIndex})" class="hrd-af-btn-remove shrink-0" aria-label="ลบวันที่">
                    <i class="fas fa-trash text-xs"></i><span class="hidden sm:inline">ลบวัน</span>
                </button>
            </summary>
            <div class="space-y-3 p-3">
                ${idField}
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div>
                        <label class="${window.hrdAfLabelClass}">วันที่ (ปฏิทิน) *</label>
                        <input type="date" name="dates[${dateIndex}][date_datetime]" id="dateDateTime${dateIndex}" class="${window.hrdAfInputClass}" required onchange="updateDateTitle(${dateIndex}); hrdSyncDateSummary(${dateIndex})">
                    </div>
                    <div>
                        <label class="${window.hrdAfLabelClass}">ชื่อวันที่ *</label>
                        <input type="text" name="dates[${dateIndex}][date_title]" id="dateTitle${dateIndex}" class="${window.hrdAfInputClass}" placeholder="เช่น วันแรก" required oninput="hrdSyncDateSummary(${dateIndex})">
                    </div>
                    <div>
                        <label class="${window.hrdAfLabelClass}">สถานที่</label>
                        <input type="text" name="dates[${dateIndex}][date_location]" class="${window.hrdAfInputClass}" placeholder="ห้อง / อาคาร">
                    </div>
                    <div>
                        <label class="${window.hrdAfLabelClass}">รายละเอียดวัน</label>
                        <input type="text" name="dates[${dateIndex}][date_detail]" class="${window.hrdAfInputClass}">
                    </div>
                </div>
                <div class="border-t border-slate-200 pt-3">
                    <div class="mb-2 flex items-center justify-between gap-2">
                        <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">ช่วงเวลา</p>
                        <button type="button" onclick="addTime(${dateIndex})" class="hrd-af-btn-add hrd-af-btn-add-sm">
                            <i class="fas fa-plus"></i> เพิ่มช่วงเวลา
                        </button>
                    </div>
                    <div id="timesContainer${dateIndex}" class="space-y-2"></div>
                </div>
            </div>
        </details>`;
    };

    window.buildTimeCardHtml = function(dateIndex, timeIndex, existingTimeId) {
        const idField = (window.HRD_PROJECT_FORM_WITH_IDS && existingTimeId)
            ? `<input type="hidden" name="dates[${dateIndex}][times][${timeIndex}][id]" value="${existingTimeId}">`
            : '';
        const openTime = timeIndex === 0 ? 'open' : '';
        return `
        <details class="time-item hrd-time-collapse rounded-md border border-slate-200 bg-slate-50/60" data-time-index="${timeIndex}" ${openTime}>
            <summary class="flex items-center justify-between gap-2 px-3 py-2">
                <span class="js-time-summary text-xs font-semibold text-slate-700">ช่วงเวลา ${timeIndex + 1}</span>
                <button type="button" onclick="event.preventDefault(); event.stopPropagation(); removeTime(this)" class="hrd-af-btn-remove !py-1" aria-label="ลบช่วงเวลา"><i class="fas fa-trash text-xs"></i><span>ลบ</span></button>
            </summary>
            <div class="space-y-2 border-t border-slate-200/80 px-3 pb-3 pt-2">
                ${idField}
                <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                    <div class="col-span-2 sm:col-span-2">
                        <label class="${window.hrdAfLabelClass}">ชื่อช่วง *</label>
                        <input type="text" name="dates[${dateIndex}][times][${timeIndex}][time_title]" class="${window.hrdAfInputClass}" required id="timeTitle_${dateIndex}_${timeIndex}" oninput="hrdSyncTimeSummary(${dateIndex}, ${timeIndex})">
                    </div>
                    <div>
                        <label class="${window.hrdAfLabelClass}">เริ่ม *</label>
                        <input type="time" name="dates[${dateIndex}][times][${timeIndex}][time_start]" class="${window.hrdAfInputClass}" value="08:00" required onchange="updateTimeTitle(${dateIndex}, ${timeIndex}); hrdSyncTimeSummary(${dateIndex}, ${timeIndex})">
                    </div>
                    <div>
                        <label class="${window.hrdAfLabelClass}">สิ้นสุด *</label>
                        <input type="time" name="dates[${dateIndex}][times][${timeIndex}][time_end]" class="${window.hrdAfInputClass}" value="17:00" required onchange="updateTimeTitle(${dateIndex}, ${timeIndex}); hrdSyncTimeSummary(${dateIndex}, ${timeIndex})">
                    </div>
                </div>
                <div>
                    <label class="${window.hrdAfLabelClass}">รายละเอียด</label>
                    <input type="text" name="dates[${dateIndex}][times][${timeIndex}][time_detail]" class="${window.hrdAfInputClass}">
                </div>
                <div class="flex flex-wrap items-end gap-3 rounded-md border border-slate-200 bg-white p-2">
                    <label class="flex items-center gap-2 text-xs text-slate-600">
                        <input type="hidden" name="dates[${dateIndex}][times][${timeIndex}][time_limit]" value="0">
                        <input type="checkbox" name="dates[${dateIndex}][times][${timeIndex}][time_limit]" value="1" class="rounded border-slate-300 text-blue-600" onchange="toggleMaxParticipants(this, '${dateIndex}_${timeIndex}')">
                        จำกัดจำนวน
                    </label>
                    <div class="min-w-[6rem] flex-1">
                        <label class="${window.hrdAfLabelClass}">สูงสุด</label>
                        <input type="number" name="dates[${dateIndex}][times][${timeIndex}][time_max]" min="0" class="${window.hrdAfInputClass} bg-slate-100" value="1" disabled id="maxParticipants_${dateIndex}_${timeIndex}">
                    </div>
                </div>
            </div>
        </details>`;
    };

    window.buildLinkCardHtml = function(linkIndex, existingLinkId) {
        const idField = (window.HRD_PROJECT_FORM_WITH_IDS && existingLinkId)
            ? `<input type="hidden" name="links[${linkIndex}][id]" value="${existingLinkId}">`
            : '';
        return `
        <details class="link-item rounded-md border border-slate-200 bg-white" ${linkIndex === 0 ? 'open' : ''}>
            <summary class="flex cursor-pointer items-center justify-between border-b border-slate-100 bg-slate-50/80 px-3 py-2 list-none">
                <span class="text-sm font-semibold text-slate-900">ลิงก์ ${linkIndex + 1}</span>
                <button type="button" onclick="event.preventDefault(); event.stopPropagation(); removeLink(this)" class="hrd-af-btn-remove" aria-label="ลบลิงก์"><i class="fas fa-trash text-xs"></i><span>ลบ</span></button>
            </summary>
            <div class="space-y-2 p-3">
                ${idField}
                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                    <div>
                        <label class="${window.hrdAfLabelClass}">ชื่อลิงก์</label>
                        <input type="text" name="links[${linkIndex}][link_name]" class="${window.hrdAfInputClass}">
                    </div>
                    <div>
                        <label class="${window.hrdAfLabelClass}">URL</label>
                        <input type="url" name="links[${linkIndex}][link_url]" class="${window.hrdAfInputClass}" placeholder="https://">
                    </div>
                </div>
                <div class="rounded-md border border-slate-200 bg-slate-50/60 p-2">
                    <label class="flex items-center gap-2 text-xs text-slate-600">
                        <input type="hidden" name="links[${linkIndex}][link_limit]" value="0">
                        <input type="checkbox" name="links[${linkIndex}][link_limit]" value="1" class="rounded border-slate-300 text-blue-600" onchange="toggleLinkTimeFields(this, '${linkIndex}')">
                        จำกัดเวลาเข้าถึง
                    </label>
                    <div class="mt-2 grid grid-cols-2 gap-2">
                        <div>
                            <label class="${window.hrdAfLabelClass}">ตั้งแต่</label>
                            <input type="time" name="links[${linkIndex}][link_time_start]" class="${window.hrdAfInputClass} bg-slate-100" disabled id="linkTimeStart_${linkIndex}">
                        </div>
                        <div>
                            <label class="${window.hrdAfLabelClass}">ถึง</label>
                            <input type="time" name="links[${linkIndex}][link_time_end]" class="${window.hrdAfInputClass} bg-slate-100" disabled id="linkTimeEnd_${linkIndex}">
                        </div>
                    </div>
                </div>
            </div>
        </details>`;
    };
</script>
