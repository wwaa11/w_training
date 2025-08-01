@extends("layouts.nurse")
@section("content")
    <div>
        <div class="m-auto mt-3 w-full rounded p-3 md:w-3/4">
            <div class="text-2xl font-bold">
                <a class="text-blue-600" href="{{ route("nurse.admin.index") }}">Project Management</a>
                / <a class="text-blue-600" href="{{ route("nurse.admin.project.management", $project->id) }}">{{ $project->title }}</a>
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
                    <th class="border p-2">รหัสพนักงาน</th>
                    <th class="border p-2">ชื่อ - สกุล</th>
                    <th class="border p-2">ตำแหน่ง</th>
                    <th class="border p-2">แผนก</th>
                    <th class="border p-2">CHECK IN</th>
                    <th class="border p-2">APPROVE</th>
                    <th class="border p-2"></th>
                </thead>
                <tbody id="userTable">
                    @foreach ($project->dateData as $date)
                        @foreach ($date->timeData as $time)
                            @foreach ($time->transactionData as $index => $transaction)
                                <tr>
                                    <td class="border p-2 text-center">{{ $date->title }}</td>
                                    <td class="border p-2 text-center">{{ $time->title }}</td>
                                    <td class="border p-2 text-center">{{ $transaction->user_id }}</td>
                                    <td class="border p-2">{{ $transaction->userData->name }}</td>
                                    <td class="border p-2">{{ $transaction->userData->position }}</td>
                                    <td class="border p-2">{{ $transaction->userData->department }}</td>
                                    <td class="border p-2 text-center">
                                        @if ($transaction->user_sign !== null)
                                            {{ date("d/m/y H:i", strtotime($transaction->user_sign)) }}
                                        @endif
                                    </td>
                                    <td class="border p-2 text-center">
                                        @if ($transaction->admin_sign !== null)
                                            {{ date("d/m/y H:i", strtotime($transaction->admin_sign)) }}
                                        @endif
                                    </td>
                                    <td class="border p-2 text-center">
                                        <button class="cursor-pointer text-red-600" onclick="deleteRegister('{{ $transaction->id }}','{{ $transaction->user_id }} {{ $transaction->userData->name }}')">ลบข้อมูลการลงทะเบียน</button>
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
                axios.post('{{ route("nurse.admin.transactions.delete") }}', {
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
