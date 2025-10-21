@extends("layouts.nurse")
@section("content")
    <div class="mx-auto max-w-5xl px-4 py-6 md:px-6">
        <form class="mb-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm" action="{{ route("nurse.admin.project.update", $project->id) }}" method="POST">
            @csrf
            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex items-center justify-between gap-3">
                <label class="text-2xl font-semibold">
                    <a class="py-2 text-blue-700 hover:text-blue-800" href="{{ route("nurse.admin.project.management", ["project_id" => $project->id]) }}"">
                        {{ $project->title }}
                    </a>
                    / แก้ไขโครงการฝึกอบรม
                </label>
                <button class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700" type="submit">บันทึก</button>
            </div>
            <hr class="my-4" />
            <label class="font-medium">ชื่อโครงการฝึกอบรม</label>
            <input class="mb-3 w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-blue-500" type="text" name="title" placeholder="ชื่อโครงการฝึกอบรม" value="{{ old("title", $project->title) }}" required>
            <label class="font-medium">Type of Excel</label>
            <select class="mb-3 w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-blue-500" name="export_type" required>
                <option value="" disabled>โปรดระบุ</option>
                <option value="1" {{ old("export_type", $project->export_type) == 1 ? "selected" : "" }}>ใบบันทึกฝึกอบรม ภาคปฐมนิเทศ</option>
                <option value="2" {{ old("export_type", $project->export_type) == 2 ? "selected" : "" }}>ใบบันทึกฝึกอบรม ส่วนกลางโรงพยาบาล</option>
                <option value="3" {{ old("export_type", $project->export_type) == 3 ? "selected" : "" }}>ใบบันทึกการฝึกอบรมภาคอิสระ</option>
            </select>
            <label class="font-medium">สถานที่จัดโครงการ</label>
            <input class="mb-3 w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-blue-500" type="text" name="location" placeholder="สถานที่จัดโครงการ" value="{{ old("location", $project->location) }}" required>
            <div class="flex flex-row gap-3">
                <div class="w-1/2">
                    <label class="font-medium">วันที่เริ่มการลงทะเบียน</label>
                    <input class="mb-3 w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-blue-500" type="date" name="register_start" value="{{ old("register_start", date("Y-m-d", strtotime($project->register_start))) }}" placeholder="วันที่เริ่ม" required>
                </div>
                <div class="w-1/2">
                    <label class="font-medium">วันที่สิ้นสุดการลงทะเบียน</label>
                    <input class="mb-3 w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-blue-500" type="date" name="register_end" value="{{ old("register_end", date("Y-m-d", strtotime($project->register_end))) }}" placeholder="วันที่สิ้นสุด" required>
                </div>
            </div>
            <label class="font-medium">รายละเอียดการฝึกอบรม</label>
            <textarea class="mb-4 w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-blue-500" name="detail" cols="30" rows="5">{{ old("detail", $project->detail) }}</textarea>
            <div class="mb-4 flex items-center rounded-lg border border-gray-200 bg-gray-50 ps-4">
                <input type="hidden" name="multiple_register" value="0">
                <input class="h-4 w-4 rounded-sm border-gray-300 bg-white text-blue-600 focus:ring-2 focus:ring-blue-500" id="multiple_register" type="checkbox" value="1" name="multiple_register" {{ old("multiple_register", $project->multiple) ? "checked" : "" }}>
                <label class="ms-2 w-full py-4 text-sm font-medium text-gray-900" for="multiple_register">การเปิดลงทะเบียนมากกว่า 1 รายการ</label>
            </div>

            <div class="mb-6 rounded-xl border border-gray-200 bg-gray-50 p-4">
                <label class="text-2xl font-semibold">วันที่เปิดการฝึกอบรม</label>
                <hr class="my-3" />
                <div class="flex flex-row gap-3">
                    <div class="w-2/5">
                        <label class="font-medium">วันที่เปิดลงทะเบียน</label>
                        <input class="mb-3 w-full rounded-lg border border-gray-300 bg-white p-2 focus:ring-2 focus:ring-blue-500" id="date_start" type="date" name="training_start" value="{{ old("training_start") }}" placeholder="วันที่เริ่ม">
                    </div>
                    <div class="w-2/5">
                        <label class="font-medium">&nbsp;</label>
                        <input class="mb-3 w-full rounded-lg border border-gray-300 bg-white p-2 focus:ring-2 focus:ring-blue-500" id="date_end" type="date" name="training_end" value="{{ old("training_end") }}" placeholder="วันที่สิ้นสุด">
                    </div>
                    <div class="w-1/5">
                        <label class="font-medium">&nbsp;</label>
                        <button class="mb-3 w-full cursor-pointer rounded-lg bg-yellow-400 p-2 text-white hover:bg-yellow-500" onclick="dateAdd()" type="button">เพิ่มวันที่</button>
                    </div>
                </div>
                <div id="date_section">
                    @foreach ($project->dateData as $date)
                        <div class="flex flex-row flex-wrap gap-3 rounded-lg border border-gray-200 bg-white p-3 shadow-sm" id="date{{ $date->date }}">
                            <input class="flex-1 rounded-lg border border-gray-300 p-3 focus:ring-2 focus:ring-blue-500" name="date[{{ $date->date }}][title]" type="text" value="{{ $date->title }}">
                            <input name="date[{{ $date->date }}][date]" type="hidden" value="{{ $date->date }}">
                            <button class="w-12 flex-none cursor-pointer rounded-lg bg-red-50 p-3 text-red-600 hover:bg-red-100" type="button" onclick="removeElementID('#date{{ $date->date }}')"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-6 rounded-xl border border-gray-200 bg-gray-50 p-4">
                <div class="flex flex-row gap-3">
                    <label class="flex-1 pt-2 text-2xl font-semibold">รอบการลงทะเบียน <span class="text-sm text-red-600">*รอบการลงทะเบียนจะเหมือนกันในทุกๆวันที่ทำการเปิดอบรม</span></label>
                    <button class="cursor-pointer rounded-lg bg-blue-600 p-2 px-4 text-white hover:bg-blue-700" type="button" onclick="addTime()"><i class="fa fa-plus"></i> เพิ่มรอบ การลงทะเบีบน</button>
                </div>
                <hr class="my-3" />
                <div id="time_section">
                    @php($idx = 1)
                    @php($times = optional($project->dateData->first())->timeData ?? collect([]))
                    @foreach ($times as $time)
                        <div class="mt-3 flex flex-row gap-3 rounded-lg border border-gray-200 bg-white p-3 shadow-sm" id="time_{{ $idx }}">
                            <div class="flex-none cursor-pointer text-red-600" onclick="$('#time_{{ $idx }}').remove()">
                                <div>&nbsp;</div>
                                <i class="fa-solid fa-xmark"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium">ชื่อรอบการลงทะเบียน</div>
                                <input class="mb-3 w-full rounded-lg border border-gray-300 bg-white p-2 focus:ring-2 focus:ring-blue-500" type="text" name="time[{{ $idx }}][title]" placeholder="ชื่อรอบการลงทะเบียน" value="{{ $time->title }}" required>
                            </div>
                            <div>
                                <div class="text-center font-medium">เริ่ม</div>
                                <input class="mb-3 w-full rounded-lg border border-gray-300 bg-white p-2 text-center focus:ring-2 focus:ring-blue-500" type="time" name="time[{{ $idx }}][start]" placeholder="เวลาเปิดลงทะเบียน" value="{{ \Carbon\Carbon::parse($time->time_start)->format("H:i") }}" required>
                            </div>
                            <div>
                                <div class="text-center font-medium">สิ้นสุด</div>
                                <input class="mb-3 w-full rounded-lg border border-gray-300 bg-white p-2 text-center focus:ring-2 focus:ring-blue-500" type="time" name="time[{{ $idx }}][end]" placeholder="เวลาเปิดลงทะเบียน" value="{{ \Carbon\Carbon::parse($time->time_end)->format("H:i") }}" required>
                            </div>
                            <div>
                                <div class="text-center font-medium">จำนวนที่นั้ง</div>
                                <input class="mb-3 w-full rounded-lg border border-gray-300 bg-white p-2 text-center focus:ring-2 focus:ring-blue-500" type="number" name="time[{{ $idx }}][max]" placeholder="กรณีไม่จำกัดให้ใส่ 0" value="{{ $time->max }}" required>
                            </div>
                        </div>
                        @php($idx++)
                    @endforeach
                </div>
            </div>
        </form>
    </div>
