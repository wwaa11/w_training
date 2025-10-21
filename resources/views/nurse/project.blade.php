@extends("layouts.nurse")
@section("meta")
    <meta http-equiv="Refresh" content="60">
@endsection
@section("content")
    <div class="container mx-auto px-3">
        <!-- Header -->
        <div class="mb-4">
            <div class="flex items-center justify-between">
                <div class="min-w-0 flex-1">
                    <h1 class="text-xl font-bold text-gray-900 sm:text-2xl"><a class="text-blue-600" href="{{ route("nurse.index") }}">รายการลงทะเบียน</a> / {{ $project->title }}</h1>
                    <p class="mt-1 text-xs text-gray-600 sm:text-sm">จัดการรอบการลงทะเบียนและติดตามสถานะของคุณ</p>
                </div>
                <button class="ml-3 inline-flex items-center rounded-lg bg-blue-600 px-3 py-2 text-xs font-medium text-white hover:bg-blue-700 sm:px-4 sm:text-sm" onclick="refreshPage()">
                    <i class="fas fa-arrows-rotate mr-1.5 sm:mr-2"></i>
                    อัพเดตข้อมูล
                </button>
            </div>
        </div>

        <!-- Info Notice -->
        @if (!$project->multiple)
            <div class="mb-4 rounded-xl bg-gradient-to-r from-yellow-50 to-yellow-100 p-3 shadow-sm sm:p-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle mr-2 mt-0.5 text-yellow-600"></i>
                    <p class="text-xs text-yellow-800 sm:text-sm">ต้องการเปลี่ยนวันที่ลงทะเบียน กรุณายกเลิกวันลงทะเบียนเดิมก่อน</p>
                </div>
            </div>
        @endif

        <!-- My Transactions -->
        <div class="mb-4 rounded-xl bg-white p-3 shadow-sm sm:p-4">
            <div class="mb-2 flex items-center">
                <i class="fas fa-clipboard-list mr-2 text-green-600"></i>
                <h2 class="text-base font-semibold text-gray-900 sm:text-lg">รายการของฉัน</h2>
            </div>
            @if (count($project->mytransactions) != 0)
                @foreach ($project->mytransactions as $transaction)
                    <x-nurse-transaction-item :transaction="$transaction" />
                @endforeach
            @else
                <div class="rounded border border-gray-200 bg-gray-50 p-3 text-xs text-gray-600 sm:text-sm">ยังไม่มีการลงทะเบียนสำหรับโปรเจกต์นี้</div>
            @endif
        </div>

        <!-- All Registration Rounds -->
        <div class="rounded-xl bg-white p-3 shadow-sm sm:p-4">
            <div class="mb-2 flex items-center">
                <i class="fas fa-calendar-check mr-2 text-blue-600"></i>
                <h2 class="text-base font-semibold text-gray-900 sm:text-lg">รอบการลงทะเบียนทั้งหมด</h2>
            </div>
            <div class="space-y-2">
                @foreach ($project->dateData as $date)
                    @if ($date->date >= date("Y-m-d"))
                        <!-- Date header card -->
                        <div class="cursor-pointer rounded-xl border border-gray-200 bg-gradient-to-r from-gray-50 to-white p-4 shadow-sm transition-all duration-200 hover:border-gray-300" onclick="openID('#date_{{ $date->id }}')">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="text-lg font-bold text-gray-900">{{ $date->title }}</div>
                                    @if ($date->detail !== null)
                                        <div class="mt-1 text-xs text-gray-600 sm:text-sm">{{ $date->detail }}</div>
                                    @endif
                                </div>
                                <div class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800 sm:text-sm">
                                    <i class="fas fa-chevron-down mr-1"></i>
                                    ดูรอบ
                                </div>
                            </div>
                        </div>
                        <!-- Date content -->
                        <div class="hidden space-y-2 p-2" id="date_{{ $date->id }}">
                            @foreach ($date->timeData as $time)
                                <div class="rounded-lg border border-gray-200 bg-white p-3 shadow-sm sm:p-4">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900 sm:text-base">{{ $time->title }}
                                                @if ($time->max != 0)
                                                    ({{ $time->transactionData->count() }}/{{ $time->max }})
                                                @endif
                                            </div>
                                            @if ($time->detail !== null)
                                                <div class="mt-1 text-xs text-gray-600 sm:text-sm">{{ $time->detail }}</div>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2">
                                            @if ($project->multiple)
                                                @if ($time->transactionData->where("user_id", Auth::user()->userid)->where("nurse_time_id", $time->id)->count() !== 0)
                                                    <div class="inline-flex items-center rounded-lg bg-gradient-to-r from-red-500 to-red-600 px-3 py-2 text-xs font-semibold text-white shadow-sm sm:text-sm">ลงทะเบียนรอบนี้แล้ว</div>
                                                @elseif ($time->transactionData->count() !== 0 && $time->transactionData->count() == $time->max)
                                                    <div class="inline-flex items-center rounded-lg bg-gradient-to-r from-red-500 to-red-600 px-3 py-2 text-xs font-semibold text-white shadow-sm sm:text-sm">รอบการลงทะเบียนเต็มแล้ว</div>
                                                @else
                                                    <div class="inline-flex cursor-pointer items-center rounded-lg bg-gradient-to-r from-green-500 to-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition-all duration-300 hover:from-green-600 hover:to-green-700 sm:text-sm" onclick="register('{{ $project->id }}','{{ $project->title }}','{{ $date->title }}','{{ $time->id }}','{{ $time->title }}')">ลงทะเบียน</div>
                                                @endif
                                            @elseif ($time->max == 0)
                                                @if (count($project->mytransactions) == 0)
                                                    <div class="inline-flex cursor-pointer items-center rounded-lg bg-gradient-to-r from-green-500 to-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition-all duration-300 hover:from-green-600 hover:to-green-700 sm:text-sm" onclick="register('{{ $project->id }}','{{ $project->title }}','{{ $date->title }}','{{ $time->id }}','{{ $time->title }}')">
                                                        ลงทะเบียน
                                                    </div>
                                                @else
                                                    <div class="inline-flex items-center rounded-lg bg-gray-400 px-3 py-2 text-xs font-semibold text-white shadow-sm sm:text-sm">มีการลงทะเบียนแล้ว</div>
                                                @endif
                                            @else
                                                @if ($time->transactionData->count() !== 0 && $time->transactionData->count() == $time->max)
                                                    <div class="inline-flex items-center rounded-lg bg-gradient-to-r from-red-500 to-red-600 px-3 py-2 text-xs font-semibold text-white shadow-sm sm:text-sm">รอบการลงทะเบียนเต็มแล้ว</div>
                                                @elseif ($time->transactionData->count() < $time->max && count($project->mytransactions) == 0)
                                                    <div class="inline-flex cursor-pointer items-center rounded-lg bg-gradient-to-r from-green-500 to-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition-all duration-300 hover:from-green-600 hover:to-green-700 sm:text-sm" onclick="register('{{ $project->id }}','{{ $project->title }}','{{ $date->title }}','{{ $time->id }}','{{ $time->title }}')">
                                                        ลงทะเบียน
                                                    </div>
                                                @else
                                                    <div class="inline-flex items-center rounded-lg bg-gray-400 px-3 py-2 text-xs font-semibold text-white shadow-sm sm:text-sm">มีการลงทะเบียนแล้ว</div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection
