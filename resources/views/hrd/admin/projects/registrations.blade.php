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
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-4">
            <div class="rounded-lg bg-blue-50 p-4">
                <div class="flex items-center">
                    <i class="fas fa-users mr-3 text-2xl text-blue-600"></i>
                    <div>
                        <p class="text-2xl font-bold text-blue-900">{{ $registrations->total() }}</p>
                        <p class="text-sm text-blue-700">การลงทะเบียนทั้งหมด</p>
                    </div>
                </div>
            </div>
            <div class="rounded-lg bg-green-50 p-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-2xl text-green-600"></i>
                    <div>
                        <p class="text-2xl font-bold text-green-900">{{ $registrations->where("attend_datetime", "!=", null)->count() }}</p>
                        <p class="text-sm text-green-700">เข้าร่วมแล้ว (Check-in)</p>
                    </div>
                </div>
            </div>
            <div class="rounded-lg bg-yellow-50 p-4">
                <div class="flex items-center">
                    <i class="fas fa-clock mr-3 text-2xl text-yellow-600"></i>
                    <div>
                        <p class="text-2xl font-bold text-yellow-900">{{ $registrations->where("attend_datetime", null)->count() }}</p>
                        <p class="text-sm text-yellow-700">รอเข้าร่วม</p>
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
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">สถานะ</th>
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
                                        <div class="flex space-x-2">
                                            <button class="rounded bg-blue-600 px-2 py-1 text-xs text-white hover:bg-blue-700" onclick="openEditModal({{ $registration->id }}, '{{ $registration->user->name ?? "N/A" }}', {{ $registration->time_id }}, '{{ $registration->attend_datetime ? "true" : "false" }}')">
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
                    <i class="fas fa-users mb-4 text-4xl text-gray-300"></i>
                    <p class="text-gray-500">ยังไม่มีการลงทะเบียน</p>
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

            <form action="{{ route("hrd.admin.registrations.store", $project->id) }}" method="POST">
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
                                <optgroup label="{{ $date->date_title }} ({{ \Carbon\Carbon::parse($date->date_datetime)->format("d/m/Y") }})">
                                    @foreach ($date->times as $time)
                                        <option value="{{ $time->id }}">
                                            {{ $time->time_title }} ({{ $time->time_start }} - {{ $time->time_end }})
                                            @if ($time->time_limit)
                                                - ลงทะเบียนแล้ว: {{ $time->attends->where("attend_delete", false)->count() }}/{{ $time->time_max }}
                                            @endif
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" type="checkbox" name="attend_datetime">
                            <span class="ml-2 text-sm text-gray-700">เข้าร่วมแล้ว (Check-in)</span>
                        </label>
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
                                <optgroup label="{{ $date->date_title }} ({{ \Carbon\Carbon::parse($date->date_datetime)->format("d/m/Y") }})">
                                    @foreach ($date->times as $time)
                                        <option value="{{ $time->id }}">
                                            {{ $time->time_title }} ({{ $time->time_start }} - {{ $time->time_end }})
                                            @if ($time->time_limit)
                                                - ลงทะเบียนแล้ว: {{ $time->attends->where("attend_delete", false)->count() }}/{{ $time->time_max }}
                                            @endif
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" id="editAttendDatetime" type="checkbox" name="attend_datetime">
                            <span class="ml-2 text-sm text-gray-700">เข้าร่วมแล้ว (Check-in)</span>
                        </label>
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
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
            document.getElementById('addModal').classList.add('flex');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
            document.getElementById('addModal').classList.remove('flex');
        }

        function openEditModal(registrationId, userName, timeId, hasAttended) {
            document.getElementById('editUserName').value = userName;
            document.getElementById('editTimeId').value = timeId;
            document.getElementById('editAttendDatetime').checked = hasAttended === 'true';
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
    </script>
@endsection
