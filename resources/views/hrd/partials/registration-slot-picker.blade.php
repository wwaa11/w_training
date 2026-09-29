@php
    $onlyAvailable = $onlyAvailable ?? false;
@endphp

@if ($registrationTimeSlots->isEmpty())
    <div class="rounded-md border border-dashed border-slate-200 bg-blue-50/40 px-4 py-8 text-center text-sm text-slate-600">
        <i class="fas fa-calendar-times mb-2 text-2xl text-blue-400"></i>
        <p>ไม่มีเซสชันที่เปิดให้ลงทะเบียนในขณะนี้</p>
    </div>
@else
    <div class="space-y-4">
        @foreach ($registrationTimeSlots as $dateSlot)
            @php
                $slotsToShow = collect($dateSlot["slots"]);
                if ($onlyAvailable) {
                    $slotsToShow = $slotsToShow->filter(fn ($s) => ! $s["userRegistered"]);
                }
            @endphp
            @if ($slotsToShow->isEmpty())
                @continue
            @endif
            <details class="hrd-reg-date group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" @if ($loop->first) open @endif>
                <summary class="flex cursor-pointer list-none items-center justify-between gap-2 border-b border-slate-100 bg-blue-50/50 px-4 py-3 marker:content-none [&::-webkit-details-marker]:hidden">
                    <div class="min-w-0 text-left">
                        <h3 class="font-semibold text-slate-900">{{ $dateSlot["date"]->date_title }}</h3>
                        <p class="text-xs text-slate-600">{{ $dateSlot["date"]->date_datetime->translatedFormat("l j M Y") }} · {{ $slotsToShow->count() }} ช่วงเวลา</p>
                    </div>
                    <div class="flex shrink-0 items-center gap-2">
                        @if ($dateSlot["dateIsToday"])
                            <span class="rounded bg-white px-2 py-0.5 text-xs font-medium text-blue-800 ring-1 ring-slate-200">วันนี้</span>
                        @endif
                        <i class="fas fa-chevron-down text-xs text-slate-400 transition group-open:rotate-180"></i>
                    </div>
                </summary>
                <div class="space-y-2 p-3 sm:p-4">
                    @if (! $onlyAvailable && $dateSlot["date"]->date_detail)
                        <p class="rounded-md bg-slate-50 px-3 py-2 text-sm text-slate-600">{{ $dateSlot["date"]->date_detail }}</p>
                    @endif
                    @if (! $onlyAvailable && $dateSlot["date"]->date_location)
                        <p class="text-xs text-slate-500"><i class="fas fa-map-marker-alt mr-1 text-blue-600"></i>{{ $dateSlot["date"]->date_location }}</p>
                    @endif
                    @foreach ($slotsToShow as $slot)
                        @include("hrd.partials.registration-slot-card", [
                            "slot" => $slot,
                            "project" => $project,
                            "registrationUserGroup" => $registrationUserGroup,
                        ])
                    @endforeach
                </div>
            </details>
        @endforeach
    </div>
@endif
