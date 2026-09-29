@php
    $isRegistered = (bool) $slot['userRegistered'];
    $isFull = $slot['isFull'] && ! $isRegistered;
    $canSelect = ! $isRegistered && ! $isFull;
    $inputId = 'time_' . $slot['time']->id;
@endphp

<div @class([
    'js-registration-slot rounded-2xl border p-3 transition-colors sm:p-4',
    'border-slate-200 bg-slate-50/80' => $isRegistered,
    'border-slate-200 bg-white opacity-60' => $isFull,
    'border-slate-200 bg-white hover:border-slate-300 hover:bg-blue-50/40' => $canSelect,
])>
    <div class="flex gap-3">
        @if ($canSelect)
            <div class="flex shrink-0 items-start pt-1">
                @if ($project->project_type === 'single')
                    <input class="h-5 w-5 text-blue-600 focus:ring-blue-500" id="{{ $inputId }}" name="time_ids[]" type="radio" value="{{ $slot['time']->id }}" required>
                @else
                    <input class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500" id="{{ $inputId }}" name="time_ids[]" type="checkbox" value="{{ $slot['time']->id }}">
                @endif
            </div>
        @endif

        <div class="min-w-0 flex-1">
            <label @if ($isRegistered) data-slot-registered="true" @endif @class(['block', 'cursor-pointer' => $canSelect, 'cursor-default' => ! $canSelect]) @if ($canSelect) for="{{ $inputId }}" @endif>
                <div class="flex flex-wrap items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-base font-semibold text-slate-900">{{ $slot['timeRange'] }}</p>
                        @if (filled($slot['time']->time_title))
                            <p class="mt-0.5 text-sm text-slate-600">{{ $slot['time']->time_title }}</p>
                        @endif
                    </div>
                    <div class="shrink-0">
                        @if ($isRegistered)
                            <span class="inline-flex items-center rounded-lg bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-900">
                                <i class="fas fa-check mr-1"></i>ลงทะเบียนแล้ว
                            </span>
                        @elseif ($isFull)
                            <span class="inline-flex items-center rounded-lg bg-red-100 px-2 py-1 text-xs font-semibold text-red-800">เต็ม</span>
                        @else
                            <span class="inline-flex items-center rounded-lg bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800">เลือกได้</span>
                        @endif
                    </div>
                </div>

                @if (filled($slot['time']->time_detail))
                    <p class="mt-2 text-xs leading-relaxed text-slate-500">{{ $slot['time']->time_detail }}</p>
                @endif

                @include('hrd.partials.registration-capacity', ['capacity' => $slot['capacity']])

                <p class="mt-2 text-xs text-slate-500">
                    <i class="fas fa-sign-in-alt mr-1 text-blue-600"></i>
                    เช็คอินได้ตั้งแต่ {{ $slot['checkinFrom'] }}
                </p>
            </label>
        </div>
    </div>
</div>
