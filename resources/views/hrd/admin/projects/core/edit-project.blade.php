@extends("layouts.hrd")

@section("content")
    @component("hrd.admin.projects.partials.form-page-shell", [
        "backUrl" => route("hrd.admin.projects.show", $project->id),
        "pageTitle" => "แก้ไขโปรเจกต์",
        "headerActions" => '<button type="submit" form="projectForm" class="hrd-af-btn-primary !px-3 !py-2"><i class="fas fa-save"></i><span class="hidden sm:inline">บันทึก</span></button>',
    ])
        <form class="space-y-3" id="projectForm" action="{{ route("hrd.admin.projects.update", $project->id) }}" method="POST">
            @csrf

            @component("hrd.admin.projects.partials.form-section", [
                "sectionNumber" => 1,
                "title" => "สถานะโปรเจกต์",
                "description" => "เปิดหรือปิดการแสดงต่อผู้ใช้",
            ])
                <label class="flex cursor-pointer items-start gap-3 rounded-md border border-slate-200 bg-blue-50/50 px-3 py-2.5">
                    <input class="mt-0.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500" type="checkbox" name="project_active" value="1" @checked(old("project_active", $project->project_active))>
                    <span>
                        <span class="block text-sm font-semibold text-slate-900">โปรเจกต์ใช้งาน</span>
                        <span class="mt-0.5 block text-xs text-slate-500">ปิดเพื่อซ่อนจากรายการลงทะเบียน</span>
                    </span>
                </label>
            @endcomponent

            @component("hrd.admin.projects.partials.form-section", [
                "sectionNumber" => 2,
                "title" => "ข้อมูลพื้นฐาน",
                "description" => $project->project_name,
            ])
                @include("hrd.admin.projects.partials.form-basic-fields", ["project" => $project])
            @endcomponent

            @component("hrd.admin.projects.partials.form-section", [
                "sectionNumber" => 3,
                "title" => "วันและช่วงเวลา",
                "description" => "แต่ละวันย่อ/ขยายได้ — เหมาะกับโปรเจกต์หลายวัน",
            ])
                <div class="mb-2 flex flex-wrap gap-2">
                    <button class="hrd-af-btn-secondary !py-1.5 !text-xs" type="button" onclick="hrdExpandAllDates()"><i class="fas fa-expand-alt"></i> ขยายทั้งหมด</button>
                    <button class="hrd-af-btn-secondary !py-1.5 !text-xs" type="button" onclick="hrdCollapseAllDates()"><i class="fas fa-compress-alt"></i> ย่อทั้งหมด</button>
                </div>
                <div class="space-y-2" id="datesContainer"></div>
                <button class="hrd-af-btn-add mt-3 w-full sm:w-auto" type="button" onclick="addDate()">
                    <i class="fas fa-plus"></i> เพิ่มวันที่
                </button>
            @endcomponent

            @component("hrd.admin.projects.partials.form-section", [
                "sectionNumber" => 4,
                "title" => "ลิงก์ทรัพยากร",
                "description" => "ไม่บังคับ",
            ])
                <div class="space-y-2" id="linksContainer"></div>
                <button class="hrd-af-btn-add mt-3 w-full sm:w-auto" type="button" onclick="addLink()">
                    <i class="fas fa-plus"></i> เพิ่มลิงก์
                </button>
            @endcomponent

            <div class="sticky bottom-0 z-10 -mx-1 flex flex-wrap justify-end gap-2 border-t border-slate-200 bg-white/95 px-1 py-3 backdrop-blur-sm">
                <a class="hrd-af-btn-secondary" href="{{ route("hrd.admin.projects.show", $project->id) }}">ยกเลิก</a>
                <button class="hrd-af-btn-primary" type="submit">
                    <i class="fas fa-save"></i> อัปเดตโปรเจกต์
                </button>
            </div>
        </form>
    @endcomponent
@endsection

