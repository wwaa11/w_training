@extends("layout")
@section("content")
    <div class="m-auto w-full md:w-3/4">
        <div class="m-3">
            <form id="form" action="{{ env("APP_URL") }}/admin/createProject" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-3 md:grid-cols-1">
                    <div class="flex flex-col rounded bg-white p-3" id="Project_Detail">
                        @if (session("message"))
                            <div class="mx-3 rounded bg-red-600 p-3 text-center text-white">
                                {{ session("message") }}
                            </div>
                        @endif
                        <div class="p-3 text-2xl font-bold">สร้างโครงการใหม่</div>
                        <hr>
                        <div class="p-3 font-bold">ชื่อโครงการ<span class="text-red-600">*</span></div>
                        <input class="mx-3 rounded border border-gray-500 p-3" name="project_name" type="text">
                        <div class="p-3 font-bold">รายละเอียดโครงการ</div>
                        <textarea class="mx-3 mb-3 rounded border border-gray-500 p-3" id="" name="project_detail" rows="5"></textarea>
                        <div class="mx-3 mb-3 rounded bg-gray-200 p-3 text-lg font-bold">รายละเอียดรอบ</div>
                        <input class="mx-3 mb-3 rounded border border-gray-500 p-3" id="item_name" placeholder="ชื่อรอบ" type="text">
                        <input class="mx-3 mb-3 rounded border border-gray-500 p-3" id="item_detail" placeholder="รายละเอียด" type="text">
                        <div class="mx-3 mb-2 flex flex-row flex-wrap gap-3 bg-gray-100 px-3">
                            <input class="p-3 accent-red-500" id="item_note_1_box" type="checkbox">
                            <label class="p-3" for="item_note_1_box">แสดงหัวข้อเพิ่มเติม 1</label>
                            <input class="flex-1 rounded border border-gray-500 p-3" id="item_note_1_title" name="item[item_note_1_title]" placeholder="ชื่อหัวข้อ" type="text">
                            <input class="flex-1 rounded border border-gray-500 p-3" id="item_note_1_value" placeholder="ค่าเริ่มต้น" type="text">
                        </div>
                        <div class="mx-3 mb-2 flex flex-row flex-wrap gap-3 bg-gray-100 px-3">
                            <input class="p-3 accent-red-500" id="item_note_2_box" type="checkbox">
                            <label class="p-3" for="item_note_2_box">แสดงหัวข้อเพิ่มเติม 2</label>
                            <input class="flex-1 rounded border border-gray-500 p-3" id="item_note_2_title" name="item[item_note_2_title]" placeholder="ชื่อหัวข้อ" type="text">
                            <input class="flex-1 rounded border border-gray-500 p-3" id="item_note_2_value" placeholder="ค่าเริ่มต้น" type="text">
                        </div>
                        <div class="mx-3 mb-3 flex flex-row flex-wrap gap-3 bg-gray-100 px-3">
                            <input class="p-3 accent-red-500" id="item_note_3_box" type="checkbox">
                            <label class="p-3" for="item_note_3_box">แสดงหัวข้อเพิ่มเติม 3</label>
                            <input class="flex-1 rounded border border-gray-500 p-3" id="item_note_3_title" name="item[item_note_3_title]" placeholder="ชื่อหัวข้อ" type="text">
                            <input class="flex-1 rounded border border-gray-500 p-3" id="item_note_3_value" placeholder="ค่าเริ่มต้น" type="text">
                        </div>
                        <div class="mx-3 mb-3 flex flex-row flex-wrap gap-3">
                            <input class="flex-1 rounded border border-gray-500 p-3" id="item_seat" placeholder="จำนวนคนต่อรอบ" type="number">
                            <button class="flex-1 cursor-pointer rounded bg-green-400 p-3" type="button" onclick="addTime()">
                                <i class="fa-solid fa-plus"></i>เพิ่มรอบ
                            </button>
                        </div>
                        <div class="mx-3 mb-3 rounded bg-gray-200 p-3 text-lg font-bold">จำนวนรอบในแต่ละวัน</div>
                        <div class="mx-3">
                            <table class="table w-full table-auto">
                                <thead class="border-collapse border">
                                    <th class="border p-3">ชื่อรอบ</th>
                                    <th class="border p-3">รายละเอียด</th>
                                    <th class="hidden border p-3" id="note_1"></th>
                                    <th class="hidden border p-3" id="note_2"></th>
                                    <th class="hidden border p-3" id="note_3"></th>
                                    <th class="border p-3">จำนวน</th>
                                    <th class="border p-3">#</th>
                                </thead>
                                <tbody id="item_section"></tbody>
                            </table>
                        </div>
                        <div class="mx-3 mb-3 rounded bg-gray-200 p-3 text-lg font-bold">ระยะเวลาของโครงการ</div>
                        <div class="flex flex-col flex-wrap gap-3 p-3 md:flex-row">
                            <input class="flex-1 rounded border border-gray-500 p-3" id="date_start" value="{{ date("Y-m-d") }}" onchange="dateChange()" type="date">
                            <input class="flex-1 rounded border border-gray-500 p-3" id="date_end" value="{{ date("Y-m-d") }}" type="date" onchange="dateChange()">
                            <button class="flex-1 cursor-pointer rounded bg-green-400 p-3" type="button" onclick="dateAdd()"><i class="fa-solid fa-plus"></i>เพิ่มวันที่</button>
                        </div>
                        <div class="mx-3 rounded bg-gray-200 p-3 text-lg font-bold">จำนวนวันทั้งหมด</div>
                        <div id="date_section"></div>
                        <button class="mt-3 w-full flex-1 rounded border-2 border-blue-600 p-3 text-blue-600" type="button" onclick="submitFn(event)">บันทึก</button>
                    </div>
                </div>
                <div class="flex-1 p-3">
                </div>
            </form>
        </div>
    </div>
