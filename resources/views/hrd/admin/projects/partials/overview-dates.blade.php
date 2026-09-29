@php
    $activeDates = $project->dates->where('date_delete', false)->sortBy('date_datetime');
@endphp

<div class="mb-2 flex flex-wrap gap-2">
    <button class="hrd-af-btn-secondary !py-1.5 !text-xs" type="button" onclick="hrdOvExpandAllDates()"><i class="fas fa-expand-alt"></i> ขยายทั้งหมด</button>
    <button class="hrd-af-btn-secondary !py-1.5 !text-xs" type="button" onclick="hrdOvCollapseAllDates()"><i class="fas fa-compress-alt"></i> ย่อทั้งหมด</button>
    <span class="ml-auto inline-flex items-center rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-slate-900">{{ $activeDates->count() }} วัน</span>
</div>

<div class="space-y-2">
    @forelse ($activeDates as $date)
        @php
            $dateTimes = $date->times->where('time_delete', false);
        @endphp
        <details class="hrd-ov-date overflow-hidden rounded-md border border-slate-200 bg-white" @if ($loop->first) open @endif>
            <summary class="flex flex-wrap items-center gap-2 border-b border-slate-100 bg-slate-50/80 px-3 py-2.5">
                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-md bg-blue-600 text-xs font-bold text-white">{{ $loop->iteration }}</span>
                <div class="min-w-0 flex-1 text-left">
                    <p class="truncate text-sm font-semibold text-slate-900">{{ $date->date_title }}</p>
                    <p class="text-xs text-slate-600">
                        <i class="fas fa-calendar-day mr-1 text-blue-600/70"></i>
                        {{ \Carbon\Carbon::parse($date->date_datetime)->format('d/m/Y') }}
                        · {{ $dateTimes->count() }} ช่วงเวลา
                        · {{ $date->lectures->count() }} วิทยากร
                    </p>
                </div>
                <i class="js-ov-date-chevron fas fa-chevron-down shrink-0 text-xs text-blue-600 transition-transform"></i>
            </summary>

            <div class="space-y-3 p-3">
                <div class="flex flex-wrap items-center gap-2 text-xs">
                    <span @class(['hrd-ov-pill-on' => $date->date_active, 'hrd-ov-pill-off' => ! $date->date_active])>
                        <i class="fas fa-{{ $date->date_active ? 'check' : 'minus' }}"></i>
                        {{ $date->date_active ? 'ใช้งาน' : 'ไม่ใช้งาน' }}
                    </span>
                    <span class="hrd-ov-pill-off"><i class="fas fa-location-dot"></i> {{ $date->date_location ?: 'ไม่ระบุสถานที่' }}</span>
                </div>
                @if ($date->date_detail)
                    <p class="text-sm text-slate-600">{{ $date->date_detail }}</p>
                @endif

                <div class="flex flex-wrap gap-2">
                    <button class="hrd-ov-btn-sm hrd-ov-btn-sm--lecturer" type="button" onclick="addLecturer('{{ $date->id }}', '{{ addslashes($date->date_title) }}')">
                        <i class="fas fa-user-plus"></i> เพิ่มวิทยากร
                    </button>
                    <a class="hrd-ov-btn-sm hrd-ov-btn-sm--excel-reg" href="{{ route('hrd.admin.export.excel.date', $date->id) }}">
                        <i class="fas fa-file-excel"></i> ผู้ลงทะเบียน
                    </a>
                    <a class="hrd-ov-btn-sm hrd-ov-btn-sm--excel-lecture" href="{{ route('hrd.admin.export.excel.datelecture', $date->id) }}">
                        <i class="fas fa-file-excel"></i> วิทยากร
                    </a>
                </div>

                <div class="grid grid-cols-1 gap-3 lg:grid-cols-2">
                    <div>
                        <p class="mb-2 text-[11px] font-semibold uppercase tracking-wide text-blue-800">ช่วงเวลา</p>
                        <div class="space-y-2">
                            @forelse ($dateTimes as $time)
                                <div class="rounded-md border border-slate-200 bg-slate-50/60 p-3">
                                    <div class="flex flex-wrap items-start justify-between gap-2">
                                        <p class="text-sm font-semibold text-slate-900">{{ $time->time_title }}</p>
                                        <span @class(['hrd-ov-pill-on' => $time->time_active, 'hrd-ov-pill-off' => ! $time->time_active])>
                                            {{ $time->time_active ? 'ใช้งาน' : 'ปิด' }}
                                        </span>
                                    </div>
                                    @if ($time->time_detail)
                                        <p class="mt-1 text-xs text-slate-600">{{ $time->time_detail }}</p>
                                    @endif
                                    <p class="mt-2 text-xs text-slate-600">
                                        @if ($time->time_limit)
                                            <i class="fas fa-users mr-1 text-blue-700"></i>
                                            {{ $time->activeAttendsCount($project->id) }} / {{ $time->time_max }} คน
                                        @else
                                            <i class="fas fa-infinity mr-1"></i> ไม่จำกัดจำนวน
                                        @endif
                                    </p>
                                    <a class="hrd-ov-btn-sm hrd-ov-btn-sm--pdf mt-2" href="{{ route('hrd.admin.export.pdf.time', ['project_id' => $project->id, 'time_id' => $time->id]) }}">
                                        <i class="fas fa-file-pdf"></i> ใบลงทะเบียน PDF
                                    </a>
                                </div>
                            @empty
                                <p class="rounded-md border border-dashed border-slate-200 py-4 text-center text-xs text-slate-500">ยังไม่มีช่วงเวลา</p>
                            @endforelse
                        </div>
                    </div>

                    <div>
                        <p class="mb-2 text-[11px] font-semibold uppercase tracking-wide text-blue-800">วิทยากร</p>
                        <div class="space-y-2">
                            @forelse ($date->lectures as $lecture)
                                <div class="flex items-center justify-between gap-2 rounded-md border border-slate-200 bg-white p-3">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-slate-900">{{ $lecture->user->userid }} {{ $lecture->user->name }}</p>
                                        <p class="truncate text-xs text-slate-500">{{ $lecture->user->position }} · {{ $lecture->user->department }}</p>
                                    </div>
                                    <button class="hrd-af-btn-remove !py-1" type="button" aria-label="ลบวิทยากร {{ $lecture->user->name }}" onclick="deleteLecture('{{ $lecture->id }}', '{{ addslashes($lecture->user->name) }}')">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            @empty
                                <p class="rounded-md border border-dashed border-slate-200 py-4 text-center text-xs text-slate-500">ยังไม่มีวิทยากร</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </details>
    @empty
        <div class="rounded-md border border-dashed border-slate-200 py-10 text-center">
            <i class="fas fa-calendar-xmark mb-2 text-2xl text-blue-300"></i>
            <p class="text-sm text-slate-500">ยังไม่มีวันที่ในโปรเจกต์นี้</p>
            <a class="mt-3 inline-flex hrd-af-btn-primary" href="{{ route('hrd.admin.projects.edit', $project->id) }}"><i class="fas fa-edit"></i> แก้ไขโปรเจกต์</a>
        </div>
    @endforelse
</div>
