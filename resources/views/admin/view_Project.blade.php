@extends("layout")
@section("content")
    <div class="m-auto flex">
        <div class="m-auto mt-3 w-full rounded p-3 md:w-3/4">
            <div class="text-2xl font-bold"><a class="text-blue-600" href="{{ env("APP_URL") }}/admin">Admin Management</a> / {{ $project->project_name }}</div>
            <hr>
            <div class="flex gap-3 p-3">
                <a class="flex-1" href="{{ env("APP_URL") }}/admin/excel/{{ $project->id }}">
                    <div class="cursor-pointer rounded bg-blue-200 p-3 text-center">รายชื่อผู้ลงทะเบียนทั้งหมด</div>
                </a>
                <a class="flex-1" href="{{ env("APP_URL") }}/admin/checkin/{{ $project->id }}">
                    <div class="cursor-pointer rounded bg-blue-200 p-3 text-center">Check In ผู้ลงชื่อเข้าทดสอบ</div>
                </a>
            </div>
            <div class="text-2xl font-bold">วันที่เปิดลงทะเบียน</div>
            <hr>
            @foreach ($project->slots as $slot)
                <table class="mb-3 table w-full border-collapse">
                    <thead class="bg-gray-200">
                        <th class="border p-3">{{ $slot->slot_name }}</th>
                        <th class="w-36 border p-3">จำนวนลงทะเบียน</th>
                    </thead>
                    <tbody>
                        @foreach ($slot->items as $item)
                            <tr class="bg-white">
                                <td class="border p-3">{{ $item->item_name }} <a class="float-end text-green-600" href="{{ env("APP_URL") }}/admin/exceldate/{{ $item->id }}"><i class="fa-solid fa-file-excel"></i> excel</a></td>
                                <td class="border p-3 text-center">{{ count($item->transactions) }} / {{ $item->item_available + count($item->transactions) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        </div>
    </div>
@endsection
@section("scripts")
@endsection
