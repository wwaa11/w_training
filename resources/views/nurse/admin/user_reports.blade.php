@extends("layouts.nurse")
@section("content")
    <div class="m-auto flex">
        <div class="m-auto mt-3 w-full rounded p-3 md:w-3/4">
            <div class="text-2xl font-bold">Users Report</div>
            <hr>
            <div class="flex flex-col">
                <div>
                    <select class="mt-3 w-full rounded border p-3" id="selectDepartment" onchange="changeDept()">
                        <option disabled selected>โปรดเลือก</option>
                        @foreach ($departmentArray as $dept)
                            <option value="{{ $dept }}" @if ($department == $dept) selected @endif>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
                @foreach ($data as $key => $department)
                    <div>
                        <div class="m-3 flex text-2xl">
                            <div class="flex-1 text-red-600">{{ $key }}</div>
                            <a class="text-blue-600 underline" href="{{ env("APP_URL") }}/nurse/admin/userscoreexport/{{ $key }}">Export</a>
                        </div>
                        <table class="my-3 w-full rounded bg-white p-3">
                            <thead class="h-24 overflow-clip bg-gray-200">
                                <th class="border border-gray-600 p-2">รหัสพนักงาน</th>
                                <th class="border border-gray-600 p-2">ชื่อ - สกุล</th>
                                <th class="border border-gray-600 p-2">ตำแหน่ง</th>
                                @foreach ($projects as $project)
                                    <th class="border border-gray-600 p-2 text-center" style="writing-mode: sideways-lr;">{{ $project->title }}</th>
                                @endforeach
                                <th class="border border-gray-600 p-2">วิทยากร</th>
                                <th class="border border-gray-600 bg-green-200 p-2">Total</th>
                            </thead>
                            <tbody>
                                @foreach ($department as $user)
                                    <tr>
                                        <td class="border border-gray-600 p-2">{{ $user["user"] }}</td>
                                        <td class="border border-gray-600 p-2">{{ $user["name"] }}</td>
                                        <td class="border border-gray-600 p-2">{{ $user["position"] }}</td>
                                        @foreach ($projects as $project)
                                            <th class="border border-gray-600 p-2 text-center">{{ $user[$project->title] }}</th>
                                        @endforeach
                                        <td class="border border-gray-600 p-2 text-center">{{ $user["lecture"] }}</td>
                                        <td class="border border-gray-600 bg-green-200 p-2 text-center font-bold text-red-600">{{ $user["total"] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
@endsection
@section("scripts")
    <script>
        function changeDept() {
            Swal.fire({
                title: 'Please, wait.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
            })

            dept = $('#selectDepartment').find(":selected").val();
            window.location.replace('{{ env("APP_URL") }}/nurse/admin/userscore?department=' + dept);
        }
    </script>
@endsection
