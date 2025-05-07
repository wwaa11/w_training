@extends("layouts.nurse")
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
            <div class="my-3 bg-[#eeeeee] p-3 text-3xl">
                คะแนนของฉัน : <span class="text-red-600">{{ $myscore }}</span>
            </div>
            @foreach ($lectures as $lecture)
                <div class="mt-3 flex flex-row rounded border border-[#eaf7ab] bg-[#eeeeee] p-3 shadow">
                    <div class="m-auto w-[30%] text-center">
                        <div class="text-sm">{{ $lecture->dateData->dateThai }}</div>
                        <div class="text-3xl text-[#008387]">{{ date("d", strtotime($lecture->dateData->date)) }}</div>
                        <div>{{ $lecture->dateData->monthThai }}</div>
                    </div>
                    <div class="relative flex-1 border-l-2 border-[#6d6d6d] px-3">
                        <div class="prompt-medium text-2xl text-[#008387]">วิทยากร</div>
                        <div class="prompt-medium text-2xl text-[#008387]">{{ $lecture->dateData->projectData->title }}</div>
                        <div class="absolute bottom-0 right-3 top-0 text-4xl text-red-400">
                            5
                        </div>
                    </div>
                </div>
            @endforeach
            @foreach ($transactions as $transaction)
                <div class="mt-3 flex flex-row rounded border border-[#eaf7ab] bg-[#eeeeee] p-3 shadow">
                    <div class="m-auto w-[30%] text-center">
                        <div class="text-sm">{{ $transaction->timeData->dateData->dateThai }}</div>
                        <div class="text-3xl text-[#008387]">{{ date("d", strtotime($transaction->timeData->dateData->date)) }}</div>
                        <div>{{ $transaction->timeData->dateData->monthThai }}</div>
                    </div>
                    <div class="relative flex-1 border-l-2 border-[#6d6d6d] px-3">
                        <div class="prompt-medium text-2xl text-[#008387]">{{ $transaction->projectData->title }}</div>
                        <div class="mt-2"><i class="fa-regular fa-clock w-8 text-[#008387]"></i> {{ $transaction->timedata->title }}</div>
                        <div class="mt-2"><i class="fa-solid fa-map-pin w-8 text-[#008387]"></i> {{ $transaction->projectData->location }}</div>
                        @if ($transaction->user_sign !== null)
                            <div class="mt-3 text-green-700">
                                <i class="fa-solid fa-location-dot w-8"></i> CHECK IN {{ date("H:i", strtotime($transaction->user_sign)) }}
                            </div>
                        @endif
                        @if ($transaction->admin_sign !== null)
                            <div class="mt-3 text-green-700">
                                <i class="fa-solid fa-user-nurse w-8"></i> อนุมัติ {{ date("H:i", strtotime($transaction->admin_sign)) }}
                            </div>
                            <div class="absolute bottom-0 right-3 top-0 text-4xl text-red-400">
                                1
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
