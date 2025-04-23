@extends("layout")
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
                <div class="mt-3 flex flex-row rounded border border-[#eaf7ab] bg-[#eeeeee] p-3 shadow">
                    <div class="m-auto w-[30%] text-center">
                        <div class="text-sm">{{ $transaction->item->slot->dateThai }}</div>
                        <div class="text-3xl text-[#008387]">{{ date("d", strtotime($transaction->item->slot->slot_date)) }}</div>
                        <div>{{ $transaction->item->slot->monthThai }}</div>
                    </div>
                    <div class="relative flex-1 border-l-2 border-[#6d6d6d] px-3">
                        <div class="prompt-medium text-2xl text-[#008387]">{{ $transaction->item->slot->project->project_name }}</div>
                        <div class="mt-2"><i class="fa-regular fa-clock w-8 text-[#008387]"></i> {{ $transaction->item->item_name }}</div>
                        @if ($transaction->seat !== null)
                            <div class="mt-2 flex rounded bg-red-500 p-3 text-white lg:hidden">
                                <div class="pt-2">เลขที่นั่งสอบ</div>
                                <div class="flex-1 text-end text-3xl">{{ $transaction->seat }}</div>
                            </div>
                            <div class="right-0 top-0 hidden lg:absolute lg:block">
                                <div class="text-center text-6xl text-red-600">{{ $transaction->seat }}</div>
                                <div class="text-sm">เลขที่นั่งสอบ</div>
                            </div>
                        @endif
                        @if ($transaction->item->item_note_1_active)
                            <div class="mt-2"><i class="fa-solid fa-map-pin w-8 text-[#008387]"></i></i> {{ $transaction->item->item_note_1_title }} : {{ $transaction->item->item_note_1_value }}</div>
                        @endif
                        @if (!$transaction->checkin)
                            <span class="absolute bottom-0 right-0 cursor-pointer text-red-600" onclick="deleteTransaction('{{ $transaction->item->slot->project->id }}','{{ $transaction->item->slot->project->project_name }}')"><i class="fa-solid fa-trash"></i></span>
                        @endif
                        @if (date("Y-m-d") == $transaction->item->slot->slot_date)
                            @if (!$transaction->checkin)
                                <button class="mt-3 cursor-pointer rounded border border-[#eaf7ab] bg-red-500 p-3 text-white" onclick="sign('{{ $transaction->id }}','{{ $transaction->item->slot->project->project_name }}')">
                                    <i class="fa-solid fa-location-dot w-8"></i> CHECK IN
                                </button>
                            @else
                                <div class="mt-2 text-green-700">
                                    <i class="fa-solid fa-location-dot w-8"></i> CHECK IN {{ date("H:i", strtotime($transaction->checkin_datetime)) }}
                                </div>
                                @if ($transaction->hr_approve)
                                    <div class="mt-2 text-green-700">
                                        <i class="fa-solid fa-h w-4"></i><i class="fa-solid fa-r w-4"></i> อนุมัติ {{ date("H:i", strtotime($transaction->hr_approve_datetime)) }}
                                    </div>
                                    @if ($transaction->item->slot->project->link !== null)
                                        <div class="mt-3 cursor-pointer rounded-t bg-red-500 p-3 text-white">
                                            <i class="fa-regular fa-file-lines"></i> ข้อสอบ {{ date("H:i", strtotime($transaction->item->link_start)) }} - {{ date("H:i", strtotime($transaction->item->link_end)) }}
                                        </div>
                                        <div class="rounded-b border border-red-500">
                                            @if (!$transaction->item->link_time)
                                                @foreach ($transaction->item->slot->project->link->links as $link)
                                                    <a href="{{ $link["url"] }}" target="_blank">
                                                        <div class="m-3 rounded bg-green-400 p-3">{{ $link["title"] }}</div>
                                                    </a>
                                                @endforeach
                                            @elseif(date("Y-m-d H:i") >= date("Y-m-d H:i", strtotime($transaction->item->link_start)) && date("Y-m-d H:i") <= date("Y-m-d H:i", strtotime($transaction->item->link_end)))
                                                @foreach ($transaction->item->slot->project->link->links as $link)
                                                    <a href="{{ $link["url"] }}" target="_blank">
                                                        <div class="m-3 rounded bg-green-400 p-3">{{ $link["title"] }}</div>
                                                    </a>
                                                @endforeach
                                            @else
                                                <div class="m-3 rounded bg-red-600 p-3 text-white">ไม่สามารถใช้งานได้</div>
                                            @endif
                                        </div>
                                    @endif
                                @else
                                    <div class="mt-2 text-red-600">
                                        <i class="fa-solid fa-h w-4"></i><i class="fa-solid fa-r w-4"></i> รอการอนุมัติ
                                    </div>
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
                    @if ($slot->slot_date >= date("Y-m-d"))
                        @if ($slot->slot_date == date("Y-m-d") && auth()->user()->admin)
                            <div class="mt-3 flex flex-row rounded border border-[#eaf7ab] bg-[#eeeeee] p-3 font-bold shadow" onclick="openID('#date_{{ $slot->id }}')">
                                <div class="flex-1 p-3 text-xl">{{ $slot->slot_name }}</div>
                                <div class="p-3 text-xl font-bold"><i class="fa-solid fa-angle-down"></i></div>
                            </div>
                            <div class="hidden flex-col gap-6 bg-white" id="date_{{ $slot->id }}">
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
                        @elseif($slot->slot_date == date("Y-m-d"))
                        @else
                            <div class="mt-3 flex flex-row rounded border border-[#eaf7ab] bg-[#eeeeee] p-3 font-bold shadow" onclick="openID('#date_{{ $slot->id }}')">
                                <div class="flex-1 p-3 text-xl">{{ $slot->slot_name }}</div>
                                <div class="p-3 text-xl font-bold"><i class="fa-solid fa-angle-down"></i></div>
                            </div>
                            <div class="hidden flex-col gap-6 bg-white" id="date_{{ $slot->id }}">
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
                        @endif
                    @endif
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
                title: "ลงชื่อ : " + project_name,
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
