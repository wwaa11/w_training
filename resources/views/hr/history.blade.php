@extends("layouts.hr")
@section("meta")
    <meta http-equiv="Refresh" content="60">
@endsection
@section("content")
    <div class="m-auto w-full p-3 md:w-3/4">
        <div class="mb-6 rounded-lg border border-[#eaf7ab] bg-[#c1dccd] p-3 shadow">
            <div class="flex text-3xl text-[#1a3f34]">
                <div class="flex-1">ประวัติการลงทะเบียน</div>
                <div class="cursor-pointer text-lg" onclick="refreshPage()"><i class="fa-solid fa-arrows-rotate"></i> อัพเดตข้อมูล</div>
            </div>
            <hr class="border-[#eaf7ab] shadow">
            @foreach ($transactions as $transaction)
                <div class="mt-3 flex flex-row rounded border border-[#eaf7ab] bg-[#eeeeee] p-3 shadow">
                    <div class="m-auto w-[30%] text-center">
                        <div class="text-sm">{{ $transaction->item->slot->dateThai }}</div>
                        <div class="text-3xl text-[#008387]">{{ date("d", strtotime($transaction->item->slot->slot_date)) }}</div>
                        <div>{{ $transaction->item->slot->monthThai }}</div>
                    </div>
                    <div class="relative flex-1 border-l-2 border-[#6d6d6d] px-3">
                        <div class="prompt-medium text-2xl text-[#008387]">{{ $transaction->item->slot->project->project_name }}</div>
                        <div class="mt-2"><i class="fa-regular fa-clock w-8 text-[#008387]"></i> {{ $transaction->item->item_name }}</div>
                        @if ($transaction->item->item_note_1_active)
                            <div class="mt-2">
                                <i class="fa-solid fa-map-pin w-8 text-[#008387]"></i></i> {{ $transaction->item->item_note_1_title }} : {{ $transaction->item->item_note_1_value }}
                            </div>
                        @endif
                        @if ($transaction->checkin_datetime !== null)
                            <div class="mt-2 text-green-700">
                                <i class="fa-solid fa-location-dot w-8"></i> CHECK IN {{ date("d/m/Y H:i", strtotime($transaction->checkin_datetime)) }}
                            </div>
                        @endif
                        @if ($transaction->hr_approve_datetime !== null)
                            <div class="mt-2 text-green-700">
                                <i class="fa-solid fa-h w-4"></i><i class="fa-solid fa-r w-4"></i> อนุมัติ {{ date("d/m/Y H:i", strtotime($transaction->hr_approve_datetime)) }}
                            </div>
                        @endif
                        @if ($transaction->scoreData !== null)
                            <div class="mt-3 cursor-pointer rounded-t bg-green-500 p-3 text-white" onclick="openID('#transaction{{ $transaction->id }} ')">
                                <i class="fa-regular fa-file-lines"></i> คะแนนสอบ
                            </div>
                            <div class="hidden rounded-b border border-green-500" id="transaction{{ $transaction->id }}">
                                @if ($transaction->scoreHeader->title_1 !== null)
                                    <div class="m-3">
                                        <div class="">{{ $transaction->scoreHeader->title_1 }}</div>
                                        <div class="text-red-600">{{ $transaction->scoreData->result_1 }}</div>
                                    </div>
                                @endif
                                @if ($transaction->scoreHeader->title_2 !== null)
                                    <div class="m-3">
                                        <div class="">{{ $transaction->scoreHeader->title_2 }}</div>
                                        <div class="text-red-600">{{ $transaction->scoreData->result_2 }}</div>
                                    </div>
                                @endif
                                @if ($transaction->scoreHeader->title_3 !== null)
                                    <div class="m-3">
                                        <div class="">{{ $transaction->scoreHeader->title_3 }}</div>
                                        <div class="text-red-600">{{ $transaction->scoreData->result_3 }}</div>
                                    </div>
                                @endif
                                @if ($transaction->scoreHeader->title_4 !== null)
                                    <div class="m-3">
                                        <div class="">{{ $transaction->scoreHeader->title_4 }}</div>
                                        <div class="text-red-600">{{ $transaction->scoreData->result_4 }}</div>
                                    </div>
                                @endif
                                @if ($transaction->scoreHeader->title_5 !== null)
                                    <div class="m-3">
                                        <div class="">{{ $transaction->scoreHeader->title_5 }}</div>
                                        <div class="text-red-600">{{ $transaction->scoreData->result_5 }}</div>
                                    </div>
                                @endif
                                @if ($transaction->scoreHeader->title_6 !== null)
                                    <div class="m-3">
                                        <div class="">{{ $transaction->scoreHeader->title_6 }}</div>
                                        <div class="text-red-600">{{ $transaction->scoreData->result_6 }}</div>
                                    </div>
                                @endif
                                @if ($transaction->scoreHeader->title_7 !== null)
                                    <div class="m-3">
                                        <div class="">{{ $transaction->scoreHeader->title_7 }}</div>
                                        <div class="text-red-600">{{ $transaction->scoreData->result_7 }}</div>
                                    </div>
                                @endif
                                @if ($transaction->scoreHeader->title_8 !== null)
                                    <div class="m-3">
                                        <div class="">{{ $transaction->scoreHeader->title_8 }}</div>
                                        <div class="text-red-600">{{ $transaction->scoreData->result_8 }}</div>
                                    </div>
                                @endif
                                @if ($transaction->scoreHeader->title_9 !== null)
                                    <div class="m-3">
                                        <div class="">{{ $transaction->scoreHeader->title_9 }}</div>
                                        <div class="text-red-600">{{ $transaction->scoreData->result_9 }}</div>
                                    </div>
                                @endif
                                @if ($transaction->scoreHeader->title_10 !== null)
                                    <div class="m-3">
                                        <div class="">{{ $transaction->scoreHeader->title_10 }}</div>
                                        <div class="text-red-600">{{ $transaction->scoreData->result_10 }}</div>
                                    </div>
                                @endif
                                @if ($transaction->scoreHeader->title_11 !== null)
                                    <div class="m-3">
                                        <div class="">{{ $transaction->scoreHeader->title_11 }}</div>
                                        <div class="text-red-600">{{ $transaction->scoreData->result_11 }}</div>
                                    </div>
                                @endif
                                @if ($transaction->scoreHeader->title_12 !== null)
                                    <div class="m-3">
                                        <div class="">{{ $transaction->scoreHeader->title_12 }}</div>
                                        <div class="text-red-600">{{ $transaction->scoreData->result_12 }}</div>
                                    </div>
                                @endif
                                @if ($transaction->scoreHeader->title_13 !== null)
                                    <div class="m-3">
                                        <div class="">{{ $transaction->scoreHeader->title_13 }}</div>
                                        <div class="text-red-600">{{ $transaction->scoreData->result_13 }}</div>
                                    </div>
                                @endif
                                @if ($transaction->scoreHeader->title_14 !== null)
                                    <div class="m-3">
                                        <div class="">{{ $transaction->scoreHeader->title_14 }}</div>
                                        <div class="text-red-600">{{ $transaction->scoreData->result_14 }}</div>
                                    </div>
                                @endif
                                @if ($transaction->scoreHeader->title_15 !== null)
                                    <div class="m-3">
                                        <div class="">{{ $transaction->scoreHeader->title_15 }}</div>
                                        <div class="text-red-600">{{ $transaction->scoreData->result_15 }}</div>
                                    </div>
                                @endif
                                @if ($transaction->scoreHeader->title_16 !== null)
                                    <div class="m-3">
                                        <div class="">{{ $transaction->scoreHeader->title_16 }}</div>
                                        <div class="text-red-600">{{ $transaction->scoreData->result_16 }}</div>
                                    </div>
                                @endif
                                @if ($transaction->scoreHeader->title_17 !== null)
                                    <div class="m-3">
                                        <div class="">{{ $transaction->scoreHeader->title_17 }}</div>
                                        <div class="text-red-600">{{ $transaction->scoreData->result_17 }}</div>
                                    </div>
                                @endif
                                @if ($transaction->scoreHeader->title_18 !== null)
                                    <div class="m-3">
                                        <div class="">{{ $transaction->scoreHeader->title_18 }}</div>
                                        <div class="text-red-600">{{ $transaction->scoreData->result_18 }}</div>
                                    </div>
                                @endif
                                @if ($transaction->scoreHeader->title_19 !== null)
                                    <div class="m-3">
                                        <div class="">{{ $transaction->scoreHeader->title_19 }}</div>
                                        <div class="text-red-600">{{ $transaction->scoreData->result_19 }}</div>
                                    </div>
                                @endif
                                @if ($transaction->scoreHeader->title_20 !== null)
                                    <div class="m-3">
                                        <div class="">{{ $transaction->scoreHeader->title_20 }}</div>
                                        <div class="text-red-600">{{ $transaction->scoreData->result_20 }}</div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@section("scripts")
    <script>
        function openID(id) {
            $(id).toggle();
        }
    </script>
@endsection
