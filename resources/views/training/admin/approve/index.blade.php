@extends("layouts.training")
@section("content")
    <div class="mx-auto mt-10 max-w-6xl rounded-lg bg-white p-6 shadow-md">
        <a class="mb-3 inline-flex items-center text-gray-600 transition-colors hover:text-gray-800" href="{{ route("training.admin.index") }}">
            <i class="fa-solid fa-arrow-left mr-2"></i>Back to Management
        </a>
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-2xl font-bold text-blue-800">อนุมัติการเข้าอบรม</h2>
        </div>
        <form class="mb-6 flex flex-col items-center gap-4 sm:flex-row" method="GET" action="">
            <label class="font-medium text-gray-700" for="filterDate">เลือกวันที่:</label>
            <input class="rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" id="filterDate" type="date" name="name" value="{{ $filterDate }}">
            <label class="font-medium text-gray-700" for="filterAdmin">สถานะอนุมัติ:</label>
            <select class="rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" id="filterAdmin" name="admin">
                <option value="all" {{ $filterAdmin == "all" ? "selected" : "" }}>ทั้งหมด</option>
                <option value="true" {{ $filterAdmin == "true" ? "selected" : "" }}>อนุมัติแล้ว</option>
                <option value="false" {{ $filterAdmin == "false" ? "selected" : "" }}>ยังไม่อนุมัติ</option>
            </select>
            <button class="rounded bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700" type="submit">ค้นหา</button>
        </form>

        @if (!$attendances->isEmpty() && $attendances->where("admin", false)->count() > 0)
            <div class="mb-4 flex justify-end">
                <button class="flex items-center gap-2 rounded bg-green-600 px-5 py-2 text-white shadow transition hover:bg-green-700" id="approve-all-btn" type="button" onclick="approveusers()">
                    <span id="approve-all-text">อนุมัติทั้งหมด</span>
                    <svg class="hidden h-5 w-5 animate-spin text-white" id="approve-all-spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Client-side filters -->
        <div class="mb-4 flex flex-wrap gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700" for="filterTeam">กลุ่ม</label>
                <select class="rounded border border-gray-300 px-3 py-2" id="filterTeam">
                    <option value="">ทั้งหมด</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700" for="filterTeacher">ครูผู้สอน</label>
                <select class="rounded border border-gray-300 px-3 py-2" id="filterTeacher">
                    <option value="">ทั้งหมด</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700" for="filterTime">เวลา</label>
                <select class="rounded border border-gray-300 px-3 py-2" id="filterTimeClient">
                    <option value="">ทั้งหมด</option>
                </select>
            </div>
        </div>

        @if ($attendances->isEmpty())
            <div class="py-8 text-center text-gray-500">ไม่พบข้อมูลการเข้าอบรมสำหรับวันที่นี้</div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full rounded-lg border border-gray-200 bg-white">
                    <thead class="bg-blue-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-gray-700">รหัสผู้ใช้</th>
                            <th class="px-4 py-2 text-left text-gray-700">วันที่</th>
                            <th class="px-4 py-2 text-left text-gray-700">เวลาเช็คอิน</th>
                            <th class="px-4 py-2 text-left text-gray-700">กลุ่ม</th>
                            <th class="px-4 py-2 text-left text-gray-700">ครูผู้สอน</th>
                            <th class="px-4 py-2 text-left text-gray-700">เวลา</th>
                            <th class="px-4 py-2 text-center text-gray-700">สถานะผู้ใช้</th>
                            <th class="px-4 py-2 text-center text-gray-700">สถานะแอดมิน</th>
                            <th class="px-4 py-2 text-center text-gray-700">การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attendances as $attend)
                            <tr class="border-t border-gray-100 hover:bg-blue-50" id="row-{{ $attend->id }}">
                                <td class="px-4 py-2">{{ $attend->user_id }}</td>
                                <td class="px-4 py-2">{{ $attend->date_name }}</td>
                                <td class="px-4 py-2">{{ date("H:i", strtotime($attend->user_date)) }}</td>
                                <td class="px-4 py-2">
                                    {{ $attend->date && $attend->date->time && $attend->date->time->session && $attend->date->time->session->teacher && $attend->date->time->session->teacher->team ? $attend->date->time->session->teacher->team->name : "-" }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $attend->date && $attend->date->time && $attend->date->time->session && $attend->date->time->session->teacher ? $attend->date->time->session->teacher->name : "-" }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $attend->date && $attend->date->time ? $attend->date->time->name : "-" }}
                                </td>
                                <td class="px-4 py-2 text-center">
                                    @if ($attend->user)
                                        <span class="m-auto flex h-6 w-6 items-center justify-center rounded-full bg-green-100 text-green-600">✔</span>
                                    @else
                                        <span class="m-auto flex h-6 w-6 items-center justify-center rounded-full bg-red-100 text-red-600">✗</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-center" id="admin-status-{{ $attend->id }}">
                                    @if ($attend->admin)
                                        <span class="m-auto flex h-6 w-6 items-center justify-center rounded-full bg-green-100 text-green-600">✔</span>
                                    @else
                                        <span class="m-auto flex h-6 w-6 items-center justify-center rounded-full bg-red-100 text-red-600">✗</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-center" id="action-{{ $attend->id }}">
                                    @if (!$attend->admin)
                                        <button class="approve-btn rounded bg-green-500 px-3 py-1 text-white transition hover:bg-green-600" data-id="{{ $attend->id }}" onclick="approveuser('{{ $attend->id }}')" type="button">อนุมัติ</button>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
