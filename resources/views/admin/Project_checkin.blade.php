@extends("layout")
@section("content")
    <div class="m-auto">
        <div class="m-auto mt-3 w-full rounded p-3 md:w-3/4">
            <div class="text-2xl font-bold"><a class="text-blue-600" href="{{ env("APP_URL") }}/admin">Admin Management</a> / <a class="text-blue-600" href="{{ env("APP_URL") }}/admin/project/{{ $project->id }}">{{ $project->project_name }}</a> / Approve</div>
            <hr>
            <div class="flex flex-row gap-3">
                <input class="mt-3 w-full flex-1 rounded border border-gray-400 p-3" id="searchInput" onkeyup="search()" placeholder="ค้นหา" type="text">
                <div class="m-auto">
                    <select class="mt-2 flex-none rounded border bg-gray-200 p-2" id="searchType" onchange="changeSearch()">
                        <option @if ($select == "not approve") selected @endif value="notapprove">Not Approve</option>
                        <option @if ($select == "approved") selected @endif value="approve">Approved</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="w-full rounded p-3">
            <table class="w-full">
                <thead class="bg-gray-200">
                    <th class="border p-3">วันที่</th>
                    <th class="border p-3">รอบ</th>
                    <th class="border p-3">รหัสพนักงาน</th>
                    <th class="border p-3">ชื่อ - สกุล</th>
                    <th class="border p-3">ตำแหน่ง</th>
                    <th class="border p-3">แผนก</th>
                    <th class="border p-3">Check-In</th>
                    <th class="border p-3">HR Approve</th>
                </thead>
                <tbody id="userTable">
                    @foreach ($transactions as $transcation)
                        <tr>
                            <td class="border p-2 text-center">{{ $transcation->item->slot->slot_name }}</td>
                            <td class="border p-2 text-center">{{ $transcation->item->item_name }}</td>
                            <td class="border p-2 text-center">{{ $transcation->userData->userid }}</td>
                            <td class="border p-2">{{ $transcation->userData->name }}</td>
                            <td class="border p-2">{{ $transcation->userData->position }}</td>
                            <td class="border p-2">{{ $transcation->userData->department }}</td>
                            <td class="border p-2 text-center text-green-600">{{ date("d/m/Y H:i", strtotime($transcation->checkin_datetime)) }}</td>
                            <td class="border p-3 text-center">
                                @if ($transcation->hr_approve == false)
                                    <button class="cursor-pointer rounded p-3 text-red-600" onclick="approve('{{ $transcation->id }}','{{ $transcation->item->slot->slot_name }}','{{ $transcation->item->item_name }}','{{ $transcation->userData->userid }}','{{ $transcation->userData->name }}','{{ $transcation->userData->position }}','{{ $transcation->userData->department }}')" type="button">Approve</button>
                                @else
                                    {{ date("d/m/Y H:i", strtotime($transcation->hr_approve_datetime)) }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section("scripts")
    <script>
        function changeSearch() {
            type = $('#searchType').find(":selected").val();
            if (type == 'notapprove') {
                window.location.replace('{{ env("APP_URL") }}/admin/checkin/{{ $project->id }}');
            } else {
                window.location.replace('{{ env("APP_URL") }}/admin/approved/{{ $project->id }}');
            }
        }

        function search() {
            var value = $('#searchInput').val().toLowerCase();
            $("#userTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        }

        async function approve(id, date, time, userid, name, position, department) {
            alert = await Swal.fire({
                title: "ยืนยันการ Approve : " + userid,
                html: "วันที่ :" + date + " รอบ : " + time + "<br>รหัสพนักงาน : " + userid + "<br>ชื่อ-สกุล : " + name + "<br>ตำแหน่ง : " + position + "<br>แผนก : " + department + "",
                icon: 'warning',
                allowOutsideClick: false,
                showConfirmButton: true,
                confirmButtonColor: 'green',
                confirmButtonText: 'Approve',
                showCancelButton: true,
                cancelButtonColor: 'gray',
                cancelButtonText: 'ยกเลิก',
            })

            if (alert.isConfirmed) {
                axios.post('{{ env("APP_URL") }}/admin/approveCheckin', {
                    'id': id,
                }).then((res) => {
                    Swal.fire({
                        title: res['data']['message'],
                        icon: 'success',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: 'green'
                    }).then(function(isConfirmed) {
                        window.location.reload()
                    })
                });
            }
        }
    </script>
@endsection
