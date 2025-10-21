@extends("layouts.nurse")
@section("content")
    <div class="container mx-auto px-3">
        <!-- Header -->
        <div class="mb-4">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-bold text-gray-900 sm:text-2xl">Users Management</h1>
                <button class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-2 text-xs font-medium text-white hover:bg-blue-700 sm:px-4 sm:text-sm" onclick="refreshPage()">
                    <i class="fas fa-arrows-rotate mr-1.5 sm:mr-2"></i>
                    อัพเดตข้อมูล
                </button>
            </div>
            <p class="mt-1 text-xs text-gray-600 sm:text-sm">ค้นหาและจัดการผู้ใช้พนักงาน</p>
        </div>

        <!-- Search -->
        <div class="mb-4 flex items-center">
            <input class="flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200" id="searchInput" autocomplete="off" onkeyup="search()" type="text" placeholder="รหัสพนักงาน">
            <button class="ml-2 rounded-lg bg-gray-200 px-3 py-2 text-xs font-medium text-gray-800 hover:bg-gray-300 sm:text-sm" onclick="searchUser()">ค้นหา</button>
        </div>

        <!-- Pagination -->
        <div class="mb-2">
            {{ $users->links() }}
        </div>

        <!-- Users Table -->
        <div class="overflow-x-auto rounded-xl shadow">
            <table class="my-3 w-full min-w-max rounded bg-white p-3 text-sm">
                <thead class="sticky top-0 z-10 bg-gray-200">
                    <tr>
                        <th class="border border-gray-600 p-2">รหัสพนักงาน</th>
                        <th class="border border-gray-600 p-2">ชื่อ - สกุล</th>
                        <th class="border border-gray-600 p-2">ตำแหน่ง</th>
                        <th class="border border-gray-600 p-2">แผนก</th>
                        <th class="border border-gray-600 p-2"></th>
                    </tr>
                </thead>
                <tbody id="userTable">
                    @foreach ($users as $user)
                        <tr class="@if ($loop->even) bg-gray-50 @endif transition hover:bg-blue-50" id="user{{ $user->userid }}">
                            <td class="border p-2 text-center">
                                @if ($user->admin)
                                    <span class="text-red-600">(Admin)</span>
                                @endif
                                {{ $user->userid }}
                            </td>
                            <td class="border p-2">{{ $user->name }}</td>
                            <td class="border p-2">{{ $user->position }}</td>
                            <td class="border p-2">{{ $user->department }}</td>
                            <td class="border p-2 text-center">
                                <button class="cursor-pointer rounded bg-red-50 px-3 py-1 text-red-600 hover:bg-red-100" onclick="resetPassword('{{ $user->userid }}')" type="button">รีเซ็ตรหัสผ่าน</button>
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
        function refreshPage() { window.location.reload(); }

        $('#searchInput').keypress(function(e) {
            if (e.which == 13) { searchUser(); }
        });

        function search() {
            var value = $('#searchInput').val().toLowerCase();
            $("#userTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        }

        function searchUser() {
            var userid = $('#searchInput').val().toLowerCase();
            axios.post('{{ route("nurse.admin.users.search") }}', { 'userid': userid }).then((res) => {
                let html = '';
                $.each(res.data.data, function(index, value) {
                    html += '<tr class="transition hover:bg-blue-50" id="user' + value.userid + '">' +
                        '<td class="border p-2 text-center">' + (value.admin ? '<span class="text-red-600">(Admin)</span> ' : '') + value.userid + '</td>' +
                        '<td class="border p-2">' + value.name + '</td>' +
                        '<td class="border p-2">' + value.position + '</td>' +
                        '<td class="border p-2">' + value.department + '</td>' +
                        '<td class="border p-2 text-center">' +
                        '<button class="cursor-pointer rounded bg-red-50 px-3 py-1 text-red-600 hover:bg-red-100" onclick="resetPassword(\'' + value.userid + '\')">รีเซ็ตรหัสผ่าน</button>' +
                        '</td>' +
                        '</tr>';
                });
                $('#userTable').html(html);
            });
        }

        async function resetPassword(userid) {
            const alert = await Swal.fire({
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
                axios.post('{{ route("nurse.admin.users.resetpassword") }}', { 'userid': userid }).then((res) => {
                    Swal.fire({
                        title: res['data']['message'],
                        icon: 'success',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: 'green'
                    }).then(function(isConfirmed) { })
                });
            }
        }
    </script>
@endsection
