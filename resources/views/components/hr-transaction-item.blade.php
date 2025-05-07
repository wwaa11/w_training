<div class="mt-3 flex flex-row rounded border border-[#eaf7ab] bg-[#eeeeee] p-3 shadow">
    <div class="m-auto w-[30%] text-center">
        <div class="text-sm">{{ $transaction->item->slot->dateThai }}</div>
        <div class="text-3xl text-[#008387]">{{ date("d", strtotime($transaction->item->slot->slot_date)) }}</div>
        <div>{{ $transaction->item->slot->monthThai }}</div>
    </div>
    <div class="relative flex-1 border-l-2 border-[#6d6d6d] px-3">
        <div class="prompt-medium text-2xl text-[#008387]">{{ $transaction->item->slot->project->project_name }}</div>
        <div class="mt-2"><i class="fa-regular fa-clock w-8 text-[#008387]"></i> {{ $transaction->item->item_name }}</div>
        @if ($transaction->seat !== null)
            <div class="mt-2 flex rounded bg-red-500 p-3 text-white lg:hidden">
                <div class="pt-2">เลขที่นั่งสอบ</div>
                <div class="flex-1 text-end text-3xl">{{ $transaction->seat }}</div>
            </div>
            <div class="right-0 top-0 hidden lg:absolute lg:block">
                <div class="text-center text-5xl text-red-600">{{ $transaction->seat }}</div>
                <div class="text-sm">เลขที่นั่งสอบ</div>
            </div>
        @endif
        <div class="mt-2"><i class="fa-solid fa-map-pin w-8 text-[#008387]"></i> {{ $transaction->item->item_note_1_title }} : {{ $transaction->item->item_note_1_value }}</div>
        @if (!$transaction->checkin)
            @if ($transaction->item->slot->slot_date !== date("Y-m-d"))
                @if (date("Y-m-d H:i") <= date("Y-m-d 17:00") && $transaction->item->slot->slot_date == date("Y-m-d", strtotime(date("Y-m-d") . " +1 day")))
                    <span class="absolute bottom-0 right-0 cursor-pointer text-red-600" onclick="deleteTransaction('{{ $transaction->item->slot->project->id }}','{{ $transaction->item->slot->project->project_name }}')"><i class="fa-solid fa-trash"></i></span>
                @elseif($transaction->item->slot->slot_date > date("Y-m-d", strtotime(date("Y-m-d") . " +1 day")))
                    <span class="absolute bottom-0 right-0 cursor-pointer text-red-600" onclick="deleteTransaction('{{ $transaction->item->slot->project->id }}','{{ $transaction->item->slot->project->project_name }}')"><i class="fa-solid fa-trash"></i></span>
                @endif
            @endif
        @endif
        @if (date("Y-m-d") == $transaction->item->slot->slot_date)
            @if (!$transaction->checkin)
                <button class="mt-3 cursor-pointer rounded border border-[#eaf7ab] bg-red-500 p-3 text-white" onclick="sign('{{ $transaction->id }}','{{ $transaction->item->slot->project->project_name }}')">
                    <i class="fa-solid fa-location-dot w-8"></i> CHECK IN
                </button>
            @else
                <div class="mt-3 text-green-700"><i class="fa-solid fa-location-dot w-8"></i> CHECK IN {{ date("H:i", strtotime($transaction->checkin_datetime)) }}</div>
                @if ($transaction->hr_approve)
                    <div class="mt-3 text-green-700">
                        <i class="fa-solid fa-h w-4"></i><i class="fa-solid fa-r w-4"></i> อนุมัติ {{ date("H:i", strtotime($transaction->hr_approve_datetime)) }}
                    </div>
                @endif
                @if ($transaction->item->slot->project->link !== null)
                    <div class="mt-3 cursor-pointer rounded-t bg-red-500 p-3 text-white">
                        <i class="fa-regular fa-file-lines"></i> ข้อสอบ {{ date("H:i", strtotime($transaction->item->link_start)) }} - {{ date("H:i", strtotime($transaction->item->link_end)) }}
                    </div>
                    <div class="rounded-b border border-red-500">
                        @if ($transaction->hr_approve)
                            @if (!$transaction->item->link_time)
                                @foreach ($transaction->item->slot->project->link->links as $link)
                                    <a href="{{ $link["url"] }}" target="_blank">
                                        <div class="m-3 rounded bg-green-400 p-3">{{ $link["title"] }}</div>
                                    </a>
                                @endforeach
                            @elseif(date("Y-m-d H:i") >= date("Y-m-d H:i", strtotime($transaction->item->link_start)) && date("Y-m-d H:i") <= date("Y-m-d H:i", strtotime($transaction->item->link_end)))
                                @foreach ($transaction->item->slot->project->link->links as $link)
                                    <a href="{{ $link["url"] }}" target="_blank">
                                        <div class="m-3 rounded bg-green-400 p-3">{{ $link["title"] }}</div>
                                    </a>
                                @endforeach
                            @else
                                <div class="m-3 rounded bg-red-600 p-3 text-white">ไม่สามารถใช้งานได้</div>
                            @endif
                        @else
                            <div class="m-3 rounded bg-red-600 p-3 text-white">รอการอนุมัตอจาก HR</div>
                        @endif
                    </div>
                @endif
            @endif
        @endif
    </div>
</div>
