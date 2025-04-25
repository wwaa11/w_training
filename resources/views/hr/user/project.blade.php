@extends("layouts.hr")
@section("meta")
    <meta http-equiv="Refresh" content="60">
@endsection
@section("content")
    <div class="m-auto w-full p-3 md:w-3/4">
        <div class="mb-6 rounded-lg border border-[#eaf7ab] bg-[#c1dccd] p-3 shadow">
            <div class="flex text-3xl text-[#1a3f34]">
                <div class="flex-1"><a class="text-blue-600" href="{{ env("APP_URL") }}/">รายการลงทะเบียน</a> / {{ $project->project_name }}</div>
                <div class="cursor-pointer text-lg" onclick="refreshPage()"><i class="fa-solid fa-arrows-rotate"></i> อัพเดตข้อมูล</div>
            </div>
            <hr class="border-[#eaf7ab] shadow">
            <div class="text-sm text-red-600">ต้องการเปลี่ยนวันที่ลงทะเบียน กรุณายกเลิกวันลงทะเบียนเดิมก่อน</div>
            @if ($isRegister)
                <x-hr-transaction-item :transaction="$transaction" />
            @endif
        </div>
        <div class="mb-6 rounded-lg border border-[#eaf7ab] bg-[#c1dccd] p-3 shadow">
            <div class="rounded p-3 text-3xl text-[#1a3f34]">รอบการลงทะเบียนทั้งหมด</div>
            <hr class="border-[#eaf7ab] shadow">
            <div class="flex flex-col">
                @foreach ($project->slots as $slot)
                    @if ($slot->slot_date >= date("Y-m-d"))
                        @if ($slot->slot_date == date("Y-m-d") && auth()->user()->admin)
                            <div class="mt-3 flex flex-row rounded border border-[#eaf7ab] bg-[#eeeeee] p-3 font-bold shadow" onclick="openID('#date_{{ $slot->id }}')">
                                <div class="flex-1 p-3 text-xl">{{ $slot->slot_name }}</div>
                                <div class="p-3 text-xl font-bold"><i class="fa-solid fa-angle-down"></i></div>
                            </div>
                            <div class="hidden flex-col gap-6 bg-white" id="date_{{ $slot->id }}">
                                @foreach ($slot->items as $item)
                                    <div class="flex rounded p-3">
                                        <div class="flex-1 p-3">{{ $item->item_name }}</div>
                                        @if ($isRegister)
                                            @if ($item->item_available > 0)
                                                <div class="flex cursor-pointer rounded bg-gray-400 p-3 text-white">มีการลงทะเบียนแล้ว</div>
                                            @else
                                                <div class="flex cursor-pointer rounded bg-red-600 p-3 text-white">เต็มแล้ว</div>
                                            @endif
                                        @elseif($item->item_available > 0)
                                            <div class="flex cursor-pointer rounded bg-[#c1dccd] p-3" onclick="register('{{ $project->id }}','{{ $project->project_name }}','{{ $item->id }}','{{ $slot->slot_name }}','{{ $item->item_name }}')">ลงทะเบียน</div>
                                        @else
                                            <div class="flex cursor-pointer rounded bg-red-600 p-3 text-white">ปิดการลงทะเบียน</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @elseif($slot->slot_date !== date("Y-m-d"))
                            <div class="mt-3 flex flex-row rounded border border-[#eaf7ab] bg-[#eeeeee] p-3 font-bold shadow" onclick="openID('#date_{{ $slot->id }}')">
                                <div class="flex-1 p-3 text-xl">{{ $slot->slot_name }}</div>
                                <div class="p-3 text-xl font-bold"><i class="fa-solid fa-angle-down"></i></div>
                            </div>
                            <div class="hidden flex-col gap-6 bg-white" id="date_{{ $slot->id }}">
                                @foreach ($slot->items as $item)
                                    <div class="flex rounded p-3">
                                        <div class="flex-1 p-3">{{ $item->item_name }}</div>
                                        @if ($isRegister)
                                            @if ($item->item_available > 0)
                                                <div class="flex cursor-pointer rounded bg-gray-400 p-3 text-white">มีการลงทะเบียนแล้ว</div>
                                            @else
                                                <div class="flex cursor-pointer rounded bg-red-600 p-3 text-white">เต็มแล้ว</div>
                                            @endif
                                        @elseif($item->item_available > 0)
                                            <div class="flex cursor-pointer rounded bg-[#c1dccd] p-3" onclick="register('{{ $project->id }}','{{ $project->project_name }}','{{ $item->id }}','{{ $slot->slot_name }}','{{ $item->item_name }}')">ลงทะเบียน</div>
                                        @else
                                            <div class="flex cursor-pointer rounded bg-red-600 p-3 text-white">ปิดการลงทะเบียน</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection
