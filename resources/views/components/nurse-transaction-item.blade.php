<div class="mt-3 flex flex-row rounded-lg border border-gray-200 bg-white p-4 shadow-sm transition-shadow hover:shadow-md">
    <div class="m-auto w-[30%] text-center">
        <div class="text-xs text-gray-500">{{ $transaction->timeData->dateData->dateThai }}</div>
        <div class="text-3xl font-bold text-[#008387]">{{ date("d", strtotime($transaction->timeData->dateData->date)) }}</div>
        <div class="text-sm text-gray-600">{{ $transaction->timeData->dateData->monthThai }}</div>
    </div>
    <div class="relative flex-1 border-l border-gray-200 pl-4">
        <div class="text-lg font-semibold text-gray-900">{{ $transaction->projectData->title }}</div>
        <div class="mt-1 text-sm text-gray-700"><i class="fa-regular fa-clock w-5 text-[#008387]"></i> {{ $transaction->timedata->title }}</div>
        <div class="mt-1 text-sm text-gray-700"><i class="fa-solid fa-map-pin w-5 text-[#008387]"></i> {{ $transaction->projectData->location }}</div>
        @if ($transaction->user_sign == null)
            @if (date("Y-m-d") !== date("Y-m-d", strtotime($transaction->date_time)) || $transaction->projectData->multiple)
                <button class="absolute right-0 top-0 text-red-600 hover:text-red-700" type="button" onclick="deleteTransaction('{{ $transaction->projectData->id }}','{{ $transaction->projectData->title }}')">
                    <i class="fa-solid fa-trash"></i>
                </button>
            @endif
            @if (date("Y-m-d") == date("Y-m-d", strtotime($transaction->date_time)))
                <div class="mt-3 cursor-pointer rounded-md bg-red-600 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-red-700" onclick="sign('{{ $transaction->id }}','{{ $transaction->projectData->title }}')">
                    <i class="fa-solid fa-location-dot mr-2"></i> CHECK IN
                </div>
            @endif
        @else
            <hr class="my-2 border-t border-gray-200">
            <div class="inline-flex cursor-pointer items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-600 hover:text-green-800">
                <i class="fa-solid fa-location-dot mr-2"></i> CHECK IN {{ date("H:i", strtotime($transaction->user_sign)) }}
            </div>
            @if ($transaction->admin_sign !== null)
                <div class="inline-flex cursor-pointer items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-600 hover:text-blue-800">
                    <i class="fa-solid fa-user-nurse mr-2"></i> อนุมัติ {{ date("H:i", strtotime($transaction->admin_sign)) }}
                </div>
            @endif
        @endif
    </div>
</div>
