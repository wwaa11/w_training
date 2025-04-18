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
                    <div class="cursor-pointer rounded bg-blue-200 p-3 text-center">Approve ผู้ลงทะเบียน</div>
                </a>
            </div>
            <div class="flex-col">
                <a class="flex-1" href="{{ env("APP_URL") }}/admin/excel/project/{{ $project->id }}">
                    <div class="cursor-pointer rounded py-3 text-green-600 hover:text-green-800"><i class="fa-solid fa-file-excel"></i> Excel ดาวน์โหลดข้อมูลผู้ลงทะเบียนทั้งหมด หลักสูตร {{ $project->project_name }}</div>
                </a>
            </div>
            <div class="flex-col">
                <a class="flex-1" href="{{ env("APP_URL") }}/admin/dbd/project/{{ $project->id }}">
                    <div class="cursor-pointer rounded py-3 text-green-600 hover:text-green-800"><i class="fa-solid fa-file-excel"></i> Excel แบบฟอร์มกรมพัฒน์ หลักสูตร {{ $project->project_name }}</div>
                </a>
            </div>
            <div class="flex-col">
                <a class="flex-1" href="{{ env("APP_URL") }}/admin/onebook/project/{{ $project->id }}">
                    <div class="cursor-pointer rounded py-3 text-green-600 hover:text-green-800"><i class="fa-solid fa-file-excel"></i> Excel Onebook หลักสูตร {{ $project->project_name }}</div>
                </a>
            </div>
            <div class="text-2xl font-bold">วันที่เปิดลงทะเบียน</div>
            <hr>
            @foreach ($project->slots as $slot)
                <table class="mb-3 table w-full border-collapse">
                    <thead class="bg-gray-200">
                        <th class="border p-3 text-start">
                            <span>{{ $slot->slot_name }}</span>
                            <a href="{{ env("APP_URL") }}/admin/excel/slot/{{ $slot->id }}"><span class="ms-6 text-green-600"><i class="fa-solid fa-file-excel"></i></span></a>
                        </th>
                        <th class="w-36 border p-3">จำนวนลงทะเบียน</th>
                    </thead>
                    <tbody>
                        @foreach ($slot->items as $item)
                            <tr class="bg-white">
                                <td class="border p-3">{{ $item->item_name }} <a class="float-end text-red-600" href="{{ env("APP_URL") }}/admin/pdf/slot/{{ $item->id }}"><i class="fa-regular fa-file-pdf"></i></a></td>
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