@endsection
@section("scripts")
    <script>
        var index = {{ isset($idx) ? $idx : 1 }};

        function addTime() {
            $('#time_section').append('<div class="mt-3 flex flex-row gap-3 rounded-lg border border-gray-200 bg-white p-3 shadow-sm" id="time_' + index + '">' +
                '<div class="flex-none cursor-pointer text-red-600" onclick=\'$("#time_' + index + '").remove()\'>' +
                '<div>&nbsp;</div>' +
                '<i class="fa-solid fa-xmark"></i>' +
                '</div>' +
                '<div class="flex-1">' +
                '<div class="font-medium">ชื่อรอบการลงทะเบียน</div>' +
                '<input class="mb-3 w-full rounded-lg border border-gray-300 bg-white p-2 focus:ring-2 focus:ring-blue-500" type="text" name="time[' + index + '][title]" placeholder="ชื่อรอบการลงทะเบียน" value="08:00 - 17:00" required>' +
                '</div>' +
                '<div>' +
                '<div class="text-center font-medium">เริ่ม</div>' +
                '<input class="mb-3 w-full rounded-lg border border-gray-300 bg-white p-2 text-center focus:ring-2 focus:ring-blue-500" type="time" name="time[' + index + '][start]" placeholder="เวลาเปิดลงทะเบียน" value="08:00" required>' +
                '</div>' +
                '<div>' +
                '<div class="text-center font-medium">สิ้นสุด</div>' +
                '<input class="mb-3 w-full rounded-lg border border-gray-300 bg-white p-2 text-center focus:ring-2 focus:ring-blue-500" type="time" name="time[' + index + '][end]" placeholder="เวลาเปิดลงทะเบียน" value="17:00" required>' +
                '</div>' +
                '<div>' +
                '<div class="text-center font-medium">จำนวนที่นั้ง</div>' +
                '<input class="mb-3 w-full rounded-lg border border-gray-300 bg-white p-2 text-center focus:ring-2 focus:ring-blue-500" type="number" name="time[' + index + '][max]" placeholder="กรณีไม่จำกัดให้ใส่ 0" required>' +
                '</div>' +
                '</div>');
            index++;
        }

        async function dateAdd() {
            dateStart = $('#date_start').val()
            dateEnd = $('#date_end').val()
            if (dateStart != '' && dateEnd != '') {
                await axios.post('{{ route("nurse.admin.project.dateBetween") }}', {
                    'start': dateStart,
                    'end': dateEnd,
                }).then((res) => {
                    if (res.data.status == 'success') {
                        dateArray = res.data.dates
                        dateArray.forEach(date => {
                            html = '<div class="flex flex-row flex-wrap gap-3 rounded-lg border border-gray-200 bg-white p-3 shadow-sm" id="date' + date.date + '">';
                            html += '<input name="date[' + date.date + '][title]" class="flex-1 rounded-lg border border-gray-300 p-3 focus:ring-2 focus:ring-blue-500" type="text" value="' + date.title + '">';
                            html += '<input name="date[' + date.date + '][date]" type="hidden" value="' + date.date + '">';
                            html += '<button class="flex-none w-12 cursor-pointer rounded-lg bg-red-50 p-3 text-red-600 hover:bg-red-100" type="button" onclick="removeElementID(\'#date' + date.date + '\')"><i class="fa-solid fa-xmark"></i></button>';
                            html += '</div>';
                            $('#date_section').append(html);
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            icon: 'error',
                            allowOutsideClick: true,
                            showConfirmButton: true,
                            confirmButtonColor: 'red'
                        })
                    }
                })
            } else {
                Swal.fire({
                    title: 'โปรดเลือกวันที่ต้องการเพิ่ม',
                    icon: 'error',
                    allowOutsideClick: true,
                    showConfirmButton: true,
                    confirmButtonColor: 'red'
                })
            }

        }

        function removeElementID(id) {
            $(id).remove()
        }
    </script>
@endsection
