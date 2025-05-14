@extends("layouts.hr")
@section("content")
    <div class="m-auto flex">
        <div class="m-auto mt-3 w-full rounded p-3 md:w-3/4">
            <div class="text-2xl font-bold">
                <a class="text-blue-600" href="{{ env("APP_URL") }}/hr/admin">Project Management</a>
                / {{ $project->project_name }}
            </div>
            <hr>
            <div class="flex gap-3 p-3">
                <a class="flex-1" href="{{ env("APP_URL") }}/hr/admin/transactions/{{ $project->id }}">
                    <div class="cursor-pointer rounded bg-blue-200 p-3 text-center"><i class="fa-solid fa-users"></i> รายชื่อผู้ลงทะเบียนทั้งหมด</div>
                </a>
                <a class="flex-1" href="{{ env("APP_URL") }}/hr/admin/approve?project={{ $project->id }}&approve=false&time=all">
                    <div class="cursor-pointer rounded bg-blue-200 p-3 text-center"><i class="fa-solid fa-check-double"></i> Approve ผู้ลงทะเบียน</div>
                </a>
                <a class="flex-1" href="{{ env("APP_URL") }}/hr/admin/scores?project={{ $project->id }}&userid=null">
                    <div class="cursor-pointer rounded bg-green-200 p-3 text-center"><i class="fa-solid fa-list-ol"></i> คะแนนสอบ</div>
                </a>
                <a class="flex-1" href="{{ env("APP_URL") }}/hr/admin/link/{{ $project->id }}">
                    <div class="cursor-pointer rounded bg-gray-200 p-3 text-center"><i class="fa-solid fa-gear"></i> การจัดการ Url ข้อสอบ</div>
                </a>
            </div>
            <div class="flex-col">
                <a class="flex-1" href="{{ env("APP_URL") }}/hr/admin/export/excel/all_date/{{ $project->id }}">
                    <div class="cursor-pointer rounded py-3 text-green-600 hover:text-green-800"><i class="fa-solid fa-file-excel"></i> Excel ดาวน์โหลดข้อมูลผู้ลงทะเบียนทั้งหมด หลักสูตร {{ $project->project_name }}</div>
                </a>
            </div>
            <div class="flex-col">
                <a class="flex-1" href="{{ env("APP_URL") }}/hr/admin/export/excel/dbd/{{ $project->id }}">
                    <div class="cursor-pointer rounded py-3 text-green-600 hover:text-green-800"><i class="fa-solid fa-file-excel"></i> Excel แบบฟอร์มกรมพัฒน์ หลักสูตร {{ $project->project_name }}</div>
                </a>
            </div>
            <div class="flex-col">
                <a class="flex-1" href="{{ env("APP_URL") }}/hr/admin/export/excel/onebook/{{ $project->id }}">
                    <div class="cursor-pointer rounded py-3 text-green-600 hover:text-green-800"><i class="fa-solid fa-file-excel"></i> Excel Onebook หลักสูตร {{ $project->project_name }}</div>
                </a>
            </div>
            <div class="text-2xl font-bold">วันที่เปิดลงทะเบียน</div>
            <hr>
            @foreach ($project->slots as $slot)
                <table class="mb-3 table w-full border-collapse">
                    <thead class="bg-gray-200">
                        <th class="border p-3 text-start">
                            <span>{{ $slot->slot_name }}</span>
                            <a href="{{ env("APP_URL") }}/hr/admin/export/excel/date/{{ $slot->id }}"><span class="ms-6 text-green-600"><i class="fa-solid fa-file-excel"></i></span></a>
                        </th>
                        <th class="w-36 border p-3">จำนวนลงทะเบียน</th>
                    </thead>
                    <tbody>
                        @foreach ($slot->items as $item)
                            <tr class="bg-white">
                                <td class="border p-3">
                                    <div class="flex gap-3">
                                        @if (count($item->transactions) == $item->item_max_available)
                                            <div class="lg:w-42 flex-1 cursor-pointer text-red-600 lg:flex-none">
                                                <i class="fa-solid fa-ban"></i>&nbsp;มีผู้ลงทะเบียนเต็มแล้ว
                                            </div>
                                        @else
                                            <div class="lg:w-42 flex-1 cursor-pointer text-green-600 lg:flex-none" onclick="addTransaction('{{ $item->id }}','{{ $item->item_name }}')">
                                                <i class="fa-solid fa-plus"></i>&nbsp;เพิ่มผู้ลงทะเบียน
                                            </div>
                                        @endif
                                        <div class="flex-1">{{ $item->item_name }} </div>
                                        <a class="flex-none text-end text-red-600" href="{{ env("APP_URL") }}/hr/admin/export/pdf/time/{{ $item->id }}">
                                            <i class="fa-regular fa-file-pdf"></i>
                                        </a>
                                    </div>
                                </td>
                                <td class="border p-3 text-center">{{ count($item->transactions) }} / {{ $item->item_max_available }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        </div>
    </div>
@endsection
@section("scripts")
    <script>
        async function addTransaction(id, title) {
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
                axios.post('{{ env("APP_URL") }}/hr/admin/createTransaction', {
                    'project_id': '{{ $project->id }}',
                    'item_id': id,
                    'user': alert.value
                }).then((res) => {
                    if (res['data']['status'] == 'success') {
                        Swal.fire({
                            title: res['data']['message'],
                            html: res['data']['name'] + ' รอบ ' + res['data']['slot'],
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
                            confirmButtonText: 'red',
                            confirmButtonColor: 'green'
                        })
                    }

                });
            }
        }
    </script>
@endsection
