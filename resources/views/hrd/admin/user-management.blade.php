@extends("layouts.hrd")

@section("content")
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">จัดการผู้ใช้</h1>
                    <p class="mt-2 text-gray-600">ค้นหาและจัดการข้อมูลผู้ใช้ในระบบ</p>
                </div>
                <div class="flex items-center gap-3">
                    <button class="flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-gray-700 transition-colors hover:bg-gray-200" onclick="refreshPage()" title="รีเฟรช">
                        <i class="fas fa-sync-alt"></i>
                        รีเฟรช
                    </button>
                </div>
            </div>
        </div>

        <!-- Search Section -->
        <div class="mb-6 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row">
                <div class="flex-1">
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input class="w-full rounded-lg border border-gray-300 py-3 pl-10 pr-4 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500" id="searchInput" type="text" placeholder="ค้นหาด้วยรหัสพนักงาน, ชื่อ, ตำแหน่ง, หรือแผนก..." autocomplete="off">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <button class="hidden text-gray-400 hover:text-gray-600" id="clearSearchBtn" onclick="clearSearch()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <button class="rounded-lg bg-blue-600 px-6 py-3 font-medium text-white transition-colors hover:bg-blue-700" onclick="searchUser()">
                    <i class="fas fa-search mr-2"></i>
                    ค้นหา
                </button>
            </div>

            <!-- Search Info -->
            <div class="mt-3">
                <div class="flex items-center gap-2 text-sm text-gray-600" id="searchInfo">
                    <i class="fas fa-info-circle"></i>
                    <span>พิมพ์เพื่อค้นหาแบบทันที หรือคลิกปุ่มค้นหาเพื่อค้นหาแบบละเอียด</span>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex items-center">
                    <div class="rounded-full bg-blue-100 p-2 text-blue-600">
                        <i class="fas fa-users text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">ผู้ใช้ทั้งหมด</p>
                        <p class="text-2xl font-bold text-gray-900" id="totalUsers">{{ $users->total() }}</p>
                    </div>
                </div>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex items-center">
                    <div class="rounded-full bg-purple-100 p-2 text-purple-600">
                        <i class="fas fa-search text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">ผลการค้นหา</p>
                        <p class="text-2xl font-bold text-gray-900" id="searchResults">-</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-6 py-4">
                <h3 class="text-lg font-medium text-gray-900">รายชื่อผู้ใช้</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                รหัสพนักงาน
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                ชื่อ - สกุล
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                ตำแหน่ง
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                แผนก
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                สถานะ
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">
                                การดำเนินการ
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white" id="userTable">
                        @foreach ($users as $user)
                            <tr class="transition-colors hover:bg-gray-50" id="user{{ $user->userid }}">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-300">
                                                <span class="text-sm font-medium text-gray-700">{{ mb_substr($user->name, 0, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->userid }}</div>
                                            @if ($user->admin)
                                                <div class="text-xs font-medium text-red-600">ผู้ดูแลระบบ</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $user->position ?: "-" }}</div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $user->department ?: "-" }}</div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @if ($user->admin)
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                            <i class="fas fa-shield-alt mr-1"></i>
                                            ผู้ดูแลระบบ
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                            <i class="fas fa-user mr-1"></i>
                                            ผู้ใช้ทั่วไป
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a class="inline-flex items-center rounded-md border border-transparent bg-blue-100 px-3 py-2 text-sm font-medium leading-4 text-blue-700 transition-colors hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" href="{{ route("hrd.admin.users.attendances", $user->id) }}">
                                            <i class="fas fa-calendar-check mr-1"></i>
                                            ประวัติ
                                        </a>
                                        <button class="inline-flex items-center rounded-md border border-transparent bg-red-100 px-3 py-2 text-sm font-medium leading-4 text-red-700 transition-colors hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" onclick="resetPassword('{{ $user->userid }}')">
                                            <i class="fas fa-key mr-1"></i>
                                            รีเซ็ตรหัสผ่าน
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection

