@extends("layouts.nurse")
@section("meta")
    <meta http-equiv="Refresh" content="60">
@endsection
@section("content")
    <div class="m-auto w-full p-3 md:w-3/4">
        <div class="mb-6 rounded-lg border border-[#eaf7ab] bg-[#c1dccd] p-3 shadow">
            <div class="flex text-3xl text-[#1a3f34]">
                <div class="flex-1"><a class="text-blue-600" href="{{ env("APP_URL") }}/nurse/">รายการลงทะเบียน</a> / {{ $project->title }}</div>
                <div class="cursor-pointer text-lg" onclick="refreshPage()"><i class="fa-solid fa-arrows-rotate"></i> อัพเดตข้อมูล</div>
            </div>
            <hr class="shadow">
            <div class="text-sm text-red-600">ต้องการเปลี่ยนวันที่ลงทะเบียน กรุณายกเลิกวันลงทะเบียนเดิมก่อน</div>
            @if (count($project->mytransactions) != 0)
                @foreach ($project->mytransactions as $transaction)
                    <x-nurse-transaction-item :transaction="$transaction" />
                @endforeach
            @endif
        </div>
        <div class="mb-6 rounded-lg border border-[#eaf7ab] bg-[#c1dccd] p-3 shadow">
            <div class="rounded p-3 text-3xl">รอบการลงทะเบียนทั้งหมด</div>
            <hr class="shadow">
            <div class="flex flex-col">
                @foreach ($project->dateData as $date)
                    @if ($date->date >= date("Y-m-d"))
                        <div class="mt-3 flex flex-row rounded border border-[#eaf7ab] bg-[#eeeeee] p-3 font-bold shadow" onclick="openID('#date_{{ $date->id }}')">
                            <div class="flex-1 p-3 text-xl">{{ $date->title }}</div>
                            @if ($date->detail !== null)
                                <div class="flex-none p-3">{{ $date->detail }}</div>
                            @endif
                            <div class="p-3 text-xl font-bold"><i class="fa-solid fa-angle-down"></i></div>
                        </div>
                        <div class="hidden flex-col gap-6 bg-white" id="date_{{ $date->id }}">
                            @foreach ($date->timeData as $time)
                                <div class="flex rounded p-3">
                                    <div class="flex-1 p-3">{{ $time->title }}</div>
                                    @if ($project->multiple)
                                        @if ($time->transactionData->count() !== 0 && $time->transactionData->count() == $time->max)
                                            <div class="flex cursor-pointer rounded bg-red-400 p-3 text-white">รอบการลงทะเบียนเต็มแล้ว</div>
                                        @else
                                            <div class="flex cursor-pointer rounded bg-[#c1dccd] p-3" onclick="register('{{ $project->id }}','{{ $project->title }}','{{ $date->title }}','{{ $time->id }}','{{ $time->title }}')">ลงทะเบียน</div>
                                        @endif
                                    @elseif ($time->max == 0)
                                        @if (count($project->mytransactions) == 0)
                                            <div class="flex cursor-pointer rounded bg-[#c1dccd] p-3" onclick="register('{{ $project->id }}','{{ $project->title }}','{{ $date->title }}','{{ $time->id }}','{{ $time->title }}')">ลงทะเบียน</div>
                                        @else
                                            <div class="flex cursor-pointer rounded bg-gray-400 p-3 text-white">มีการลงทะเบียนแล้ว</div>
                                        @endif
                                    @else
                                        @if ($time->transactionData->count() !== 0 && $time->transactionData->count() == $time->max)
                                            <div class="flex cursor-pointer rounded bg-red-400 p-3 text-white">รอบการลงทะเบียนเต็มแล้ว</div>
                                        @elseif ($time->transactionData->count() < $time->max && count($project->mytransactions) == 0)
                                            <div class="flex cursor-pointer rounded bg-[#c1dccd] p-3" onclick="register('{{ $project->id }}','{{ $project->title }}','{{ $date->title }}','{{ $time->id }}','{{ $time->title }}')">ลงทะเบียน</div>
                                        @else
                                            <div class="flex cursor-pointer rounded bg-gray-400 p-3 text-white">มีการลงทะเบียนแล้ว</div>
                                        @endif
                                    @endif
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
                axios.post('{{ env("APP_URL") }}/nurse/project/create', {
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
                axios.post('{{ env("APP_URL") }}/nurse/project/delete', {
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
            alert = await Swal.fire({
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
                axios.post('{{ env("APP_URL") }}/nurse/project/sign', {
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
