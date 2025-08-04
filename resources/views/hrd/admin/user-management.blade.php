@extends("layouts.hrd")
@section("content")
    <div class="m-auto flex">
        <div class="m-auto mt-3 w-full rounded p-3 md:w-3/4">
            <div class="text-2xl font-bold">Users Management</div>
            <hr>
            <div class="flex">
                <div class="my-2 w-12 cursor-pointer rounded-s bg-gray-300 p-3 text-center" onclick="refreshPage()"><i class="fa-solid fa-arrows-rotate"></i></div>
                <input class="my-2 w-full flex-1 border border-gray-300 bg-white p-3" id="searchInput" autocomplete="off" onkeyup="search()" type="text" placeholder="รหัสพนักงาน">
                <div class="my-2 w-24 cursor-pointer rounded-e bg-gray-300 p-3 text-center" onclick="searchUser()">ค้นหา</div>
            </div>
            <div class="mt-4">
                {{ $users->links() }}
            </div>
            <table class="my-3 w-full rounded bg-white p-3">
                <thead class="bg-gray-200">
                    <th class="border p-3">รหัสพนักงาน</th>
                    <th class="border p-3">ชื่อ - สกุล</th>
                    <th class="border p-3">ตำแหน่ง</th>
                    <th class="border p-3">แผนก</th>
                    <th class="border p-3">การดำเนินการ</th>
                </thead>
                <tbody id="userTable">
                    @foreach ($users as $user)
                        <tr id="user{{ $user->userid }}">
                            <td class="border p-2 text-center">
                                @if ($user->admin)
                                    <span class="text-red-600">(Admin)</span>
                                @endif
                                {{ $user->userid }}
                            </td>
                            <td class="border p-2">{{ $user->name }}</td>
                            <td class="border p-2">{{ $user->position }}</td>
                            <td class="border p-2">{{ $user->department }}</td>
                            <td class="border p-3 text-center">
                                <div class="flex justify-center gap-2">
                                    <a class="cursor-pointer rounded bg-blue-500 p-2 text-white hover:bg-blue-600" href="{{ route("hrd.admin.users.attendances", $user->id) }}">
                                        <i class="fa-solid fa-calendar-check"></i> ประวัติการเข้าร่วม
                                    </a>
                                    <button class="cursor-pointer rounded bg-red-500 p-2 text-white hover:bg-red-600" onclick="resetPassword('{{ $user->userid }}')" type="button">
                                        <i class="fa-solid fa-key"></i> รีเซ็ตรหัสผ่าน
                                    </button>
                                </div>
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
        function refreshPage() {
            window.location.reload();
        }

        $('#searchInput').keypress(function(e) {
            if (e.which == 13) {
                searchUser();
            }
        });

        function search() {
            var value = $('#searchInput').val().toLowerCase();
            $("#userTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        }

        function searchUser() {
            var userid = $('#searchInput').val().toLowerCase();

            axios.post('{{ route("hrd.admin.users.search") }}', {
                'userid': userid,
            }).then((res) => {
                html = '';
                $.each(res.data.data, function(index, value) {
                    html += '<tr id="user' + value.userid + '">' +
                        '<td class="border p-2">' + value.userid + '</td>' +
                        '<td class="border p-2">' + value.name + '</td>' +
                        '<td class="border p-2">' + value.position + '</td>' +
                        '<td class="border p-2">' + value.department + '</td>' +
                        '<td class="border p-3 text-center">' +
                        '<div class="flex gap-2 justify-center">' +
                        '<a href="{{ route("hrd.admin.users.attendances", ":userId") }}'.replace(':userId', value.userid) + '" class="cursor-pointer rounded bg-blue-500 p-2 text-white hover:bg-blue-600">' +
                        '<i class="fa-solid fa-calendar-check"></i> ประวัติการเข้าร่วม</a>' +
                        '<button class="cursor-pointer rounded bg-red-500 p-2 text-white hover:bg-red-600" onclick="resetPassword(\'' + value.userid + '\')">' +
                        '<i class="fa-solid fa-key"></i> รีเซ็ตรหัสผ่าน</button>' +
                        '</div>' +
                        '</td>' +
                        '</tr>';
                });
                $('#userTable').html(html);
            });
        }

        async function resetPassword(userid) {
            alert = await Swal.fire({
                title: "ยืนยันการรีเซ็ตรหัสผ่าน : " + userid,
                html: "รหัสผ่านที่ถูกรีเซ็ต จะถูกเปลี่ยนเป็น <span class=\"text-red-600\"><br>รหัสพนักงาน : " + userid + "</span> ",
                icon: 'warning',
                allowOutsideClick: false,
                showConfirmButton: true,
                confirmButtonColor: 'red',
                confirmButtonText: 'รีเซ็ตรหัสผ่าน',
                showCancelButton: true,
                cancelButtonColor: 'gray',
                cancelButtonText: 'ยกเลิก',
            })

            if (alert.isConfirmed) {
                axios.post('{{ route("hrd.admin.users.resetpassword") }}', {
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
