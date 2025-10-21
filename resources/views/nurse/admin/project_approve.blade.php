@extends("layouts.nurse")
@section("meta")
    <meta http-equiv="Refresh" content="60">
@endsection
@section("content")
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-lg bg-white p-4 shadow-lg sm:p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-800">
                    <a class="text-blue-600 hover:underline" href="{{ route("nurse.admin.project.management", $project->id) }}">{{ $project->title }}</a>
                    <span class="text-gray-500">/ อนุมัติผู้ลงทะเบียน</span>
                </h1>
                @if ($query["sign"] == "false")
                    <button class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white transition duration-200 hover:bg-green-700" onclick="approveArray()" type="button">Approve Select User</button>
                @endif
            </div>

            <div class="mb-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <select class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm" id="searchTime" onchange="changeSearch()">
                        <option @if ($query["time"] == "all") selected @endif value="all">ทั้งหมด</option>
                        @foreach ($query["option"] as $option)
                            <option @if ($query["time"] == $option) selected @endif value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                    <input class="w-full flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm" id="searchInput" onkeyup="search()" placeholder="ค้นหา" type="text">
                    <div class="flex-none">
                        <select class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm" id="searchType" onchange="changeSearch()">
                            <option @if ($query["sign"] == "false") selected @endif value="false">ยังไม่อนุมัติ</option>
                            <option @if ($query["sign"] == "true") selected @endif value="true">อนุมัติ</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="hidden overflow-x-auto sm:block">
                <table class="min-w-full border border-gray-200 bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            @if ($query["sign"] == "false")
                                <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">
                                    <input class="h-5 w-5 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500" id="selectall" type="checkbox" name="sample" />
                                </th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">วันที่</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">รอบ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">รหัสพนักงาน</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">ชื่อ - สกุล</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">ตำแหน่ง</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">แผนก</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Check-In</th>
                            <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">Nurse Approve</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" id="userTable">
                        @foreach ($transactions as $transcation)
                            <tr class="cursor-pointer hover:bg-gray-50">
                                @if ($query["sign"] == "false")
                                    <td class="px-6 py-4 text-center">
                                        <input class="checkselfCheckbox h-5 w-5 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500" id="checkbox_{{ $transcation->user }}" onchange="ChangeCheckBox('#checkbox_{{ $transcation->user }}')" name='checkbox[]' value="{{ $transcation->id }}" type="checkbox">
                                    </td>
                                @endif
                                <td class="px-6 py-4 text-sm text-gray-900" onclick="checkBox('#checkbox_{{ $transcation->user }}')">{{ $transcation->timeData->dateData->title }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900" onclick="checkBox('#checkbox_{{ $transcation->user }}')">{{ $transcation->timeData->title }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900" onclick="checkBox('#checkbox_{{ $transcation->user }}')">{{ $transcation->user_id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900" onclick="checkBox('#checkbox_{{ $transcation->user }}')">{{ $transcation->userData->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900" onclick="checkBox('#checkbox_{{ $transcation->user }}')">{{ $transcation->userData->position }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900" onclick="checkBox('#checkbox_{{ $transcation->user }}')">{{ $transcation->userData->department }}</td>
                                <td class="px-6 py-4 text-center text-sm font-medium text-green-700" onclick="checkBox('#checkbox_{{ $transcation->user }}')">{{ date("d/m/Y H:i", strtotime($transcation->user_sign)) }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if ($transcation->admin_sign == null)
                                        <button class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white transition duration-200 hover:bg-blue-700" onclick="approve('{{ $transcation->id }}','{{ $transcation->timeData->dateData->title }}','{{ $transcation->timeData->title }}','{{ $transcation->userData->userid }}','{{ $transcation->userData->name }}','{{ $transcation->userData->position }}','{{ $transcation->userData->department }}')" type="button">อนุมัติ</button>
                                    @else
                                        <span class="text-sm text-gray-700">{{ date("d/m/Y H:i", strtotime($transcation->admin_sign)) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="block space-y-3 sm:hidden">
                @foreach ($transactions as $transcation)
                    <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="font-semibold text-gray-900">{{ $transcation->timeData->dateData->title }} • {{ $transcation->timeData->title }}</div>
                            @if ($query["sign"] == "false")
                                <input class="checkselfCheckbox h-5 w-5 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500" id="checkbox_m_{{ $transcation->user }}" onchange="ChangeCheckBox('#checkbox_m_{{ $transcation->user }}')" name='checkbox[]' value="{{ $transcation->id }}" type="checkbox">
                            @endif
                        </div>
                        <div class="mt-2 text-sm text-gray-700">
                            <div>รหัส: {{ $transcation->user_id }}</div>
                            <div>ชื่อ: {{ $transcation->userData->name }}</div>
                            <div>ตำแหน่ง: {{ $transcation->userData->position }}</div>
                            <div>แผนก: {{ $transcation->userData->department }}</div>
                            <div class="text-green-700">Check-In: {{ date("d/m/Y H:i", strtotime($transcation->user_sign)) }}</div>
                        </div>
                        <div class="mt-3 text-right">
                            @if ($transcation->admin_sign == null)
                                <button class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white transition duration-200 hover:bg-blue-700" onclick="approve('{{ $transcation->id }}','{{ $transcation->timeData->dateData->title }}','{{ $transcation->timeData->title }}','{{ $transcation->userData->userid }}','{{ $transcation->userData->name }}','{{ $transcation->userData->position }}','{{ $transcation->userData->department }}')" type="button">Approve</button>
                            @else
                                <span class="text-sm text-gray-700">{{ date("d/m/Y H:i", strtotime($transcation->admin_sign)) }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@section("scripts")
    <script>
        var arraycheckbox = [];

        function search() {
            var value = $('#searchInput').val().toLowerCase();
            $("#userTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        }

        function changeSearch() {

            type = $('#searchType').find(":selected").val();
            time = $('#searchTime').find(":selected").val();
            window.location.replace('{{ route("nurse.admin.approve.index") }}?project={{ $project->id }}&sign=' + type + '&time=' + time);
        }

        $('#selectall').click(function() {
            if ($(this).is(':checked')) {
                $('input.checkselfCheckbox').each(function() {
                    $(this).prop('checked', true);
                    arraycheckbox.push($(this).val());
                });
            } else {
                $('input.checkselfCheckbox').each(function() {
                    $(this).prop('checked', false);
                    var index = arraycheckbox.indexOf($(this).val());
                    if (index !== -1) {
                        arraycheckbox.splice(index, 1);
                    }
                });
            }
            console.log(arraycheckbox)
        });

        function ChangeCheckBox(id) {
            var value = $(id).val();
            if ($(id).is(':checked')) {
                arraycheckbox.push(value)
            } else {
                var index = arraycheckbox.indexOf(value);
                if (index !== -1) {
                    arraycheckbox.splice(index, 1);
                }
            }
        }

        function checkBox(id) {
            var value = $(id).val();
            if ($(id).is(':checked')) {
                $(id).prop('checked', false)

                var index = arraycheckbox.indexOf(value);
                if (index !== -1) {
                    arraycheckbox.splice(index, 1);
                }
            } else {
                $(id).prop('checked', true)
                arraycheckbox.push(value)
            }
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
                axios.post('{{ route("nurse.admin.approve.user") }}', {
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

        async function approveArray() {
            alert = await Swal.fire({
                title: "ยืนยันการ Approve ข้อมูลที่เลือกทั้งหมด",
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
                axios.post('{{ route("nurse.admin.approve.userArray") }}', {
                    'id': arraycheckbox,
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