@section("scripts")
    <script>
        document.getElementById('filterAdmin').addEventListener('change', function() {
            this.form.submit();
        });

        // --- Client-side filter logic ---
        const attendances = @json($attendances);
        // Helper to safely get nested property
        function get(obj, path) {
            return path.split('.').reduce((o, p) => (o && o[p] !== undefined ? o[p] : null), obj);
        }

        // Build unique lists for dropdowns
        const teams = [...new Set(attendances.map(a => get(a, 'date.time.session.teacher.team.name')).filter(Boolean))];
        const teachers = [...new Set(attendances.map(a => get(a, 'date.time.session.teacher.name')).filter(Boolean))];
        const times = [...new Set(attendances.map(a => get(a, 'date.time.name')).filter(Boolean))];

        // Populate dropdowns
        const teamSelect = document.getElementById('filterTeam');
        const teacherSelect = document.getElementById('filterTeacher');
        const timeSelect = document.getElementById('filterTimeClient');
        teams.forEach(team => {
            const opt = document.createElement('option');
            opt.value = team;
            opt.textContent = team;
            teamSelect.appendChild(opt);
        });
        teachers.forEach(teacher => {
            const opt = document.createElement('option');
            opt.value = teacher;
            opt.textContent = teacher;
            teacherSelect.appendChild(opt);
        });
        times.forEach(time => {
            const opt = document.createElement('option');
            opt.value = time;
            opt.textContent = time;
            timeSelect.appendChild(opt);
        });

        // Filtering logic
        function filterTable() {
            const team = teamSelect.value;
            const teacher = teacherSelect.value;
            const time = timeSelect.value;
            document.querySelectorAll('tbody tr').forEach(row => {
                const rowTeam = row.querySelector('td:nth-child(4)')?.textContent.trim();
                const rowTeacher = row.querySelector('td:nth-child(5)')?.textContent.trim();
                const rowTime = row.querySelector('td:nth-child(6)')?.textContent.trim();
                let show = true;
                if (team && rowTeam !== team) show = false;
                if (teacher && rowTeacher !== teacher) show = false;
                if (time && rowTime !== time) show = false;
                row.style.display = show ? '' : 'none';
            });
        }
        teamSelect.addEventListener('change', filterTable);
        teacherSelect.addEventListener('change', filterTable);
        timeSelect.addEventListener('change', filterTable);

        function approveuser(id) {
            const button = document.querySelector('button[data-id="' + id + '"]');
            if (button) {
                button.disabled = true;
                button.innerHTML = '<svg class="animate-spin h-5 w-5 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>';
            }
            axios.post('{{ route("training.admin.approve.user") }}', {
                'id': id,
            }).then((res) => {
                if (res.data.status === 'success') {
                    document.getElementById('admin-status-' + id).innerHTML = '<span class="flex h-6 w-6 items-center justify-center rounded-full bg-green-100 text-green-600">✔</span>';
                    document.getElementById('action-' + id).innerHTML = '<span class="text-gray-400">-</span>';
                } else {
                    if (button) {
                        button.disabled = false;
                        button.innerHTML = 'อนุมัติ';
                    }
                    alert('เกิดข้อผิดพลาด: ' + (res.data.message || 'ไม่สามารถอนุมัติได้'));
                }
            }).catch(() => {
                if (button) {
                    button.disabled = false;
                    button.innerHTML = 'อนุมัติ';
                }
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
            });
        }

        function approveusers() {
            const btn = document.getElementById('approve-all-btn');
            const text = document.getElementById('approve-all-text');
            const spinner = document.getElementById('approve-all-spinner');
            btn.disabled = true;
            text.classList.add('hidden');
            spinner.classList.remove('hidden');
            axios.post('{{ route("training.admin.approve.users") }}', {
                name: '{{ $filterDate }}',
                admin: '{{ $filterAdmin }}'
            }).then((res) => {
                if (res.data.status === 'success') {
                    window.location.reload();
                } else {
                    btn.disabled = false;
                    text.classList.remove('hidden');
                    spinner.classList.add('hidden');
                    alert('เกิดข้อผิดพลาด: ' + (res.data.message || 'ไม่สามารถอนุมัติทั้งหมดได้'));
                }
            }).catch(() => {
                btn.disabled = false;
                text.classList.remove('hidden');
                spinner.classList.add('hidden');
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
            });
        }
    </script>
@endsection
