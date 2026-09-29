@extends('layouts.hrd')

@section('content')
    @include('hrd.admin.projects.partials.form-styles')
    @include('hrd.admin.projects.partials.overview-styles')

    @php
        $typeLabels = [
            'single' => ['icon' => 'user', 'text' => 'ลงทะเบียน 1 ครั้ง'],
            'multiple' => ['icon' => 'users', 'text' => 'ลงทะเบียนได้มากกว่า 1 ครั้ง'],
            'attendance' => ['icon' => 'calendar-check', 'text' => 'ไม่ต้องลงทะเบียน'],
        ];
        $typeMeta = $typeLabels[$project->project_type] ?? ['icon' => 'circle', 'text' => $project->project_type];
        $dateCount = $project->dates->where('date_delete', false)->count();
        $timeCount = $project->dates->where('date_delete', false)->sum(fn ($date) => $date->times->where('time_delete', false)->count());
        $linkCount = $project->links->where('link_delete', false)->count();
    @endphp

    <div class="hrd-hospital hrd-admin-project-overview hrd-page mx-auto max-w-7xl px-4 py-6 text-slate-800 sm:px-6 lg:px-8">
        <header class="mb-4 flex flex-wrap items-start justify-between gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
            <div class="flex min-w-0 flex-1 items-start gap-3">
                <a class="hrd-back-btn" href="{{ route('hrd.admin.index') }}" aria-label="กลับ">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div class="min-w-0">
                    <p class="text-[11px] font-semibold uppercase tracking-widest text-blue-600">HRD Admin · โปรเจกต์</p>
                    <h1 class="mt-0.5 break-words text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl" id="projectName">{{ $project->project_name }}</h1>
                    <div class="mt-2 flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-900">
                            <i class="fas fa-{{ $typeMeta['icon'] }} mr-1"></i>{{ $typeMeta['text'] }}
                        </span>
                        <span @class([
                            'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold',
                            'bg-emerald-100 text-emerald-900' => $project->project_active,
                            'bg-red-100 text-red-800' => ! $project->project_active,
                        ])>
                            <i class="fas fa-{{ $project->project_active ? 'check-circle' : 'times-circle' }} mr-1"></i>
                            {{ $project->project_active ? 'ใช้งาน' : 'ไม่ใช้งาน' }}
                        </span>
                        @if ($project->project_group_assign)
                            <span class="inline-flex items-center rounded-full bg-sky-100 px-2.5 py-0.5 text-xs font-semibold text-sky-900">
                                <i class="fas fa-users mr-1"></i>
                                จัดกลุ่ม · {{ $project->usesAutoGroupMode() ? 'Auto' : 'Manual' }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <a class="hrd-ov-btn-edit shrink-0" href="{{ route('hrd.admin.projects.edit', $project->id) }}">
                <i class="fas fa-edit"></i><span class="hidden sm:inline">แก้ไข</span>
            </a>
        </header>

        @if (session('success'))
            <div class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-900">
                <i class="fas fa-check-circle mr-2 text-emerald-600"></i>{{ session('success') }}
            </div>
        @endif

        <div class="space-y-3">
            @component('hrd.admin.projects.partials.form-section', [
                'sectionNumber' => 1,
                'title' => 'การจัดการ',
                'description' => 'เมนูหลักสำหรับโปรเจกต์นี้',
            ])
                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                    <a class="hrd-ov-action-tile hrd-ov-action-tile--registrations" href="{{ route('hrd.admin.projects.registrations.index', $project->id) }}">
                        <i class="fas fa-users hrd-ov-action-icon"></i>
                        <span>จัดการการลงทะเบียน</span>
                    </a>
                    <a class="hrd-ov-action-tile hrd-ov-action-tile--approvals" href="{{ route('hrd.admin.projects.approvals.index', $project->id) }}">
                        <i class="fas fa-check-circle hrd-ov-action-icon"></i>
                        <span>จัดการการอนุมัติ</span>
                    </a>
                    <a class="hrd-ov-action-tile hrd-ov-action-tile--results" href="{{ route('hrd.admin.projects.results.index', $project->id) }}">
                        <i class="fas fa-chart-bar hrd-ov-action-icon"></i>
                        <span>จัดการผลการประเมิน</span>
                    </a>
                    @if ($project->project_seat_assign)
                        <a class="hrd-ov-action-tile hrd-ov-action-tile--seats" href="{{ route('hrd.admin.projects.seat.management', $project->id) }}">
                            <i class="fas fa-chair hrd-ov-action-icon"></i>
                            <span>จัดการที่นั่ง</span>
                        </a>
                    @endif
                    @if ($project->project_group_assign)
                        <a class="hrd-ov-action-tile hrd-ov-action-tile--groups" href="{{ route('hrd.admin.projects.groups.index', $project->id) }}">
                            <i class="fas fa-layer-group hrd-ov-action-icon"></i>
                            <span>จัดการกลุ่ม</span>
                        </a>
                    @endif
                </div>
            @endcomponent

            <div class="grid grid-cols-2 gap-2 lg:grid-cols-4">
                <div class="hrd-ov-stat">
                    <p class="text-xs font-medium text-slate-500">วันที่</p>
                    <p class="text-xl font-bold text-slate-900">{{ $dateCount }}</p>
                </div>
                <div class="hrd-ov-stat">
                    <p class="text-xs font-medium text-slate-500">ช่วงเวลา</p>
                    <p class="text-xl font-bold text-slate-900">{{ $timeCount }}</p>
                </div>
                <div class="hrd-ov-stat">
                    <p class="text-xs font-medium text-slate-500">ผู้เข้าร่วม</p>
                    <p class="text-xl font-bold text-slate-900">{{ $project->getUniqueParticipantsCount() }}</p>
                </div>
                <div class="hrd-ov-stat">
                    <p class="text-xs font-medium text-slate-500">ลิงก์</p>
                    <p class="text-xl font-bold text-slate-900">{{ $linkCount }}</p>
                </div>
            </div>

            @component('hrd.admin.projects.partials.form-section', [
                'sectionNumber' => 2,
                'title' => 'ข้อมูลโปรเจกต์',
                'description' => 'ช่วงลงทะเบียนและตัวเลือก',
            ])
                @if ($project->project_detail)
                    <p class="mb-3 text-sm leading-relaxed text-slate-700">{{ $project->project_detail }}</p>
                @endif
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div>
                        <p class="hrd-af-label">เริ่มการลงทะเบียน</p>
                        <p class="text-sm font-semibold text-slate-900">{{ \Carbon\Carbon::parse($project->project_start_register)->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="hrd-af-label">สิ้นสุดการลงทะเบียน</p>
                        <p class="text-sm font-semibold text-slate-900">{{ \Carbon\Carbon::parse($project->project_end_register)->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-1 gap-2 sm:grid-cols-3">
                    <div class="rounded-md border border-slate-200 bg-slate-50/60 px-3 py-2">
                        <p class="text-xs font-semibold text-slate-900">จัดที่นั่ง</p>
                        <p class="text-sm {{ $project->project_seat_assign ? 'text-emerald-700' : 'text-slate-500' }}">{{ $project->project_seat_assign ? 'เปิด' : 'ปิด' }}</p>
                    </div>
                    <div class="rounded-md border border-slate-200 bg-slate-50/60 px-3 py-2">
                        <p class="text-xs font-semibold text-slate-900">ลงทะเบียนในวันจัด</p>
                        <p class="text-sm {{ $project->project_register_today ? 'text-emerald-700' : 'text-slate-500' }}">{{ $project->project_register_today ? 'เปิด' : 'ปิด' }}</p>
                    </div>
                    <div class="rounded-md border border-slate-200 bg-slate-50/60 px-3 py-2">
                        <p class="text-xs font-semibold text-slate-900">จัดกลุ่ม</p>
                        <p class="text-sm {{ $project->project_group_assign ? 'text-emerald-700' : 'text-slate-500' }}">
                            @if ($project->project_group_assign)
                                เปิด ({{ $project->usesAutoGroupMode() ? 'Auto' : 'Manual' }})
                            @else
                                ปิด
                            @endif
                        </p>
                    </div>
                </div>
            @endcomponent

            <div class="flex flex-wrap gap-2">
                <button class="hrd-af-btn-secondary !py-1.5 !text-xs" type="button" onclick="hrdOvExpandPageSections()"><i class="fas fa-expand-alt"></i> ขยายส่วน 3–6</button>
                <button class="hrd-af-btn-secondary !py-1.5 !text-xs" type="button" onclick="hrdOvCollapsePageSections()"><i class="fas fa-compress-alt"></i> ย่อส่วน 3–6</button>
            </div>

            @component('hrd.admin.projects.partials.form-section', [
                'sectionNumber' => 3,
                'title' => 'ส่งออกรายงาน',
                'description' => '2 แถว — ผู้ลงทะเบียน / วิทยากร แล้วฟอร์มระบบ',
                'collapsible' => true,
                'defaultOpen' => false,
            ])
                @include('hrd.admin.projects.partials.overview-exports')
            @endcomponent

            @component('hrd.admin.projects.partials.form-section', [
                'sectionNumber' => 4,
                'title' => 'วันและช่วงเวลา',
                'description' => 'ย่อ/ขยายแต่ละวัน — จัดการวิทยากรและรายงาน',
                'sectionId' => 'dates-section',
                'collapsible' => true,
                'defaultOpen' => false,
            ])
                @include('hrd.admin.projects.partials.overview-dates')
            @endcomponent

            @if ($linkCount > 0)
                @component('hrd.admin.projects.partials.form-section', [
                    'sectionNumber' => 5,
                    'title' => 'ลิงก์ทรัพยากร',
                    'description' => $linkCount . ' ลิงก์',
                    'collapsible' => true,
                    'defaultOpen' => false,
                ])
                    <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
                        @foreach ($project->links->where('link_delete', false) as $link)
                            <div class="rounded-md border border-slate-200 bg-white p-3">
                                <div class="flex items-start justify-between gap-2">
                                    <p class="font-semibold text-slate-900">{{ $link->link_name }}</p>
                                    <span class="hrd-ov-pill-on">ใช้งาน</span>
                                </div>
                                <a class="mt-1 block break-all text-sm text-blue-800 hover:text-slate-900" href="{{ $link->link_url }}" target="_blank" rel="noopener">{{ $link->link_url }}</a>
                                @if ($link->link_limit)
                                    <p class="mt-2 text-xs text-slate-500">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $link->link_time_start ? \Carbon\Carbon::parse($link->link_time_start)->format('H:i') : '—' }}
                                        –
                                        {{ $link->link_time_end ? \Carbon\Carbon::parse($link->link_time_end)->format('H:i') : '—' }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endcomponent
            @endif

            @php $recentAttends = $project->attends->where('attend_delete', false); @endphp
            @if ($recentAttends->count() > 0)
                @component('hrd.admin.projects.partials.form-section', [
                    'sectionNumber' => $linkCount > 0 ? 6 : 5,
                    'title' => 'ผู้เข้าร่วมล่าสุด',
                    'description' => 'แสดง 10 รายการล่าสุด',
                    'collapsible' => true,
                    'defaultOpen' => false,
                ])
                        <div class="overflow-x-auto rounded-md border border-slate-200">
                            <table class="min-w-full text-left text-sm">
                                <thead class="bg-slate-50/80 text-xs uppercase tracking-wide text-slate-900">
                                    <tr>
                                        <th class="px-3 py-2 font-semibold">ผู้ใช้</th>
                                        <th class="px-3 py-2 font-semibold">วันที่</th>
                                        <th class="px-3 py-2 font-semibold">เวลา</th>
                                        <th class="px-3 py-2 font-semibold">ลงทะเบียนเมื่อ</th>
                                        <th class="px-3 py-2 font-semibold">สถานะ</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($recentAttends->take(10) as $attend)
                                        <tr class="hover:bg-blue-50/40">
                                            <td class="whitespace-nowrap px-3 py-2 font-medium text-slate-900">{{ $attend->user_display_name }}</td>
                                            <td class="whitespace-nowrap px-3 py-2 text-slate-700">{{ $attend->date->date_title ?? '—' }}</td>
                                            <td class="whitespace-nowrap px-3 py-2 text-slate-700">{{ $attend->time->time_title ?? '—' }}</td>
                                            <td class="whitespace-nowrap px-3 py-2 text-slate-700">{{ \Carbon\Carbon::parse($attend->created_at)->format('d/m/Y H:i') }}</td>
                                            <td class="whitespace-nowrap px-3 py-2">
                                                @if ($attend->approve_datetime)
                                                    <span class="hrd-ov-pill-on"><i class="fas fa-check"></i> Check in</span>
                                                    <span class="mt-0.5 block text-xs text-slate-500">{{ \Carbon\Carbon::parse($attend->approve_datetime)->format('d/m/Y H:i') }}</span>
                                                @else
                                                    <span class="hrd-ov-pill-off"><i class="fas fa-clock"></i> รอ</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($recentAttends->count() > 10)
                            <p class="border-t border-slate-100 px-3 py-2 text-center text-xs text-slate-500">แสดง 10 จาก {{ $recentAttends->count() }} คน</p>
                        @endif
                @endcomponent
            @endif

            <section class="overflow-hidden rounded-md border border-red-200 bg-white shadow-sm">
                <details>
                    <summary class="flex cursor-pointer items-center gap-2 border-b border-red-100 bg-red-50/80 px-4 py-3 text-sm font-semibold text-red-900">
                        <i class="fas fa-exclamation-triangle"></i>
                        การตั้งค่าขั้นสูง — ลบโปรเจกต์
                    </summary>
                    <div class="space-y-3 p-4">
                        <p class="text-sm text-red-800">การลบโปรเจกต์จะลบข้อมูลที่เกี่ยวข้องอย่างถาวร ไม่สามารถกู้คืนได้</p>
                        <label class="flex items-start gap-2 text-sm text-slate-700">
                            <input class="mt-1 rounded border-slate-300 text-red-600 focus:ring-red-500" id="confirmDelete1" type="checkbox">
                            <span>ฉันเข้าใจว่าการลบโปรเจกต์จะลบข้อมูลทั้งหมดอย่างถาวร</span>
                        </label>
                        <label class="flex items-start gap-2 text-sm text-slate-700">
                            <input class="mt-1 rounded border-slate-300 text-red-600 focus:ring-red-500" id="confirmDelete2" type="checkbox">
                            <span>ฉันได้สำรองข้อมูลที่จำเป็นแล้ว</span>
                        </label>
                        <div>
                            <label class="hrd-af-label text-red-900" for="deleteConfirmation">พิมพ์ DELETE เพื่อยืนยัน</label>
                            <input class="hrd-af-input !border-red-200 focus:!border-red-400" id="deleteConfirmation" type="text" placeholder="DELETE" autocomplete="off">
                        </div>
                        <button class="hrd-af-btn-remove cursor-not-allowed opacity-50" id="deleteProjectBtn" type="button" disabled onclick="confirmDelete()">
                            <i class="fas fa-trash"></i> ลบโปรเจกต์
                        </button>
                    </div>
                </details>
            </section>
        </div>
    </div>

    <div class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-900/50 p-4" id="deleteModal" role="dialog" aria-modal="true">
        <div class="w-full max-w-md rounded-md border border-red-100 bg-white p-6 shadow-sm text-center">
            <p class="text-lg font-semibold text-red-700"><i class="fas fa-exclamation-triangle mr-2"></i>ยืนยันการลบ</p>
            <p class="mt-2 text-sm text-slate-600">คุณแน่ใจหรือไม่ที่จะลบโปรเจกต์นี้? การดำเนินการนี้ไม่สามารถยกเลิกได้</p>
            <div class="mt-6 flex flex-wrap justify-center gap-2">
                <button class="hrd-af-btn-secondary" type="button" onclick="hideDeleteModal()">ยกเลิก</button>
                <button class="inline-flex items-center gap-2 rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700" id="modalDeleteBtn" type="button" onclick="deleteProject()">
                    ลบโปรเจกต์
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function hrdOvExpandAllDates() {
            document.querySelectorAll('.hrd-ov-date').forEach((el) => { el.open = true; });
        }

        function hrdOvCollapseAllDates() {
            document.querySelectorAll('.hrd-ov-date').forEach((el) => { el.open = false; });
        }

        function hrdOvExpandPageSections() {
            document.querySelectorAll('.js-ov-page-section').forEach((el) => { el.open = true; });
        }

        function hrdOvCollapsePageSections() {
            document.querySelectorAll('.js-ov-page-section').forEach((el) => { el.open = false; });
        }

        function confirmDelete() {
            const modal = document.getElementById('deleteModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function hideDeleteModal() {
            const modal = document.getElementById('deleteModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function checkDeleteConfirmation() {
            const checkbox1 = document.getElementById('confirmDelete1');
            const checkbox2 = document.getElementById('confirmDelete2');
            const textInput = document.getElementById('deleteConfirmation');
            const deleteBtn = document.getElementById('deleteProjectBtn');
            if (!deleteBtn) return;

            const ok = checkbox1?.checked && checkbox2?.checked && textInput?.value === 'DELETE';
            deleteBtn.disabled = !ok;
            deleteBtn.classList.toggle('opacity-50', !ok);
            deleteBtn.classList.toggle('cursor-not-allowed', !ok);
        }

        document.addEventListener('DOMContentLoaded', function() {
            ['confirmDelete1', 'confirmDelete2'].forEach((id) => {
                document.getElementById(id)?.addEventListener('change', checkDeleteConfirmation);
            });
            document.getElementById('deleteConfirmation')?.addEventListener('input', checkDeleteConfirmation);
            adjustProjectNameFontSize();
        });

        function adjustProjectNameFontSize() {
            const projectNameElement = document.getElementById('projectName');
            if (!projectNameElement) return;
            const length = projectNameElement.textContent.trim().length;
            let fontSize = '1.25rem';
            if (length > 50) fontSize = '1rem';
            else if (length > 30) fontSize = '1.0625rem';
            else if (length > 20) fontSize = '1.125rem';
            projectNameElement.style.fontSize = fontSize;
        }

        async function addLecturer(dateId, title) {
            const alert = await Swal.fire({
                title: 'เพิ่มวิทยากร',
                html: 'วันที่ ' + title,
                input: 'text',
                inputPlaceholder: 'รหัสพนักงาน',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
            });

            if (!alert.isConfirmed) return;

            if (alert.value === '') {
                Swal.fire({ title: 'โปรดใส่รหัสพนักงาน', icon: 'error', confirmButtonColor: '#dc2626' });
                return;
            }

            Swal.fire({ title: 'กรุณารอสักครู่', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });

            axios.post('{{ route('hrd.admin.projects.lecture.add', $project->id) }}', {
                date_id: dateId,
                user: alert.value,
            }).then((res) => {
                Swal.fire({
                    title: res.data.message,
                    icon: res.data.status === 'success' ? 'success' : 'error',
                    confirmButtonColor: '#2563eb',
                }).then(() => {
                    if (res.data.status === 'success') window.location.reload();
                });
            }).catch(() => {
                Swal.fire({ title: 'เกิดข้อผิดพลาดในการเพิ่มวิทยากร', icon: 'error', confirmButtonColor: '#dc2626' });
            });
        }

        async function deleteLecture(lectureId, name) {
            const alert = await Swal.fire({
                title: 'ยืนยันลบวิทยากร ' + name,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'ลบ',
                cancelButtonText: 'ยกเลิก',
            });

            if (!alert.isConfirmed) return;

            axios.post('{{ route('hrd.admin.projects.lecture.delete', $project->id) }}', {
                lecture_id: lectureId,
            }).then((res) => {
                Swal.fire({
                    title: res.data.message,
                    icon: res.data.status === 'success' ? 'success' : 'error',
                    confirmButtonColor: res.data.status === 'success' ? '#2563eb' : '#dc2626',
                }).then(() => {
                    if (res.data.status === 'success') window.location.reload();
                });
            }).catch(() => {
                Swal.fire({ title: 'เกิดข้อผิดพลาดในการลบวิทยากร', icon: 'error', confirmButtonColor: '#dc2626' });
            });
        }

        function deleteProject() {
            Swal.fire({
                title: 'กำลังลบโปรเจกต์...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading(),
            });

            const deleteBtn = document.getElementById('modalDeleteBtn');
            const originalText = deleteBtn ? deleteBtn.innerHTML : '';
            if (deleteBtn) {
                deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>กำลังลบ...';
                deleteBtn.disabled = true;
            }

            axios.post(`{{ route('hrd.admin.projects.delete', $project->id) }}`)
                .then((response) => {
                    Swal.close();
                    if (response.data && response.data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ',
                            text: response.data.message || 'ลบโปรเจกต์แล้ว',
                            confirmButtonColor: '#2563eb',
                        }).then(() => {
                            window.location.href = response.data.redirect_url || '{{ route('hrd.admin.index') }}';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: response.data?.message || 'ไม่สามารถลบได้',
                            confirmButtonColor: '#dc2626',
                        });
                    }
                })
                .catch((error) => {
                    Swal.close();
                    let errorMessage = 'เกิดข้อผิดพลาดในการลบโปรเจกต์';
                    if (error.response?.data?.message) {
                        errorMessage = error.response.data.message;
                    }
                    Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: errorMessage, confirmButtonColor: '#dc2626' });
                })
                .finally(() => {
                    if (deleteBtn) {
                        deleteBtn.innerHTML = originalText;
                        deleteBtn.disabled = false;
                    }
                    hideDeleteModal();
                });
        }
    </script>
@endsection