@endsection
@section("scripts")
    <script>
        itemIndex = 0;

        function dateChange() {
            dateStart = $('#date_start').val()
            dateEnd = $('#date_end').val()
            if (dateStart != '' && dateEnd == '') {
                $('#date_end').val(dateStart)
            }
            if (dateStart == '' && dateEnd != '') {
                $('#date_start').val(dateEnd)
            }
        }

        function addTime() {

            item_name = $('#item_name').val()
            item_avabile = $('#item_seat').val()

            if (item_name == '') {
                Swal.fire({
                    title: 'กรุณาใส่ชื่อรอบ',
                    icon: 'error',
                    allowOutsideClick: true,
                    showConfirmButton: true,
                    confirmButtonColor: 'red'
                })

                return
            }

            itemIndex += 1;

            note_1_check = $('#item_note_1_box').is(':checked')
            note_2_check = $('#item_note_2_box').is(':checked')
            note_3_check = $('#item_note_3_box').is(':checked')

            if (note_1_check) {
                $('#note_1').removeClass('hidden')
                $('#note_1').html($('#item_note_1_title').val())
            } else {
                $('#note_1').addClass('hidden')
            }
            if (note_2_check) {
                $('#note_2').removeClass('hidden')
                $('#note_2').html($('#item_note_2_title').val())
            } else {
                $('#note_2').addClass('hidden')
            }
            if (note_3_check) {
                $('#note_3').removeClass('hidden')
                $('#note_3').html($('#item_note_3_title').val())
            } else {
                $('#note_3').addClass('hidden')
            }

            item_detail = $('#item_detail').val()
            item_value_1 = $('#item_note_1_value').val()
            item_value_2 = $('#item_note_2_value').val()
            item_value_3 = $('#item_note_3_value').val()

            html = '<tr id="itemList' + itemIndex + '">';
            html += '<td class="border p-3"><input class="w-full bg-gray-200 p-2" name="item[list][' + itemIndex + '][name]" value="' + item_name + '" type="text"></td>';
            html += '<td class="border p-3"><input class="w-full bg-gray-200 p-2" name="item[list][' + itemIndex + '][detail]" value="' + item_detail + '" type="text"></td>';
            if (note_1_check) {
                html += '<td class="border p-3"><input class="w-full bg-gray-200 p-2" name="item[list][' + itemIndex + '][note_1_value]" value="' + item_value_1 + '" type="text"></td>';
            }
            if (note_2_check) {
                html += '<td class="border p-3"><input class="w-full bg-gray-200 p-2" name="item[list][' + itemIndex + '][note_2_value]" value="' + item_value_2 + '" type="text"></td>';
            }
            if (note_3_check) {
                html += '<td class="border p-3"><input class="w-full bg-gray-200 p-2" name="item[list][' + itemIndex + '][note_3_value]" value="' + item_value_3 + '" type="text"></td>';
            }
            html += '<td class="border p-3"><input class="w-full bg-gray-200 p-2" name="item[list][' + itemIndex + '][avabile]" value="' + item_avabile + '" type="text"></td>';
            html += '<td class="border p-3 text-center"><button class="w-12 flex-none cursor-pointer rounded bg-red-400 p-2" type="button" onclick="removeElementID(\'#itemList' + itemIndex + '\')"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button></td>';
            html += '</tr>';

            $('#item_section').append(html)
        }

        async function dateAdd() {
            dateStart = $('#date_start').val()
            dateEnd = $('#date_end').val()
            seat = $('#date_seat').val()
            if (dateStart != '' && dateEnd != '') {
                await axios.post('{{ env("APP_URL") }}/admin/addDate', {
                    'start': dateStart,
                    'end': dateEnd,
                }).then((res) => {
                    if (res.data.status == 'success') {
                        dateArray = res.data.dates
                        dateArray.forEach(date => {
                            html = '<div class="flex gap-3 p-3 flex-row flex-wrap" id="date' + date.date + '">';
                            html += '<input name="slot[' + date.date + '][title]" class="flex-1 rounded border border-gray-500 p-3" type="text" value="' + date.title + '">';
                            html += '<input name="slot[' + date.date + '][date]" type="hidden" value="' + date.date + '">';
                            html += '<button class="flex-none w-12 cursor-pointer rounded bg-red-400 p-3" type="button" onclick="removeElementID(\'#date' + date.date + '\')"><i class="fa-solid fa-xmark"></i></button>';
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

        function submitFn(event) {
            event.preventDefault();

            $('#form').submit()
        }
    </script>
@endsection
