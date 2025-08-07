@extends("layouts.training")

@section("content")
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="mb-2 text-2xl font-bold text-gray-900">Teacher Dashboard</h1>
            <p class="text-gray-600">Manage attendance approvals for your training sessions</p>
        </div>

        <div class="rounded-lg bg-white p-6 shadow-md">
            <form class="mb-6 flex flex-col items-center gap-4 sm:flex-row" method="GET" action="">
                <label class="font-medium text-gray-700" for="filterAdmin">Status:</label>
                <select class="rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" id="filterAdmin" name="admin">
                    <option value="all" {{ $filterAdmin == "all" ? "selected" : "" }}>All</option>
                    <option value="false" {{ $filterAdmin == "false" ? "selected" : "" }}>Not Approved</option>
                    <option value="true" {{ $filterAdmin == "true" ? "selected" : "" }}>Approved</option>
                </select>
            </form>

            @if (!$attendances->isEmpty() && $attendances->where("admin", false)->count() > 0)
                <div class="mb-4 flex justify-end">
                    <button class="flex items-center gap-2 rounded bg-green-600 px-5 py-2 text-white shadow transition hover:bg-green-700" id="approve-all-btn" type="button" onclick="approveusers()">
                        <span id="approve-all-text">Approve All records.</span>
                        <svg class="hidden h-5 w-5 animate-spin text-white" id="approve-all-spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                    </button>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full rounded-lg border border-gray-200 bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Department</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @php
                            $attend_array = [];
                        @endphp
                        @if ($attendances->isEmpty())
                            <tr class="py-8 text-center text-gray-500">
                                <td colspan="6">ไม่พบข้อมูลการเข้าอบรมสำหรับวันที่นี้</td>
                            </tr>
                        @else
                            @foreach ($attendances as $attendance)
                                @if (isset($attendance->date) && $attendance->date->time->session->teacher->name === auth()->user()->name)
                                    @php
                                        $attend_array[] = $attendance->id;
                                    @endphp

                                    <tr class="hover:bg-gray-50">
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $attendance->user_info["name_EN"] }}</div>
                                            <div class="text-sm text-gray-500">{{ $attendance->userid }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $attendance->user_info["department_EN"] }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $attendance->date->name }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $attendance->date->time->name }}</td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            @if ($attendance->admin)
                                                <span class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">Approved</span>
                                            @else
                                                <span class="inline-flex rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800">Pending</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                            @if (!$attendance->admin)
                                                <button class="rounded bg-blue-600 px-3 py-1 text-white transition hover:bg-blue-700" onclick="approveuser({{ $attendance->id }})">Approve</button>
                                            @else
                                                <span class="text-gray-500">Already Approved</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section("scripts")
    <script>
        document.getElementById('filterAdmin').addEventListener('change', function() {
            this.form.submit();
        });

        function approveuser(id) {
            Swal.fire({
                title: 'Confirm Approval',
                text: 'Do you want to approve this attendance record?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Approve',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('{{ route("training.admin.approve.user") }}', {
                        id: id
                    }).then((res) => {
                        Swal.fire(
                            'Approved!',
                            'The attendance record has been approved successfully',
                            'success'
                        ).then(() => {
                            window.location.reload();
                        });
                    }).catch((error) => {
                        Swal.fire(
                            'Error!',
                            'Unable to approve the attendance record',
                            'error'
                        );
                    });
                }
            });
        }

        function approveusers() {
            Swal.fire({
                title: 'Confirm Bulk Approval',
                text: 'Do you want to approve all unapproved attendance records?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Approve All',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const btn = document.getElementById('approve-all-btn');
                    const text = document.getElementById('approve-all-text');
                    const spinner = document.getElementById('approve-all-spinner');

                    btn.disabled = true;
                    text.textContent = 'Approving...';
                    spinner.classList.remove('hidden');

                    axios.post('{{ route("training.admin.approve.teacher") }}', {
                        ids: '{{ json_encode($attend_array) }}'
                    }).then((res) => {
                        Swal.fire(
                            'Approved!',
                            'All attendance records have been approved successfully',
                            'success'
                        ).then(() => {
                            window.location.reload();
                        });
                    }).catch((error) => {
                        btn.disabled = false;
                        text.textContent = 'Approve All records.';
                        spinner.classList.add('hidden');
                        Swal.fire(
                            'Error!',
                            'Unable to approve the attendance records',
                            'error'
                        );
                    });
                }
            });
        }
    </script>
@endsection
