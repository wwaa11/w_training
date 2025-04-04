@extends("layout")
@section("content")
    @foreach ($project->slots as $slot)
        @foreach ($slot->items as $item)
            @foreach ($item->transactions as $index => $transaction)
                <div class="m-auto w-1/2" style="size: A4">
                    <table class="table w-full">
                        <thead>
                            <th class="border p-2 text-end" colspan="3"><img class="float-end aspect-auto h-16" src="{{ url("images/Side Logo.png") }}" alt=""></th>
                            <th class="border p-2" colspan="3">ใบลงทะเบียน</th>
                        </thead>
                        <thead>
                            <th class="border p-2">วันที่</th>
                            <th class="border p-2" colspan="2">{{ $slot->slot_name }}</th>
                            <th class="border p-2">เวลา</th>
                            <th class="border p-2" colspan="2">{{ $item->item_name }}</th>
                        </thead>
                        <thead>
                            <th class="border p-2">เรื่อง</th>
                            <th class="border p-2" colspan="5">{{ $project->project_name }}</th>
                        </thead>
                        <thead>
                            <th class="border p-2">ลำดับ</th>
                            <th class="border p-2">รหัสพนักงาน</th>
                            <th class="border p-2">ชื่อ - สกุล</th>
                            <th class="border p-2">ตำแหน่ง</th>
                            <th class="border p-2">แผนก</th>
                            <th class="border p-2">ลายเช็น</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border p-2 text-center">{{ $index + 1 }}</td>
                                <td class="border p-2">{{ $transaction->user }}</td>
                                <td class="border p-2">{{ $transaction->userData->name }}</td>
                                <td class="border p-2">{{ $transaction->userData->position }}</td>
                                <td class="border p-2">{{ $transaction->userData->department }}</td>
                                <td class="w-32 border p-2"></td>
                            </tr>
                            <tr>
                                <td class="border p-2 text-end" colspan="5">รวม</td>
                                <td class="border p-2 text-center">{{ count($item->transactions) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach
        @endforeach
    @endforeach
@endsection
@section("scripts")
@endsection
