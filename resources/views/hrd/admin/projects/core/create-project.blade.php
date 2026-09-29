@extends("layouts.hrd")

@section("content")
    @component("hrd.admin.projects.partials.form-page-shell", [
        "backUrl" => route("hrd.admin.index"),
        "pageTitle" => "สร้างโปรเจกต์ใหม่",
    ])
        <form class="space-y-3" id="projectForm" action="{{ route("hrd.admin.projects.store") }}" method="POST">
            @csrf

            @component("hrd.admin.projects.partials.form-section", [
                "sectionNumber" => 1,
                "title" => "ข้อมูลพื้นฐาน",
                "description" => "ชื่อ ประเภท และช่วงลงทะเบียน",
            ])
                @include("hrd.admin.projects.partials.form-basic-fields")
            @endcomponent

            @component("hrd.admin.projects.partials.form-section", [
                "sectionNumber" => 2,
                "title" => "วันและช่วงเวลา",
                "description" => "แต่ละวันย่อ/ขยายได้ — เหมาะกับโปรเจกต์หลายวัน",
                "sectionId" => "dates-section",
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
                "sectionNumber" => 3,
                "title" => "ลิงก์ทรัพยากร",
                "description" => "ไม่บังคับ — แสดงหลังเช็คอิน",
            ])
                <div class="space-y-2" id="linksContainer"></div>
                <button class="hrd-af-btn-add mt-3 w-full sm:w-auto" type="button" onclick="addLink()">
                    <i class="fas fa-plus"></i> เพิ่มลิงก์
                </button>
            @endcomponent

            <div class="sticky bottom-0 z-10 -mx-1 flex flex-wrap justify-end gap-2 border-t border-slate-200 bg-white/95 px-1 py-3 backdrop-blur-sm">
                <a class="hrd-af-btn-secondary" href="{{ route("hrd.admin.index") }}">ยกเลิก</a>
                <button class="hrd-af-btn-primary" type="submit">
                    <i class="fas fa-save"></i> สร้างโปรเจกต์
                </button>
            </div>
        </form>
    @endcomponent
@endsection