@section("scripts")
    <script>
        function refreshPage() {
            window.location.reload();
        }

        function clearSearch() {
            $('#searchInput').val('');
            $('#clearSearchBtn').addClass('hidden');
            $('#searchInfo').html('<i class="fas fa-info-circle"></i><span>พิมพ์เพื่อค้นหาแบบทันที หรือคลิกปุ่มค้นหาเพื่อค้นหาแบบละเอียด</span>');
            $('#searchResults').text('-');
            // Reload the page to restore original data
            window.location.reload();
        }



        $('#searchInput').keypress(function(e) {
            if (e.which == 13) {
                searchUser();
            }
        });

        // Update search info and clear button based on input
        $('#searchInput').on('input', function() {
            var value = $(this).val();
            if (value.length > 0) {
                $('#clearSearchBtn').removeClass('hidden');
                $('#searchInfo').html('<i class="fas fa-search"></i><span>กำลังค้นหาแบบทันที...</span>');
            } else {
                $('#clearSearchBtn').addClass('hidden');
                $('#searchInfo').html('<i class="fas fa-info-circle"></i><span>พิมพ์เพื่อค้นหาแบบทันที หรือคลิกปุ่มค้นหาเพื่อค้นหาแบบละเอียด</span>');
                $('#searchResults').text('-');
            }
        });

        function search() {
            var value = $('#searchInput').val().toLowerCase();
            var visibleCount = 0;

            $("#userTable tr").each(function() {
                var text = $(this).text().toLowerCase();
                var isVisible = text.indexOf(value) > -1;
                $(this).toggle(isVisible);
                if (isVisible) visibleCount++;
            });

            $('#searchResults').text(visibleCount);
        }

        function searchUser() {
            var userid = $('#searchInput').val().toLowerCase();

            if (!userid) {
                Swal.fire({
                    title: 'กรุณาใส่คำค้นหา',
                    text: 'โปรดใส่รหัสพนักงาน, ชื่อ, ตำแหน่ง, หรือแผนกที่ต้องการค้นหา',
                    icon: 'warning',
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: '#3B82F6'
                });
                return;
            }

            // Show loading state
            $('#searchInfo').html('<i class="fas fa-spinner fa-spin"></i><span>กำลังค้นหา...</span>');

            axios.post('{{ route("hrd.admin.users.search") }}', {
                'userid': userid,
            }).then((res) => {
                var html = '';
                $.each(res.data.data, function(index, value) {
                    var statusBadge = value.admin ?
                        '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="fas fa-shield-alt mr-1"></i>ผู้ดูแลระบบ</span>' :
                        '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-user mr-1"></i>ผู้ใช้ทั่วไป</span>';

                    html += '<tr id="user' + value.userid + '" class="hover:bg-gray-50 transition-colors">' +
                        '<td class="px-6 py-4 whitespace-nowrap">' +
                        '<div class="flex items-center">' +
                        '<div class="flex-shrink-0 h-10 w-10">' +
                        '<div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">' +
                        '<span class="text-sm font-medium text-gray-700">' + (value.name ? value.name.substring(0, 2) : '--') + '</span>' +
                        '</div>' +
                        '</div>' +
                        '<div class="ml-4">' +
                        '<div class="text-sm font-medium text-gray-900">' + value.userid + '</div>' +
                        (value.admin ? '<div class="text-xs text-red-600 font-medium">ผู้ดูแลระบบ</div>' : '') +
                        '</div>' +
                        '</div>' +
                        '</td>' +
                        '<td class="px-6 py-4 whitespace-nowrap">' +
                        '<div class="text-sm font-medium text-gray-900">' + value.name + '</div>' +
                        '</td>' +
                        '<td class="px-6 py-4 whitespace-nowrap">' +
                        '<div class="text-sm text-gray-900">' + (value.position || '-') + '</div>' +
                        '</td>' +
                        '<td class="px-6 py-4 whitespace-nowrap">' +
                        '<div class="text-sm text-gray-900">' + (value.department || '-') + '</div>' +
                        '</td>' +
                        '<td class="px-6 py-4 whitespace-nowrap">' + statusBadge + '</td>' +
                        '<td class="px-6 py-4 whitespace-nowrap text-center">' +
                        '<div class="flex items-center justify-center space-x-2">' +
                        '<a href="{{ route("hrd.admin.users.attendances", ":userId") }}'.replace(':userId', value.id) + '" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">' +
                        '<i class="fas fa-calendar-check mr-1"></i>ประวัติ</a>' +
                        '<button onclick="resetPassword(\'' + value.userid + '\')" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">' +
                        '<i class="fas fa-key mr-1"></i>รีเซ็ตรหัสผ่าน</button>' +
                        '</div>' +
                        '</td>' +
                        '</tr>';
                });
                $('#userTable').html(html);

                // Update search info and results
                if (res.data.data.length > 0) {
                    $('#searchInfo').html('<i class="fas fa-check-circle text-green-500"></i><span>พบ ' + res.data.data.length + ' รายการ</span>');
                    $('#searchResults').text(res.data.data.length);
                } else {
                    $('#searchInfo').html('<i class="fas fa-exclamation-circle text-yellow-500"></i><span>ไม่พบข้อมูลที่ค้นหา</span>');
                    $('#searchResults').text('0');
                }
            }).catch((error) => {
                console.error('Search error:', error);
                $('#searchInfo').html('<i class="fas fa-exclamation-triangle text-red-500"></i><span>เกิดข้อผิดพลาดในการค้นหา</span>');
                $('#searchResults').text('-');
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถค้นหาผู้ใช้ได้',
                    icon: 'error',
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: '#EF4444'
                });
            });
        }

        async function resetPassword(userid) {
            const result = await Swal.fire({
                title: "ยืนยันการรีเซ็ตรหัสผ่าน",
                html: `
                    <div class="text-left">
                        <p class="mb-3">คุณต้องการรีเซ็ตรหัสผ่านสำหรับ:</p>
                        <div class="bg-gray-100 p-3 rounded-lg">
                            <p class="font-medium text-gray-900">รหัสพนักงาน: <span class="text-blue-600">${userid}</span></p>
                        </div>
                        <p class="mt-3 text-sm text-gray-600">รหัสผ่านใหม่จะเป็น: <span class="font-medium text-red-600">${userid}</span></p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'รีเซ็ตรหัสผ่าน',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
            });

            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'กำลังรีเซ็ตรหัสผ่าน...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                axios.post('{{ route("hrd.admin.users.resetpassword") }}', {
                    'userid': userid,
                }).then((res) => {
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: res.data.message,
                        icon: 'success',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#10B981'
                    });
                }).catch((error) => {
                    console.error('Reset password error:', error);
                    Swal.fire({
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถรีเซ็ตรหัสผ่านได้',
                        icon: 'error',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#EF4444'
                    });
                });
            }
        }
    </script>
@endsection
