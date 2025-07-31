@extends("layouts.hrd")

@section("content")
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center">
                <a class="mr-4 text-blue-600 hover:text-blue-800" href="{{ route("hrd.admin.projects.show", $project->id) }}">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">จัดการการอนุมัติการเข้าร่วม</h1>
                    <p class="text-gray-600">{{ $project->project_name }} - แสดงเฉพาะการลงทะเบียนที่มีการเข้าร่วมแล้ว (สามารถเลือกวันที่ในอนาคตได้)</p>
                </div>
            </div>
        </div>

        @if (session("success"))
            <div class="mb-6 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session("success") }}
                </div>
            </div>
        @endif

        @if (session("error"))
            <div class="mb-6 rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session("error") }}
                </div>
            </div>
        @endif

        <!-- Statistics -->
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-4">
            <div class="rounded-lg bg-blue-50 p-4">
                <div class="flex items-center">
                    <i class="fas fa-users mr-3 text-2xl text-blue-600"></i>
                    <div>
                        <p class="text-2xl font-bold text-blue-900">{{ $registrations->count() }}</p>
                        <p class="text-sm text-blue-700">การเข้าร่วมทั้งหมด</p>
                    </div>
                </div>
            </div>
            <div class="rounded-lg bg-green-50 p-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-2xl text-green-600"></i>
                    <div>
                        <p class="text-2xl font-bold text-green-900">{{ $registrations->where("approve_datetime", "!=", null)->count() }}</p>
                        <p class="text-sm text-green-700">อนุมัติแล้ว</p>
                    </div>
                </div>
            </div>
            <div class="rounded-lg bg-yellow-50 p-4">
                <div class="flex items-center">
                    <i class="fas fa-clock mr-3 text-2xl text-yellow-600"></i>
                    <div>
                        <p class="text-2xl font-bold text-yellow-900">{{ $registrations->where("approve_datetime", null)->count() }}</p>
                        <p class="text-sm text-yellow-700">รออนุมัติ</p>
                    </div>
                </div>
            </div>
            <div class="rounded-lg bg-purple-50 p-4">
                <div class="flex items-center">
                    <i class="fas fa-calendar mr-3 text-2xl text-purple-600"></i>
                    <div>
                        <p class="text-2xl font-bold text-purple-900">{{ $project->dates->count() }}</p>
                        <p class="text-sm text-purple-700">วันที่จัดงานทั้งหมด</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="mb-6 rounded-lg bg-white p-6 shadow-lg">
            <h2 class="mb-4 text-xl font-semibold text-gray-800">
                <i class="fas fa-filter mr-2 text-blue-600"></i>ตัวกรอง
            </h2>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">วันที่</label>
                    <select class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-blue-500" id="filterDate" onchange="applyFilters()">
                        <option value="">ทุกวันที่</option>
                        @foreach ($availableDates as $date)
                            @php
                                $dateValue = \Carbon\Carbon::parse($date->date_datetime)->format("Y-m-d");
                                $isSelected = $dateValue == $filterDate;
                            @endphp
                            <option value="{{ $dateValue }}" {{ $isSelected ? "selected" : "" }}>
                                {{ $date->date_title }} ({{ \Carbon\Carbon::parse($date->date_datetime)->format("d/m/Y") }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">ช่วงเวลา</label>
                    <select class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-blue-500" id="filterTime" onchange="applyFilters()">
                        <option value="">ทุกช่วงเวลา</option>
                        @if ($filterDate)
                            @php
                                $selectedDate = $project->dates->where("date_datetime", "like", $filterDate . "%")->first();
                            @endphp
                            @if ($selectedDate)
                                @foreach ($selectedDate->times as $time)
                                    <option value="{{ $time->id }}">
                                        {{ $time->time_title }} ({{ $time->time_start }} - {{ $time->time_end }})
                                    </option>
                                @endforeach
                            @endif
                        @endif
                    </select>
                </div>
                <div class="flex items-end">
                    <button class="w-full rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700" onclick="applyFilters()">
                        <i class="fas fa-search mr-2"></i>กรอง
                    </button>
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="mb-6 rounded-lg bg-white p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">
                        <i class="fas fa-tasks mr-2 text-blue-600"></i>การดำเนินการแบบกลุ่ม
                    </h2>
                    <p class="text-sm text-gray-600">เลือกการลงทะเบียนที่เข้าร่วมแล้วเพื่ออนุมัติ</p>
                </div>
                <div class="flex space-x-2">
                    <button class="rounded-lg bg-green-600 px-4 py-2 font-semibold text-white hover:bg-green-700" onclick="selectAllPending()">
                        <i class="fas fa-check-double mr-2"></i>เลือกทั้งหมดที่รออนุมัติ
                    </button>
                    <button class="rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700" onclick="bulkApprove()">
                        <i class="fas fa-check mr-2"></i>อนุมัติที่เลือก
                    </button>
                </div>
            </div>
        </div>

        <!-- Registrations Table -->
        <div class="rounded-lg bg-white p-6 shadow-lg">
            <h2 class="mb-4 text-xl font-semibold text-gray-800">
                <i class="fas fa-list mr-2 text-blue-600"></i>รายการเข้าร่วมโปรแกรม
            </h2>

            @if ($registrations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">
                                    <input class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" id="selectAll" type="checkbox" onchange="toggleSelectAll()">
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">ผู้ใช้</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">วันที่</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">ช่วงเวลา</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">ลงทะเบียนเมื่อ</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">เข้าร่วมเมื่อ</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">สถานะ</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($registrations as $registration)
                                <tr class="registration-row border-b border-gray-100 hover:bg-gray-50" data-date="{{ \Carbon\Carbon::parse($registration->date->date_datetime)->format("Y-m-d") }}" data-time="{{ $registration->time_id }}">
                                    <td class="px-4 py-3 text-sm">
                                        @if (!$registration->approve_datetime)
                                            <input class="registration-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500" type="checkbox" value="{{ $registration->id }}">
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <div>
                                            <div class="font-medium">{{ $registration->user->name ?? "N/A" }}</div>
                                            <div class="text-xs text-gray-500">{{ $registration->user->userid ?? "N/A" }}</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <div>
                                            <div class="font-medium">{{ $registration->date->date_title ?? "N/A" }}</div>
                                            <div class="text-xs text-gray-500">{{ $registration->date->date_datetime ? \Carbon\Carbon::parse($registration->date->date_datetime)->format("d/m/Y") : "N/A" }}</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <div>
                                            <div class="font-medium">{{ $registration->time->time_title ?? "N/A" }}</div>
                                            <div class="text-xs text-gray-500">
                                                {{ $registration->time->time_start ?? "N/A" }} - {{ $registration->time->time_end ?? "N/A" }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $registration->created_at ? \Carbon\Carbon::parse($registration->created_at)->format("d/m/Y H:i") : "N/A" }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $registration->attend_datetime ? \Carbon\Carbon::parse($registration->attend_datetime)->format("d/m/Y H:i") : "N/A" }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @if ($registration->approve_datetime)
                                            <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>อนุมัติแล้ว
                                            </span>
                                            <div class="mt-1 text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($registration->approve_datetime)->format("d/m/Y H:i") }}
                                            </div>
                                        @else
                                            <span class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>รออนุมัติ
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @if (!$registration->approve_datetime)
                                            <button class="rounded bg-green-600 px-2 py-1 text-xs text-white hover:bg-green-700" onclick="approveRegistration({{ $registration->id }}, '{{ $registration->user->userid ?? "N/A" }}')">
                                                <i class="fas fa-check mr-1"></i>อนุมัติ
                                            </button>
                                        @else
                                            <button class="rounded bg-red-600 px-2 py-1 text-xs text-white hover:bg-red-700" onclick="unapproveRegistration({{ $registration->id }}, '{{ $registration->user->userid ?? "N/A" }}')">
                                                <i class="fas fa-times mr-1"></i>ยกเลิกอนุมัติ
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                @php
                    $selectedDate = $availableDates->where("date_datetime", "like", $filterDate . "%")->first();
                    $isFutureDate = $selectedDate && \Carbon\Carbon::parse($selectedDate->date_datetime)->isFuture();
                @endphp

                <div class="py-8 text-center">
                    @if ($isFutureDate)
                        <i class="fas fa-calendar-day mb-4 text-4xl text-blue-300"></i>
                        <p class="text-gray-500">ยังไม่มีการเข้าร่วมในวันที่เลือก</p>
                        <p class="mt-2 text-sm text-gray-400">วันที่นี้ยังไม่มาถึง จึงยังไม่มีการเข้าร่วมโปรแกรม</p>
                    @else
                        <i class="fas fa-users mb-4 text-4xl text-gray-300"></i>
                        <p class="text-gray-500">ไม่พบการลงทะเบียนที่เข้าร่วมแล้วในวันที่เลือก</p>
                        <p class="mt-2 text-sm text-gray-400">อาจยังไม่มีผู้เข้าร่วมหรือยังไม่มีการเช็คอิน</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection

@section("scripts")
    <script>
        function applyFilters() {
            const filterDate = document.getElementById('filterDate').value;
            const filterTime = document.getElementById('filterTime').value;

            const rows = document.querySelectorAll('.registration-row');

            rows.forEach(row => {
                const rowDate = row.getAttribute('data-date');
                const rowTime = row.getAttribute('data-time');

                let showRow = true;

                if (filterDate && rowDate !== filterDate) {
                    showRow = false;
                }

                if (filterTime && rowTime !== filterTime) {
                    showRow = false;
                }

                row.style.display = showRow ? '' : 'none';
            });

            // Update select all checkbox
            updateSelectAllCheckbox();
        }

        function updateTimeFilter() {
            const filterDate = document.getElementById('filterDate').value;
            const filterTimeSelect = document.getElementById('filterTime');

            // Clear current options
            filterTimeSelect.innerHTML = '<option value="">ทุกช่วงเวลา</option>';

            if (filterDate) {
                // Get times for the selected date from all project dates
                const projectDates = @json($project->dates);
                const selectedDate = projectDates.find(date =>
                    date.date_datetime.startsWith(filterDate)
                );

                if (selectedDate && selectedDate.times) {
                    selectedDate.times.forEach(time => {
                        const option = document.createElement('option');
                        option.value = time.id;
                        option.textContent = `${time.time_title} (${time.time_start} - ${time.time_end})`;
                        filterTimeSelect.appendChild(option);
                    });
                }
            }

            // Apply filters after updating time options
            applyFilters();
        }

        function selectAllPending() {
            const checkboxes = document.querySelectorAll('.registration-checkbox:not(:disabled)');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            updateSelectAllCheckbox();
        }

        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.registration-checkbox:not(:disabled)');

            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        }

        function updateSelectAllCheckbox() {
            const visibleCheckboxes = document.querySelectorAll('.registration-checkbox:not(:disabled)');
            const checkedVisibleCheckboxes = document.querySelectorAll('.registration-checkbox:not(:disabled):checked');
            const selectAllCheckbox = document.getElementById('selectAll');

            if (visibleCheckboxes.length === 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            } else if (checkedVisibleCheckboxes.length === 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            } else if (checkedVisibleCheckboxes.length === visibleCheckboxes.length) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = true;
            }
        }

        function bulkApprove() {
            const selectedCheckboxes = document.querySelectorAll('.registration-checkbox:checked');
            const attendIds = Array.from(selectedCheckboxes).map(cb => cb.value);

            if (attendIds.length === 0) {
                alert('กรุณาเลือกการลงทะเบียนที่ต้องการอนุมัติ');
                return;
            }

            if (confirm(`คุณแน่ใจหรือไม่ที่จะอนุมัติการลงทะเบียน ${attendIds.length} รายการ?`)) {
                const filterDate = document.getElementById('filterDate').value;
                const filterTime = document.getElementById('filterTime').value;

                axios.post(`{{ route("hrd.admin.projects.bulk_approve", $project->id) }}`, {
                        attend_ids: attendIds,
                        filter_date: filterDate,
                        filter_time_id: filterTime
                    })
                    .then(response => {
                        alert(response.data.success);
                        location.reload();
                    })
                    .catch(error => {
                        console.error('Error bulk approving:', error);
                        alert('เกิดข้อผิดพลาดในการอนุมัติ กรุณาลองใหม่อีกครั้ง');
                    });
            }
        }

        function approveRegistration(attendId, userId) {
            if (confirm(`คุณแน่ใจหรือไม่ที่จะอนุมัติการลงทะเบียนของ ${userId}?`)) {
                axios.post(`{{ route("hrd.admin.projects.approve_registration", $project->id) }}`, {
                        attend_id: attendId
                    })
                    .then(response => {
                        alert('อนุมัติการลงทะเบียนเรียบร้อยแล้ว!');
                        location.reload();
                    })
                    .catch(error => {
                        console.error('Error approving registration:', error);
                        alert('เกิดข้อผิดพลาดในการอนุมัติ กรุณาลองใหม่อีกครั้ง');
                    });
            }
        }

        function unapproveRegistration(attendId, userId) {
            if (confirm(`คุณแน่ใจหรือไม่ที่จะยกเลิกการอนุมัติของ ${userId}?`)) {
                axios.post(`{{ route("hrd.admin.projects.unapprove_registration", $project->id) }}`, {
                        attend_id: attendId
                    })
                    .then(response => {
                        alert('ยกเลิกการอนุมัติเรียบร้อยแล้ว!');
                        location.reload();
                    })
                    .catch(error => {
                        console.error('Error unapproving registration:', error);
                        alert('เกิดข้อผิดพลาดในการยกเลิกการอนุมัติ กรุณาลองใหม่อีกครั้ง');
                    });
            }
        }

        // Initialize select all checkbox state
        document.addEventListener('DOMContentLoaded', function() {
            updateSelectAllCheckbox();

            // Set up date filter change handler
            document.getElementById('filterDate').addEventListener('change', function() {
                updateTimeFilter();
            });
        });
    </script>
@endsection
