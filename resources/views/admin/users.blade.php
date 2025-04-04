@extends("layout")
@section("content")
    <div class="m-auto flex">
        <div class="m-auto mt-3 w-full rounded p-3 md:w-3/4">
            <div class="text-2xl font-bold">Users Management</div>
            <hr>
            <input class="mt-3 w-full bg-white p-3" id="searchInput" onkeyup="search()" type="text" placeholder="ค้นหา">
            <table class="my-3 w-full rounded bg-white p-3">
                <thead class="bg-gray-200">
                    <th class="border p-3">รหัสพนักงาน</th>
                    <th class="border p-3">ชื่อ - สกุล</th>
                    <th class="border p-3">ตำแหน่ง</th>
                    <th class="border p-3">แผนก</th>
                    <th class="border p-3"></th>
                </thead>
                <tbody id="userTable">
                    @foreach ($users as $user)
                        <tr id="user{{ $user->userid }}">
                            <td class="border p-2">
                                @if ($user->admin)
                                    <span class="text-red-600">(Admin)</span>
                                @endif
                                {{ $user->userid }}
                            </td>
                            <td class="border p-2">{{ $user->name }}</td>
                            <td class="border p-2">{{ $user->position }}</td>
                            <td class="border p-2">{{ $user->department }}</td>
                            <td class="border p-3 text-center">
                                <button class="cursor-pointer rounded p-3 text-red-600" onclick="reserPassword('{{ $user->userid }}')" type="button">รีเซ็ตรหัสผ่าน</button>
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
        function search() {
            var value = $('#searchInput').val().toLowerCase();
            $("#userTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        }

        async function reserPassword(userid) {
            alert = await Swal.fire({
                title: "ยืนยันการรีเซ็ตรหัสผ่าน : " + userid,
                html: "รหัสผ่านที่ถูกรีเซ็ต จะถูกเปลี่ยนเป็น <span class=\"text-red-600\">รหัสพนักงาน (" + userid + ")</span> อีกครั้ง ",
                icon: 'warning',
                allowOutsideClick: false,
                showConfirmButton: true,
                confirmButtonColor: 'red',
                confirmButtonText: 'เปลี่ยนรหัสผ่าน',
                showCancelButton: true,
                cancelButtonColor: 'gray',
                cancelButtonText: 'ยกเลิก',
            })

            if (alert.isConfirmed) {
                axios.post('{{ env("APP_URL") }}/admin/resetpassword', {
                    'userid': userid,
                }).then((res) => {
                    Swal.fire({
                        title: res['data']['message'],
                        icon: 'success',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: 'green'
                    }).then(function(isConfirmed) {

                    })
                });
            }
        }
    </script>
@endsection
