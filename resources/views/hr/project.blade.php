@extends("layouts.hr")
@section("meta")
    <meta http-equiv="Refresh" content="60">
@endsection
@section("content")
    <div class="m-auto w-full p-3 md:w-3/4">
        <div class="mb-6 rounded-lg border border-[#eaf7ab] bg-[#c1dccd] p-3 shadow">
            <div class="flex text-3xl text-[#1a3f34]">
                <div class="flex-1"><a class="text-blue-600" href="{{ env("APP_URL") }}/">รายการลงทะเบียน</a> / {{ $project->project_name }}</div>
                <div class="cursor-pointer text-lg" onclick="refreshPage()"><i class="fa-solid fa-arrows-rotate"></i> อัพเดตข้อมูล</div>
            </div>
            <hr class="border-[#eaf7ab] shadow">
            <div class="text-sm text-red-600">ต้องการเปลี่ยนวันที่ลงทะเบียน กรุณายกเลิกวันลงทะเบียนเดิมก่อน</div>
            @if ($isRegister)
                <x-hr-transaction-item :transaction="$transaction" />
            @endif
        </div>
        <div class="mb-6 rounded-lg border border-[#eaf7ab] bg-[#c1dccd] p-3 shadow">
            <div class="rounded p-3 text-3xl text-[#1a3f34]">รอบการลงทะเบียนทั้งหมด</div>
            <hr class="border-[#eaf7ab] shadow">
            <div class="flex flex-col">
                @foreach ($project->slots as $slot)
                    @if ($slot->slot_date >= date("Y-m-d"))
                        <div class="mt-3 flex flex-row rounded border border-[#eaf7ab] bg-[#eeeeee] p-3 font-bold shadow" onclick="openID('#date_{{ $slot->id }}')">
                            <div class="flex-1 p-3 text-xl">{{ $slot->slot_name }}</div>
                            <div class="p-3 text-xl font-bold"><i class="fa-solid fa-angle-down"></i></div>
                        </div>
                        <div class="hidden flex-col gap-6 bg-white" id="date_{{ $slot->id }}">
                            @foreach ($slot->items as $item)
                                <div class="flex rounded p-3">
                                    <div class="flex-1 p-3">{{ $item->item_name }}</div>
                                    @if ($isRegister)
                                        @if ($item->item_available > 0)
                                            <div class="flex cursor-pointer rounded bg-gray-400 p-3 text-white">มีการลงทะเบียนแล้ว</div>
                                        @else
                                            <div class="flex cursor-pointer rounded bg-red-600 p-3 text-white">เต็มแล้ว</div>
                                        @endif
                                    @elseif($item->item_available > 0)
                                        @if (date("Y-m-d H:i") <= date("Y-m-d 17:00") && $slot->slot_date <= date("Y-m-d", strtotime(date("Y-m-d") . " +1 day")))
                                            <div class="flex cursor-pointer rounded bg-[#c1dccd] p-3" onclick="register('{{ $project->id }}','{{ $project->project_name }}','{{ $item->id }}','{{ $slot->slot_name }}','{{ $item->item_name }}')">ลงทะเบียน</div>
                                        @elseif($slot->slot_date > date("Y-m-d", strtotime(date("Y-m-d") . " +1 day")))
                                            <div class="flex cursor-pointer rounded bg-[#c1dccd] p-3" onclick="register('{{ $project->id }}','{{ $project->project_name }}','{{ $item->id }}','{{ $slot->slot_name }}','{{ $item->item_name }}')">ลงทะเบียน</div>
                                        @else
                                            <div class="flex cursor-pointer rounded bg-red-600 p-3 text-white">ลงทะเบียนภายในเวลา 17:00</div>
                                        @endif
                                    @else
                                        <div class="flex cursor-pointer rounded bg-red-600 p-3 text-white">ปิดการลงทะเบียน</div>
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

        async function register(project_id, project_name, item_id, slot, time) {
            const alert = await Swal.fire({
                title: `ยืนยันการลงทะเบียน<br>${project_name}`,
                html: `วันที่ <span class="text-red-600">${slot}</span><br>รอบ <span class="text-red-600">${time}</span><br>`,
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
                axios.post('{{ env("APP_URL") }}/hr/project/create', {
                        project_id: project_id,
                        item_id: item_id,
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
                axios.post('{{ env("APP_URL") }}/hr/project/delete', {
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
                axios.post('{{ env("APP_URL") }}/hr/project/sign', {
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
