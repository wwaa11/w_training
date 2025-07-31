@extends("layouts.hrd")

@section("content")
    <div class="container mx-auto px-4">
        <div class="rounded-lg bg-white p-6 shadow-lg">
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center">
                    <a class="mr-4 text-blue-600 hover:text-blue-800" href="{{ route("hrd.admin.index") }}">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $project->project_name }}</h1>
                    <span class="@if ($project->project_type === "single") bg-blue-100 text-blue-800
                    @elseif($project->project_type === "multiple") bg-green-100 text-green-800
                    @else bg-purple-100 text-purple-800 @endif ml-4 inline-flex rounded-full px-3 py-1 text-sm font-semibold">
                        @if ($project->project_type === "single")
                            เดี่ยว
                        @elseif($project->project_type === "multiple")
                            หลาย
                        @else
                            เข้าร่วม
                        @endif
                    </span>
                </div>
                <div class="flex space-x-2">
                    <a class="rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700" href="{{ route("hrd.admin.projects.registrations", $project->id) }}">
                        <i class="fas fa-users"></i> จัดการการลงทะเบียน
                    </a>
                    <a class="rounded-lg bg-green-600 px-4 py-2 font-semibold text-white hover:bg-green-700" href="{{ route("hrd.admin.projects.approvals", $project->id) }}">
                        <i class="fas fa-check-circle"></i> จัดการการอนุมัติ
                    </a>
                    <a class="rounded-lg bg-yellow-600 px-4 py-2 font-semibold text-white hover:bg-yellow-700" href="{{ route("hrd.admin.projects.edit", $project->id) }}">
                        <i class="fas fa-edit"></i> แก้ไข
                    </a>
                </div>
            </div>

            @if (session("success"))
                <div class="mb-6 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700">
                    {{ session("success") }}
                </div>
            @endif

            <!-- Project Information -->
            <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <div class="rounded-lg bg-gray-50 p-6">
                        <h2 class="mb-4 text-xl font-semibold text-gray-800">
                            <i class="fas fa-info-circle text-blue-600"></i> ข้อมูลโปรเจกต์
                        </h2>
                        <div class="space-y-4">
                            @if ($project->project_detail)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">รายละเอียด</label>
                                    <p class="mt-1 text-gray-900">{{ $project->project_detail }}</p>
                                </div>
                            @endif
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">เริ่มลงทะเบียน</label>
                                    <p class="mt-1 text-gray-900">{{ \Carbon\Carbon::parse($project->project_start_register)->format("d/m/Y H:i") }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">สิ้นสุดลงทะเบียน</label>
                                    <p class="mt-1 text-gray-900">{{ \Carbon\Carbon::parse($project->project_end_register)->format("d/m/Y H:i") }}</p>
                                </div>
                            </div>
                            <div class="flex space-x-6">
                                <div class="flex items-center">
                                    <i class="fas fa-{{ $project->project_seat_assign ? "check-circle text-green-600" : "times-circle text-red-600" }} mr-2"></i>
                                    <span class="text-sm text-gray-700">การจัดที่นั่ง</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-{{ $project->project_register_today ? "check-circle text-green-600" : "times-circle text-red-600" }} mr-2"></i>
                                    <span class="text-sm text-gray-700">ลงทะเบียนวันเดียวกัน</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-{{ $project->project_active ? "check-circle text-green-600" : "times-circle text-red-600" }} mr-2"></i>
                                    <span class="text-sm text-gray-700">ใช้งาน</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="space-y-4">
                    <div class="rounded-lg bg-blue-50 p-4">
                        <div class="flex items-center">
                            <i class="fas fa-calendar mr-3 text-2xl text-blue-600"></i>
                            <div>
                                <p class="text-2xl font-bold text-blue-900">{{ $project->dates->where("date_delete", false)->count() }}</p>
                                <p class="text-sm text-blue-700">วันที่ทั้งหมด</p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-lg bg-green-50 p-4">
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-3 text-2xl text-green-600"></i>
                            <div>
                                <p class="text-2xl font-bold text-green-900">{{ $project->dates->where("date_delete", false)->sum(function ($date) {return $date->times->where("time_delete", false)->count();}) }}</p>
                                <p class="text-sm text-green-700">ช่วงเวลาทั้งหมด</p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-lg bg-purple-50 p-4">
                        <div class="flex items-center">
                            <i class="fas fa-users mr-3 text-2xl text-purple-600"></i>
                            <div>
                                <p class="text-2xl font-bold text-purple-900">{{ $project->attends->where("attend_delete", false)->count() }}</p>
                                <p class="text-sm text-purple-700">ผู้เข้าร่วมทั้งหมด</p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-lg bg-yellow-50 p-4">
                        <div class="flex items-center">
                            <i class="fas fa-link mr-3 text-2xl text-yellow-600"></i>
                            <div>
                                <p class="text-2xl font-bold text-yellow-900">{{ $project->links->where("link_delete", false)->count() }}</p>
                                <p class="text-sm text-yellow-700">ลิงก์ทั้งหมด</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dates and Times -->
            <div class="mb-8">
                <button class="flex w-full items-center justify-between rounded-lg bg-gray-100 p-4 text-left hover:bg-gray-200" onclick="toggleSection('datesSection')">
                    <h2 class="text-xl font-semibold text-gray-800">
                        <i class="fas fa-calendar text-blue-600"></i> วันที่และเวลาของโปรเจกต์
                    </h2>
                    <i class="fas fa-chevron-down text-gray-500 transition-transform" id="datesSectionIcon"></i>
                </button>
                <div class="mt-4 hidden" id="datesSection">
                    <div class="space-y-4">
                        @foreach ($project->dates->where("date_delete", false) as $date)
                            <div class="rounded-lg border border-gray-200 p-6">
                                <div class="mb-4 flex items-start justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800">{{ $date->date_title }}</h3>
                                        <p class="text-gray-600">{{ \Carbon\Carbon::parse($date->date_datetime)->format("l, d F Y H:i") }}</p>
                                        @if ($date->date_location)
                                            <p class="text-sm text-gray-500">
                                                <i class="fas fa-map-marker-alt mr-1"></i>{{ $date->date_location }}
                                            </p>
                                        @endif
                                        @if ($date->date_detail)
                                            <p class="mt-1 text-sm text-gray-700">{{ $date->date_detail }}</p>
                                        @endif
                                    </div>
                                    <span class="{{ $date->date_active ? "bg-green-100 text-green-800" : "bg-red-100 text-red-800" }} inline-flex rounded-full px-2 py-1 text-xs font-semibold">
                                        {{ $date->date_active ? "ใช้งาน" : "ไม่ใช้งาน" }}
                                    </span>
                                </div>

                                @if ($date->times->where("time_delete", false)->count() > 0)
                                    <div class="rounded-lg bg-gray-50 p-4">
                                        <h4 class="mb-3 font-medium text-gray-700">ช่วงเวลา</h4>
                                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                                            @foreach ($date->times->where("time_delete", false) as $time)
                                                <div class="rounded border border-gray-200 bg-white p-3">
                                                    <div class="mb-2 flex items-start justify-between">
                                                        <h5 class="font-medium text-gray-800">{{ $time->time_title }}</h5>
                                                        <span class="{{ $time->time_active ? "bg-green-100 text-green-800" : "bg-red-100 text-red-800" }} inline-flex rounded px-1 py-0.5 text-xs font-semibold">
                                                            {{ $time->time_active ? "ใช้งาน" : "ไม่ใช้งาน" }}
                                                        </span>
                                                    </div>
                                                    <div class="text-sm text-gray-600">
                                                        <p><i class="fas fa-clock mr-1"></i>{{ $time->time_start }} - {{ $time->time_end }}</p>
                                                        @if ($time->time_limit)
                                                            <p><i class="fas fa-users mr-1"></i>สูงสุด: {{ $time->time_max }} คน</p>
                                                            <p><i class="fas fa-user-check mr-1"></i>ลงทะเบียนแล้ว: {{ $time->attends->where("attend_delete", false)->count() }} คน</p>
                                                        @endif
                                                        @if ($time->time_detail)
                                                            <p class="mt-1 text-xs">{{ $time->time_detail }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Links -->
            @if ($project->links->where("link_delete", false)->count() > 0)
                <div class="mb-8">
                    <button class="flex w-full items-center justify-between rounded-lg bg-gray-100 p-4 text-left hover:bg-gray-200" onclick="toggleSection('linksSection')">
                        <h2 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-link text-blue-600"></i> ลิงก์โปรเจกต์
                        </h2>
                        <i class="fas fa-chevron-down text-gray-500 transition-transform" id="linksSectionIcon"></i>
                    </button>
                    <div class="mt-4 hidden" id="linksSection">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            @foreach ($project->links->where("link_delete", false) as $link)
                                <div class="rounded-lg border border-gray-200 p-4">
                                    <div class="mb-2 flex items-start justify-between">
                                        <h3 class="font-semibold text-gray-800">{{ $link->link_name }}</h3>
                                        <span class="{{ !$link->link_delete ? "bg-green-100 text-green-800" : "bg-red-100 text-red-800" }} inline-flex rounded-full px-2 py-1 text-xs font-semibold">
                                            {{ !$link->link_delete ? "ใช้งาน" : "ถูกลบ" }}
                                        </span>
                                    </div>
                                    <a class="break-all text-sm text-blue-600 hover:text-blue-800" href="{{ $link->link_url }}" target="_blank">
                                        {{ $link->link_url }}
                                    </a>
                                    @if ($link->link_limit)
                                        <div class="mt-2 text-xs text-gray-600">
                                            <p><i class="fas fa-clock mr-1"></i>ใช้งานได้:</p>
                                            <p>ตั้งแต่: {{ $link->link_time_start ? \Carbon\Carbon::parse($link->link_time_start)->format("d/m/Y H:i") : "ไม่จำกัด" }}</p>
                                            <p>จนถึง: {{ $link->link_time_end ? \Carbon\Carbon::parse($link->link_time_end)->format("d/m/Y H:i") : "ไม่จำกัด" }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recent Attendees -->
            @if ($project->attends->where("attend_delete", false)->count() > 0)
                <div class="mb-8">
                    <button class="flex w-full items-center justify-between rounded-lg bg-gray-100 p-4 text-left hover:bg-gray-200" onclick="toggleSection('attendeesSection')">
                        <h2 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-users text-blue-600"></i> ผู้เข้าร่วมล่าสุด
                        </h2>
                        <i class="fas fa-chevron-down text-gray-500 transition-transform" id="attendeesSectionIcon"></i>
                    </button>
                    <div class="mt-4 hidden" id="attendeesSection">
                        <div class="rounded-lg bg-gray-50 p-4">
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b border-gray-200">
                                            <th class="px-3 py-2 text-left text-sm font-medium text-gray-700">ผู้ใช้</th>
                                            <th class="px-3 py-2 text-left text-sm font-medium text-gray-700">วันที่</th>
                                            <th class="px-3 py-2 text-left text-sm font-medium text-gray-700">เวลา</th>
                                            <th class="px-3 py-2 text-left text-sm font-medium text-gray-700">ลงทะเบียนเมื่อ</th>
                                            <th class="px-3 py-2 text-left text-sm font-medium text-gray-700">สถานะ</th>
                                            <th class="px-3 py-2 text-left text-sm font-medium text-gray-700">การดำเนินการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($project->attends->where("attend_delete", false)->take(10) as $attend)
                                            <tr class="border-b border-gray-100">
                                                <td class="px-3 py-2 text-sm text-gray-900">{{ $attend->user->userid ?? "N/A" }}</td>
                                                <td class="px-3 py-2 text-sm text-gray-900">{{ $attend->date->date_title ?? "N/A" }}</td>
                                                <td class="px-3 py-2 text-sm text-gray-900">{{ $attend->time->time_title ?? "N/A" }}</td>
                                                <td class="px-3 py-2 text-sm text-gray-900">{{ \Carbon\Carbon::parse($attend->created_at)->format("d/m/Y H:i") }}</td>
                                                <td class="px-3 py-2 text-sm">
                                                    @if ($attend->approve_datetime)
                                                        <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">
                                                            <i class="fas fa-check-circle mr-1"></i>อนุมัติแล้ว
                                                        </span>
                                                        <div class="mt-1 text-xs text-gray-500">
                                                            {{ \Carbon\Carbon::parse($attend->approve_datetime)->format("d/m/Y H:i") }}
                                                        </div>
                                                    @else
                                                        <span class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-800">
                                                            <i class="fas fa-clock mr-1"></i>รออนุมัติ
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-2 text-sm">
                                                    @if (!$attend->approve_datetime)
                                                        <button class="rounded bg-green-600 px-2 py-1 text-xs text-white hover:bg-green-700" onclick="approveRegistration({{ $attend->id }}, '{{ $attend->user->userid ?? "N/A" }}')">
                                                            <i class="fas fa-check mr-1"></i>อนุมัติ
                                                        </button>
                                                    @else
                                                        <button class="rounded bg-red-600 px-2 py-1 text-xs text-white hover:bg-red-700" onclick="unapproveRegistration({{ $attend->id }}, '{{ $attend->user->userid ?? "N/A" }}')">
                                                            <i class="fas fa-times mr-1"></i>ยกเลิกการอนุมัติ
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if ($project->attends->where("attend_delete", false)->count() > 10)
                                <div class="mt-4 text-center">
                                    <span class="text-sm text-gray-600">แสดง 10 จาก {{ $project->attends->where("attend_delete", false)->count() }} คน</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Advanced Settings Section (Hidden by default) -->
            <div class="mb-8">
                <button class="flex w-full items-center justify-between rounded-lg bg-red-50 p-4 text-left hover:bg-red-100" onclick="toggleAdvancedSettings()">
                    <h2 class="text-xl font-semibold text-red-800">
                        <i class="fas fa-cog text-red-600"></i> การตั้งค่าขั้นสูง
                    </h2>
                    <i class="fas fa-chevron-down text-red-500 transition-transform" id="advancedSettingsIcon"></i>
                </button>
                <div class="mt-4 hidden" id="advancedSettingsSection">
                    <div class="rounded-lg border-2 border-red-200 bg-red-50 p-6">
                        <div class="mb-4">
                            <h3 class="mb-2 text-lg font-semibold text-red-800">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                โปรดระวัง: การตั้งค่าขั้นสูง
                            </h3>
                            <p class="text-sm text-red-700">
                                การดำเนินการในส่วนนี้อาจส่งผลกระทบอย่างร้ายแรงต่อโปรเจกต์ กรุณาใช้ด้วยความระมัดระวัง
                            </p>
                        </div>

                        <!-- Delete Project Section -->
                        <div class="rounded-lg border border-red-300 bg-white p-4">
                            <h4 class="mb-3 font-semibold text-red-800">
                                <i class="fas fa-trash mr-2"></i>
                                ลบโปรเจกต์
                            </h4>
                            <p class="mb-4 text-sm text-gray-700">
                                การลบโปรเจกต์จะลบข้อมูลทั้งหมดที่เกี่ยวข้องอย่างถาวร ไม่สามารถกู้คืนได้
                            </p>

                            <!-- First confirmation -->
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input class="mr-2 rounded border-gray-300 text-red-600 focus:ring-red-500" id="confirmDelete1" type="checkbox">
                                    <span class="text-sm text-gray-700">ฉันเข้าใจว่าการลบโปรเจกต์จะลบข้อมูลทั้งหมดอย่างถาวร</span>
                                </label>
                            </div>

                            <!-- Second confirmation -->
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input class="mr-2 rounded border-gray-300 text-red-600 focus:ring-red-500" id="confirmDelete2" type="checkbox">
                                    <span class="text-sm text-gray-700">ฉันได้สำรองข้อมูลที่จำเป็นแล้ว</span>
                                </label>
                            </div>

                            <!-- Type confirmation -->
                            <div class="mb-4">
                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    พิมพ์ "DELETE" เพื่อยืนยันการลบ:
                                </label>
                                <input class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-red-500 focus:outline-none focus:ring-red-500" id="deleteConfirmation" type="text" placeholder="พิมพ์ DELETE">
                            </div>

                            <!-- Delete button (disabled by default) -->
                            <button class="cursor-not-allowed rounded-lg bg-red-600 px-4 py-2 font-semibold text-white opacity-50" id="deleteProjectBtn" disabled onclick="confirmDelete()">
                                <i class="fas fa-trash mr-2"></i>
                                ลบโปรเจกต์
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); z-index:100; align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:1rem; padding:2rem; min-width:400px; box-shadow:0 2px 16px rgba(0,0,0,0.15); text-align:center;">
            <div style="font-size:1.2rem; font-weight:600; margin-bottom:1rem; color:#dc2626;">
                <i class="fas fa-exclamation-triangle"></i> ยืนยันการลบ
            </div>
            <div style="margin-bottom:1.5rem; color:#374151;">
                คุณแน่ใจหรือไม่ที่จะลบโปรเจกต์นี้? การดำเนินการนี้ไม่สามารถยกเลิกได้
            </div>
            <div style="display:flex; gap:1rem; justify-content:center;">
                <button onclick="hideDeleteModal()" style="background:#6b7280; color:#fff; border:none; border-radius:0.5rem; padding:0.75rem 1.5rem; font-size:1rem; cursor:pointer;">
                    ยกเลิก
                </button>
                <button onclick="deleteProject()" style="background:#dc2626; color:#fff; border:none; border-radius:0.5rem; padding:0.75rem 1.5rem; font-size:1rem; font-weight:600; cursor:pointer;">
                    ลบโปรเจกต์
                </button>
            </div>
        </div>
    </div>

@endsection

@section("scripts")
    <script>
        function toggleAdvancedSettings() {
            const section = document.getElementById('advancedSettingsSection');
            const icon = document.getElementById('advancedSettingsIcon');
            if (section.classList.contains('hidden')) {
                section.classList.remove('hidden');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                section.classList.add('hidden');
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }

        function toggleSection(sectionId) {
            const section = document.getElementById(sectionId);
            const icon = document.getElementById(`${sectionId}Icon`);
            if (section.classList.contains('hidden')) {
                section.classList.remove('hidden');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                section.classList.add('hidden');
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }

        // Delete confirmation logic
        function checkDeleteConfirmation() {
            const checkbox1 = document.getElementById('confirmDelete1');
            const checkbox2 = document.getElementById('confirmDelete2');
            const textInput = document.getElementById('deleteConfirmation');
            const deleteBtn = document.getElementById('deleteProjectBtn');

            if (checkbox1.checked && checkbox2.checked && textInput.value === 'DELETE') {
                deleteBtn.disabled = false;
                deleteBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                deleteBtn.classList.add('hover:bg-red-700');
            } else {
                deleteBtn.disabled = true;
                deleteBtn.classList.add('opacity-50', 'cursor-not-allowed');
                deleteBtn.classList.remove('hover:bg-red-700');
            }
        }

        // Add event listeners for delete confirmation
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox1 = document.getElementById('confirmDelete1');
            const checkbox2 = document.getElementById('confirmDelete2');
            const textInput = document.getElementById('deleteConfirmation');

            if (checkbox1) checkbox1.addEventListener('change', checkDeleteConfirmation);
            if (checkbox2) checkbox2.addEventListener('change', checkDeleteConfirmation);
            if (textInput) textInput.addEventListener('input', checkDeleteConfirmation);
        });

        function confirmDelete() {
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function hideDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        function deleteProject() {
            axios.post(`{{ route("hrd.admin.projects.delete", $project->id) }}`)
                .then(response => {
                    window.location.href = '{{ route("hrd.admin.index") }}';
                })
                .catch(error => {
                    console.error('Error deleting project:', error);
                    alert('เกิดข้อผิดพลาดในการลบโปรเจกต์ กรุณาลองใหม่อีกครั้ง');
                });
            hideDeleteModal();
        }

        function approveRegistration(attendId, userId) {
            if (confirm(`คุณแน่ใจหรือไม่ที่จะอนุมัติการลงทะเบียนสำหรับผู้ใช้ ${userId}?`)) {
                axios.post(`{{ route("hrd.admin.projects.approve_registration", $project->id) }}`, {
                        attend_id: attendId
                    })
                    .then(response => {
                        alert('อนุมัติการลงทะเบียนสำเร็จ!');
                        location.reload();
                    })
                    .catch(error => {
                        console.error('Error approving registration:', error);
                        alert('เกิดข้อผิดพลาดในการอนุมัติ กรุณาลองใหม่อีกครั้ง');
                    });
            }
        }

        function unapproveRegistration(attendId, userId) {
            if (confirm(`คุณแน่ใจหรือไม่ที่จะยกเลิกการอนุมัติสำหรับผู้ใช้ ${userId}?`)) {
                axios.post(`{{ route("hrd.admin.projects.unapprove_registration", $project->id) }}`, {
                        attend_id: attendId
                    })
                    .then(response => {
                        alert('ยกเลิกการอนุมัติสำเร็จ!');
                        location.reload();
                    })
                    .catch(error => {
                        console.error('Error unapproving registration:', error);
                        alert('เกิดข้อผิดพลาดในการยกเลิกการอนุมัติ กรุณาลองใหม่อีกครั้ง');
                    });
            }
        }
    </script>
@endsection
