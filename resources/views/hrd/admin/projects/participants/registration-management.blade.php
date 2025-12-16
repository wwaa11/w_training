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
                    <h1 class="text-3xl font-bold text-gray-800">จัดการการลงทะเบียน</h1>
                    <p class="text-gray-600">{{ $project->project_name }}</p>
                </div>
            </div>
            <button class="rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700" onclick="openAddModal()">
                <i class="fas fa-plus mr-2"></i>เพิ่มการลงทะเบียน
            </button>

        </div>
        <div class="mb-3 rounded bg-amber-100 p-3 shadow">
            <form class="space-y-4" action="{{ route("hrd.admin.projects.registrations.import", $project->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label class="block text-sm font-medium text-gray-700" for="excel_file">Import Excel</label>
                <div class="flex gap-3">
                    <input class="mt-1 block w-full flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" id="excel_file" type="file" name="import_file" accept=".xlsx,.xls" required>
                    <button class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors duration-200 hover:bg-blue-700" type="submit">
                        <i class="fas fa-upload mr-2"></i>
                        นำเข้าข้อมูล
                    </button>
                    <a class="inline-flex items-center rounded-lg bg-gray-600 px-4 py-2 text-sm font-medium text-white transition-colors duration-200 hover:bg-gray-700" href="{{ route("hrd.admin.projects.registrations.template", $project->id) }}">
                        <i class="fas fa-download mr-2"></i>
                        ดาวน์โหลดเทมเพลต
                    </a>
                </div>
                <p class="mt-1 text-sm text-gray-500">รองรับไฟล์ .xlsx และ .xls เท่านั้น</p>
            </form>
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

        @if ($errors->any())
            <div class="mb-6 rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700">
                <h4 class="font-bold">กรุณาแก้ไขข้อผิดพลาดต่อไปนี้:</h4>
                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Statistics -->
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-5">
            <div class="rounded-lg bg-blue-50 p-4">
                <div class="flex items-center">
                    <i class="fas fa-users mr-3 text-2xl text-blue-600"></i>
                    <div>
                        <p class="text-2xl font-bold text-blue-900">{{ $totalRegistrations }}</p>
                        <p class="text-sm text-blue-700">การลงทะเบียนทั้งหมด</p>
                    </div>
                </div>
            </div>
            <div class="rounded-lg bg-green-50 p-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-2xl text-green-600"></i>
                    <div>
                        <p class="text-2xl font-bold text-green-900">{{ $attendedCount }}</p>
                        <p class="text-sm text-green-700">เข้าร่วมแล้ว (Check-in)</p>
                    </div>
                </div>
            </div>
            <div class="rounded-lg bg-yellow-50 p-4">
                <div class="flex items-center">
                    <i class="fas fa-clock mr-3 text-2xl text-yellow-600"></i>
                    <div>
                        <p class="text-2xl font-bold text-yellow-900">{{ $notAttendedCount }}</p>
                        <p class="text-sm text-yellow-700">รอเข้าร่วม</p>
                    </div>
                </div>
            </div>
            <div class="rounded-lg bg-indigo-50 p-4">
                <div class="flex items-center">
                    <i class="fas fa-thumbs-up mr-3 text-2xl text-indigo-600"></i>
                    <div>
                        <p class="text-2xl font-bold text-indigo-900">{{ $approvedCount }}</p>
                        <p class="text-sm text-indigo-700">อนุมัติแล้ว</p>
                    </div>
                </div>
            </div>
            <div class="rounded-lg bg-purple-50 p-4">
                <div class="flex items-center">
                    <i class="fas fa-calendar mr-3 text-2xl text-purple-600"></i>
                    <div>
                        <p class="text-2xl font-bold text-purple-900">{{ $project->dates->count() }}</p>
                        <p class="text-sm text-purple-700">วันที่จัดงาน</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="mb-6 rounded-lg bg-white p-6 shadow-lg">
            <h2 class="mb-4 text-xl font-semibold text-gray-800">
                <i class="fas fa-search mr-2 text-blue-600"></i>ค้นหาและกรอง
            </h2>

            <form class="grid grid-cols-1 gap-4 md:grid-cols-4" method="GET" action="{{ route("hrd.admin.projects.registrations.index", $project->id) }}">
                <div>
                    <label class="block text-sm font-medium text-gray-700">ค้นหาด้วยรหัสผู้ใช้</label>
                    <input class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-blue-500" type="text" name="search_userid" value="{{ request("search_userid") }}" placeholder="กรอกรหัสผู้ใช้ เช่น 12345">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">สถานะเข้าร่วม</label>
                    <select class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-blue-500" name="filter_attend">
                        <option value="">ทั้งหมด</option>
                        <option value="attended" {{ request("filter_attend") == "attended" ? "selected" : "" }}>เข้าร่วมแล้ว</option>
                        <option value="not_attended" {{ request("filter_attend") == "not_attended" ? "selected" : "" }}>ยังไม่เข้าร่วม</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">สถานะอนุมัติ</label>
                    <select class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-blue-500" name="filter_approve">
                        <option value="">ทั้งหมด</option>
                        <option value="approved" {{ request("filter_approve") == "approved" ? "selected" : "" }}>อนุมัติแล้ว</option>
                        <option value="not_approved" {{ request("filter_approve") == "not_approved" ? "selected" : "" }}>ยังไม่อนุมัติ</option>
                    </select>
                </div>

                <div class="flex items-end space-x-2">
                    <button class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700" type="submit">
                        <i class="fas fa-search mr-1"></i>ค้นหา
                    </button>
                    <a class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50" href="{{ route("hrd.admin.projects.registrations.index", $project->id) }}">
                        <i class="fas fa-times mr-1"></i>ล้าง
                    </a>
                </div>
            </form>
        </div>

        <!-- Search Results Summary -->
        @if (request("search_userid") || request("filter_attend") || request("filter_approve"))
            <div class="mb-4 rounded-lg bg-blue-50 p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-filter mr-2 text-blue-600"></i>
                        <span class="text-sm font-medium text-blue-800">ผลการค้นหา:</span>
                        <span class="ml-2 text-sm text-blue-700">{{ $registrations->total() }} รายการ</span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @if (request("search_userid"))
                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800">
                                <i class="fas fa-user mr-1"></i>รหัสผู้ใช้: {{ request("search_userid") }}
                            </span>
                        @endif
                        @if (request("filter_attend"))
                            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>สถานะเข้าร่วม: {{ request("filter_attend") == "attended" ? "เข้าร่วมแล้ว" : "ยังไม่เข้าร่วม" }}
                            </span>
                        @endif
                        @if (request("filter_approve"))
                            <span class="inline-flex items-center rounded-full bg-indigo-100 px-2 py-1 text-xs font-medium text-indigo-800">
                                <i class="fas fa-thumbs-up mr-1"></i>สถานะอนุมัติ: {{ request("filter_approve") == "approved" ? "อนุมัติแล้ว" : "ยังไม่อนุมัติ" }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Registrations Table -->
        <div class="rounded-lg bg-white p-6 shadow-lg">
            <h2 class="mb-4 text-xl font-semibold text-gray-800">
                <i class="fas fa-list mr-2 text-blue-600"></i>รายการลงทะเบียน
            </h2>

            @if ($registrations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">ผู้ใช้</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">วันที่</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">ช่วงเวลา</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">ลงทะเบียนเมื่อ</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">สถานะเข้าร่วม</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">สถานะอนุมัติ</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($registrations as $registration)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
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
                                    <td class="px-4 py-3 text-sm">
                                        @if ($registration->attend_datetime)
                                            <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>เข้าร่วมแล้ว (Check-in)
                                            </span>
                                            <div class="mt-1 text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($registration->attend_datetime)->format("d/m/Y H:i") }}
                                            </div>
                                        @else
                                            <span class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>รอเข้าร่วม
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @if ($registration->approve_datetime)
                                            <span class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-800">
                                                <i class="fas fa-thumbs-up mr-1"></i>อนุมัติแล้ว
                                            </span>
                                            <div class="mt-1 text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($registration->approve_datetime)->format("d/m/Y H:i") }}
                                            </div>
                                        @else
                                            <span class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-800">
                                                <i class="fas fa-minus mr-1"></i>ยังไม่อนุมัติ
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex space-x-2">
                                            <button class="rounded bg-blue-600 px-2 py-1 text-xs text-white hover:bg-blue-700" onclick="openEditModal({{ $registration->id }}, '{{ $registration->user->name ?? "N/A" }}', {{ $registration->time_id }}, '{{ $registration->attend_datetime ? "true" : "false" }}', '{{ $registration->approve_datetime ? "true" : "false" }}', '{{ $registration->attend_datetime ? \Carbon\Carbon::parse($registration->attend_datetime)->format("Y-m-d\TH:i") : "" }}', '{{ $registration->approve_datetime ? \Carbon\Carbon::parse($registration->approve_datetime)->format("Y-m-d\TH:i") : "" }}')">
                                                <i class="fas fa-edit mr-1"></i>แก้ไข
                                            </button>
                                            <button class="rounded bg-red-600 px-2 py-1 text-xs text-white hover:bg-red-700" onclick="confirmDelete({{ $registration->id }}, '{{ $registration->user->name ?? "N/A" }}')">
                                                <i class="fas fa-trash mr-1"></i>ลบ
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $registrations->links() }}
                </div>
            @else
                <div class="py-8 text-center">
                    @if (request("search_userid") || request("filter_attend") || request("filter_approve"))
                        <i class="fas fa-search mb-4 text-4xl text-gray-300"></i>
                        <p class="text-gray-500">ไม่พบผลการค้นหาที่ตรงกับเงื่อนไขที่กำหนด</p>
                        <a class="mt-2 inline-flex items-center text-blue-600 hover:text-blue-800" href="{{ route("hrd.admin.projects.registrations.index", $project->id) }}">
                            <i class="fas fa-times mr-1"></i>ล้างการค้นหา
                        </a>
                    @else
                        <i class="fas fa-users mb-4 text-4xl text-gray-300"></i>
                        <p class="text-gray-500">ยังไม่มีการลงทะเบียน</p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Add Registration Modal -->
    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900 bg-opacity-30 backdrop-blur-sm" id="addModal">
        <div class="w-full max-w-md rounded-xl border border-gray-200 bg-white p-6 shadow-2xl">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">เพิ่มการลงทะเบียน</h3>
                <button class="text-gray-400 hover:text-gray-600" onclick="closeAddModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form action="{{ route("hrd.admin.projects.registrations.store", $project->id) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">รหัสผู้ใช้ (User ID)</label>
                        <input class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-blue-500" type="text" name="user_id" placeholder="กรอกรหัสผู้ใช้ เช่น 12345" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">ช่วงเวลา</label>
                        <select class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-blue-500" name="time_id" required>
                            <option value="">เลือกช่วงเวลา</option>
                            @foreach ($project->dates as $date)
                                <optgroup label="{{ $date->date_title }} {{ \Carbon\Carbon::parse($date->date_datetime)->format("d/m/Y") }}">
                                    @foreach ($date->times as $time)
                                        <option value="{{ $time->id }}">
                                            {{ $time->time_title }}
                                            @if ($time->time_limit)
                                                ({{ $time->time_max - $time->attends->where("attend_delete", false)->count() }}/{{ $time->time_max }})
                                            @endif
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" id="attendCheckbox" type="checkbox" name="attend_datetime" onchange="toggleAttendTime()">
                            <span class="ml-2 text-sm text-gray-700">เข้าร่วมแล้ว (Check-in)</span>
                        </label>
                        <div class="mt-2 hidden" id="attendTimeDiv">
                            <label class="block text-sm font-medium text-gray-700">วันที่และเวลาเข้าร่วม</label>
                            <input class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-blue-500" id="attendDateTime" type="datetime-local" name="attend_datetime_value">
                        </div>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" id="approveCheckbox" type="checkbox" name="approve_datetime" onchange="toggleApproveTime()">
                            <span class="ml-2 text-sm text-gray-700">อนุมัติแล้ว (Approve)</span>
                        </label>
                        <div class="mt-2 hidden" id="approveTimeDiv">
                            <label class="block text-sm font-medium text-gray-700">วันที่และเวลาอนุมัติ</label>
                            <input class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-blue-500" id="approveDateTime" type="datetime-local" name="approve_datetime_value">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50" type="button" onclick="closeAddModal()">
                        ยกเลิก
                    </button>
                    <button class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700" type="submit">
                        เพิ่มการลงทะเบียน
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Registration Modal -->
    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900 bg-opacity-30 backdrop-blur-sm" id="editModal">
        <div class="w-full max-w-md rounded-xl border border-gray-200 bg-white p-6 shadow-2xl">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">แก้ไขการลงทะเบียน</h3>
                <button class="text-gray-400 hover:text-gray-600" onclick="closeEditModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="editForm" method="POST">
                @csrf
                @method("PUT")
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">ผู้ใช้</label>
                        <input class="mt-1 block w-full rounded-md border border-gray-300 bg-gray-100 px-3 py-2" id="editUserName" type="text" readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">ช่วงเวลา</label>
                        <select class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-blue-500" id="editTimeId" name="time_id" required>
                            <option value="">เลือกช่วงเวลา</option>
                            @foreach ($project->dates as $date)
                                <optgroup label="{{ $date->date_title }} {{ \Carbon\Carbon::parse($date->date_datetime)->format("d/m/Y") }}">
                                    @foreach ($date->times as $time)
                                        <option value="{{ $time->id }}">
                                            {{ $time->time_title }}
                                            @if ($time->time_limit)
                                                ({{ $time->time_max - $time->attends->where("attend_delete", false)->count() }}/{{ $time->time_max }})
                                            @endif
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" id="editAttendDatetime" type="checkbox" name="attend_datetime" onchange="toggleEditAttendTime()">
                            <span class="ml-2 text-sm text-gray-700">เข้าร่วมแล้ว (Check-in)</span>
                        </label>
                        <div class="mt-2 hidden" id="editAttendTimeDiv">
                            <label class="block text-sm font-medium text-gray-700">วันที่และเวลาเข้าร่วม</label>
                            <input class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-blue-500" id="editAttendDateTime" type="datetime-local" name="attend_datetime_value">
                        </div>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" id="editApproveDatetime" type="checkbox" name="approve_datetime" onchange="toggleEditApproveTime()">
                            <span class="ml-2 text-sm text-gray-700">อนุมัติแล้ว (Approve)</span>
                        </label>
                        <div class="mt-2 hidden" id="editApproveTimeDiv">
                            <label class="block text-sm font-medium text-gray-700">วันที่และเวลาอนุมัติ</label>
                            <input class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-blue-500" id="editApproveDateTime" type="datetime-local" name="approve_datetime_value">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50" type="button" onclick="closeEditModal()">
                        ยกเลิก
                    </button>
                    <button class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700" type="submit">
                        บันทึกการเปลี่ยนแปลง
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900 bg-opacity-30 backdrop-blur-sm" id="deleteModal">
        <div class="w-full max-w-md rounded-xl border border-gray-200 bg-white p-6 shadow-2xl">
            <div class="text-center">
                <i class="fas fa-exclamation-triangle mb-4 text-4xl text-red-500"></i>
                <h3 class="mb-2 text-lg font-semibold text-gray-900">ยืนยันการลบ</h3>
                <p class="mb-6 text-gray-600">คุณแน่ใจหรือไม่ที่จะลบการลงทะเบียนของ <span class="font-semibold" id="deleteUserName"></span>?</p>

                <form id="deleteForm" method="POST">
                    @csrf
                    @method("DELETE")
                    <div class="flex justify-center space-x-3">
                        <button class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50" type="button" onclick="closeDeleteModal()">
                            ยกเลิก
                        </button>
                        <button class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700" type="submit">
                            ลบการลงทะเบียน
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section("scripts")
    <script>
        function toggleAttendTime() {
            const checkbox = document.getElementById('attendCheckbox');
            const timeDiv = document.getElementById('attendTimeDiv');
            const timeSelect = document.querySelector('select[name="time_id"]');

            if (checkbox.checked) {
                timeDiv.classList.remove('hidden');
                if (timeSelect.value) {
                    setDefaultAttendDateTime();
                }
            } else {
                timeDiv.classList.add('hidden');
            }
        }

        function toggleApproveTime() {
            const checkbox = document.getElementById('approveCheckbox');
            const timeDiv = document.getElementById('approveTimeDiv');
            const timeSelect = document.querySelector('select[name="time_id"]');

            if (checkbox.checked) {
                timeDiv.classList.remove('hidden');
                if (timeSelect.value) {
                    setDefaultApproveDateTime();
                }
            } else {
                timeDiv.classList.add('hidden');
            }
        }

        function setDefaultAttendDateTime() {
            const timeSelect = document.querySelector('select[name="time_id"]');
            const attendDateTime = document.getElementById('attendDateTime');

            if (timeSelect.value) {
                const selectedOption = timeSelect.options[timeSelect.selectedIndex];
                const optgroup = selectedOption.parentElement;
                const dateLabel = optgroup.label;

                // Extract date from optgroup label (format: "วันที่ 1 25/12/2024")
                const dateMatch = dateLabel.match(/(\d{1,2}\/\d{1,2}\/\d{4})/);
                if (dateMatch) {
                    const dateStr = dateMatch[1];
                    const [day, month, year] = dateStr.split('/');
                    const dateTimeStr = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}T08:00`;
                    attendDateTime.value = dateTimeStr;
                }
            }
        }

        function setDefaultApproveDateTime() {
            const timeSelect = document.querySelector('select[name="time_id"]');
            const approveDateTime = document.getElementById('approveDateTime');

            if (timeSelect.value) {
                const selectedOption = timeSelect.options[timeSelect.selectedIndex];
                const optgroup = selectedOption.parentElement;
                const dateLabel = optgroup.label;

                // Extract date from optgroup label (format: "วันที่ 1 25/12/2024")
                const dateMatch = dateLabel.match(/(\d{1,2}\/\d{1,2}\/\d{4})/);
                if (dateMatch) {
                    const dateStr = dateMatch[1];
                    const [day, month, year] = dateStr.split('/');
                    const dateTimeStr = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}T17:00`;
                    approveDateTime.value = dateTimeStr;
                }
            }
        }

        function toggleEditAttendTime() {
            const checkbox = document.getElementById('editAttendDatetime');
            const timeDiv = document.getElementById('editAttendTimeDiv');
            const timeSelect = document.getElementById('editTimeId');

            if (checkbox.checked) {
                timeDiv.classList.remove('hidden');
                if (timeSelect.value) {
                    setDefaultEditAttendDateTime();
                }
            } else {
                timeDiv.classList.add('hidden');
            }
        }

        function toggleEditApproveTime() {
            const checkbox = document.getElementById('editApproveDatetime');
            const timeDiv = document.getElementById('editApproveTimeDiv');
            const timeSelect = document.getElementById('editTimeId');

            if (checkbox.checked) {
                timeDiv.classList.remove('hidden');
                if (timeSelect.value) {
                    setDefaultEditApproveDateTime();
                }
            } else {
                timeDiv.classList.add('hidden');
            }
        }

        function setDefaultEditAttendDateTime() {
            const timeSelect = document.getElementById('editTimeId');
            const attendDateTime = document.getElementById('editAttendDateTime');

            if (timeSelect.value) {
                const selectedOption = timeSelect.options[timeSelect.selectedIndex];
                const optgroup = selectedOption.parentElement;
                const dateLabel = optgroup.label;

                // Extract date from optgroup label (format: "วันที่ 1 25/12/2024")
                const dateMatch = dateLabel.match(/(\d{1,2}\/\d{1,2}\/\d{4})/);
                if (dateMatch) {
                    const dateStr = dateMatch[1];
                    const [day, month, year] = dateStr.split('/');
                    const dateTimeStr = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}T08:00`;
                    attendDateTime.value = dateTimeStr;
                }
            }
        }

        function setDefaultEditApproveDateTime() {
            const timeSelect = document.getElementById('editTimeId');
            const approveDateTime = document.getElementById('editApproveDateTime');

            if (timeSelect.value) {
                const selectedOption = timeSelect.options[timeSelect.selectedIndex];
                const optgroup = selectedOption.parentElement;
                const dateLabel = optgroup.label;

                // Extract date from optgroup label (format: "วันที่ 1 25/12/2024")
                const dateMatch = dateLabel.match(/(\d{1,2}\/\d{1,2}\/\d{4})/);
                if (dateMatch) {
                    const dateStr = dateMatch[1];
                    const [day, month, year] = dateStr.split('/');
                    const dateTimeStr = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}T17:00`;
                    approveDateTime.value = dateTimeStr;
                }
            }
        }

        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
            document.getElementById('addModal').classList.add('flex');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
            document.getElementById('addModal').classList.remove('flex');
        }

        function openEditModal(registrationId, userName, timeId, hasAttended, hasApproved, attendDateTime, approveDateTime) {
            document.getElementById('editUserName').value = userName;
            document.getElementById('editTimeId').value = timeId;
            document.getElementById('editAttendDatetime').checked = hasAttended === 'true';
            document.getElementById('editApproveDatetime').checked = hasApproved === 'true';

            // Set datetime values if provided
            if (attendDateTime) {
                document.getElementById('editAttendDateTime').value = attendDateTime;
            }
            if (approveDateTime) {
                document.getElementById('editApproveDateTime').value = approveDateTime;
            }

            // Show/hide time inputs based on checkbox states
            toggleEditAttendTime();
            toggleEditApproveTime();

            document.getElementById('editForm').action = `{{ url("/hrd/admin/projects/{$project->id}/registrations") }}/${registrationId}`;

            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
        }

        function confirmDelete(registrationId, userName) {
            document.getElementById('deleteUserName').textContent = userName;
            document.getElementById('deleteForm').action = `{{ url("/hrd/admin/projects/{$project->id}/registrations") }}/${registrationId}`;

            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('fixed')) {
                event.target.classList.add('hidden');
                event.target.classList.remove('flex');
            }
        });

        // Add event listeners for time select changes
        document.addEventListener('DOMContentLoaded', function() {
            // Add modal time select change
            const addTimeSelect = document.querySelector('select[name="time_id"]');
            if (addTimeSelect) {
                addTimeSelect.addEventListener('change', function() {
                    if (document.getElementById('attendCheckbox').checked) {
                        setDefaultAttendDateTime();
                    }
                    if (document.getElementById('approveCheckbox').checked) {
                        setDefaultApproveDateTime();
                    }
                });
            }

            // Edit modal time select change
            const editTimeSelect = document.getElementById('editTimeId');
            if (editTimeSelect) {
                editTimeSelect.addEventListener('change', function() {
                    if (document.getElementById('editAttendDatetime').checked) {
                        setDefaultEditAttendDateTime();
                    }
                    if (document.getElementById('editApproveDatetime').checked) {
                        setDefaultEditApproveDateTime();
                    }
                });
            }
        });
    </script>
@endsection
