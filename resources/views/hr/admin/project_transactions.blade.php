@extends("layouts.hr")
@section("content")
    <div>
        <div class="m-auto mt-3 w-full rounded p-3 md:w-3/4">
            <div class="text-2xl font-bold">
                <a class="text-blue-600" href="{{ route("hr.admin.index") }}">Project Management</a>
                / <a class="text-blue-600" href="{{ route("hr.admin.project.management", $project->id) }}">{{ $project->project_name }}</a>
                / รายชื่อผู้ลงทะเบียนทั้งหมด
            </div>
            <hr>
            <input class="mt-3 w-full rounded border border-gray-400 p-3" id="searchInput" onkeyup="search()" placeholder="ค้นหา" type="text">
        </div>
        <div class="w-full rounded p-3">
            <table class="w-full" id="user-table">
                <thead class="bg-gray-200">
                    <th class="border p-2">วันที่</th>
                    <th class="border p-2">รอบ</th>
                    <th class="border p-2">ที่นั่ง</th>
                    <th class="border p-2">รหัสพนักงาน</th>
                    <th class="border p-2">ชื่อ - สกุล</th>
                    <th class="border p-2">ตำแหน่ง</th>
                    <th class="border p-2">แผนก</th>
                    <th class="border p-2">Check-In</th>
                    <th class="border p-2">HR Approve</th>
                    <th class="border p-2"></th>
                </thead>
                <tbody id="userTable">
                    @foreach ($project->slots as $slot)
                        @foreach ($slot->items as $item)
                            @foreach ($item->transactions as $index => $transaction)
                                <tr>
                                    <td class="border p-2 text-center">{{ $slot->slot_name }}</td>
                                    <td class="border p-2 text-center">{{ $item->item_name }}</td>
                                    <td class="border p-2 text-center">{{ $transaction->seat }}</td>
                                    <td class="border p-2 text-center">{{ $transaction->user }}</td>
                                    <td class="border p-2">{{ $transaction->userData->name }}</td>
                                    <td class="border p-2">{{ $transaction->userData->position }}</td>
                                    <td class="border p-2">{{ $transaction->userData->department }}</td>
                                    <td class="border p-2 text-center">
                                        @if ($transaction->checkin_datetime !== null)
                                            {{ date("d/m/y H:i", strtotime($transaction->checkin_datetime)) }}
                                        @endif
                                    </td>
                                    <td class="border p-2 text-center">
                                        @if ($transaction->hr_approve_datetime !== null)
                                            {{ date("d/m/y H:i", strtotime($transaction->hr_approve_datetime)) }}
                                        @endif
                                    </td>
                                    <td class="border p-2 text-center">
                                        <button class="cursor-pointer text-red-600" onclick="deleteRegister('{{ $transaction->id }}','{{ $transaction->user }} {{ $transaction->userData->name }}')">ลบข้อมูลการลงทะเบียน</button>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section("scripts")
    <script>
        function copyTable() {
            var urlField = document.getElementById('user-table')
            var range = document.createRange()
            range.selectNode(urlField)
            window.getSelection().addRange(range)
            document.execCommand('copy')
        }

        function search() {
            var value = $('#searchInput').val().toLowerCase();
            $("#userTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        }

        async function deleteRegister(id, name) {
            alert = await Swal.fire({
                title: "ยืนยันลบข้อมูลการลงทะเบียน " + name,
                icon: 'warning',
                showConfirmButton: true,
                confirmButtonColor: 'red',
                confirmButtonText: 'ยืนยัน',
                showCancelButton: true,
                cancelButtonColor: 'gray',
                cancelButtonText: 'ยกเลิก',
            })

            if (alert.isConfirmed) {
                axios.post('{{ route("hr.admin.project.deleteTransaction") }}', {
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
