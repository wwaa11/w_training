@extends("layouts.hr")
@section("content")
    <div class="m-auto flex">
        <div class="m-auto mt-3 w-full rounded p-3 md:w-3/4">
            <div class="text-2xl font-bold"><a class="text-blue-600" href="{{ route("hr.admin.index") }}">Project Management</a>
                / <a class="text-blue-600" href="{{ route("hr.admin.project.management", $project->id) }}">{{ $project->project_name }}</a>
                / คะแนนสอบ
            </div>
            <hr>
            <div class="mt-6 rounded border border-gray-200 bg-gray-50 p-3 shadow">
                <button class="cursor-pointer rounded bg-blue-600 p-3 text-white" onclick="importScore()"><i class="fa-solid fa-file-import"></i> นำเข้าข้อมูล</button>
                <div>
                    <div class="mt-3 flex gap-3">
                        <input class="w-full rounded border border-gray-400 p-3" id="userid" type="text" placeholder="รหัสพนักงงาน">
                        <button class="cursor-pointer rounded bg-blue-600 p-3 text-white" onclick="searchUserID()">ค้นหา</button>
                    </div>
                    <table class="mt-3 w-full">
                        @if ($project->scoreHeader !== null)
                            <thead>
                                <tr>
                                    <th class="border p-3">รหัสพนักงาน</th>
                                    <th class="border p-3">ชื่อ - นามสกุล</th>
                                    @if ($project->scoreHeader->title_1 !== null)
                                        <th class="border p-3">{{ $project->scoreHeader->title_1 }}</th>
                                    @endif
                                    @if ($project->scoreHeader->title_2 !== null)
                                        <th class="border p-3">{{ $project->scoreHeader->title_2 }}</th>
                                    @endif
                                    @if ($project->scoreHeader->title_3 !== null)
                                        <th class="border p-3">{{ $project->scoreHeader->title_3 }}</th>
                                    @endif
                                    @if ($project->scoreHeader->title_4 !== null)
                                        <th class="border p-3">{{ $project->scoreHeader->title_4 }}</th>
                                    @endif
                                    @if ($project->scoreHeader->title_5 !== null)
                                        <th class="border p-3">{{ $project->scoreHeader->title_5 }}</th>
                                    @endif
                                    @if ($project->scoreHeader->title_6 !== null)
                                        <th class="border p-3">{{ $project->scoreHeader->title_6 }}</th>
                                    @endif
                                    @if ($project->scoreHeader->title_7 !== null)
                                        <th class="border p-3">{{ $project->scoreHeader->title_7 }}</th>
                                    @endif
                                    @if ($project->scoreHeader->title_8 !== null)
                                        <th class="border p-3">{{ $project->scoreHeader->title_8 }}</th>
                                    @endif
                                    @if ($project->scoreHeader->title_9 !== null)
                                        <th class="border p-3">{{ $project->scoreHeader->title_9 }}</th>
                                    @endif
                                    @if ($project->scoreHeader->title_10 !== null)
                                        <th class="border p-3">{{ $project->scoreHeader->title_10 }}</th>
                                    @endif
                                    @if ($project->scoreHeader->title_11 !== null)
                                        <th class="border p-3">{{ $project->scoreHeader->title_11 }}</th>
                                    @endif
                                    @if ($project->scoreHeader->title_12 !== null)
                                        <th class="border p-3">{{ $project->scoreHeader->title_12 }}</th>
                                    @endif
                                    @if ($project->scoreHeader->title_13 !== null)
                                        <th class="border p-3">{{ $project->scoreHeader->title_13 }}</th>
                                    @endif
                                    @if ($project->scoreHeader->title_14 !== null)
                                        <th class="border p-3">{{ $project->scoreHeader->title_14 }}</th>
                                    @endif
                                    @if ($project->scoreHeader->title_15 !== null)
                                        <th class="border p-3">{{ $project->scoreHeader->title_15 }}</th>
                                    @endif
                                    @if ($project->scoreHeader->title_16 !== null)
                                        <th class="border p-3">{{ $project->scoreHeader->title_16 }}</th>
                                    @endif
                                    @if ($project->scoreHeader->title_17 !== null)
                                        <th class="border p-3">{{ $project->scoreHeader->title_17 }}</th>
                                    @endif
                                    @if ($project->scoreHeader->title_18 !== null)
                                        <th class="border p-3">{{ $project->scoreHeader->title_18 }}</th>
                                    @endif
                                    @if ($project->scoreHeader->title_19 !== null)
                                        <th class="border p-3">{{ $project->scoreHeader->title_19 }}</th>
                                    @endif
                                    @if ($project->scoreHeader->title_20 !== null)
                                        <th class="border p-3">{{ $project->scoreHeader->title_20 }}</th>
                                    @endif
                                </tr>
                            </thead>
                        @endif
                        <tbody>
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td class="border p-3">{{ $transaction->userData->userid }}</td>
                                    <td class="border p-3">{{ $transaction->userData->name }}</td>
                                    @if ($transaction->result_1 !== null)
                                        <th class="border p-3">{{ $transaction->result_1 }}</th>
                                    @endif
                                    @if ($transaction->result_2 !== null)
                                        <th class="border p-3">{{ $transaction->result_2 }}</th>
                                    @endif
                                    @if ($transaction->result_3 !== null)
                                        <th class="border p-3">{{ $transaction->result_3 }}</th>
                                    @endif
                                    @if ($transaction->result_4 !== null)
                                        <th class="border p-3">{{ $transaction->result_4 }}</th>
                                    @endif
                                    @if ($transaction->result_5 !== null)
                                        <th class="border p-3">{{ $transaction->result_5 }}</th>
                                    @endif
                                    @if ($transaction->result_6 !== null)
                                        <th class="border p-3">{{ $transaction->result_6 }}</th>
                                    @endif
                                    @if ($transaction->result_7 !== null)
                                        <th class="border p-3">{{ $transaction->result_7 }}</th>
                                    @endif
                                    @if ($transaction->result_8 !== null)
                                        <th class="border p-3">{{ $transaction->result_8 }}</th>
                                    @endif
                                    @if ($transaction->result_9 !== null)
                                        <th class="border p-3">{{ $transaction->result_9 }}</th>
                                    @endif
                                    @if ($transaction->result_10 !== null)
                                        <th class="border p-3">{{ $transaction->result_10 }}</th>
                                    @endif
                                    @if ($transaction->result_11 !== null)
                                        <th class="border p-3">{{ $transaction->result_11 }}</th>
                                    @endif
                                    @if ($transaction->result_12 !== null)
                                        <th class="border p-3">{{ $transaction->result_12 }}</th>
                                    @endif
                                    @if ($transaction->result_13 !== null)
                                        <th class="border p-3">{{ $transaction->result_13 }}</th>
                                    @endif
                                    @if ($transaction->result_14 !== null)
                                        <th class="border p-3">{{ $transaction->result_14 }}</th>
                                    @endif
                                    @if ($transaction->result_15 !== null)
                                        <th class="border p-3">{{ $transaction->result_15 }}</th>
                                    @endif
                                    @if ($transaction->result_16 !== null)
                                        <th class="border p-3">{{ $transaction->result_16 }}</th>
                                    @endif
                                    @if ($transaction->result_17 !== null)
                                        <th class="border p-3">{{ $transaction->result_17 }}</th>
                                    @endif
                                    @if ($transaction->result_18 !== null)
                                        <th class="border p-3">{{ $transaction->result_18 }}</th>
                                    @endif
                                    @if ($transaction->result_19 !== null)
                                        <th class="border p-3">{{ $transaction->result_19 }}</th>
                                    @endif
                                    @if ($transaction->result_20 !== null)
                                        <th class="border p-3">{{ $transaction->result_20 }}</th>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("scripts")
    <script>
        async function importScore() {
            alert = await Swal.fire({
                title: "นำเข้าข้อมูลคะแนนสอบ",
                input: "file",
                confirmButtonColor: 'green',
                inputAttributes: {
                    "accept": ".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                    "aria-label": "Upload score excel."
                }
            });
            if (alert.isConfirmed && alert.value !== null) {
                Swal.fire({
                    title: 'กำลังนำเข้าข้อมูล',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                })
                const formData = new FormData();
                formData.append("project_id", {{ $project->id }});
                formData.append("file", alert.value);

                axios.post('{{ route("hr.admin.scores.import") }}', formData, {
                    "Content-Type": "multipart/form-data"
                }).then((res) => {
                    Swal.fire({
                        title: res['data']['message'],
                        icon: 'success',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: 'green'
                    }).then(function(isConfirmed) {
                        window.location.reload()
                    })
                }).catch(function(error) {
                    Swal.fire({
                        title: 'Error',
                        icon: 'error',
                        showConfirmButton: false,
                    })
                });;
            }
        }

        function searchUserID() {
            user = $('#userid').val();
            window.location.replace('{{ route("hr.admin.scores.index") }}?project={{ $project->id }}&project=' + {{ $project->id }} + '&userid=' + user);
        }
    </script>
@endsection
