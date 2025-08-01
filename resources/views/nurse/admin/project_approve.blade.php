@extends("layouts.nurse")
@section("meta")
    <meta http-equiv="Refresh" content="60">
@endsection
@section("content")
    <div class="m-auto w-full p-3 md:w-3/4">
        <div class="text-2xl font-bold">
            <a class="text-blue-600" href="{{ route("nurse.admin.index") }}">Training Management</a>
            / <a class="text-blue-600" href="{{ route("nurse.admin.project.management", $project->id) }}">{{ $project->title }}</a>
            / Approve
        </div>
        <hr>
        <div class="flex flex-row gap-3">
            <select class="mt-2 flex-none rounded border bg-gray-200 p-2" id="searchTime" onchange="changeSearch()">
                <option @if ($query["time"] == "all") selected @endif value="all">โปรดระบุ</option>
                @foreach ($query["option"] as $option)
                    <option @if ($query["time"] == $option) selected @endif value="{{ $option }}">{{ $option }}</option>
                @endforeach
            </select>
            <input class="mt-3 w-full flex-1 rounded border border-gray-400 p-3" id="searchInput" onkeyup="search()" placeholder="ค้นหา" type="text">
            <div class="m-auto">
                <select class="mt-2 flex-none rounded border bg-gray-200 p-2" id="searchType" onchange="changeSearch()">
                    <option @if ($query["sign"] == "false") selected @endif value="false">Not Approve</option>
                    <option @if ($query["sign"] == "true") selected @endif value="true">Approved</option>
                </select>
            </div>
        </div>
        <div class="w-full rounded p-3">
            <table class="w-full">
                @if ($query["sign"] == "false")
                    <thead>
                        <td class="text-center" colspan="2">
                            <div class="mb-1 cursor-pointer rounded bg-green-300 p-3" onclick="approveArray()">Approve Select User</div>
                        </td>
                        <td colspan="6"></td>
                    </thead>
                @endif
                <thead class="bg-gray-200">
                    @if ($query["sign"] == "false")
                        <th class="border p-3"><input class="h-6 w-6 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500" id="selectall" type="checkbox" name="sample" /></th>
                    @endif
                    <th class="border p-3">วันที่</th>
                    <th class="border p-3">รอบ</th>
                    <th class="border p-3">รหัสพนักงาน</th>
                    <th class="border p-3">ชื่อ - สกุล</th>
                    <th class="border p-3">ตำแหน่ง</th>
                    <th class="border p-3">แผนก</th>
                    <th class="border p-3">Check-In</th>
                    <th class="border p-3">Nurse Approve</th>
                </thead>
                <tbody id="userTable">
                    @foreach ($transactions as $transcation)
                        <tr class="cursor-pointer">
                            @if ($query["sign"] == "false")
                                <td class="border p-2 text-center font-bold">
                                    <input class="checkselfCheckbox h-6 w-6 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500" id="checkbox_{{ $transcation->user }}" onchange="ChangeCheckBox('#checkbox_{{ $transcation->user }}')" name='checkbox[]' value="{{ $transcation->id }}" type="checkbox">
                                </td>
                            @endif
                            <td class="border p-2 text-center" onclick="checkBox('#checkbox_{{ $transcation->user }}')">{{ $transcation->timeData->dateData->title }}</td>
                            <td class="border p-2 text-center" onclick="checkBox('#checkbox_{{ $transcation->user }}')">{{ $transcation->timeData->title }}</td>
                            <td class="border p-2 text-center" onclick="checkBox('#checkbox_{{ $transcation->user }}')">{{ $transcation->user_id }}</td>
                            <td class="border p-2" onclick="checkBox('#checkbox_{{ $transcation->user }}')">{{ $transcation->userData->name }}</td>
                            <td class="border p-2" onclick="checkBox('#checkbox_{{ $transcation->user }}')">{{ $transcation->userData->position }}</td>
                            <td class="border p-2" onclick="checkBox('#checkbox_{{ $transcation->user }}')">{{ $transcation->userData->department }}</td>
                            <td class="border p-2 text-center text-green-600" onclick="checkBox('#checkbox_{{ $transcation->user }}')">{{ date("d/m/Y H:i", strtotime($transcation->user_sign)) }}</td>
                            <td class="border p-3 text-center">
                                @if ($transcation->admin_sign == null)
                                    <button class="cursor-pointer rounded p-3 text-red-600" onclick="approve('{{ $transcation->id }}','{{ $transcation->timeData->dateData->title }}','{{ $transcation->timeData->title }}','{{ $transcation->userData->userid }}','{{ $transcation->userData->name }}','{{ $transcation->userData->position }}','{{ $transcation->userData->department }}')" type="button">Approve</button>
                                @else
                                    {{ date("d/m/Y H:i", strtotime($transcation->admin_sign)) }}
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
