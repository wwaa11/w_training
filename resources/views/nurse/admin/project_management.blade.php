@extends("layouts.nurse")
@section("content")
    <div class="min-h-screen">
        <!-- Header Section -->
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-4">
                <a class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600 transition-colors duration-200 hover:bg-blue-200" href="{{ route("nurse.admin.index") }}">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
                <div class="flex-1">
                    <div class="flex items-start justify-between">
                        <h1 class="break-words font-bold text-gray-900" style="font-size: 1.875rem;">{{ $project->title }}</h1>
                    </div>
                    <div class="mt-2 flex items-center space-x-3">
                        <span class="{{ $project->multiple ? "bg-green-100 text-green-800" : "bg-blue-100 text-blue-800" }} inline-flex items-center rounded-full px-3 py-1 text-sm font-medium">
                            <i class="fas fa-{{ $project->multiple ? "users" : "user" }} mr-2"></i>
                            {{ $project->multiple ? "ลงทะเบียนได้มากกว่า 1 ครั้ง" : "ลงทะเบียน 1 ครั้ง" }}
                        </span>
                        <button class="inline-flex items-center rounded-lg bg-red-50 px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-100" type="button" onclick="deleteProject({{ $project->id }})">
                            <i class="fa fa-trash mr-2"></i> ลบการฝึกอบรม
                        </button>
                    </div>
                </div>
            </div>

            @if (session("success"))
                <div class="mt-4 rounded-lg border border-green-200 bg-green-50 p-4">
                    <div class="flex">
                        <i class="fas fa-check-circle mr-3 mt-0.5 text-green-400"></i>
                        <p class="text-green-800">{{ session("success") }}</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            <!-- Action Buttons Section -->
            <div class="mb-8 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 flex items-center text-lg font-semibold text-gray-900">
                    <i class="fas fa-cogs mr-3 text-blue-600"></i>
                    การจัดการโปรเจกต์
                </h2>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    <a class="flex h-12 w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-3 text-sm font-medium text-white transition-colors duration-200 hover:bg-blue-700" href="{{ route("nurse.admin.transactions.index", $project->id) }}">
                        <i class="fas fa-users mr-2"></i>
                        รายการทะเบียนทั้งหมด
                    </a>
                    <a class="flex h-12 w-full items-center justify-center rounded-lg bg-green-600 px-4 py-3 text-sm font-medium text-white transition-colors duration-200 hover:bg-green-700" href="{{ route("nurse.admin.approve.index") }}?project={{ $project->id }}&sign=false&time=all">
                        <i class="fas fa-check mr-2"></i>
                        อนุมัติผู้ลงทะเบียน
                    </a>
                    <a class="flex h-12 w-full items-center justify-center rounded-lg bg-yellow-600 px-4 py-3 text-sm font-medium text-white transition-colors duration-200 hover:bg-yellow-700" href="{{ route("nurse.admin.project.edit", $project->id) }}">
                        <i class="fas fa-edit mr-2"></i>
                        แก้ไขโครงการ
                    </a>
                </div>
            </div>

            <!-- Project Information -->
            <div class="rounded-xl border border-gray-200 bg-white py-6 shadow-sm">
                <div class="border-b border-gray-200 px-6 pb-4">
                    <h2 class="flex items-center text-lg font-semibold text-gray-900">
                        <i class="fas fa-info-circle mr-3 text-blue-600"></i>
                        ข้อมูลโปรเจกต์
                    </h2>
                </div>
                <div class="p-6">
                    @if ($project->detail)
                        <div class="mb-6">
                            <h3 class="mb-2 text-sm font-medium text-gray-700">รายละเอียด</h3>
                            <p class="leading-relaxed text-gray-900">{{ $project->detail }}</p>
                        </div>
                    @endif
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <h3 class="mb-2 text-sm font-medium text-gray-700">เริ่มลงทะเบียน</h3>
                            <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($project->register_start)->format("d/m/Y H:i") }}</p>
                        </div>
                        <div>
                            <h3 class="mb-2 text-sm font-medium text-gray-700">สิ้นสุดลงทะเบียน</h3>
                            <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($project->register_end)->format("d/m/Y H:i") }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reports and Management Section -->
            <div class="mt-6 rounded-xl bg-white p-6 shadow-sm">
                <h2 class="mb-6 flex items-center text-lg font-semibold text-gray-900">
                    <i class="fas fa-chart-line mr-3 text-blue-600"></i>
                    รายงานการส่งออก
                </h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <a class="group flex items-center rounded-lg bg-green-50 p-4 transition-colors duration-200 hover:bg-green-100" href="{{ route("nurse.admin.export.excel.users", $project->id) }}">
                        <div class="flex-shrink-0">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 transition-colors duration-200 group-hover:bg-green-200">
                                <i class="fas fa-file-excel text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="font-medium text-gray-900">รายชื่อผู้ฝึกอบรมทั้งหมด</p>
                            <p class="text-sm text-gray-600">ส่งออกผู้ลงทะเบียนทั้งหมด</p>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400 transition-colors duration-200 group-hover:text-gray-600"></i>
                    </a>

                    <a class="group flex items-center rounded-lg bg-green-50 p-4 transition-colors duration-200 hover:bg-green-100" href="{{ route("nurse.admin.export.excel.lectures", $project->id) }}">
                        <div class="flex-shrink-0">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 transition-colors duration-200 group-hover:bg-green-200">
                                <i class="fas fa-file-excel text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="font-medium text-gray-900">รายชื่อวิทยากรทั้งหมด</p>
                            <p class="text-sm text-gray-600">ส่งออกรายชื่อวิทยากร</p>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400 transition-colors duration-200 group-hover:text-gray-600"></i>
                    </a>

                    <a class="group flex items-center rounded-lg bg-green-50 p-4 transition-colors duration-200 hover:bg-green-100" href="{{ route("nurse.admin.export.excel.dbd", $project->id) }}">
                        <div class="flex-shrink-0">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 transition-colors duration-200 group-hover:bg-green-200">
                                <i class="fas fa-file-excel text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="font-medium text-gray-900">แบบฟอร์มกรมพัฒน์</p>
                            <p class="text-sm text-gray-600">ส่งออกแบบฟอร์มตามกรมพัฒน์</p>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400 transition-colors duration-200 group-hover:text-gray-600"></i>
                    </a>

                    <a class="group flex items-center rounded-lg bg-green-50 p-4 transition-colors duration-200 hover:bg-green-100" href="{{ route("nurse.admin.export.excel.type", $project->id) }}">
                        <div class="flex-shrink-0">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 transition-colors duration-200 group-hover:bg-green-200">
                                <i class="fas fa-file-excel text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="font-medium text-gray-900">{{ $project->export_type_name }}</p>
                            <p class="text-sm text-gray-600">ส่งออกตามประเภทการรายงาน</p>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400 transition-colors duration-200 group-hover:text-gray-600"></i>
                    </a>

                    <a class="group flex items-center rounded-lg bg-green-50 p-4 transition-colors duration-200 hover:bg-green-100" href="{{ route("nurse.admin.export.excel.onebook", $project->id) }}">
                        <div class="flex-shrink-0">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 transition-colors duration-200 group-hover:bg-green-200">
                                <i class="fas fa-file-excel text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="font-medium text-gray-900">Onebook หลักสูตร {{ $project->title }}</p>
                            <p class="text-sm text-gray-600">ส่งออกแบบ Onebook</p>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400 transition-colors duration-200 group-hover:text-gray-600"></i>
                    </a>
                </div>
            </div>

            <!-- Registration Dates Section -->
            <div class="mt-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 flex items-center text-lg font-semibold text-gray-900">
                    <i class="fas fa-calendar-alt mr-3 text-blue-600"></i>
                    วันที่เปิดลงทะเบียน
                </h2>
                @foreach ($project->dateData as $date)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <th class="p-3 text-start" colspan="2">
                                <span>{{ $date->title }}</span>
                                <a class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-600 hover:text-green-800" href="{{ route("nurse.admin.export.excel.date.users", $date->id) }}">
                                    <span class="text-green-600 hover:text-green-800"><i class="fa-solid fa-file-excel"></i> รายชื่อผู้ฝึกอบรม</span>
                                </a>
                                <a class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-600 hover:text-green-800" href="{{ route("nurse.admin.export.excel.date.dbd", $date->id) }}">
                                    <span class="text-green-600 hover:text-green-800"><i class="fa-solid fa-file-excel"></i> แบบฟอร์มกรมพัฒน์</span>
                                </a>
                                <button class="float-end ms-3 inline-flex items-center rounded-md bg-blue-500 px-2.5 py-1.5 text-white hover:bg-blue-600" type="button" onclick="addlecturer('{{ $date->id }}','{{ $date->title }}')"><i class="fa fa-plus mr-1"></i> วิทยากร</button>
                            </th>
                            <th class="w-36 p-3">จำนวนลงทะเบียน</th>
                        </thead>
                        <tbody>
                            @foreach ($date->timeData as $time)
                                <tr>
                                    <td class="p-3" colspan="2">
                                        <div class="flex gap-3">
                                            @if ($time->max == 0)
                                                <div class="lg:w-42 cursor-pointer text-green-600 hover:text-green-700 lg:flex-none" onclick="createTransaction('{{ $time->id }}','{{ $time->title }}')"><i class="fa-solid fa-plus"></i>&nbsp;เพิ่มผู้ลงทะเบียน</div>
                                            @elseif($time->max != 0)
                                                @if ($time->max == $time->transactionData->count())
                                                    <div class="lg:w-42 flex-1 cursor-pointer text-red-600 lg:flex-none">
                                                        <i class="fa-solid fa-ban"></i>&nbsp;มีผู้ลงทะเบียนเต็มแล้ว
                                                    </div>
                                                @elseif ($time->transactionData->count() < $time->max)
                                                    <div class="lg:w-42 cursor-pointer text-green-600 hover:text-green-700 lg:flex-none" onclick="createTransaction('{{ $time->id }}','{{ $time->title }}')"><i class="fa-solid fa-plus"></i>&nbsp;เพิ่มผู้ลงทะเบียน</div>
                                                @endif
                                            @endif
                                            <div class="flex-1">{{ $time->title }} </div>
                                        </div>
                                    </td>
                                    <td class="p-3 text-center">{{ count($time->transactionData) }}</td>
                                </tr>
                            @endforeach
                            @if (count($date->lecturesData) > 0)
                                <tr>
                                    <td class="p-3">
                                        วิทยากร
                                        <a class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-600 hover:text-green-800" href="{{ route("nurse.admin.export.excel.datelecture", $date->id) }}">
                                            <i class="fa-solid fa-file-excel me-1"></i> รายชื่อวิทยากร
                                        </a>
                                    </td>
                                    <td class="p-3" colspan="2">คะแนนที่ได้</td>
                                </tr>
                                @foreach ($date->lecturesData as $lecture)
                                    <tr class="bg-white hover:bg-green-100">
                                        <td class="p-3">{{ $lecture->user_id . " " . $lecture->userData->name }}</td>
                                        <td class="w-36 p-3">
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <input class="form-input rounded bg-gray-100 px-2 py-1" id="lecturer_{{ $lecture->id }}" type="number" min="0" value="{{ $lecture->score }}" placeholder="คะแนนวิทยากร">
                                                <button class="rounded bg-blue-500 px-3 py-1 text-white hover:bg-blue-600" type="button" onclick="updateLecturerScore('{{ $lecture->id }}')">Update</button>
                                                <span class="ml-2 text-sm" id="lecturer_feedback_{{ $lecture->id }}"></span>
                                            </div>
                                        </td>
                                        <td class="p-3 text-center">
                                            <button class="cursor-pointer text-red-600 hover:text-red-700" type="button" onclick="deleteLecture('{{ $lecture->id }}','{{ $lecture->userData->name }}')"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                @endforeach
            </div>
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
