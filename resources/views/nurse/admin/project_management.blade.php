@extends("layouts.nurse")
@section("content")
    <div class="m-auto flex">
        <div class="m-auto mt-3 w-full rounded p-3 md:w-3/4">
            <div class="cursor-pointer text-end text-red-600" onclick="deleteProject({{ $project->id }})">ลบการฝึกอบรม</div>
            <div class="text-2xl font-bold">
                <a class="text-blue-600" href="{{ route("nurse.admin.index") }}">Project Management</a>
                / {{ $project->title }}
            </div>
            <hr>
            <div class="my-3 flex gap-3">
                <a class="flex-1" href="{{ route("nurse.admin.approve.index") }}?project={{ $project->id }}&sign=false&time=all">
                    <div class="cursor-pointer rounded bg-green-600 p-3 text-center text-white"><i class="fa-solid fa-check-double"></i> Approve ผู้ลงทะเบียน</div>
                </a>
                <a class="flex-1" href="{{ route("nurse.admin.transactions.index", $project->id) }}">
                    <div class="cursor-pointer rounded bg-green-600 p-3 text-center text-white"><i class="fa-solid fa-users"></i> ผู้ลงทะเบียนทั้งหมด</div>
                </a>
            </div>
            <div class="text-2xl font-bold">Export</div>
            <hr>
            <a class="flex-1" href="{{ route("nurse.admin.export.excel.users", $project->id) }}">
                <div class="cursor-pointer rounded py-3 text-green-600 hover:text-green-800"><i class="fa-solid fa-file-excel"></i> Excel รายชื่อผู้ฝึกอบรมทั้งหมด</div>
            </a>
            <a class="flex-1" href="{{ route("nurse.admin.export.excel.lectures", $project->id) }}">
                <div class="cursor-pointer rounded py-3 text-green-600 hover:text-green-800"><i class="fa-solid fa-file-excel"></i> Excel รายชื่อวิทยากรทั้งหมด</div>
            </a>
            <a class="flex-1" href="{{ route("nurse.admin.export.excel.dbd", $project->id) }}">
                <div class="cursor-pointer rounded py-3 text-green-600 hover:text-green-800"><i class="fa-solid fa-file-excel"></i> Excel แบบฟอร์มกรมพัฒน์</div>
            </a>
            <a class="flex-1" href="{{ route("nurse.admin.export.excel.type", $project->id) }}">
                <div class="cursor-pointer rounded py-3 text-green-600 hover:text-green-800"><i class="fa-solid fa-file-excel"></i> Excel {{ $project->export_type_name }}</div>
            </a>
            <a class="flex-1" href="{{ route("nurse.admin.export.excel.onebook", $project->id) }}">
                <div class="cursor-pointer rounded py-3 text-green-600 hover:text-green-800"><i class="fa-solid fa-file-excel"></i> Excel Onebook หลักสูตร {{ $project->title }}</div>
            </a>
            <div class="text-2xl font-bold">วันที่เปิดลงทะเบียน</div>
            <hr class="mb-3">
            @foreach ($project->dateData as $date)
                <table class="mb-3 table w-full border-collapse">
                    <thead class="bg-gray-200">
                        <th class="border p-3 text-start" colspan="2">
                            <span>{{ $date->title }}</span>
                            <a href="{{ route("nurse.admin.export.excel.date.users", $date->id) }}">
                                <span class="ms-6 text-green-600 hover:text-green-800"><i class="fa-solid fa-file-excel"></i> รายชื่อผู้ฝึกอบรม</span>
                            </a>
                            <a href="{{ route("nurse.admin.export.excel.date.dbd", $date->id) }}">
                                <span class="ms-6 text-green-600 hover:text-green-800"><i class="fa-solid fa-file-excel"></i> แบบฟอร์มกรมพัฒน์</span>
                            </a>
                            <span class="float-end ms-3 cursor-pointer rounded bg-blue-400 p-2" onclick="addlecturer('{{ $date->id }}','{{ $date->title }}')"><i class="fa fa-plus"></i> วิทยากร</span>
                        </th>
                        <th class="w-36 border p-3">จำนวนลงทะเบียน</th>
                    </thead>
                    <tbody>
                        @foreach ($date->timeData as $time)
                            <tr class="bg-white">
                                <td class="border p-3" colspan="2">
                                    <div class="flex gap-3">
                                        @if ($time->max == 0)
                                            <div class="lg:w-42 cursor-pointer text-green-600 lg:flex-none" onclick="createTransaction('{{ $time->id }}','{{ $time->title }}')"><i class="fa-solid fa-plus"></i>&nbsp;เพิ่มผู้ลงทะเบียน</div>
                                        @elseif($time->max != 0)
                                            @if ($time->max == $time->transactionData->count())
                                                <div class="lg:w-42 flex-1 cursor-pointer text-red-600 lg:flex-none">
                                                    <i class="fa-solid fa-ban"></i>&nbsp;มีผู้ลงทะเบียนเต็มแล้ว
                                                </div>
                                            @elseif ($time->transactionData->count() < $time->max)
                                                <div class="lg:w-42 cursor-pointer text-green-600 lg:flex-none" onclick="createTransaction('{{ $time->id }}','{{ $time->title }}')"><i class="fa-solid fa-plus"></i>&nbsp;เพิ่มผู้ลงทะเบียน</div>
                                            @endif
                                        @endif
                                        <div class="flex-1">{{ $time->title }} </div>
                                    </div>
                                </td>
                                <td class="border p-3 text-center">{{ count($time->transactionData) }}</td>
                            </tr>
                        @endforeach
                        @if (count($date->lecturesData) > 0)
                            <tr>
                                <td class="border bg-gray-300 p-3">
                                    วิทยากร
                                    <a class="ms-6 cursor-pointer rounded py-3 text-green-600 hover:text-green-800" href="{{ route("nurse.admin.export.excel.datelecture", $date->id) }}">
                                        <i class="fa-solid fa-file-excel"></i> รายชื่อวิทยากร
                                    </a>
                                </td>
                                <td class="border bg-gray-300 p-3" colspan="2">คะแนนที่ได้</td>
                            </tr>
                            @foreach ($date->lecturesData as $lecture)
                                <tr class="bg-white hover:bg-green-200">
                                    <td class="border p-3">{{ $lecture->user_id . " " . $lecture->userData->name }}</td>
                                    <td class="w-36 border p-3">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <input class="form-input rounded border px-2 py-1" id="lecturer_{{ $lecture->id }}" type="number" min="0" value="{{ $lecture->score }}" placeholder="คะแนนวิทยากร">
                                            <button class="rounded bg-blue-500 px-3 py-1 text-white hover:bg-blue-600" type="button" onclick="updateLecturerScore('{{ $lecture->id }}')">Update</button>
                                            <span class="ml-2 text-sm" id="lecturer_feedback_{{ $lecture->id }}"></span>
                                        </div>
                                    </td>
                                    <td class="border p-3 text-center">
                                        <button class="cursor-pointer text-red-600" type="button" onclick="deleteLecture('{{ $lecture->id }}','{{ $lecture->userData->name }}')"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            @endforeach
        </div>
    </div>
