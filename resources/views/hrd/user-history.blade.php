@extends("layouts.hrd")

@section("content")
    @include("hrd.partials.app-page", [
        "appEyebrow" => "HRD",
        "appTitle" => "ประวัติการเข้าร่วม",
        "appSubtitle" => "การลงทะเบียนและการเช็คอินของคุณ",
        "appBackUrl" => route("hrd.index"),
    ])

        <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 lg:grid-cols-5">
            <div class="hrd-stat-card hrd-stat-card--blue sm:p-4">
                <p class="text-[11px] font-semibold uppercase tracking-wide text-blue-700">ลงทะเบียน</p>
                <p class="mt-1 text-2xl font-bold tabular-nums text-slate-900">{{ $statistics["total"] }}</p>
                @if (isset($statistics["legacy"]) && $statistics["legacy"]["total"] > 0)
                    <p class="mt-1 text-[10px] text-slate-600">ใหม่ {{ $statistics["new"]["total"] }} · เดิม {{ $statistics["legacy"]["total"] }}</p>
                @endif
            </div>
            <div class="hrd-stat-card hrd-stat-card--emerald sm:p-4">
                <p class="text-[11px] font-semibold uppercase tracking-wide text-emerald-700">เข้าร่วมแล้ว</p>
                <p class="mt-1 text-2xl font-bold tabular-nums text-slate-900">{{ $statistics["attended"] }}</p>
            </div>
            <div class="hrd-stat-card hrd-stat-card--amber sm:p-4">
                <p class="text-[11px] font-semibold uppercase tracking-wide text-amber-700">รอเข้าร่วม</p>
                <p class="mt-1 text-2xl font-bold tabular-nums text-slate-900">{{ $statistics["pending"] }}</p>
            </div>
            <div class="hrd-stat-card hrd-stat-card--violet sm:p-4">
                <p class="text-[11px] font-semibold uppercase tracking-wide text-violet-700">อนุมัติแล้ว</p>
                <p class="mt-1 text-2xl font-bold tabular-nums text-slate-900">{{ $statistics["approved"] }}</p>
            </div>
            <div class="col-span-2 hrd-stat-card sm:col-span-1 sm:p-4">
                <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">รออนุมัติ</p>
                <p class="mt-1 text-2xl font-bold tabular-nums text-slate-900">{{ $statistics["pendingApproval"] }}</p>
            </div>
        </div>

        @if ($attendanceHistory->count() > 0)
            <section class="hrd-panel">
                <div class="border-b border-slate-100 px-4 py-3 sm:px-5">
                    <h2 class="text-base font-bold text-slate-900">รายการเข้าร่วมโปรแกรม</h2>
                </div>

                <div class="space-y-3 p-3 sm:p-4">
                    @foreach ($attendanceHistory as $index => $attendance)
                        @php
                            $project = $attendance->project;
                            $date = $attendance->date;
                            $time = $attendance->time;
                            $hasAttended = $attendance->attend_datetime !== null;
                            $isToday = $date && $date->date_datetime->format("Y-m-d") === now()->format("Y-m-d");
                            $isPast = $date && $date->date_datetime->format("Y-m-d") < now()->format("Y-m-d");
                        @endphp

                        <article class="hrd-card bg-blue-50/40 p-4 transition hover:bg-white">
                                <div class="space-y-3">
                                    <!-- Header with badges -->
                                    <div class="flex flex-wrap items-start gap-1.5 sm:gap-2">
                                        <div class="flex items-center gap-2">
                                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white sm:h-7 sm:w-7 sm:text-sm">{{ $index + 1 }}</span>
                                            <h3 class="text-sm font-semibold text-slate-900 sm:text-base lg:text-lg">{{ $project->project_name }}</h3>
                                        </div>

                                        <!-- Project Type Badge -->
                                        <span class="@if ($project->project_type === "single") bg-blue-100 text-blue-800
                                            @elseif($project->project_type === "multiple") bg-emerald-100 text-emerald-800
                                            @else bg-purple-100 text-purple-800 @endif inline-flex items-center rounded-full px-1.5 py-0.5 text-xs font-medium sm:px-2">
                                            @if ($project->project_type === "single")
                                                ลงทะเบียน 1 ครั้ง
                                            @elseif($project->project_type === "multiple")
                                                ลงทะเบียนได้มากกว่า 1 ครั้ง
                                            @else
                                                ไม่ต้องลงทะเบียน
                                            @endif
                                        </span>

                                        <!-- Attendance Status Badge -->
                                        @if ($hasAttended)
                                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-1.5 py-0.5 text-xs font-medium text-emerald-800 sm:px-2">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                เข้าร่วมแล้ว
                                            </span>
                                        @elseif($isToday)
                                            <span class="inline-flex items-center rounded-full bg-blue-100 px-1.5 py-0.5 text-xs font-medium text-blue-800 sm:px-2">
                                                <i class="fas fa-clock mr-1"></i>
                                                วันนี้
                                            </span>
                                        @elseif($isPast)
                                            <span class="inline-flex items-center rounded-full bg-red-100 px-1.5 py-0.5 text-xs font-medium text-red-800 sm:px-2">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                ขาด
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-yellow-100 px-1.5 py-0.5 text-xs font-medium text-yellow-800 sm:px-2">
                                                <i class="fas fa-calendar mr-1"></i>
                                                รอเข้าร่วม
                                            </span>
                                        @endif

                                        <!-- Approval Status Badge -->
                                        @if ($attendance->approve_datetime)
                                            <span class="inline-flex items-center rounded-full bg-blue-100 px-1.5 py-0.5 text-xs font-medium text-blue-800 sm:px-2">
                                                <i class="fas fa-user-shield mr-1"></i>
                                                อนุมัติแล้ว
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-slate-100 px-1.5 py-0.5 text-xs font-medium text-slate-900 sm:px-2">
                                                <i class="fas fa-clock mr-1"></i>
                                                รออนุมัติ
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Project Details -->
                                    <div class="grid grid-cols-1 gap-1.5 text-xs text-slate-600 sm:grid-cols-2 sm:text-sm lg:grid-cols-3">
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar-alt mr-1.5 text-xs text-blue-500 sm:text-sm"></i>
                                            <span class="font-medium">วันที่:</span>
                                            <span class="ml-1 truncate">{{ $date->date_title ?? "ไม่ระบุ" }}</span>
                                        </div>

                                        <div class="flex items-center">
                                            <i class="fas fa-clock mr-1.5 text-xs text-emerald-500 sm:text-sm"></i>
                                            <span class="font-medium">เวลา:</span>
                                            <span class="ml-1">
                                                @if ($time)
                                                    {{ \Carbon\Carbon::parse($time->time_start)->format("H:i") }} - {{ \Carbon\Carbon::parse($time->time_end)->format("H:i") }}
                                                @else
                                                    ไม่ระบุ
                                                @endif
                                            </span>
                                        </div>

                                        <div class="flex items-center">
                                            @if ($attendance->note)
                                                <i class="fa-solid fa-circle-info mr-1.5 text-xs text-purple-500 sm:text-sm"></i>
                                                <span class="font-medium">รายละเอียด:</span>
                                                <span class="ml-1 truncate">{{ $attendance->note->attend_note }}</span>
                                            @else
                                                <i class="fas fa-map-marker-alt mr-1.5 text-xs text-purple-500 sm:text-sm"></i>
                                                <span class="font-medium">สถานที่:</span>
                                                <span class="ml-1 truncate">{{ $date->date_location ?? "ไม่ระบุ" }}</span>
                                            @endif
                                        </div>

                                        @if ($time && $hasAttended && ($project->project_seat_assign || $project->project_group_assign))
                                            @php
                                                $userSeat = $project->project_seat_assign
                                                    ? $time
                                                        ->seats()
                                                        ->where("user_id", auth()->id())
                                                        ->where("seat_delete", false)
                                                        ->first()
                                                    : null;
                                                $historyUserGroup = $project->project_group_assign
                                                    ? \App\Models\HrGroup::where("project_id", $project->id)
                                                        ->where("user_id", auth()->id())
                                                        ->where("time_id", $time->id)
                                                        ->first()
                                                    : null;
                                            @endphp
                                            <div class="col-span-full pt-1">
                                                @include("hrd.partials.assignment-inline", [
                                                    "project" => $project,
                                                    "layout" => "full",
                                                    "hasAttended" => true,
                                                    "userSeat" => $userSeat,
                                                    "userGroup" => $historyUserGroup,
                                                ])
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Attendance and Approval Info -->
                                    @if ($hasAttended)
                                        <div class="rounded-xl bg-emerald-50 p-2 sm:p-3">
                                            <div class="flex items-center text-xs text-emerald-700 sm:text-sm">
                                                <i class="fas fa-user-check mr-1.5"></i>
                                                <span class="font-medium">เช็คอินเมื่อ:</span>
                                                <span class="ml-1">{{ \Carbon\Carbon::parse($attendance->attend_datetime)->format("d M Y, H:i") }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($attendance->approve_datetime)
                                        <div class="rounded-lg bg-blue-50 p-2 sm:p-3">
                                            <div class="flex items-center text-xs text-blue-700 sm:text-sm">
                                                <i class="fas fa-check-circle mr-1.5"></i>
                                                <span class="font-medium">อนุมัติโดยผู้ดูแลเมื่อ:</span>
                                                <span class="ml-1">{{ \Carbon\Carbon::parse($attendance->approve_datetime)->format("d M Y, H:i") }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Result Information -->
                                    @if ($attendance->result)
                                        <div class="rounded-lg bg-purple-50 p-2 sm:p-3">
                                            <div class="cursor-pointer text-xs font-medium text-purple-700 sm:text-sm" onclick="toggleResult('result-{{ $attendance->id }}')">
                                                <i class="fas fa-chart-line mr-1.5"></i>
                                                ผลการประเมิน
                                                <i class="fas fa-chevron-down ml-1.5 transition-transform duration-200" id="result-icon-{{ $attendance->id }}"></i>
                                            </div>

                                            <div class="mt-2 hidden space-y-1.5 sm:space-y-2" id="result-{{ $attendance->id }}">
                                                @php
                                                    $resultHeader = $project->resultHeader;
                                                    $result = $attendance->result;
                                                @endphp
                                                @if ($resultHeader)
                                                    @for ($i = 1; $i <= 10; $i++)
                                                        @if ($resultHeader->{"result_{$i}_name"} && $result->{"result_{$i}"})
                                                            <div class="flex justify-between rounded bg-white p-1.5 sm:p-2">
                                                                <span class="text-xs font-medium sm:text-sm">{{ $resultHeader->{"result_{$i}_name"} }}</span>
                                                                <span class="text-xs font-bold text-purple-600 sm:text-sm">{{ $result->{"result_{$i}"} }}</span>
                                                            </div>
                                                        @endif
                                                    @endfor
                                                @else
                                                    <div class="text-xs text-slate-600 sm:text-sm">
                                                        ไม่มีข้อมูลผลการประเมิน
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Project Details -->
                                    @if ($date && $date->date_detail)
                                        <div class="text-xs text-slate-600 sm:text-sm">
                                            <span class="font-medium">รายละเอียด:</span>
                                            <span class="ml-1">{{ $date->date_detail }}</span>
                                        </div>
                                    @endif

                                    <!-- Footer -->
                                    <div class="text-xs text-blue-700/80">
                                        ลงทะเบียนเมื่อ: {{ \Carbon\Carbon::parse($attendance->created_at)->format("d M Y, H:i") }}
                                    </div>
                                </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <!-- Pagination -->
            @if ($attendanceHistory->hasPages())
                <div class="mt-4">
                    {{ $attendanceHistory->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="rounded-md border border-dashed border-slate-200 bg-white p-8 text-center shadow-sm">
                <div class="mx-auto h-10 w-10 text-slate-400 sm:h-12 sm:w-12">
                    <i class="fas fa-history text-3xl sm:text-4xl"></i>
                </div>
                <h3 class="mt-3 text-base font-medium text-slate-900 sm:text-lg">ไม่มีประวัติการเข้าร่วม</h3>
                <p class="mt-1 text-xs text-slate-500 sm:text-sm">คุณยังไม่เคยลงทะเบียนหรือเข้าร่วมโปรแกรมใดๆ</p>
                <div class="mt-4">
                    <a class="inline-flex min-h-[44px] items-center rounded-md bg-blue-600 px-4 text-sm font-semibold text-white hover:bg-blue-700" href="{{ route("hrd.index") }}">
                        <i class="fas fa-search mr-1.5 sm:mr-2"></i>
                        ดูโปรแกรมที่มีอยู่
                    </a>
                </div>
            </div>
        @endif

        <!-- Legacy HR Data Section -->
        @if ($legacyTransactions->count() > 0)
            <section class="hrd-panel">
                <div class="border-b border-slate-100 px-4 py-3 sm:px-5">
                    <h2 class="text-base font-bold text-slate-900">ประวัติ (ระบบเดิม)</h2>
                </div>

                <div class="space-y-3 p-3 sm:p-4">
                    @foreach ($legacyTransactions as $index => $transaction)
                        @php
                            $item = $transaction->item;
                            $slot = $item->slot;
                            $project = $slot->project;
                            $hasAttended = $transaction->checkin_datetime !== null;
                            $isToday = $slot->slot_date === now()->format("Y-m-d");
                            $isPast = $slot->slot_date < now()->format("Y-m-d");
                        @endphp

                        <article class="hrd-card bg-blue-50/40 p-4">
                            <div class="space-y-3">
                                <!-- Header with badges -->
                                <div class="flex flex-wrap items-start gap-1.5 sm:gap-2">
                                    <div class="flex items-center gap-2">
                                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-600 sm:h-7 sm:w-7 sm:text-sm">{{ $index + 1 }}</span>
                                        <h3 class="text-sm font-semibold text-slate-900 sm:text-base lg:text-lg">{{ $project->project_name }}</h3>
                                    </div>

                                    <!-- Attendance Status Badge -->
                                    @if ($hasAttended)
                                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-1.5 py-0.5 text-xs font-medium text-emerald-800 sm:px-2">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            เข้าร่วมแล้ว
                                        </span>
                                    @elseif($isToday)
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-1.5 py-0.5 text-xs font-medium text-blue-800 sm:px-2">
                                            <i class="fas fa-clock mr-1"></i>
                                            วันนี้
                                        </span>
                                    @elseif($isPast)
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-1.5 py-0.5 text-xs font-medium text-red-800 sm:px-2">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            ขาด
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-1.5 py-0.5 text-xs font-medium text-yellow-800 sm:px-2">
                                            <i class="fas fa-calendar mr-1"></i>
                                            รอเข้าร่วม
                                        </span>
                                    @endif

                                    <!-- Legacy System Badge -->
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-1.5 py-0.5 text-xs font-medium text-slate-900 sm:px-2">
                                        <i class="fas fa-archive mr-1"></i>
                                        ระบบเดิม
                                    </span>
                                </div>

                                <!-- Project Details -->
                                <div class="grid grid-cols-1 gap-1.5 text-xs text-slate-600 sm:grid-cols-2 sm:text-sm lg:grid-cols-3">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-alt mr-1.5 text-xs text-blue-500 sm:text-sm"></i>
                                        <span class="font-medium">วันที่:</span>
                                        <span class="ml-1 truncate">{{ $slot->dateThai }}</span>
                                    </div>

                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-day mr-1.5 text-xs text-emerald-500 sm:text-sm"></i>
                                        <span class="font-medium">วันที่:</span>
                                        <span class="ml-1 truncate">{{ date("d", strtotime($slot->slot_date)) }} {{ $slot->monthThai }}</span>
                                    </div>

                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-1.5 text-xs text-purple-500 sm:text-sm"></i>
                                        <span class="font-medium">รอบ:</span>
                                        <span class="ml-1 truncate">{{ $item->item_name }}</span>
                                    </div>

                                    @if ($item->item_note_1_active)
                                        <div class="flex items-center">
                                            <i class="fas fa-map-marker-alt mr-1.5 text-xs text-orange-500 sm:text-sm"></i>
                                            <span class="font-medium">{{ $item->item_note_1_title }}:</span>
                                            <span class="ml-1 truncate">{{ $item->item_note_1_value }}</span>
                                        </div>
                                    @endif

                                    @if ($transaction->seat)
                                        <div class="col-span-full pt-1">
                                            @include("hrd.partials.assignment-inline", [
                                                "layout" => "full",
                                                "hasAttended" => true,
                                                "showSeatFeature" => true,
                                                "showGroupFeature" => false,
                                                "seatNumber" => $transaction->seat,
                                            ])
                                        </div>
                                    @endif
                                </div>

                                <!-- Attendance Info -->
                                @if ($hasAttended)
                                    <div class="rounded-xl bg-emerald-50 p-2 sm:p-3">
                                        <div class="flex items-center text-xs text-emerald-700 sm:text-sm">
                                            <i class="fas fa-user-check mr-1.5"></i>
                                            <span class="font-medium">เช็คอินเมื่อ:</span>
                                            <span class="ml-1">{{ date("d/m/Y H:i", strtotime($transaction->checkin_datetime)) }}</span>
                                        </div>
                                    </div>
                                @endif

                                <!-- Score Information -->
                                @if ($transaction->scoreData)
                                    <div class="rounded-lg bg-blue-50 p-2 sm:p-3">
                                        <div class="cursor-pointer text-xs font-medium text-blue-700 sm:text-sm" onclick="toggleScore('legacy-score-{{ $transaction->id }}')">
                                            <i class="fas fa-file-lines mr-1.5"></i>
                                            คะแนนสอบ
                                            <i class="fas fa-chevron-down ml-1.5 transition-transform duration-200" id="score-icon-{{ $transaction->id }}"></i>
                                        </div>

                                        <div class="mt-2 hidden space-y-1.5 sm:space-y-2" id="legacy-score-{{ $transaction->id }}">
                                            @if ($transaction->scoreHeader->title_1)
                                                <div class="flex justify-between rounded bg-white p-1.5 sm:p-2">
                                                    <span class="text-xs font-medium sm:text-sm">{{ $transaction->scoreHeader->title_1 }}</span>
                                                    <span class="text-xs font-bold text-red-600 sm:text-sm">{{ $transaction->scoreData->result_1 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_2)
                                                <div class="flex justify-between rounded bg-white p-1.5 sm:p-2">
                                                    <span class="text-xs font-medium sm:text-sm">{{ $transaction->scoreHeader->title_2 }}</span>
                                                    <span class="text-xs font-bold text-red-600 sm:text-sm">{{ $transaction->scoreData->result_2 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_3)
                                                <div class="flex justify-between rounded bg-white p-1.5 sm:p-2">
                                                    <span class="text-xs font-medium sm:text-sm">{{ $transaction->scoreHeader->title_3 }}</span>
                                                    <span class="text-xs font-bold text-red-600 sm:text-sm">{{ $transaction->scoreData->result_3 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_4)
                                                <div class="flex justify-between rounded bg-white p-1.5 sm:p-2">
                                                    <span class="text-xs font-medium sm:text-sm">{{ $transaction->scoreHeader->title_4 }}</span>
                                                    <span class="text-xs font-bold text-red-600 sm:text-sm">{{ $transaction->scoreData->result_4 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_5)
                                                <div class="flex justify-between rounded bg-white p-1.5 sm:p-2">
                                                    <span class="text-xs font-medium sm:text-sm">{{ $transaction->scoreHeader->title_5 }}</span>
                                                    <span class="text-xs font-bold text-red-600 sm:text-sm">{{ $transaction->scoreData->result_5 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_6)
                                                <div class="flex justify-between rounded bg-white p-1.5 sm:p-2">
                                                    <span class="text-xs font-medium sm:text-sm">{{ $transaction->scoreHeader->title_6 }}</span>
                                                    <span class="text-xs font-bold text-red-600 sm:text-sm">{{ $transaction->scoreData->result_6 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_7)
                                                <div class="flex justify-between rounded bg-white p-1.5 sm:p-2">
                                                    <span class="text-xs font-medium sm:text-sm">{{ $transaction->scoreHeader->title_7 }}</span>
                                                    <span class="text-xs font-bold text-red-600 sm:text-sm">{{ $transaction->scoreData->result_7 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_8)
                                                <div class="flex justify-between rounded bg-white p-1.5 sm:p-2">
                                                    <span class="text-xs font-medium sm:text-sm">{{ $transaction->scoreHeader->title_8 }}</span>
                                                    <span class="text-xs font-bold text-red-600 sm:text-sm">{{ $transaction->scoreData->result_8 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_9)
                                                <div class="flex justify-between rounded bg-white p-1.5 sm:p-2">
                                                    <span class="text-xs font-medium sm:text-sm">{{ $transaction->scoreHeader->title_9 }}</span>
                                                    <span class="text-xs font-bold text-red-600 sm:text-sm">{{ $transaction->scoreData->result_9 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_10)
                                                <div class="flex justify-between rounded bg-white p-1.5 sm:p-2">
                                                    <span class="text-xs font-medium sm:text-sm">{{ $transaction->scoreHeader->title_10 }}</span>
                                                    <span class="text-xs font-bold text-red-600 sm:text-sm">{{ $transaction->scoreData->result_10 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_11)
                                                <div class="flex justify-between rounded bg-white p-1.5 sm:p-2">
                                                    <span class="text-xs font-medium sm:text-sm">{{ $transaction->scoreHeader->title_11 }}</span>
                                                    <span class="text-xs font-bold text-red-600 sm:text-sm">{{ $transaction->scoreData->result_11 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_12)
                                                <div class="flex justify-between rounded bg-white p-1.5 sm:p-2">
                                                    <span class="text-xs font-medium sm:text-sm">{{ $transaction->scoreHeader->title_12 }}</span>
                                                    <span class="text-xs font-bold text-red-600 sm:text-sm">{{ $transaction->scoreData->result_12 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_13)
                                                <div class="flex justify-between rounded bg-white p-1.5 sm:p-2">
                                                    <span class="text-xs font-medium sm:text-sm">{{ $transaction->scoreHeader->title_13 }}</span>
                                                    <span class="text-xs font-bold text-red-600 sm:text-sm">{{ $transaction->scoreData->result_13 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_14)
                                                <div class="flex justify-between rounded bg-white p-1.5 sm:p-2">
                                                    <span class="text-xs font-medium sm:text-sm">{{ $transaction->scoreHeader->title_14 }}</span>
                                                    <span class="text-xs font-bold text-red-600 sm:text-sm">{{ $transaction->scoreData->result_14 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_15)
                                                <div class="flex justify-between rounded bg-white p-1.5 sm:p-2">
                                                    <span class="text-xs font-medium sm:text-sm">{{ $transaction->scoreHeader->title_15 }}</span>
                                                    <span class="text-xs font-bold text-red-600 sm:text-sm">{{ $transaction->scoreData->result_15 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_16)
                                                <div class="flex justify-between rounded bg-white p-1.5 sm:p-2">
                                                    <span class="text-xs font-medium sm:text-sm">{{ $transaction->scoreHeader->title_16 }}</span>
                                                    <span class="text-xs font-bold text-red-600 sm:text-sm">{{ $transaction->scoreData->result_16 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_17)
                                                <div class="flex justify-between rounded bg-white p-1.5 sm:p-2">
                                                    <span class="text-xs font-medium sm:text-sm">{{ $transaction->scoreHeader->title_17 }}</span>
                                                    <span class="text-xs font-bold text-red-600 sm:text-sm">{{ $transaction->scoreData->result_17 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_18)
                                                <div class="flex justify-between rounded bg-white p-1.5 sm:p-2">
                                                    <span class="text-xs font-medium sm:text-sm">{{ $transaction->scoreHeader->title_18 }}</span>
                                                    <span class="text-xs font-bold text-red-600 sm:text-sm">{{ $transaction->scoreData->result_18 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_19)
                                                <div class="flex justify-between rounded bg-white p-1.5 sm:p-2">
                                                    <span class="text-xs font-medium sm:text-sm">{{ $transaction->scoreHeader->title_19 }}</span>
                                                    <span class="text-xs font-bold text-red-600 sm:text-sm">{{ $transaction->scoreData->result_19 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_20)
                                                <div class="flex justify-between rounded bg-white p-1.5 sm:p-2">
                                                    <span class="text-xs font-medium sm:text-sm">{{ $transaction->scoreHeader->title_20 }}</span>
                                                    <span class="text-xs font-bold text-red-600 sm:text-sm">{{ $transaction->scoreData->result_20 }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Footer -->
                                <div class="flex items-center justify-between pt-1 sm:pt-2">
                                    <div class="text-xs text-slate-500">
                                        ลงทะเบียนเมื่อ: {{ \Carbon\Carbon::parse($transaction->created_at)->format("d M Y, H:i") }}
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

    @include("hrd.partials.app-page", ["appPageClose" => true])
@endsection

@section("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session("success"))
                Swal.fire({ icon: 'success', title: @json(session("success")), confirmButtonText: 'ตกลง', confirmButtonColor: '#2563eb' });
            @endif
            @if (session("error"))
                Swal.fire({ icon: 'error', title: @json(session("error")), confirmButtonText: 'ตกลง', confirmButtonColor: '#2563eb' });
            @endif
        });

        function toggleScore(scoreId) {
            const scoreElement = document.getElementById(scoreId);
            const iconElement = document.getElementById(scoreId.replace('legacy-score-', 'score-icon-'));

            if (scoreElement.classList.contains('hidden')) {
                scoreElement.classList.remove('hidden');
                iconElement.style.transform = 'rotate(180deg)';
            } else {
                scoreElement.classList.add('hidden');
                iconElement.style.transform = 'rotate(0deg)';
            }
        }

        function toggleResult(resultId) {
            const resultElement = document.getElementById(resultId);
            const iconElement = document.getElementById(resultId.replace('result-', 'result-icon-'));

            if (resultElement.classList.contains('hidden')) {
                resultElement.classList.remove('hidden');
                iconElement.style.transform = 'rotate(180deg)';
            } else {
                resultElement.classList.add('hidden');
                iconElement.style.transform = 'rotate(0deg)';
            }
        }
    </script>
@endsection
