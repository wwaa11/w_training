@extends("layouts.hrd")

@section("content")
    <div class="container mx-auto px-4 pb-20">
        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-800 sm:text-3xl">ประวัติการเข้าร่วมโปรแกรม</h1>
                    <p class="mt-1 text-sm text-gray-600 sm:text-base">ดูประวัติการลงทะเบียนและการเข้าร่วมโปรแกรมพัฒนาบุคลากรของคุณ</p>
                </div>
                <a class="ml-4 inline-flex items-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700 sm:px-4" href="{{ route("hrd.index") }}">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span class="hidden sm:inline">กลับไปหน้าโปรแกรม</span>
                    <span class="sm:hidden">กลับ</span>
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session("success"))
            <div class="mb-4 rounded-lg border border-green-400 bg-green-100 px-4 py-3 text-green-700">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="text-sm">{{ session("success") }}</span>
                </div>
            </div>
        @endif

        @if (session("error"))
            <div class="mb-4 rounded-lg border border-red-400 bg-red-100 px-4 py-3 text-red-700">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span class="text-sm">{{ session("error") }}</span>
                </div>
            </div>
        @endif

        <!-- Statistics Summary -->
        <div class="mb-6 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
            <div class="rounded-lg bg-white p-4 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100">
                            <i class="fas fa-calendar-check text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 sm:text-sm">รวมการลงทะเบียน</p>
                        <p class="text-lg font-bold text-gray-900 sm:text-2xl">{{ $statistics["total"] }}</p>
                        @if (isset($statistics["legacy"]) && $statistics["legacy"]["total"] > 0)
                            <p class="text-xs text-gray-500">ระบบใหม่: {{ $statistics["new"]["total"] }} | ระบบเดิม: {{ $statistics["legacy"]["total"] }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="rounded-lg bg-white p-4 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100">
                            <i class="fas fa-user-check text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 sm:text-sm">เข้าร่วมแล้ว</p>
                        <p class="text-lg font-bold text-gray-900 sm:text-2xl">{{ $statistics["attended"] }}</p>
                        @if (isset($statistics["legacy"]) && $statistics["legacy"]["total"] > 0)
                            <p class="text-xs text-gray-500">ระบบใหม่: {{ $statistics["new"]["attended"] }} | ระบบเดิม: {{ $statistics["legacy"]["attended"] }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="rounded-lg bg-white p-4 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-yellow-100">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 sm:text-sm">รอเข้าร่วม</p>
                        <p class="text-lg font-bold text-gray-900 sm:text-2xl">{{ $statistics["pending"] }}</p>
                        @if (isset($statistics["legacy"]) && $statistics["legacy"]["total"] > 0)
                            <p class="text-xs text-gray-500">ระบบใหม่: {{ $statistics["new"]["pending"] }} | ระบบเดิม: {{ $statistics["legacy"]["pending"] }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="rounded-lg bg-white p-4 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100">
                            <i class="fas fa-user-shield text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 sm:text-sm">อนุมัติแล้ว</p>
                        <p class="text-lg font-bold text-gray-900 sm:text-2xl">{{ $statistics["approved"] }}</p>
                        <p class="text-xs text-gray-500">ระบบใหม่เท่านั้น</p>
                    </div>
                </div>
            </div>

            <div class="col-span-2 rounded-lg bg-white p-4 shadow-sm sm:col-span-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100">
                            <i class="fas fa-hourglass-half text-gray-600"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 sm:text-sm">รออนุมัติ</p>
                        <p class="text-lg font-bold text-gray-900 sm:text-2xl">{{ $statistics["pendingApproval"] }}</p>
                        <p class="text-xs text-gray-500">ระบบใหม่เท่านั้น</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance History -->
        @if ($attendanceHistory->count() > 0)
            <div class="rounded-lg bg-white shadow-sm">
                <div class="border-b border-gray-200 px-4 py-3 sm:px-6">
                    <h2 class="text-lg font-semibold text-gray-900">รายการเข้าร่วมโปรแกรม</h2>
                </div>

                <div class="divide-y divide-gray-200">
                    @foreach ($attendanceHistory as $attendance)
                        @php
                            $project = $attendance->project;
                            $date = $attendance->date;
                            $time = $attendance->time;
                            $hasAttended = $attendance->attend_datetime !== null;
                            $isToday = $date && $date->date_datetime->format("Y-m-d") === now()->format("Y-m-d");
                            $isPast = $date && $date->date_datetime->format("Y-m-d") < now()->format("Y-m-d");
                        @endphp

                        <a class="block" href="{{ route("hrd.projects.show", $project->id) }}">
                            <div class="cursor-pointer p-4 transition-all duration-200 hover:bg-gray-50 hover:shadow-sm sm:p-6">
                                <div class="space-y-3">
                                    <!-- Header with badges -->
                                    <div class="flex flex-wrap items-start gap-2">
                                        <h3 class="text-base font-semibold text-gray-900 sm:text-lg">{{ $project->project_name }}</h3>

                                        <!-- Project Type Badge -->
                                        <span class="@if ($project->project_type === "single") bg-blue-100 text-blue-800
                                            @elseif($project->project_type === "multiple") bg-green-100 text-green-800
                                            @else bg-purple-100 text-purple-800 @endif inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium">
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
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                เข้าร่วมแล้ว
                                            </span>
                                        @elseif($isToday)
                                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">
                                                <i class="fas fa-clock mr-1"></i>
                                                วันนี้
                                            </span>
                                        @elseif($isPast)
                                            <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                ขาด
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-800">
                                                <i class="fas fa-calendar mr-1"></i>
                                                รอเข้าร่วม
                                            </span>
                                        @endif

                                        <!-- Approval Status Badge -->
                                        @if ($attendance->approve_datetime)
                                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">
                                                <i class="fas fa-user-shield mr-1"></i>
                                                อนุมัติแล้ว
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800">
                                                <i class="fas fa-clock mr-1"></i>
                                                รออนุมัติ
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Project Details -->
                                    <div class="grid grid-cols-1 gap-2 text-sm text-gray-600 sm:grid-cols-2 lg:grid-cols-3">
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                            <span class="font-medium">วันที่:</span>
                                            <span class="ml-1">{{ $date->date_title ?? "ไม่ระบุ" }}</span>
                                        </div>

                                        <div class="flex items-center">
                                            <i class="fas fa-clock mr-2 text-green-500"></i>
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
                                            <i class="fas fa-map-marker-alt mr-2 text-purple-500"></i>
                                            <span class="font-medium">สถานที่:</span>
                                            <span class="ml-1">{{ $date->date_location ?? "ไม่ระบุ" }}</span>
                                        </div>

                                        @if ($project->project_seat_assign && $time)
                                            @php
                                                $userSeat = $time
                                                    ->seats()
                                                    ->where("user_id", auth()->id())
                                                    ->where("seat_delete", false)
                                                    ->first();
                                            @endphp
                                            @if ($userSeat)
                                                <div class="col-span-full sm:col-span-2 lg:col-span-3">
                                                    <div class="inline-flex items-center rounded-lg bg-gradient-to-r from-purple-500 to-purple-600 px-3 py-2 shadow-md">
                                                        <i class="fas fa-chair mr-2 text-lg text-white"></i>
                                                        <div class="text-center">
                                                            <div class="text-xs font-medium text-purple-100">ที่นั่งของคุณ</div>
                                                            <div class="text-lg font-bold text-white">{{ $userSeat->seat_number }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($project->project_group_assign)
                                                @php
                                                    $userGroup = \App\Models\HrGroup::where("project_id", $project->id)
                                                        ->where("user_id", auth()->id())
                                                        ->first();
                                                @endphp
                                                @if ($userGroup)
                                                    <div class="col-span-full sm:col-span-2 lg:col-span-3">
                                                        <div class="inline-flex items-center rounded-lg bg-gradient-to-r from-indigo-500 to-indigo-600 px-3 py-2 shadow-md">
                                                            <i class="fas fa-users mr-2 text-lg text-white"></i>
                                                            <div class="text-center">
                                                                <div class="text-xs font-medium text-indigo-100">กลุ่มของคุณ</div>
                                                                <div class="text-lg font-bold text-white">{{ $userGroup->group }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif
                                    </div>

                                    <!-- Attendance and Approval Info -->
                                    @if ($hasAttended)
                                        <div class="rounded-lg bg-green-50 p-3">
                                            <div class="flex items-center text-sm text-green-700">
                                                <i class="fas fa-user-check mr-2"></i>
                                                <span class="font-medium">เช็คอินเมื่อ:</span>
                                                <span class="ml-1">{{ \Carbon\Carbon::parse($attendance->attend_datetime)->format("d M Y, H:i") }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($attendance->approve_datetime)
                                        <div class="rounded-lg bg-blue-50 p-3">
                                            <div class="flex items-center text-sm text-blue-700">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                <span class="font-medium">อนุมัติโดยผู้ดูแลเมื่อ:</span>
                                                <span class="ml-1">{{ \Carbon\Carbon::parse($attendance->approve_datetime)->format("d M Y, H:i") }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Project Details -->
                                    @if ($date && $date->date_detail)
                                        <div class="text-sm text-gray-600">
                                            <span class="font-medium">รายละเอียด:</span>
                                            <span class="ml-1">{{ $date->date_detail }}</span>
                                        </div>
                                    @endif

                                    <!-- Footer -->
                                    <div class="flex items-center justify-between pt-2">
                                        <div class="text-xs text-gray-500">
                                            ลงทะเบียนเมื่อ: {{ \Carbon\Carbon::parse($attendance->created_at)->format("d M Y, H:i") }}
                                        </div>

                                        <div class="inline-flex items-center text-sm font-medium text-blue-600">
                                            <i class="fas fa-arrow-right mr-1 transition-transform duration-200 group-hover:translate-x-1"></i>
                                            ดูรายละเอียด
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Pagination -->
            @if ($attendanceHistory->hasPages())
                <div class="mt-6">
                    {{ $attendanceHistory->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="rounded-lg bg-white p-8 text-center shadow-sm sm:p-12">
                <div class="mx-auto h-12 w-12 text-gray-400 sm:h-16 sm:w-16">
                    <i class="fas fa-history text-4xl sm:text-5xl"></i>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">ไม่มีประวัติการเข้าร่วม</h3>
                <p class="mt-2 text-sm text-gray-500">คุณยังไม่เคยลงทะเบียนหรือเข้าร่วมโปรแกรมใดๆ</p>
                <div class="mt-6">
                    <a class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700" href="{{ route("hrd.index") }}">
                        <i class="fas fa-search mr-2"></i>
                        ดูโปรแกรมที่มีอยู่
                    </a>
                </div>
            </div>
        @endif

        <!-- Legacy HR Data Section -->
        @if ($legacyTransactions->count() > 0)
            <div class="mt-8 rounded-lg bg-white shadow-sm">

                <div class="divide-y divide-gray-200">
                    @foreach ($legacyTransactions as $transaction)
                        @php
                            $item = $transaction->item;
                            $slot = $item->slot;
                            $project = $slot->project;
                            $hasAttended = $transaction->checkin_datetime !== null;
                            $isToday = $slot->slot_date === now()->format("Y-m-d");
                            $isPast = $slot->slot_date < now()->format("Y-m-d");
                        @endphp

                        <div class="p-4 transition-all duration-200 hover:bg-gray-50 sm:p-6">
                            <div class="space-y-3">
                                <!-- Header with badges -->
                                <div class="flex flex-wrap items-start gap-2">
                                    <h3 class="text-base font-semibold text-gray-900 sm:text-lg">{{ $project->project_name }}</h3>

                                    <!-- Attendance Status Badge -->
                                    @if ($hasAttended)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            เข้าร่วมแล้ว
                                        </span>
                                    @elseif($isToday)
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            วันนี้
                                        </span>
                                    @elseif($isPast)
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            ขาด
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-800">
                                            <i class="fas fa-calendar mr-1"></i>
                                            รอเข้าร่วม
                                        </span>
                                    @endif

                                    <!-- Legacy System Badge -->
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800">
                                        <i class="fas fa-archive mr-1"></i>
                                        ระบบเดิม
                                    </span>
                                </div>

                                <!-- Project Details -->
                                <div class="grid grid-cols-1 gap-2 text-sm text-gray-600 sm:grid-cols-2 lg:grid-cols-3">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                        <span class="font-medium">วันที่:</span>
                                        <span class="ml-1">{{ $slot->dateThai }}</span>
                                    </div>

                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-day mr-2 text-green-500"></i>
                                        <span class="font-medium">วันที่:</span>
                                        <span class="ml-1">{{ date("d", strtotime($slot->slot_date)) }} {{ $slot->monthThai }}</span>
                                    </div>

                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-2 text-purple-500"></i>
                                        <span class="font-medium">รอบ:</span>
                                        <span class="ml-1">{{ $item->item_name }}</span>
                                    </div>

                                    @if ($item->item_note_1_active)
                                        <div class="flex items-center">
                                            <i class="fas fa-map-marker-alt mr-2 text-orange-500"></i>
                                            <span class="font-medium">{{ $item->item_note_1_title }}:</span>
                                            <span class="ml-1">{{ $item->item_note_1_value }}</span>
                                        </div>
                                    @endif

                                    @if ($transaction->seat)
                                        <div class="col-span-full sm:col-span-2 lg:col-span-3">
                                            <div class="inline-flex items-center rounded-lg bg-gradient-to-r from-purple-500 to-purple-600 px-3 py-2 shadow-md">
                                                <i class="fas fa-chair mr-2 text-lg text-white"></i>
                                                <div class="text-center">
                                                    <div class="text-xs font-medium text-purple-100">ที่นั่งของคุณ</div>
                                                    <div class="text-lg font-bold text-white">{{ $transaction->seat }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Attendance Info -->
                                @if ($hasAttended)
                                    <div class="rounded-lg bg-green-50 p-3">
                                        <div class="flex items-center text-sm text-green-700">
                                            <i class="fas fa-user-check mr-2"></i>
                                            <span class="font-medium">เช็คอินเมื่อ:</span>
                                            <span class="ml-1">{{ date("d/m/Y H:i", strtotime($transaction->checkin_datetime)) }}</span>
                                        </div>
                                    </div>
                                @endif

                                <!-- Score Information -->
                                @if ($transaction->scoreData)
                                    <div class="rounded-lg bg-blue-50 p-3">
                                        <div class="cursor-pointer text-sm font-medium text-blue-700" onclick="toggleScore('legacy-score-{{ $transaction->id }}')">
                                            <i class="fas fa-file-lines mr-2"></i>
                                            คะแนนสอบ
                                            <i class="fas fa-chevron-down ml-2 transition-transform duration-200" id="score-icon-{{ $transaction->id }}"></i>
                                        </div>

                                        <div class="mt-3 hidden space-y-2" id="legacy-score-{{ $transaction->id }}">
                                            @if ($transaction->scoreHeader->title_1)
                                                <div class="flex justify-between rounded bg-white p-2">
                                                    <span class="font-medium">{{ $transaction->scoreHeader->title_1 }}</span>
                                                    <span class="font-bold text-red-600">{{ $transaction->scoreData->result_1 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_2)
                                                <div class="flex justify-between rounded bg-white p-2">
                                                    <span class="font-medium">{{ $transaction->scoreHeader->title_2 }}</span>
                                                    <span class="font-bold text-red-600">{{ $transaction->scoreData->result_2 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_3)
                                                <div class="flex justify-between rounded bg-white p-2">
                                                    <span class="font-medium">{{ $transaction->scoreHeader->title_3 }}</span>
                                                    <span class="font-bold text-red-600">{{ $transaction->scoreData->result_3 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_4)
                                                <div class="flex justify-between rounded bg-white p-2">
                                                    <span class="font-medium">{{ $transaction->scoreHeader->title_4 }}</span>
                                                    <span class="font-bold text-red-600">{{ $transaction->scoreData->result_4 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_5)
                                                <div class="flex justify-between rounded bg-white p-2">
                                                    <span class="font-medium">{{ $transaction->scoreHeader->title_5 }}</span>
                                                    <span class="font-bold text-red-600">{{ $transaction->scoreData->result_5 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_6)
                                                <div class="flex justify-between rounded bg-white p-2">
                                                    <span class="font-medium">{{ $transaction->scoreHeader->title_6 }}</span>
                                                    <span class="font-bold text-red-600">{{ $transaction->scoreData->result_6 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_7)
                                                <div class="flex justify-between rounded bg-white p-2">
                                                    <span class="font-medium">{{ $transaction->scoreHeader->title_7 }}</span>
                                                    <span class="font-bold text-red-600">{{ $transaction->scoreData->result_7 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_8)
                                                <div class="flex justify-between rounded bg-white p-2">
                                                    <span class="font-medium">{{ $transaction->scoreHeader->title_8 }}</span>
                                                    <span class="font-bold text-red-600">{{ $transaction->scoreData->result_8 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_9)
                                                <div class="flex justify-between rounded bg-white p-2">
                                                    <span class="font-medium">{{ $transaction->scoreHeader->title_9 }}</span>
                                                    <span class="font-bold text-red-600">{{ $transaction->scoreData->result_9 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_10)
                                                <div class="flex justify-between rounded bg-white p-2">
                                                    <span class="font-medium">{{ $transaction->scoreHeader->title_10 }}</span>
                                                    <span class="font-bold text-red-600">{{ $transaction->scoreData->result_10 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_11)
                                                <div class="flex justify-between rounded bg-white p-2">
                                                    <span class="font-medium">{{ $transaction->scoreHeader->title_11 }}</span>
                                                    <span class="font-bold text-red-600">{{ $transaction->scoreData->result_11 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_12)
                                                <div class="flex justify-between rounded bg-white p-2">
                                                    <span class="font-medium">{{ $transaction->scoreHeader->title_12 }}</span>
                                                    <span class="font-bold text-red-600">{{ $transaction->scoreData->result_12 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_13)
                                                <div class="flex justify-between rounded bg-white p-2">
                                                    <span class="font-medium">{{ $transaction->scoreHeader->title_13 }}</span>
                                                    <span class="font-bold text-red-600">{{ $transaction->scoreData->result_13 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_14)
                                                <div class="flex justify-between rounded bg-white p-2">
                                                    <span class="font-medium">{{ $transaction->scoreHeader->title_14 }}</span>
                                                    <span class="font-bold text-red-600">{{ $transaction->scoreData->result_14 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_15)
                                                <div class="flex justify-between rounded bg-white p-2">
                                                    <span class="font-medium">{{ $transaction->scoreHeader->title_15 }}</span>
                                                    <span class="font-bold text-red-600">{{ $transaction->scoreData->result_15 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_16)
                                                <div class="flex justify-between rounded bg-white p-2">
                                                    <span class="font-medium">{{ $transaction->scoreHeader->title_16 }}</span>
                                                    <span class="font-bold text-red-600">{{ $transaction->scoreData->result_16 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_17)
                                                <div class="flex justify-between rounded bg-white p-2">
                                                    <span class="font-medium">{{ $transaction->scoreHeader->title_17 }}</span>
                                                    <span class="font-bold text-red-600">{{ $transaction->scoreData->result_17 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_18)
                                                <div class="flex justify-between rounded bg-white p-2">
                                                    <span class="font-medium">{{ $transaction->scoreHeader->title_18 }}</span>
                                                    <span class="font-bold text-red-600">{{ $transaction->scoreData->result_18 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_19)
                                                <div class="flex justify-between rounded bg-white p-2">
                                                    <span class="font-medium">{{ $transaction->scoreHeader->title_19 }}</span>
                                                    <span class="font-bold text-red-600">{{ $transaction->scoreData->result_19 }}</span>
                                                </div>
                                            @endif
                                            @if ($transaction->scoreHeader->title_20)
                                                <div class="flex justify-between rounded bg-white p-2">
                                                    <span class="font-medium">{{ $transaction->scoreHeader->title_20 }}</span>
                                                    <span class="font-bold text-red-600">{{ $transaction->scoreData->result_20 }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Footer -->
                                <div class="flex items-center justify-between pt-2">
                                    <div class="text-xs text-gray-500">
                                        ลงทะเบียนเมื่อ: {{ \Carbon\Carbon::parse($transaction->created_at)->format("d M Y, H:i") }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection

@section("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('HRD History page loaded');
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
    </script>
@endsection