@endsection
@section("scripts")
    <script>
        async function createTransaction(id, title) {
            alert = await Swal.fire({
                title: "ยืนยันข้อมูลการลงทะเบียน",
                html: 'รอบ ' + title + '<br>',
                input: "text",
                inputPlaceholder: "รหัสพนักงาน",
                icon: 'question',
                showConfirmButton: true,
                confirmButtonColor: 'green',
                confirmButtonText: 'ยืนยัน',
                showCancelButton: true,
                cancelButtonColor: 'gray',
                cancelButtonText: 'ยกเลิก',
            })

            if (alert.isConfirmed) {
                if (alert.value == '') {
                    Swal.fire({
                        title: "โปรดใส่รหัสพนักงาน",
                        icon: 'error',
                        showConfirmButton: true,
                        confirmButtonColor: 'red',
                        confirmButtonText: 'ยืนยัน',
                    })

                    return;
                }
                Swal.fire({
                    title: 'กรุณารอสักครู่',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                })
                axios.post('{{ route("nurse.admin.transactions.create") }}', {
                    'project_id': '{{ $project->id }}',
                    'time_id': id,
                    'user': alert.value
                }).then((res) => {
                    if (res['data']['status'] == 'success') {
                        Swal.fire({
                            title: res['data']['message'],
                            html: res['data']['name'] + ' รอบ ' + res['data']['time'],
                            icon: 'success',
                            confirmButtonText: 'ตกลง',
                            confirmButtonColor: 'green'
                        }).then(function(isConfirmed) {
                            if (isConfirmed) {
                                window.location.reload()
                            }
                        })
                    } else {
                        Swal.fire({
                            title: res['data']['message'],
                            icon: 'error',
                            confirmButtonText: 'ตกลง',
                            confirmButtonColor: 'red'
                        })
                    }

                });
            }
        }

        async function addlecturer(id, title) {
            alert = await Swal.fire({
                title: "ยืนยัน วิทยากร",
                html: 'รอบ ' + title + '<br>',
                input: "text",
                inputPlaceholder: "รหัสพนักงาน",
                icon: 'question',
                showConfirmButton: true,
                confirmButtonColor: 'green',
                confirmButtonText: 'ยืนยัน',
                showCancelButton: true,
                cancelButtonColor: 'gray',
                cancelButtonText: 'ยกเลิก',
            })

            if (alert.isConfirmed) {
                if (alert.value == '') {
                    Swal.fire({
                        title: "โปรดใส่รหัสพนักงาน",
                        icon: 'error',
                        showConfirmButton: true,
                        confirmButtonColor: 'red',
                        confirmButtonText: 'ยืนยัน',
                    })

                    return;
                }
                Swal.fire({
                    title: 'กรุณารอสักครู่',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                })
                axios.post('{{ route("nurse.admin.lecture.add") }}', {
                    'date_id': id,
                    'user': alert.value
                }).then((res) => {
                    if (res['data']['status'] == 'success') {
                        Swal.fire({
                            title: res['data']['message'],
                            icon: 'success',
                            confirmButtonText: 'ตกลง',
                            confirmButtonColor: 'green'
                        }).then(function(isConfirmed) {
                            if (isConfirmed) {
                                window.location.reload()
                            }
                        })
                    } else {
                        Swal.fire({
                            title: res['data']['message'],
                            icon: 'error',
                            confirmButtonText: 'ตกลง',
                            confirmButtonColor: 'red'
                        })
                    }

                });
            }
        }

        async function deleteLecture(id, name) {
            alert = await Swal.fire({
                title: "ยืนยันลบวิทยากร " + name,
                icon: 'warning',
                showConfirmButton: true,
                confirmButtonColor: 'red',
                confirmButtonText: 'ยืนยัน',
                showCancelButton: true,
                cancelButtonColor: 'gray',
                cancelButtonText: 'ยกเลิก',
            })

            if (alert.isConfirmed) {
                axios.post('{{ route("nurse.admin.lecture.delete") }}', {
                    'lecture_id': id
                }).then((res) => {
                    Swal.fire({
                        title: res['data']['message'],
                        icon: 'success',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: 'green'
                    }).then(function(isConfirmed) {
                        if (isConfirmed) {
                            window.location.reload()
                        }
                    })
                });
            }
        }

        async function deleteProject(id) {
            alert = await Swal.fire({
                title: "ยืนยันลบโครงการ",
                html: "คุณต้องการลบโครงการนี้ใช่หรือไม่? <br> หากต้องการกู้คืนโปรดติดต่อแผนก IT",
                icon: 'warning',
                showConfirmButton: true,
                confirmButtonColor: 'red',
                confirmButtonText: 'ยืนยัน',
                showCancelButton: true,
                cancelButtonColor: 'gray',
                cancelButtonText: 'ยกเลิก',
            })

            if (alert.isConfirmed) {
                axios.post('{{ route("nurse.admin.project.delete") }}', {
                    'project_id': id
                }).then((res) => {
                    Swal.fire({
                        title: 'ลบโครงการสำเร็จ',
                        icon: 'success',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: 'green'
                    }).then(function(isConfirmed) {
                        if (isConfirmed) {
                            window.location = '{{ route("nurse.admin.index") }}';
                        }
                    })
                });
            }
        }

        function updateLecturerScore(lectureId) {
            const input = document.getElementById('lecturer_' + lectureId);
            const feedback = document.getElementById('lecturer_feedback_' + lectureId);
            const score = input.value;
            feedback.textContent = 'Updating...';
            feedback.className = 'ml-2 text-sm text-gray-500';

            axios.post(
                    '{{ route("nurse.admin.lecturer.update-score") }}', {
                        lecture_id: lectureId,
                        score: score
                    }
                )
                .then(response => {
                    if (response.data.success) {
                        feedback.textContent = 'Updated!';
                        feedback.className = 'ml-2 text-sm text-green-600';
                    } else {
                        feedback.textContent = response.data.message || 'Update failed.';
                        feedback.className = 'ml-2 text-sm text-red-600';
                    }
                })
                .catch(error => {
                    feedback.textContent = 'Network error.';
                    feedback.className = 'ml-2 text-sm text-red-600';
                });
        }
    </script>
@endsection