@section("scripts")
    <script>
        function refreshPage() {
            window.location.reload();
        }

        function openID(id) {
            $(id).toggle();
        }

        async function register(project_id, project_name, date, time_id, time) {
            const alert = await Swal.fire({
                title: `ยืนยันการลงทะเบียน<br>${project_name}`,
                html: `วันที่ <span class="text-red-600">${date}</span><br>รอบ <span class="text-red-600">${time}</span><br>`,
                icon: "question",
                allowOutsideClick: false,
                showConfirmButton: true,
                confirmButtonColor: "green",
                confirmButtonText: "ลงทะเบียน",
                showCancelButton: true,
                cancelButtonColor: "gray",
                cancelButtonText: "ยกเลิก",
            });

            if (alert.isConfirmed) {
                axios.post('{{ route("nurse.project.create") }}', {
                        project_id: project_id,
                        time_id: time_id,
                    })
                    .then((res) => {
                        Swal.fire({
                            title: res.data.message,
                            icon: "success",
                            confirmButtonText: "ตกลง",
                            confirmButtonColor: "green",
                        }).then(() => window.location.reload());
                    });
            }
        }

        async function deleteTransaction(projectId, name) {
            const alert = await Swal.fire({
                title: `ยืนยันการยกเลิกการทะเบียน ${name}`,
                icon: "warning",
                allowOutsideClick: false,
                showConfirmButton: true,
                confirmButtonColor: "red",
                confirmButtonText: "ยืนยัน",
                showCancelButton: true,
                cancelButtonColor: "gray",
                cancelButtonText: "ยกเลิก",
            });

            if (alert.isConfirmed) {
                axios.post('{{ route("nurse.project.delete") }}', {
                        project_id: projectId,
                    })
                    .then((res) => {
                        Swal.fire({
                            title: res.data.message,
                            icon: "success",
                            confirmButtonText: "ตกลง",
                            confirmButtonColor: "green",
                        }).then(() => window.location.reload());
                    });
            }
        }

        async function sign(id, project_name) {
            const alert = await Swal.fire({
                title: "ลงชื่อ : " + project_name,
                icon: "warning",
                allowOutsideClick: false,
                showConfirmButton: true,
                confirmButtonColor: "green",
                confirmButtonText: "ลงชื่อ",
                showCancelButton: true,
                cancelButtonColor: "gray",
                cancelButtonText: "ยกเลิก",
            });

            if (alert.isConfirmed) {
                axios.post('{{ route("nurse.project.sign") }}', {
                        transaction_id: id,
                    })
                    .then((res) => {
                        Swal.fire({
                            title: res["data"]["message"],
                            icon: "success",
                            confirmButtonText: "ตกลง",
                            confirmButtonColor: "green",
                        }).then(function(isConfirmed) {
                            if (isConfirmed) {
                                window.location.reload();
                            }
                        });
                    });
            }
        }
    </script>
@endsection