@section("scripts")
    @include("hrd.admin.projects.partials.form-swal")
    @include("hrd.admin.projects.partials.form-dynamic-ui", ["withRecordIds" => false])
    <script>
        let dateIndex = 0;
        let linkIndex = 0;

        const thaiMonths = [
            'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
            'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
        ];

        function formatThaiDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            const day = date.getDate();
            const month = thaiMonths[date.getMonth()];
            const year = date.getFullYear() + 543;
            return `${day} ${month} ${year}`;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const oldDates = @json(old("dates", []));
            const oldLinks = @json(old("links", []));

            if (oldDates.length > 0) {
                oldDates.forEach((dateData, index) => {
                    addDate();
                    restoreDateData(index, dateData);
                });
            } else {
                addDate();
            }

            if (oldLinks.length > 0) {
                oldLinks.forEach((linkData, index) => {
                    addLink();
                    restoreLinkData(index, linkData);
                });
            }

            const oldProjectType = @json(old("project_type", ""));
            if (oldProjectType) {
                showProjectTypeHint(oldProjectType);
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
                title: 'ยืนยันการสร้างโครงการ',
                text: 'คุณต้องการสร้างโครงการนี้ใช่หรือไม่?',
                icon: 'question',
                confirmText: 'ใช่, สร้าง',
            }).then((result) => {
                if (result.isConfirmed) this.submit();
            });
        });

        @if ($errors->any())
            Swal.fire({
                title: 'เกิดข้อผิดพลาด',
                html: @json(implode('<br>', $errors->all())),
                icon: 'error',
                confirmButtonText: 'ตกลง',
                confirmButtonColor: window.HRD_ADMIN_SWAL.dangerColor,
            });
        @endif

        function addDate() {
            const container = document.getElementById('datesContainer');
            const openByDefault = container.children.length === 0;
            container.insertAdjacentHTML('beforeend', window.buildDateCardHtml(dateIndex, null, openByDefault));
            addTime(dateIndex);
            window.hrdSyncDateSummary(dateIndex);
            dateIndex++;
        }

        function updateDateTitle(dateIndex) {
            const dateTimeInput = document.getElementById(`dateDateTime${dateIndex}`);
            const dateTitleInput = document.getElementById(`dateTitle${dateIndex}`);
            if (dateTimeInput?.value && dateTitleInput && !dateTitleInput.value) {
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

        function addTime(dateIndex) {
            const container = document.getElementById(`timesContainer${dateIndex}`);
            const timeIndex = container.children.length;
            container.insertAdjacentHTML('beforeend', window.buildTimeCardHtml(dateIndex, timeIndex, null));
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
            const dateItem = item.closest('.date-item');
            const dateIndex = dateItem?.dataset?.dateIndex;
            window.hrdAdminConfirmDelete('ลบช่วงเวลานี้?', () => {
                item.remove();
                if (dateIndex !== undefined) window.hrdSyncDateSummary(dateIndex);
            });
        }

        function addLink() {
            const container = document.getElementById('linksContainer');
            container.insertAdjacentHTML('beforeend', window.buildLinkCardHtml(linkIndex, null));
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
            if (!checkbox.checked) {
                startField.value = '';
                endField.value = '';
            }
        }

        function removeLink(button) {
            const item = button.closest('.link-item');
            if (!item) return;
            window.hrdAdminConfirmDelete('ลบลิงก์นี้?', () => item.remove());
        }

        function restoreDateData(index, dateData) {
            const dateItem = document.querySelector(`[data-date-index="${index}"]`);
            if (!dateItem) return;

            if (dateData.date_title) dateItem.querySelector(`#dateTitle${index}`).value = dateData.date_title;
            if (dateData.date_datetime) dateItem.querySelector(`#dateDateTime${index}`).value = dateData.date_datetime;
            if (dateData.date_location) dateItem.querySelector(`input[name="dates[${index}][date_location]"]`).value = dateData.date_location;
            if (dateData.date_detail) dateItem.querySelector(`input[name="dates[${index}][date_detail]"]`).value = dateData.date_detail;

            if (dateData.times) {
                const timesContainer = document.getElementById(`timesContainer${index}`);
                if (timesContainer) {
                    timesContainer.innerHTML = '';
                    dateData.times.forEach((timeData, timeIndex) => {
                        addTime(index);
                        restoreTimeData(index, timeIndex, timeData);
                    });
                }
            }
            window.hrdSyncDateSummary(index);
        }

        function restoreTimeData(dateIndex, timeIndex, timeData) {
            const timeItem = document.querySelector(`#timesContainer${dateIndex} .time-item[data-time-index="${timeIndex}"]`);
            if (!timeItem) return;

            if (timeData.time_title) timeItem.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_title]"]`).value = timeData.time_title;
            if (timeData.time_start) timeItem.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_start]"]`).value = timeData.time_start;
            if (timeData.time_end) timeItem.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_end]"]`).value = timeData.time_end;
            if (timeData.time_max) timeItem.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_max]"]`).value = timeData.time_max;
            if (timeData.time_detail) timeItem.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_detail]"]`).value = timeData.time_detail;
            if (timeData.time_limit == '1') {
                const checkbox = timeItem.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_limit]"][type="checkbox"]`);
                checkbox.checked = true;
                toggleMaxParticipants(checkbox, `${dateIndex}_${timeIndex}`);
            }
        }

        function restoreLinkData(index, linkData) {
            const linkItem = document.querySelectorAll('.link-item')[index];
            if (!linkItem) return;

            if (linkData.link_name) linkItem.querySelector(`input[name="links[${index}][link_name]"]`).value = linkData.link_name;
            if (linkData.link_url) linkItem.querySelector(`input[name="links[${index}][link_url]"]`).value = linkData.link_url;
            if (linkData.link_limit == '1') {
                const checkbox = linkItem.querySelector(`input[name="links[${index}][link_limit]"][type="checkbox"]`);
                checkbox.checked = true;
                toggleLinkTimeFields(checkbox, index);
                if (linkData.link_time_start) linkItem.querySelector(`input[name="links[${index}][link_time_start]"]`).value = linkData.link_time_start;
                if (linkData.link_time_end) linkItem.querySelector(`input[name="links[${index}][link_time_end]"]`).value = linkData.link_time_end;
            }
        }
    </script>
@endsection
