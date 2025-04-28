@extends("layouts.nurse")
@section("content")
    <div class="m-auto w-full p-3 md:w-3/4">
        <form class="mb-6 rounded-lg p-3 shadow" action="{{ route("NurseAdminStore") }}" method="POST">
            @csrf
            @if ($errors->any())
                <div class="text-red-600">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="flex flex-row gap-3">
                <label class="flex-1 pt-2 text-2xl">สร้างโครงการฝึกอบรมใหม่</label>
                <button class="w-48 cursor-pointer rounded bg-green-400 p-3" type="submit">บันทึก</button>
            </div>
            <hr class="my-3" />
            <label class="">ชื่อโครงการฝึกอบรม</label>
            <input class="mb-3 w-full rounded border border-gray-400 p-2" type="text" name="title" placeholder="ชื่อโครงการฝึกอบรม" value="{{ old("title") }}" required>
            <label class="">สถานที่จัดโครงการ</label>
            <input class="mb-3 w-full rounded border border-gray-400 p-2" type="text" name="location" placeholder="สถานที่จัดโครงการ" value="{{ old("location") }}" required>
            <div class="flex flex-row gap-3">
                <div class="w-1/2">
                    <label class="">วันที่เริ่มการลงทะเบียน</label>
                    <input class="mb-3 w-full rounded border border-gray-400 p-2" type="date" name="register_start" value="{{ old("register_start", date("Y-m-d")) }}" placeholder="วันที่เริ่ม" required>
                </div>
                <div class="w-1/2">
                    <label class="">วันที่สิ้นสุดการลงทะเบียน</label>
                    <input class="mb-3 w-full rounded border border-gray-400 p-2" type="date" name="register_end" value="{{ old("register_end") }}" placeholder="วันที่สิ้นสุด" required>
                </div>
            </div>
            <label class="">รายละเอียดการฝึกอบรม</label>
            <textarea class="mb-3 w-full rounded border border-gray-400 p-2" name="detail" cols="30" rows="5">{{ old("detail") }}</textarea>
            <div class="mb-3 bg-gray-100 p-3">
                <label class="text-2xl">วันที่เปิดการฝึกอบรม</label>
                <hr class="my-3" />
                <div class="flex flex-row gap-3">
                    <div class="w-1/2">
                        <label class="">วันที่เปิดลงทะเบียน</label>
                        <input class="mb-3 w-full rounded border border-gray-400 bg-white p-2" type="date" name="training_start" value="{{ old("training_start") }}" placeholder="วันที่เริ่ม" required>
                    </div>
                    <div class="w-1/2">
                        <label class="">&nbsp;</label>
                        <input class="mb-3 w-full rounded border border-gray-400 bg-white p-2" type="date" name="training_end" value="{{ old("training_end") }}" placeholder="วันที่สิ้นสุด" required>
                    </div>
                </div>
            </div>
            <div class="mb-3 bg-gray-100 p-3">
                <div class="flex flex-row gap-3">
                    <label class="flex-1 pt-2 text-2xl">รอบการลงทะเบียน <span class="text-sm text-red-600">*รอบการลงทะเบียนจะเหมือนกันในทุกๆวันที่ทำการเปิดอบรม</span></label>
                    <button class="cursor-pointer rounded bg-blue-500 p-2 text-white" type="button" onclick="addTime()"><i class="fa fa-plus"></i> เพิ่มรอบ การลงทะเบีบน</button>
                </div>
                <hr class="my-3" />
                <div id="time_section"></div>
            </div>
        </form>
    </div>
@endsection
@section("scripts")
    <script>
        var index = 1;

        function addTime() {
            $('#time_section').append('<div class="mt-3 flex flex-row gap-3 rounded p-3" id="time_' + index + '">' +
                '<div class="flex-none cursor-pointer text-red-600" onclick="$(#time_' + index + ').remove()">' +
                '<div>&nbsp;</div>' +
                '<i class="fa fa-x"></i>' +
                '</div>' +
                '<div class="flex-1">' +
                '<div class="">ชื่อรอบการลงทะเบียน</div>' +
                '<input class="mb-3 w-full rounded border border-gray-400 bg-white p-2" type="text" name="time[' + index + '][title]" placeholder="ชื่อรอบการลงทะเบียน" value="08:00 - 17:00" required>' +
                '</div>' +
                '<div>' +
                '<div class="text-center">เริ่ม</div>' +
                '<input class="mb-3 w-full rounded border border-gray-400 bg-white p-2 text-center" type="time" name="time[' + index + '][start]" placeholder="เวลาเปิดลงทะเบียน" value="08:00" required>' +
                '</div>' +
                '<div>' +
                '<div class="text-center">สิ้นสุด</div>' +
                '<input class="mb-3 w-full rounded border border-gray-400 bg-white p-2 text-center" type="time" name="time[' + index + '][end]" placeholder="เวลาเปิดลงทะเบียน" value="17:00" required>' +
                '</div>' +
                '</div>');
        }
    </script>
@endsection
