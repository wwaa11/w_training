@extends("layout")
@section("content")
    <div class="m-auto w-full p-3 md:w-3/4">
        <div class="mb-6 rounded-lg border border-[#eaf7ab] bg-[#c1dccd] p-3 shadow">
            <div class="text-3xl text-[#1a3f34]">ประวัติการลงทะเบียน</div>
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
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@section("scripts")
    <script></script>
@endsection
