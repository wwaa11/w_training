@extends("layout")
@section("content")
    <div class="m-auto w-full p-3 md:w-3/4">
        <div class="mb-6 rounded-lg border border-[#eaf7ab] bg-[#c1dccd] p-3 shadow">
            <div class="text-3xl text-[#1a3f34]"><a class="text-blue-600" href="{{ env("APP_URL") }}/">รายการลงทะเบียน</a> / {{ $project->project_name }}</div>
            <hr class="border-[#eaf7ab] shadow">
            @if ($isRegister)
                <div class="mt-3 flex flex-row rounded border border-[#eaf7ab] bg-[#eeeeee] p-3 shadow">
                    <div class="m-auto w-[20%] p-3 text-center md:w-[30%]">
                        <div class="text-4xl text-[#008387]">{{ date("d", strtotime($transaction->item->slot->slot_date)) }}</div>
                        <div>{{ date("M Y", strtotime($transaction->item->slot->slot_date)) }}</div>
                    </div>
                    <div class="relative flex-1 border-l-2 px-3">
                        <div class="prompt-medium text-2xl text-[#008387]">{{ $transaction->item->slot->project->project_name }}</div>
                        <div class="mt-2"><i class="fa-regular fa-clock text-[#008387]"></i> {{ $transaction->item->item_name }}</div>
                        @if ($transaction->item->item_note_1_active)
                            <div class="mt-2"><i class="fa-solid fa-map-pin text-[#008387]"></i></i> {{ $transaction->item->item_note_1_title }} : {{ $transaction->item->item_note_1_value }}</div>
                        @endif
                        @if (!$transaction->checkin)
                            <span class="absolute bottom-0 right-0 cursor-pointer text-red-600" onclick="deleteTransaction('{{ $transaction->item->slot->project->id }}','{{ $transaction->item->slot->project->project_name }}')"><i class="fa-solid fa-trash"></i></span>
                        @endif
                        @if (date("Y-m-d") == $transaction->item->slot->slot_date)
                            @if (!$transaction->checkin)
                                <div class="mt-2 cursor-pointer rounded text-lg text-red-600" onclick="sign('{{ $transaction->id }}','{{ $transaction->item->slot->project->project_name }}')"><i class="fa-solid fa-location-dot"></i> Check-IN</div>
                            @else
                                <div class="mt-2 text-green-700"><i class="fa-solid fa-location-dot"></i> Check-IN {{ date("d/m/Y H:i", strtotime($transaction->checkin_datetime)) }}</div>
                                @if ($transaction->hr_approve)
                                    <div class="mt-2 text-green-700">HR : อนุมัติ {{ date("d/m/Y H:i", strtotime($transaction->hr_approve_datetime)) }}</div>
                                @else
                                    <div class="mt-2 text-red-600">HR : รอการอนุมัติ</div>
                                @endif
                            @endif
                        @endif
                    </div>
                </div>
            @endif
        </div>
        <div class="mb-6 rounded-lg border border-[#eaf7ab] bg-[#c1dccd] p-3 shadow">
            <div class="rounded p-3 text-3xl text-[#1a3f34]">รอบการลงทะเบียนทั้งหมด</div>
            <hr class="border-[#eaf7ab] shadow">
            <div class="flex flex-col">
                @foreach ($project->slots as $slot)
                    <div class="mt-3 flex flex-row rounded border border-[#eaf7ab] bg-[#eeeeee] p-3 font-bold shadow" onclick="openID('#date_{{ $slot->id }}')">
                        <div class="flex-1 p-3 text-xl">{{ $slot->slot_name }}</div>
                        <div class="p-3 text-xl font-bold"><i class="fa-solid fa-angle-down"></i></div>
                    </div>
                    <div class="flex hidden flex-col gap-6 bg-white" id="date_{{ $slot->id }}">
                        @foreach ($slot->items as $item)
                            <div class="flex rounded p-3">
                                <div class="flex-1 p-3">{{ $item->item_name }}</div>
                                {{-- <div class="flex p-3">{{ $item->item_available }}</div> --}}
                                @if ($isRegister)
                                    @if ($item->item_available > 0)
                                        <div class="flex cursor-pointer rounded bg-gray-400 p-3 text-white">มีการลงทะเบียนแล้ว</div>
                                    @else
                                        <div class="flex cursor-pointer rounded bg-red-600 p-3 text-white">เต็มแล้ว</div>
                                    @endif
                                @elseif($item->item_available > 0)
                                    <div class="flex cursor-pointer rounded bg-[#c1dccd] p-3" onclick="register('{{ $item->id }}','{{ $slot->slot_name }}','{{ $item->item_name }}')">ลงทะเบียน</div>
                                @else
                                    <div class="flex cursor-pointer rounded bg-red-600 p-3 text-white">ปิดการลงทะเบียน</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@section("scripts")
    <script>
        function openID(id) {
            $(id).toggle()
        }
        async function register(item_id, slot, time) {
            alert = await Swal.fire({
                title: "ยืนยันการลงทะเบียน<br>{{ $project->project_name }}",
                html: "วันที่ <span class=\"text-red-600\">" + slot + "</span><br>รอบ <span class=\"text-red-600\">" + time + "</span><br>",
                icon: 'question',
                allowOutsideClick: false,
                showConfirmButton: true,
                confirmButtonColor: 'green',
                confirmButtonText: 'ลงทะเบียน',
                showCancelButton: true,
                cancelButtonColor: 'gray',
                cancelButtonText: 'ยกเลิก',
            })

            if (alert.isConfirmed) {
                axios.post('{{ env("APP_URL") }}/save', {
                    'project_id': '{{ $project->id }}',
                    'item_id': item_id,
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
        async function deleteTransaction(id, name) {
            alert = await Swal.fire({
                title: "ยืนยันการเปลี่ยนวันที่ลงทะเบียน " + name,
                // html: "การเปลี่ยนรอบการลงทะเบียน ระบบจะทำการลบข้อมูลการลงทะเบียนออกจึงจะสามารถเปลี่ยนรอบการลงทะเบียนได้<br><span class=\"text-red-600\">*กรณีที่วันที่เลือกวันที่ต้องการลงทะเบียนไม่ได้ และ วันที่ลงทะเบียนขณะนี้เต็ม จะต้องทำการเปลี่ยนวันที่ใหม่ไม่สามารถนำวันที่ลงทะเบียนเดิมกลับมาได้</span>",
                icon: 'warning',
                allowOutsideClick: false,
                showConfirmButton: true,
                confirmButtonColor: 'red',
                confirmButtonText: 'ยืนยัน',
                showCancelButton: true,
                cancelButtonColor: 'gray',
                cancelButtonText: 'ยกเลิก',
            })

            if (alert.isConfirmed) {
                axios.post('{{ env("APP_URL") }}/delete', {
                    'project_id': '{{ $project->id }}'
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
        async function sign(id, project_name) {
            alert = await Swal.fire({
                title: "ลงชื่อ " + project_name,
                icon: 'warning',
                allowOutsideClick: false,
                showConfirmButton: true,
                confirmButtonColor: 'green',
                confirmButtonText: 'ลงชื่อ',
                showCancelButton: true,
                cancelButtonColor: 'gray',
                cancelButtonText: 'ยกเลิก',
            })

            if (alert.isConfirmed) {
                axios.post('{{ env("APP_URL") }}/sign', {
                    'transaction_id': id
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
    </script>
@endsection
