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
        @if ($transaction->user_sign == null)
            @if (date("Y-m-d") !== date("Y-m-d", strtotime($transaction->date_time)))
                <span class="absolute right-0 top-0 cursor-pointer text-red-600" onclick="deleteTransaction('{{ $transaction->projectData->id }}','{{ $transaction->projectData->title }}')"><i class="fa-solid fa-trash"></i></span>
            @elseif ($transaction->projectData->multiple)
                <span class="absolute right-0 top-0 cursor-pointer text-red-600" onclick="deleteTransaction('{{ $transaction->projectData->id }}','{{ $transaction->projectData->title }}')"><i class="fa-solid fa-trash"></i></span>
            @endif
            @if (date("Y-m-d") == date("Y-m-d", strtotime($transaction->date_time)))
                <div class="mt-2 cursor-pointer rounded border bg-red-500 py-2 text-center text-white" onclick="sign('{{ $transaction->id }}','{{ $transaction->projectData->title }}')">
                    <i class="fa-solid fa-location-dot w-8"></i> CHECK IN
                </div>
            @endif
        @else
            <div class="mt-3 text-green-700"><i class="fa-solid fa-location-dot w-8"></i> CHECK IN {{ date("H:i", strtotime($transaction->user_sign)) }}</div>
            @if ($transaction->admin_sign !== null)
                <div class="mt-3 text-green-700">
                    <i class="fa-solid fa-user-nurse w-8"></i> อนุมัติ {{ date("H:i", strtotime($transaction->admin_sign)) }}
                </div>
            @endif
        @endif
    </div>
</div>