@section("scripts")
    @include("hrd.admin.projects.partials.form-swal")
    @include("hrd.admin.projects.partials.form-dynamic-ui", ["withRecordIds" => true])
    <script>
        let dateIndex = 0;
        let linkIndex = 0;
        const editData = @json($editData);

        function formatThaiDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            const day = date.getDate();
            const month = editData.thaiMonths[date.getMonth()];
            const year = date.getFullYear() + 543;
            return `${day} ${month} ${year}`;
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadExistingData();
            if (@json($project->project_type)) {
                showProjectTypeHint(@json($project->project_type));
            }
        });

        function showProjectTypeHint(projectType) {
            const hintDiv = document.getElementById('projectTypeHint');
            let hintText = '';
            let hintClass = '';
            switch (projectType) {
                case 'single':
                    hintText = 'ผู้ใช้ลงทะเบียนได้ 1 ครั้ง';
                    hintClass = 'rounded-md border border-slate-200 bg-blue-50 px-2 py-1.5 text-slate-900';
                    break;
                case 'multiple':
                    hintText = 'ผู้ใช้ลงทะเบียนได้หลายเซสชัน';
                    hintClass = 'rounded-md border border-cyan-200 bg-cyan-50 px-2 py-1.5 text-cyan-900';
                    break;
                case 'attendance':
                    hintText = 'ไม่ต้องลงทะเบียน — เช็คอินเข้าร่วมได้เลย';
                    hintClass = 'rounded-md border border-sky-200 bg-sky-50 px-2 py-1.5 text-sky-900';
                    break;
                default:
                    hintDiv.classList.add('hidden');
                    return;
            }
            hintDiv.innerHTML = `<div class="${hintClass}">${hintText}</div>`;
            hintDiv.classList.remove('hidden');
        }

        document.getElementById('projectForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const requiredFields = this.querySelectorAll('[required]');
            let missingFields = [];
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    const label = field.closest('div')?.querySelector('label')?.textContent?.replace('*', '').trim();
                    if (label) missingFields.push(label);
                }
            });
            if (missingFields.length > 0) {
                window.hrdAdminConfirm({
                    title: 'ข้อมูลไม่ครบถ้วน',
                    html: 'กรุณากรอก: ' + missingFields.join(', '),
                    icon: 'warning',
                    showCancelButton: false,
                    confirmButtonColor: window.HRD_ADMIN_SWAL.warningColor,
                    confirmText: 'ตกลง',
                });
                return;
            }
            window.hrdAdminConfirm({
                title: 'ยืนยันการอัปเดตโครงการ',
                text: 'คุณต้องการอัปเดตโครงการนี้ใช่หรือไม่?',
                icon: 'question',
                confirmText: 'ใช่, บันทึก',
            }).then((result) => { if (result.isConfirmed) this.submit(); });
        });

        @if ($errors->any())
            Swal.fire({ title: 'เกิดข้อผิดพลาด', html: @json(implode('<br>', $errors->all())), icon: 'error', confirmButtonText: 'ตกลง', confirmButtonColor: window.HRD_ADMIN_SWAL.dangerColor });
        @endif

        function loadExistingData() {
            if (editData.dates?.length > 0) {
                editData.dates.forEach((dateData, index) => {
                    addDate(dateData.id);
                    loadDateData(index, dateData);
                });
            } else {
                addDate();
            }
            if (editData.links?.length > 0) {
                editData.links.forEach((linkData, index) => {
                    addLink(linkData.id);
                    loadLinkData(index, linkData);
                });
            } else {
                addLink();
            }

            setTimeout(() => {
                document.querySelectorAll('.hrd-date-collapse').forEach((el, i) => {
                    el.open = i === 0;
                });
                document.querySelectorAll('.date-item').forEach((el) => {
                    const idx = el.dataset.dateIndex;
                    if (idx !== undefined) window.hrdSyncDateSummary(idx);
                });
            }, 400);
        }

        function loadDateData(index, dateData) {
            setTimeout(() => {
                const dateItem = document.querySelector(`[data-date-index="${index}"]`);
                if (!dateItem) return;
                const dateTitleInput = dateItem.querySelector(`#dateTitle${index}`);
                const dateTimeInput = dateItem.querySelector(`#dateDateTime${index}`);
                if (dateTitleInput) dateTitleInput.value = dateData.date_title || '';
                if (dateData.date_datetime && dateTimeInput) dateTimeInput.value = dateData.date_datetime;
                const locationInput = dateItem.querySelector(`input[name="dates[${index}][date_location]"]`);
                const detailInput = dateItem.querySelector(`input[name="dates[${index}][date_detail]"]`);
                if (locationInput) locationInput.value = dateData.date_location || '';
                if (detailInput) detailInput.value = dateData.date_detail || '';

                const timesContainer = document.getElementById(`timesContainer${index}`);
                if (!timesContainer) return;
                timesContainer.innerHTML = '';
                if (dateData.times?.length > 0) {
                    dateData.times.forEach((timeData, timeIndex) => {
                        addTime(index, timeData.id);
                        setTimeout(() => loadTimeData(index, timeIndex, timeData), 80 * (timeIndex + 1));
                    });
                } else {
                    addTime(index);
                }
                window.hrdSyncDateSummary(index);
            }, 30);
        }

        function loadTimeData(dateIndex, timeIndex, timeData) {
            const timeItem = document.querySelector(`#timesContainer${dateIndex} .time-item:nth-child(${timeIndex + 1})`);
            if (!timeItem) return;
            const titleInput = timeItem.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_title]"]`);
            const startInput = timeItem.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_start]"]`);
            const endInput = timeItem.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_end]"]`);
            const maxInput = timeItem.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_max]"]`);
            const detailInput = timeItem.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_detail]"]`);
            if (titleInput) titleInput.value = timeData.time_title || '';
            if (startInput) startInput.value = convertTo24HourFormat(timeData.time_start || '08:00');
            if (endInput) endInput.value = convertTo24HourFormat(timeData.time_end || '17:00');
            if (maxInput) maxInput.value = timeData.time_max ?? 1;
            if (detailInput) detailInput.value = timeData.time_detail || '';
            if (timeData.time_limit) {
                const checkbox = timeItem.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_limit]"][type="checkbox"]`);
                if (checkbox) {
                    checkbox.checked = true;
                    toggleMaxParticipants(checkbox, `${dateIndex}_${timeIndex}`);
                }
            }
            updateTimeTitle(dateIndex, timeIndex);
            window.hrdSyncTimeSummary(dateIndex, timeIndex);
            window.hrdSyncDateSummary(dateIndex);
        }

        function convertTo24HourFormat(timeString) {
            if (!timeString || typeof timeString !== 'string') return '08:00';
            timeString = timeString.trim().toUpperCase();
            if (timeString.includes('T') && (timeString.includes('Z') || timeString.includes('+') || timeString.includes('-'))) {
                try {
                    const date = new Date(timeString);
                    if (!isNaN(date.getTime())) {
                        return `${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}`;
                    }
                } catch (e) {}
            }
            if (/^\d{1,2}:\d{2}$/.test(timeString)) {
                const [hours, minutes] = timeString.split(':');
                const hour = parseInt(hours, 10);
                const minute = parseInt(minutes, 10);
                if (hour >= 0 && hour <= 23 && minute >= 0 && minute <= 59) {
                    return `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
                }
            }
            if (timeString.includes('AM') || timeString.includes('PM')) {
                const timeMatch = timeString.match(/(\d{1,2}):(\d{2})\s*(AM|PM)/);
                if (timeMatch) {
                    let hours = parseInt(timeMatch[1], 10);
                    const minutes = parseInt(timeMatch[2], 10);
                    const period = timeMatch[3];
                    if (period === 'PM' && hours !== 12) hours += 12;
                    else if (period === 'AM' && hours === 12) hours = 0;
                    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
                }
            }
            return '08:00';
        }

        function loadLinkData(index, linkData) {
            setTimeout(() => {
                const linkItems = document.querySelectorAll('.link-item');
                const linkItem = linkItems[index];
                if (!linkItem) return;
                const nameInput = linkItem.querySelector(`input[name="links[${index}][link_name]"]`);
                const urlInput = linkItem.querySelector(`input[name="links[${index}][link_url]"]`);
                const startInput = linkItem.querySelector(`input[name="links[${index}][link_time_start]"]`);
                const endInput = linkItem.querySelector(`input[name="links[${index}][link_time_end]"]`);
                const limitCheckbox = linkItem.querySelector(`input[name="links[${index}][link_limit]"][type="checkbox"]`);
                if (nameInput) nameInput.value = linkData.link_name || '';
                if (urlInput) urlInput.value = linkData.link_url || '';
                if (linkData.link_limit && limitCheckbox) {
                    limitCheckbox.checked = true;
                    toggleLinkTimeFields(limitCheckbox, index);
                    setTimeout(() => {
                        if (startInput && linkData.link_time_start) startInput.value = linkData.link_time_start;
                        if (endInput && linkData.link_time_end) endInput.value = linkData.link_time_end;
                    }, 10);
                }
            }, 30);
        }

        function addDate(existingDateId = null) {
            const container = document.getElementById('datesContainer');
            const openByDefault = container.children.length === 0;
            container.insertAdjacentHTML('beforeend', window.buildDateCardHtml(dateIndex, existingDateId, openByDefault));
            window.hrdSyncDateSummary(dateIndex);
            dateIndex++;
        }

        function updateDateTitle(dateIndex) {
            const dateTimeInput = document.getElementById(`dateDateTime${dateIndex}`);
            const dateTitleInput = document.getElementById(`dateTitle${dateIndex}`);
            if (dateTimeInput?.value && dateTitleInput) {
                dateTitleInput.value = formatThaiDate(dateTimeInput.value);
            }
        }

        function updateTimeTitle(dateIndex, timeIndex) {
            const timeStartInput = document.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_start]"]`);
            const timeEndInput = document.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_end]"]`);
            const timeTitleInput = document.getElementById(`timeTitle_${dateIndex}_${timeIndex}`);
            if (timeStartInput && timeEndInput && timeTitleInput && timeStartInput.value && timeEndInput.value) {
                timeTitleInput.value = `${timeStartInput.value} - ${timeEndInput.value}`;
            }
        }

        function removeDate(index) {
            const dateItem = document.querySelector(`[data-date-index="${index}"]`);
            if (!dateItem) return;
            window.hrdAdminConfirmDelete('ลบวันนี้และช่วงเวลาทั้งหมดในวันนี้', () => {
                dateItem.remove();
                updateDateRowNumbers();
            });
        }

        function updateDateRowNumbers() {
            document.querySelectorAll('.date-item').forEach((item, index) => {
                const badge = item.querySelector('.js-date-badge-num');
                if (badge) badge.textContent = index + 1;
                const idx = item.dataset.dateIndex;
                if (idx !== undefined) window.hrdSyncDateSummary(idx);
            });
        }

        function addTime(dateIndex, existingTimeId = null) {
            const container = document.getElementById(`timesContainer${dateIndex}`);
            const timeIndex = container.children.length;
            container.insertAdjacentHTML('beforeend', window.buildTimeCardHtml(dateIndex, timeIndex, existingTimeId));
            updateTimeTitle(dateIndex, timeIndex);
            window.hrdSyncTimeSummary(dateIndex, timeIndex);
            window.hrdSyncDateSummary(dateIndex);
        }

        function toggleMaxParticipants(checkbox, id) {
            const maxParticipantsField = document.getElementById(`maxParticipants_${id}`);
            if (!maxParticipantsField) return;
            maxParticipantsField.disabled = !checkbox.checked;
            maxParticipantsField.classList.toggle('bg-slate-100', !checkbox.checked);
            maxParticipantsField.classList.toggle('bg-white', checkbox.checked);
        }

        function removeTime(button) {
            const item = button.closest('.time-item');
            if (!item) return;
            const dateIndex = item.closest('.date-item')?.dataset?.dateIndex;
            window.hrdAdminConfirmDelete('ลบช่วงเวลานี้?', () => {
                item.remove();
                if (dateIndex !== undefined) window.hrdSyncDateSummary(dateIndex);
            });
        }

        function addLink(existingLinkId = null) {
            const container = document.getElementById('linksContainer');
            container.insertAdjacentHTML('beforeend', window.buildLinkCardHtml(linkIndex, existingLinkId));
            linkIndex++;
        }

        function toggleLinkTimeFields(checkbox, index) {
            const startField = document.getElementById(`linkTimeStart_${index}`);
            const endField = document.getElementById(`linkTimeEnd_${index}`);
            if (!startField || !endField) return;
            startField.disabled = !checkbox.checked;
            endField.disabled = !checkbox.checked;
            startField.classList.toggle('bg-slate-100', !checkbox.checked);
            endField.classList.toggle('bg-slate-100', !checkbox.checked);
            if (!checkbox.checked) { startField.value = ''; endField.value = ''; }
        }

        function removeLink(button) {
            const item = button.closest('.link-item');
            if (!item) return;
            window.hrdAdminConfirmDelete('ลบลิงก์นี้?', () => item.remove());
        }
    </script>
@endsection
