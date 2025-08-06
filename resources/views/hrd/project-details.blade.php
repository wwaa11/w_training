@extends("layouts.hrd")

@section("content")
    <div class="container mx-auto px-3 pb-16">
        <!-- Breadcrumb -->
        <nav class="mb-3">
            <ol class="flex items-center space-x-1.5 text-xs text-gray-500 sm:space-x-2 sm:text-sm">
                <li><a class="hover:text-blue-600" href="{{ route("hrd.index") }}">โปรแกรม HRD</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li class="truncate text-gray-900">{{ $project->project_name }}</li>
            </ol>
        </nav>

        @if (session("success"))
            <div class="mb-3 rounded-lg border border-green-400 bg-green-100 px-3 py-2 text-green-700 sm:px-4 sm:py-3">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2 text-sm"></i>
                    <span class="text-xs sm:text-sm">{{ session("success") }}</span>
                </div>
            </div>
        @endif

        @if (session("error"))
            <div class="mb-3 rounded-lg border border-red-400 bg-red-100 px-3 py-2 text-red-700 sm:px-4 sm:py-3">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2 text-sm"></i>
                    <span class="text-xs sm:text-sm">{{ session("error") }}</span>
                </div>
            </div>
        @endif

        @if (session("info"))
            <div class="mb-3 rounded-lg border border-blue-400 bg-blue-100 px-3 py-2 text-blue-700 sm:px-4 sm:py-3">
                <div class="flex items-center">
                    <i class="fas fa-info-circle mr-2 text-sm"></i>
                    <span class="text-xs sm:text-sm">{{ session("info") }}</span>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-3 rounded-lg border border-red-400 bg-red-100 px-3 py-2 text-red-700 sm:px-4 sm:py-3">
                <h4 class="text-sm font-bold">กรุณาแก้ไขข้อผิดพลาดต่อไปนี้:</h4>
                <ul class="mt-2 list-inside list-disc text-xs sm:text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="space-y-4 sm:space-y-6">
            <!-- Check-in Section for All Project Types -->
            @if ($availableCheckIns->count() > 0)
                <div class="rounded-xl bg-gradient-to-r from-blue-50 to-blue-100 p-3 shadow-sm sm:p-6">
                    <div class="mb-3 flex items-center sm:mb-4">
                        <i class="fas fa-clock mr-2 text-xl text-blue-600 sm:mr-3 sm:text-2xl"></i>
                        <div>
                            <h2 class="text-lg font-bold text-blue-900 sm:text-xl lg:text-2xl">เช็คอินตอนนี้</h2>
                            <p class="text-xs text-blue-700 sm:text-sm lg:text-base">คุณสามารถเช็คอินสำหรับเซสชันต่อไปนี้ได้ตอนนี้:</p>
                        </div>
                    </div>

                    <div class="space-y-2 sm:space-y-3">
                        @foreach ($availableCheckIns as $checkIn)
                            <div class="rounded-lg border border-blue-200 bg-white p-3 shadow-sm sm:p-4">
                                <div class="mb-2 sm:mb-3">
                                    <h3 class="text-sm font-semibold text-gray-900 sm:text-base">{{ $project->project_name }}</h3>
                                    <p class="text-xs text-gray-600 sm:text-sm">{{ $checkIn["date"]->date_title }}</p>
                                    <p class="text-xs text-gray-500 sm:text-sm">{{ $checkIn["time"]->time_title }}</p>
                                    <p class="text-xs text-blue-600 sm:text-sm">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ \Carbon\Carbon::parse($checkIn["time"]->time_start)->format("H:i") }} - {{ \Carbon\Carbon::parse($checkIn["time"]->time_end)->format("H:i") }}
                                    </p>
                                    @if ($project->project_seat_assign && $checkIn["userSeat"])
                                        <div class="mt-2 transform animate-pulse sm:mt-3">
                                            <div class="inline-flex items-center rounded-lg bg-gradient-to-r from-purple-500 to-purple-600 px-3 py-1.5 shadow-lg sm:px-4 sm:py-2">
                                                <i class="fas fa-chair mr-2 text-sm text-white sm:text-lg"></i>
                                                <div class="text-center">
                                                    <div class="text-xs font-medium text-purple-100">ที่นั่งของคุณ</div>
                                                    <div class="text-lg font-bold text-white sm:text-xl">{{ $checkIn["userSeat"]->seat_number }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($project->project_group_assign && $checkIn["userGroup"])
                                        <div class="mt-2 transform animate-pulse sm:mt-3">
                                            <div class="inline-flex items-center rounded-lg bg-gradient-to-r from-indigo-500 to-indigo-600 px-3 py-1.5 shadow-lg sm:px-4 sm:py-2">
                                                <i class="fas fa-users mr-2 text-sm text-white sm:text-lg"></i>
                                                <div class="text-center">
                                                    <div class="text-xs font-medium text-indigo-100">กลุ่มของคุณ</div>
                                                    <div class="text-lg font-bold text-white sm:text-xl">{{ $checkIn["userGroup"]->group }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @if ($checkIn["projectType"] === "attendance")
                                    <form class="attendance-form-top" action="{{ route("hrd.projects.attend.store", $project->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="time_id" value="{{ $checkIn["time"]->id }}">
                                        <button class="group relative inline-flex w-full items-center justify-center overflow-hidden rounded-lg bg-gradient-to-r from-green-500 to-green-600 px-4 py-2.5 text-white shadow-lg transition-all duration-300 hover:from-green-600 hover:to-green-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 active:scale-95 sm:px-6 sm:py-3" type="submit">
                                            <div class="absolute inset-0 bg-gradient-to-r from-green-400 to-green-500 opacity-0 transition-opacity duration-300 group-hover:opacity-20"></div>
                                            <i class="fas fa-user-check mr-2 text-sm sm:text-lg"></i>
                                            <span class="text-sm font-semibold sm:text-base">เช็คอินตอนนี้</span>
                                            <i class="fas fa-arrow-right ml-2 transition-transform duration-300 group-hover:translate-x-1"></i>
                                        </button>
                                    </form>
                                @else
                                    <form class="stamp-form-top" action="{{ route("hrd.projects.stamp.store", [$project->id, $checkIn["userRegistration"]->id]) }}" method="POST">
                                        @csrf
                                        <button class="group relative inline-flex w-full items-center justify-center overflow-hidden rounded-lg bg-gradient-to-r from-green-500 to-green-600 px-4 py-2.5 text-white shadow-lg transition-all duration-300 hover:from-green-600 hover:to-green-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 active:scale-95 sm:px-6 sm:py-3" type="submit">
                                            <div class="absolute inset-0 bg-gradient-to-r from-green-400 to-green-500 opacity-0 transition-opacity duration-300 group-hover:opacity-20"></div>
                                            <i class="fas fa-stamp mr-2 text-sm sm:text-lg"></i>
                                            <span class="text-sm font-semibold sm:text-base">เช็คอินตอนนี้</span>
                                            <i class="fas fa-arrow-right ml-2 transition-transform duration-300 group-hover:translate-x-1"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Main Content -->
            <div>
                <!-- Project Header -->
                <div class="mb-4 rounded-lg bg-white p-4 shadow-sm sm:p-6">
                    <div class="mb-3 flex items-start justify-between">
                        @if ($registrationData["statusBadge"])
                            <span class="bg-{{ $registrationData["statusBadge"]["type"] }}-100 text-{{ $registrationData["statusBadge"]["type"] }}-800 inline-flex items-center rounded-full px-2 py-1 text-xs font-medium sm:px-3 sm:text-sm">
                                <i class="{{ $registrationData["statusBadge"]["icon"] }} mr-1{{ $registrationData["statusBadge"]["type"] === "blue" ? " text-blue-400" : "" }}"{{ $registrationData["statusBadge"]["type"] === "blue" ? ' style="font-size: 6px;"' : "" }}></i>
                                {{ $registrationData["statusBadge"]["text"] }}
                            </span>
                        @endif
                    </div>

                    <h1 class="mb-3 text-2xl font-bold text-gray-900 sm:text-3xl">{{ $project->project_name }}</h1>

                    @if ($project->project_detail)
                        <div class="prose prose-gray max-w-none">
                            <p class="text-sm leading-relaxed text-gray-700 sm:text-base">{{ $project->project_detail }}</p>
                        </div>
                    @endif

                    <!-- User Group Information -->
                    @if ($project->project_group_assign)
                        @php
                            $userGroup = \App\Models\HrGroup::where("project_id", $project->id)
                                ->where("user_id", auth()->id())
                                ->first();
                        @endphp
                        @if ($userGroup)
                            <div class="mt-4">
                                <div class="inline-flex items-center rounded-lg bg-gradient-to-r from-indigo-500 to-indigo-600 px-4 py-3 shadow-lg">
                                    <i class="fas fa-users mr-3 text-lg text-white"></i>
                                    <div class="text-center">
                                        <div class="text-sm font-medium text-indigo-100">กลุ่มของคุณ</div>
                                        <div class="text-xl font-bold text-white">{{ $userGroup->group }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Merged Registrations & Schedule Section -->
                @if ($registrationData["userRegistrations"]->count() > 0 || $project->project_type === "attendance")
                    <div class="mb-4 rounded-lg bg-white p-4 shadow-sm sm:p-6">
                        <h2 class="mb-3 text-lg font-semibold text-gray-900 sm:text-xl">
                            <i class="fas fa-calendar-check mr-2 text-green-500"></i>
                            @if ($project->project_type === "attendance")
                                ตารางการเข้าร่วมโปรแกรม
                            @else
                                การลงทะเบียนและตารางการเข้าร่วม
                            @endif
                        </h2>

                        @if ($project->isFull())
                            <!-- Project Full Notice -->
                            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 sm:p-4">
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation-triangle mr-2 mt-1 text-red-500 sm:mr-3"></i>
                                    <div>
                                        <h3 class="font-medium text-red-800">โปรเจกต์เต็มแล้ว</h3>
                                        <p class="mt-1 text-sm text-red-700">
                                            ขออภัย โปรเจกต์นี้เต็มแล้ว ไม่สามารถลงทะเบียนเพิ่มเติมได้
                                            @if ($project->project_type === "single")
                                                ทุกช่วงเวลามีผู้ลงทะเบียนครบแล้ว
                                            @elseif($project->project_type === "multiple")
                                                ทุกช่วงเวลามีผู้ลงทะเบียนครบแล้ว
                                            @endif
                                        </p>
                                        <div class="mt-2 text-xs text-red-600">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            จำนวนผู้ลงทะเบียนทั้งหมด: {{ $project->activeAttends->count() }} คน
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="space-y-4">
                            @foreach ($project->dates->where("date_delete", false) as $date)
                                <div class="rounded-lg border border-gray-200 p-3 sm:p-4">
                                    <div class="mb-3 flex flex-col justify-between sm:flex-row sm:items-center">
                                        <h3 class="text-base font-medium text-gray-900 sm:text-lg">{{ $date->date_title }}</h3>
                                        <span class="mt-1 text-sm text-gray-500 sm:mt-0">{{ $date->date_datetime->format("l, d M Y") }}</span>
                                    </div>

                                    @if ($date->date_detail)
                                        <p class="mb-3 text-sm text-gray-600">{{ $date->date_detail }}</p>
                                    @endif

                                    @if ($date->date_location)
                                        <div class="mb-3 flex items-center text-sm text-gray-600">
                                            <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                                            {{ $date->date_location }}
                                        </div>
                                    @endif

                                    <!-- Time Slots -->
                                    @if ($date->times->where("time_delete", false)->count() > 0)
                                        <div class="mt-4">
                                            <h4 class="mb-2 text-sm font-medium text-gray-700">ช่วงเวลา:</h4>
                                            <div class="space-y-3">
                                                @foreach ($date->times->where("time_delete", false) as $time)
                                                    @php
                                                        $timeState = $registrationData["timeSlotStates"][$time->id] ?? [];
                                                        $userRegistration = $registrationData["userRegistrations"]->where("time_id", $time->id)->first();
                                                        $hasAttended = $timeState["hasAttended"] ?? false;
                                                        $userRegistered = $timeState["userRegistered"] ?? false;
                                                        $showLinks = $timeState["showLinks"] ?? false;
                                                        $canStamp = $timeState["canStamp"] ?? false;
                                                        $canCheckIn = $timeState["canCheckIn"] ?? false;
                                                        $timeSlotMessage = $timeState["timeSlotMessage"] ?? null;
                                                        $attendanceRecord = $timeState["attendanceRecord"] ?? null;
                                                    @endphp

                                                    <div class="{{ $hasAttended ? "bg-blue-50 border-blue-200" : ($userRegistered ? "bg-green-50 border-green-200" : "bg-gray-50") }} rounded-lg border border-gray-100 p-3">
                                                        <div class="mb-2 flex flex-col justify-between sm:flex-row sm:items-center">
                                                            <span class="flex items-center font-medium text-gray-900">
                                                                @if ($hasAttended)
                                                                    <i class="fas fa-check-circle mr-2 text-blue-500"></i>
                                                                @elseif($userRegistered)
                                                                    <i class="fas fa-user-check mr-2 text-green-500"></i>
                                                                @endif
                                                                {{ $time->time_title }}
                                                            </span>
                                                            @if ($time->time_limit && $project->project_type !== "attendance")
                                                                @php
                                                                    $registered = $time->activeAttends->count();
                                                                    $available = $time->time_max - $registered;
                                                                @endphp
                                                                <span class="{{ $available > 0 ? "bg-green-100 text-green-800" : "bg-red-100 text-red-800" }} mt-1 rounded-full px-2 py-1 text-xs sm:mt-0">
                                                                    {{ $available > 0 ? "เหลือ {$available} ที่" : "เต็ม" }}
                                                                </span>
                                                            @endif
                                                        </div>

                                                        <div class="text-sm text-gray-600">
                                                            <div class="flex items-center">
                                                                <i class="fas fa-clock mr-1"></i>
                                                                {{ \Carbon\Carbon::parse($time->time_start)->format("H:i") }} -
                                                                {{ \Carbon\Carbon::parse($time->time_end)->format("H:i") }}
                                                            </div>

                                                            @if ($time->time_detail)
                                                                <p class="mt-1 text-xs text-gray-500">{{ $time->time_detail }}</p>
                                                            @endif

                                                            <!-- Seat Assignment Info -->
                                                            @if ($project->project_seat_assign)
                                                                @php
                                                                    $userSeat = $time
                                                                        ->seats()
                                                                        ->where("user_id", auth()->id())
                                                                        ->where("seat_delete", false)
                                                                        ->first();
                                                                @endphp
                                                                @if ($userSeat)
                                                                    <div class="mt-3 transform animate-pulse">
                                                                        <div class="inline-flex items-center rounded-lg bg-gradient-to-r from-purple-500 to-purple-600 px-3 py-2 shadow-lg">
                                                                            <i class="fas fa-chair mr-2 text-lg text-white"></i>
                                                                            <div class="text-center">
                                                                                <div class="text-xs font-medium text-purple-100">ที่นั่งของคุณ</div>
                                                                                <div class="text-lg font-bold text-white">{{ $userSeat->seat_number }}</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endif

                                                            <!-- Group Assignment Info -->
                                                            @if ($project->project_group_assign)
                                                                @php
                                                                    $userGroup = \App\Models\HrGroup::where("project_id", $project->id)
                                                                        ->where("user_id", auth()->id())
                                                                        ->first();
                                                                @endphp
                                                                @if ($userGroup)
                                                                    <div class="mt-3 transform animate-pulse">
                                                                        <div class="inline-flex items-center rounded-lg bg-gradient-to-r from-indigo-500 to-indigo-600 px-3 py-2 shadow-lg">
                                                                            <i class="fas fa-users mr-2 text-lg text-white"></i>
                                                                            <div class="text-center">
                                                                                <div class="text-xs font-medium text-indigo-100">กลุ่มของคุณ</div>
                                                                                <div class="text-lg font-bold text-white">{{ $userGroup->group }}</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endif

                                                            <!-- Registration Status -->
                                                            @if ($userRegistration)
                                                                <div class="mt-2 border-t border-gray-200 pt-2">
                                                                    @if ($userRegistration->attend_datetime)
                                                                        <div class="flex items-center text-xs font-medium text-blue-600">
                                                                            <i class="fas fa-check-circle mr-1"></i>
                                                                            เข้าร่วมแล้ว: {{ $userRegistration->attend_datetime->format("d M Y, H:i") }}
                                                                        </div>
                                                                    @else
                                                                        <div class="text-xs text-green-600">
                                                                            <i class="fas fa-user-check mr-1"></i>
                                                                            ลงทะเบียนแล้ว: {{ $userRegistration->created_at->format("d M Y, H:i") }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endif

                                                            <!-- Time-specific Links -->
                                                            @if ($project->links->where("link_delete", false)->count() > 0 && $showLinks)
                                                                <div class="mt-3 border-t border-gray-200 pt-3">
                                                                    <div class="mb-2 text-xs font-medium text-blue-700">
                                                                        <i class="fas fa-link mr-1"></i>
                                                                        ทรัพยากรสำหรับเซสชัน
                                                                    </div>
                                                                    <div class="space-y-2">
                                                                        @foreach ($project->links->where("link_delete", false) as $link)
                                                                            @php
                                                                                $linkAvailable = true;
                                                                                if ($link->link_limit) {
                                                                                    $now = now();
                                                                                    $linkAvailable = (!$link->link_time_start || $now >= $link->link_time_start) && (!$link->link_time_end || $now <= $link->link_time_end);
                                                                                }
                                                                            @endphp

                                                                            @if ($linkAvailable)
                                                                                <div class="flex items-center justify-between rounded border border-blue-200 bg-blue-50 p-2">
                                                                                    <span class="text-xs font-medium text-blue-800">{{ $link->link_name }}</span>
                                                                                    <a class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800" href="{{ $link->link_url }}" target="_blank">
                                                                                        <i class="fas fa-external-link-alt mr-1"></i>
                                                                                        เปิด
                                                                                    </a>
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            <!-- Action Buttons -->
                                                            @if ($userRegistration && !$hasAttended)
                                                                <div class="mt-2 text-xs font-medium text-green-600">
                                                                    <i class="fas fa-user-check mr-1"></i>
                                                                    คุณลงทะเบียนสำหรับเซสชันนี้แล้ว
                                                                </div>

                                                                @if ($canStamp)
                                                                    <div class="mt-3">
                                                                        <form class="stamp-form" action="{{ route("hrd.projects.stamp.store", [$project->id, $userRegistration->id]) }}" method="POST">
                                                                            @csrf
                                                                            <button class="group relative inline-flex w-full items-center justify-center overflow-hidden rounded-lg bg-gradient-to-r from-green-500 to-green-600 px-4 py-2 text-white shadow-lg transition-all duration-300 hover:from-green-600 hover:to-green-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 active:scale-95" type="submit">
                                                                                <div class="absolute inset-0 bg-gradient-to-r from-green-400 to-green-500 opacity-0 transition-opacity duration-300 group-hover:opacity-20"></div>
                                                                                <i class="fas fa-stamp mr-2 text-lg"></i>
                                                                                <span class="text-sm font-semibold">เช็คอินตอนนี้</span>
                                                                                <i class="fas fa-arrow-right ml-2 transition-transform duration-300 group-hover:translate-x-1"></i>
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                @endif

                                                                @if ($project->project_type === "single" || $project->project_type === "multiple" || $project->project_type === "attendance")
                                                                    @php
                                                                        $today = now()->format("Y-m-d");
                                                                        $dateIsToday = $date->date_datetime->format("Y-m-d") === $today;
                                                                        $canUnregister = $project->project_register_today || !$dateIsToday;

                                                                        // For all project types, allow unregistration even if attended
                                                                        $canUnregister = true;
                                                                    @endphp

                                                                    @if ($canUnregister)
                                                                        <div class="mt-3">
                                                                            <button class="unregister-trigger w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" data-registration-id="{{ $userRegistration->id }}" type="button">
                                                                                <i class="fas fa-user-times mr-2 text-gray-500"></i>
                                                                                ยกเลิกการลงทะเบียน
                                                                            </button>
                                                                        </div>
                                                                    @else
                                                                        <div class="mt-2 text-xs text-red-600">
                                                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                                                            ไม่สามารถยกเลิกการลงทะเบียนในวันเดียวกันได้
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            @elseif($canCheckIn)
                                                                <div class="mt-3">
                                                                    <form class="attendance-form" action="{{ route("hrd.projects.attend.store", $project->id) }}" method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="time_id" value="{{ $time->id }}">
                                                                        <button class="group relative inline-flex w-full items-center justify-center overflow-hidden rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-2 text-white shadow-lg transition-all duration-300 hover:from-blue-600 hover:to-blue-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:scale-95" type="submit">
                                                                            <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-blue-500 opacity-0 transition-opacity duration-300 group-hover:opacity-20"></div>
                                                                            <i class="fas fa-user-check mr-2 text-lg"></i>
                                                                            <span class="text-sm font-semibold">เช็คอินตอนนี้</span>
                                                                            <i class="fas fa-arrow-right ml-2 transition-transform duration-300 group-hover:translate-x-1"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            @endif

                                                            @if ($timeSlotMessage)
                                                                <div class="mt-2 text-xs text-gray-500">
                                                                    <i class="fas fa-clock mr-1"></i>
                                                                    {{ $timeSlotMessage }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Registration Section -->
                @if ($registrationData["showRegisterForm"])
                    <div class="mb-4 rounded-lg bg-white p-4 shadow-sm sm:p-6" id="registration">
                        @if ($project->isFull())
                            <!-- Project Full Notice in Registration -->
                            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 sm:p-4">
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation-triangle mr-2 mt-1 text-red-500 sm:mr-3"></i>
                                    <div>
                                        <h3 class="font-medium text-red-800">ไม่สามารถลงทะเบียนได้</h3>
                                        <p class="mt-1 text-sm text-red-700">
                                            โปรเจกต์นี้เต็มแล้ว ไม่สามารถลงทะเบียนเพิ่มเติมได้
                                            @if ($project->project_type === "single")
                                                ทุกช่วงเวลามีผู้ลงทะเบียนครบแล้ว
                                            @elseif($project->project_type === "multiple")
                                                ทุกช่วงเวลามีผู้ลงทะเบียนครบแล้ว
                                            @endif
                                        </p>
                                        <div class="mt-2 text-xs text-red-600">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            จำนวนผู้ลงทะเบียนทั้งหมด: {{ $project->activeAttends->count() }} คน
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            @if ($registrationData["showSameDayNotice"])
                                <!-- Same-day Registration Info -->
                                <div class="mb-4 rounded-lg border border-yellow-200 bg-yellow-50 p-3 sm:p-4">
                                    <div class="flex items-start">
                                        <i class="fas fa-info-circle mr-2 mt-1 text-yellow-500"></i>
                                        <div>
                                            <h3 class="font-medium text-yellow-800">หมายเหตุการลงทะเบียน</h3>
                                            <p class="mt-1 text-sm text-yellow-700">
                                                ไม่สามารถลงทะเบียนในวันเดียวกันได้ เซสชันของวันนี้ไม่สามารถลงทะเบียนได้ แต่คุณสามารถลงทะเบียนสำหรับวันที่ในอนาคตได้
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($registrationData["canReselect"] && $registrationData["userRegistrations"]->count() > 0)
                                <!-- Reselection Notice -->
                                <div class="mb-4 rounded-lg border border-orange-200 bg-orange-50 p-3 sm:p-4">
                                    <div class="flex items-start">
                                        <i class="fas fa-edit mr-2 mt-1 text-orange-500"></i>
                                        <div>
                                            <h3 class="font-medium text-orange-800">สามารถเลือกใหม่ได้</h3>
                                            <p class="mt-1 text-sm text-orange-700">
                                                คุณลงทะเบียนสำหรับ {{ $registrationData["userRegistrations"]->count() }} เซสชันแล้ว
                                                คุณสามารถล้างการลงทะเบียนปัจจุบันและเลือกช่วงเวลาใหม่ได้
                                            </p>
                                        </div>
                                    </div>
                                    <form class="reselect-form mt-3" action="{{ route("hrd.projects.reselect", $project->id) }}" method="POST">
                                        @csrf
                                        @method("DELETE")
                                        <button class="group relative inline-flex items-center overflow-hidden rounded-lg bg-gradient-to-r from-orange-500 to-orange-600 px-4 py-2 text-white shadow-lg transition-all duration-300 hover:from-orange-600 hover:to-orange-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 active:scale-95" type="submit">
                                            <div class="absolute inset-0 bg-gradient-to-r from-orange-400 to-orange-500 opacity-0 transition-opacity duration-300 group-hover:opacity-20"></div>
                                            <i class="fas fa-redo mr-2 text-lg"></i>
                                            <span class="text-sm font-semibold">ล้างการลงทะเบียนและเลือกใหม่</span>
                                            <i class="fas fa-arrow-right ml-2 transition-transform duration-300 group-hover:translate-x-1"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif

                            <!-- Registration Information and Form -->
                            <div class="grid grid-cols-1 gap-6">
                                <!-- Registration Form -->
                                <div class="mb-4">
                                    <h2 class="mb-2 text-lg font-semibold text-gray-900 sm:text-xl">
                                        <i class="fas fa-user-plus mr-2 text-blue-500"></i>
                                        @if ($registrationData["canReselect"] && $registrationData["userRegistrations"]->count() > 0)
                                            เลือกการลงทะเบียนใหม่
                                        @else
                                            ลงทะเบียนเข้าร่วมโปรแกรม & ข้อมูลการลงทะเบียน
                                        @endif
                                    </h2>

                                    <div class="space-y-3">
                                        <p class="text-sm text-gray-600 sm:text-base">
                                            รายละเอียดและเงื่อนไขการลงทะเบียนสำหรับโปรแกรมนี้
                                        </p>

                                        @if ($project->project_type === "attendance")
                                            <div class="rounded-lg border border-purple-200 bg-purple-50 p-3">
                                                <div class="mb-2 flex items-center text-purple-800">
                                                    <i class="fas fa-info-circle mr-2"></i>
                                                    <span class="font-medium">โปรแกรมเข้าร่วม</span>
                                                </div>
                                                <p class="text-sm text-purple-700">
                                                    ไม่ต้องลงทะเบียน เพียงเข้าร่วมเซสชันตามตารางเวลา
                                                </p>
                                            </div>
                                        @else
                                            <div>
                                                <label class="text-sm font-medium text-gray-700">ช่วงเวลาการลงทะเบียน</label>
                                                <div class="mt-1 text-sm text-gray-900">
                                                    {{ $project->project_start_register->format("d M Y, H:i") }}
                                                    <br>
                                                    <span class="text-gray-500">ถึง</span>
                                                    <br>
                                                    {{ $project->project_end_register->format("d M Y, H:i") }}
                                                </div>
                                            </div>

                                            <div>
                                                <label class="text-sm font-medium text-gray-700">ประเภทการลงทะเบียน</label>
                                                <div class="mt-1 text-sm text-gray-900">
                                                    @if ($project->project_type === "single")
                                                        <i class="fas fa-check-circle mr-1 text-blue-500"></i>
                                                        เลือกช่วงเวลาหนึ่ง
                                                    @elseif($project->project_type === "multiple")
                                                        <i class="fas fa-check-circle mr-1 text-green-500"></i>
                                                        เลือกหลายช่วงเวลา
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                        @if ($project->project_seat_assign)
                                            <div>
                                                <div class="flex items-center text-sm text-blue-600">
                                                    <i class="fas fa-chair mr-1"></i>
                                                    เปิดใช้งานการจัดที่นั่ง
                                                </div>
                                            </div>
                                        @endif

                                        @if ($project->project_group_assign)
                                            <div>
                                                <div class="flex items-center text-sm text-indigo-600">
                                                    <i class="fas fa-users mr-1"></i>
                                                    เปิดใช้งานการจัดกลุ่ม
                                                </div>
                                            </div>
                                        @endif

                                        <div>
                                            @if ($project->project_register_today)
                                                <div class="flex items-center text-sm text-green-600">
                                                    <i class="fas fa-calendar-day mr-1"></i>
                                                    เปิดให้ลงทะเบียนในวันที่มีการจัดหลักสูตร
                                                </div>
                                            @else
                                                <div class="flex items-center text-sm text-red-600">
                                                    <i class="fas fa-ban mr-1"></i>
                                                    ไม่เปิดให้ลงทะเบียนในวันที่มีการจัดหลักสูตร
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <p class="text-sm text-gray-600 sm:text-base">
                                        @if ($project->project_type === "single")
                                            กรุณาเลือกช่วงเวลาหนึ่งที่เหมาะกับคุณ
                                        @elseif($project->project_type === "multiple")
                                            คุณสามารถลงทะเบียนสำหรับหลายช่วงเวลา เลือกเซสชันทั้งหมดที่คุณต้องการเข้าร่วม
                                        @endif
                                        @if ($project->project_type === "multiple" && $registrationData["userRegistrations"]->count() > 0)
                                            <br><strong>หมายเหตุ:</strong> คุณสามารถเพิ่มเซสชันเพิ่มเติมในการลงทะเบียนปัจจุบันของคุณได้
                                        @endif
                                    </p>
                                </div>

                                <form id="registrationForm" action="{{ route("hrd.projects.register.store", $project->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="project_type" value="{{ $project->project_type }}">

                                    <div class="space-y-4">
                                        @foreach ($registrationTimeSlots as $dateSlot)
                                            <div class="rounded-lg border border-gray-200 p-3 sm:p-4">
                                                <div class="mb-3 flex flex-col justify-between sm:flex-row sm:items-center">
                                                    <h3 class="text-base font-medium text-gray-900 sm:text-lg">{{ $dateSlot["date"]->date_title }}</h3>
                                                    <span class="mt-1 text-sm text-gray-500 sm:mt-0">{{ $dateSlot["date"]->date_datetime->format("l, d M Y") }}</span>
                                                </div>

                                                @if ($dateSlot["date"]->date_detail)
                                                    <div class="mb-3 rounded-lg bg-gray-50 p-3">
                                                        <p class="text-sm text-gray-700">{{ $dateSlot["date"]->date_detail }}</p>
                                                    </div>
                                                @endif

                                                <div class="space-y-2">
                                                    @foreach ($dateSlot["slots"] as $slot)
                                                        <div class="flex items-center justify-between rounded-lg border border-gray-200 p-3 transition-colors hover:bg-gray-50">
                                                            <label class="flex-1 cursor-pointer" for="time_{{ $slot["time"]->id }}">
                                                                <div class="flex items-center justify-between">
                                                                    <div>
                                                                        <h4 class="font-medium text-gray-900">{{ $slot["time"]->time_title }}</h4>
                                                                        <p class="text-sm text-gray-600">
                                                                            {{ \Carbon\Carbon::parse($slot["time"]->time_start)->format("H:i") }} - {{ \Carbon\Carbon::parse($slot["time"]->time_end)->format("H:i") }}
                                                                        </p>
                                                                    </div>
                                                                    <div class="text-right">
                                                                        @if ($slot["isLimited"])
                                                                            <span class="text-xs text-gray-500">
                                                                                {{ $slot["currentRegistrations"] }}/{{ $slot["time"]->time_max }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                @if ($slot["userSeat"])
                                                                    <div class="mt-2">
                                                                        <div class="inline-flex items-center rounded-lg bg-gradient-to-r from-purple-500 to-purple-600 px-3 py-1 shadow-md">
                                                                            <i class="fas fa-chair mr-2 text-white"></i>
                                                                            <span class="text-sm font-bold text-white">ที่นั่ง: {{ $slot["userSeat"]->seat_number }}</span>
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
                                                                        <div class="mt-2">
                                                                            <div class="inline-flex items-center rounded-lg bg-gradient-to-r from-indigo-500 to-indigo-600 px-3 py-1 shadow-md">
                                                                                <i class="fas fa-users mr-2 text-white"></i>
                                                                                <span class="text-sm font-bold text-white">กลุ่ม: {{ $userGroup->group }}</span>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            </label>

                                                            <div class="ml-4">
                                                                @if ($slot["isFull"])
                                                                    <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-800">
                                                                        <i class="fas fa-times-circle mr-1"></i>
                                                                        เต็มแล้ว
                                                                    </span>
                                                                @else
                                                                    @if ($project->project_type === "single")
                                                                        <input class="h-4 w-4 text-blue-600 focus:ring-blue-500" id="time_{{ $slot["time"]->id }}" name="time_ids[]" type="radio" value="{{ $slot["time"]->id }}" required>
                                                                    @else
                                                                        <input class="h-4 w-4 text-blue-600 focus:ring-blue-500" id="time_{{ $slot["time"]->id }}" name="time_ids[]" type="checkbox" value="{{ $slot["time"]->id }}">
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- User Information -->
                                    <div class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
                                        <h3 class="mb-3 text-lg font-semibold text-gray-900">
                                            <i class="fas fa-user mr-2 text-blue-500"></i>
                                            ข้อมูลผู้เข้าร่วม
                                        </h3>
                                        <div class="grid grid-cols-1 gap-3 text-sm sm:grid-cols-2">
                                            <div>
                                                <label class="font-medium text-gray-700">รหัสพนักงาน:</label>
                                                <div class="text-gray-900">{{ auth()->user()->userid }}</div>
                                            </div>
                                            <div>
                                                <label class="font-medium text-gray-700">ชื่อ:</label>
                                                <div class="text-gray-900">{{ auth()->user()->name }}</div>
                                            </div>
                                            <div>
                                                <label class="font-medium text-gray-700">ตำแหน่ง:</label>
                                                <div class="text-gray-900">{{ auth()->user()->position ?? "ไม่ระบุ" }}</div>
                                            </div>
                                            <div>
                                                <label class="font-medium text-gray-700">แผนก:</label>
                                                <div class="text-gray-900">{{ auth()->user()->department ?? "ไม่ระบุ" }}</div>
                                            </div>
                                            @if ($project->project_group_assign)
                                                @php
                                                    $userGroup = \App\Models\HrGroup::where("project_id", $project->id)
                                                        ->where("user_id", auth()->id())
                                                        ->first();
                                                @endphp
                                                @if ($userGroup)
                                                    <div>
                                                        <label class="font-medium text-gray-700">กลุ่ม:</label>
                                                        <div class="text-gray-900">{{ $userGroup->group }}</div>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-6">
                                        <button class="group relative inline-flex w-full items-center justify-center overflow-hidden rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-3 text-white shadow-lg transition-all duration-300 hover:from-blue-600 hover:to-blue-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:scale-95 sm:w-auto" type="submit">
                                            <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-blue-500 opacity-0 transition-opacity duration-300 group-hover:opacity-20"></div>
                                            <i class="fas fa-user-plus mr-3 text-lg"></i>
                                            <span class="text-base font-semibold">ลงทะเบียน</span>
                                            <i class="fas fa-arrow-right ml-2 transition-transform duration-300 group-hover:translate-x-1"></i>
                                        </button>
                                    </div>
                                </form>

                            </div>
                        @endif
                    </div>
                @endif

            </div>

            <!-- Sidebar -->
            <div>
                <!-- Project Links -->
                @if ($project->links->count() > 0)
                    <div class="rounded-lg bg-white p-4 shadow-sm sm:p-6">
                        <h3 class="mb-3 text-lg font-semibold text-gray-900">ทรัพยากรที่เกี่ยวข้อง</h3>
                        <div class="space-y-2">
                            @foreach ($project->links as $link)
                                <div class="flex items-center justify-between rounded border border-blue-200 bg-blue-50 p-2">
                                    <span class="text-xs font-medium text-blue-800">{{ $link->link_name }}</span>
                                    <a class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800" href="{{ $link->link_url }}" target="_blank">
                                        <i class="fas fa-external-link-alt mr-1"></i>
                                        เปิด
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registrationForm');

            if (form) {
                // Handle single vs multiple selection for single type projects
                const projectType = document.querySelector('input[name="project_type"]');

                if (projectType && projectType.value === 'single') {
                    const radioButtons = document.querySelectorAll('input[name="time_ids[]"][type="radio"]');

                    radioButtons.forEach(radio => {
                        radio.addEventListener('change', function() {
                            // Uncheck all other radio buttons when one is selected
                            radioButtons.forEach(otherRadio => {
                                if (otherRadio !== this) {
                                    otherRadio.checked = false;
                                }
                            });
                        });
                    });
                }

                // Form submission with confirmation
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const selectedTimes = document.querySelectorAll('input[name="time_ids[]"]:checked');

                    if (selectedTimes.length === 0) {
                        Swal.fire({
                            title: 'ไม่มีการเลือก',
                            text: 'กรุณาเลือกช่วงเวลาอย่างน้อยหนึ่งช่วง',
                            icon: 'warning',
                            confirmButtonText: 'ตกลง',
                            confirmButtonColor: '#f59e0b'
                        });
                        return;
                    }

                    // Build confirmation message
                    let selectionText = '';
                    selectedTimes.forEach((input, index) => {
                        const timeSlotContainer = input.closest('.flex.items-center.justify-between');
                        const timeSlot = timeSlotContainer?.querySelector('.font-medium')?.textContent || 'Unknown Session';
                        const timeElement = timeSlotContainer?.querySelector('.fa-clock')?.parentNode;
                        const timeSchedule = timeElement ? timeElement.textContent.trim() : '';
                        selectionText += `<div class="text-left mb-1">${index + 1}. ${timeSlot} (${timeSchedule})</div>`;
                    });

                    Swal.fire({
                        title: 'ยืนยันการลงทะเบียน',
                        html: `
                            <div class="text-left">
                                <p class="mb-3"><strong>โปรแกรม:</strong> {{ $project->project_name }}</p>
                                <p class="mb-2"><strong>เซสชันที่เลือก:</strong></p>
                                ${selectionText}
                            </div>
                            <p class="mt-4 text-sm text-gray-600">คุณแน่ใจหรือไม่ที่จะลงทะเบียนสำหรับ ${selectedTimes.length > 1 ? 'เซสชันเหล่านี้' : 'เซสชันนี้'}?</p>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3b82f6',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'ใช่, ลงทะเบียน',
                        cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            }

            // Handle attendance form submissions
            const attendanceForms = document.querySelectorAll('.attendance-form');
            attendanceForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Get session details with better error handling
                    const sessionCard = this.closest('.border-gray-100, .border-blue-200, .border-green-200');
                    if (!sessionCard) {
                        console.error('Could not find session card for attendance form');
                        return;
                    }

                    const timeSlot = sessionCard.querySelector('.font-medium')?.textContent || 'Unknown Session';
                    const timeElement = sessionCard.querySelector('.fa-clock')?.parentNode;
                    const timeSchedule = timeElement ? timeElement.textContent.trim() : '';

                    Swal.fire({
                        title: 'ยืนยันการเช็คอิน',
                        html: `
                            <div class="text-left">
                                <p class="mb-3"><strong>โปรแกรม:</strong> {{ $project->project_name }}</p>
                                <p class="mb-2"><strong>เซสชัน:</strong> ${timeSlot}</p>
                                ${timeSchedule ? `<p class="mb-3"><strong>เวลา:</strong> ${timeSchedule}</p>` : ''}
                            </div>
                            <p class="mt-4 text-sm text-gray-600">คุณแน่ใจหรือไม่ที่จะเช็คอินสำหรับเซสชันนี้?</p>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3b82f6',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'ใช่, เช็คอิน',
                        cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });

            // Handle top attendance form submissions (for attendance projects)
            const topAttendanceForms = document.querySelectorAll('.attendance-form-top');
            topAttendanceForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Get session details from the top check-in card
                    const sessionCard = this.closest('.rounded-lg.border.border-blue-200');
                    if (!sessionCard) {
                        console.error('Could not find session card for top attendance form');
                        return;
                    }

                    const projectName = sessionCard.querySelector('h3')?.textContent || 'Unknown Project';
                    const dateTitle = sessionCard.querySelector('p:nth-of-type(1)')?.textContent || '';
                    const timeTitle = sessionCard.querySelector('p:nth-of-type(2)')?.textContent || '';
                    const timeSchedule = sessionCard.querySelector('.text-blue-600')?.textContent || '';

                    Swal.fire({
                        title: 'ยืนยันการเช็คอิน',
                        html: `
                            <div class="text-left">
                                <p class="mb-3"><strong>โปรแกรม:</strong> ${projectName}</p>
                                ${dateTitle ? `<p class="mb-2"><strong>วันที่:</strong> ${dateTitle}</p>` : ''}
                                ${timeTitle ? `<p class="mb-2"><strong>เซสชัน:</strong> ${timeTitle}</p>` : ''}
                                ${timeSchedule ? `<p class="mb-3"><strong>เวลา:</strong> ${timeSchedule}</p>` : ''}
                            </div>
                            <p class="mt-4 text-sm text-gray-600">คุณแน่ใจหรือไม่ที่จะเช็คอินสำหรับเซสชันนี้?</p>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#16a34a',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'ใช่, เช็คอิน',
                        cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });

            // Handle top stamp form submissions (for registered projects)
            const topStampForms = document.querySelectorAll('.stamp-form-top');
            topStampForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Get session details from the top check-in card
                    const sessionCard = this.closest('.rounded-lg.border.border-blue-200');
                    if (!sessionCard) {
                        console.error('Could not find session card for top stamp form');
                        return;
                    }

                    const projectName = sessionCard.querySelector('h3')?.textContent || 'Unknown Project';
                    const dateTitle = sessionCard.querySelector('p:nth-of-type(1)')?.textContent || '';
                    const timeTitle = sessionCard.querySelector('p:nth-of-type(2)')?.textContent || '';
                    const timeSchedule = sessionCard.querySelector('.text-blue-600')?.textContent || '';

                    Swal.fire({
                        title: 'ยืนยันการเช็คอิน',
                        html: `
                            <div class="text-left">
                                <p class="mb-3"><strong>โปรแกรม:</strong> ${projectName}</p>
                                ${dateTitle ? `<p class="mb-2"><strong>วันที่:</strong> ${dateTitle}</p>` : ''}
                                ${timeTitle ? `<p class="mb-2"><strong>เซสชัน:</strong> ${timeTitle}</p>` : ''}
                                ${timeSchedule ? `<p class="mb-3"><strong>เวลา:</strong> ${timeSchedule}</p>` : ''}
                            </div>
                            <p class="mt-4 text-sm text-gray-600">คุณแน่ใจหรือไม่ที่จะเช็คอินสำหรับเซสชันที่ลงทะเบียนแล้วนี้?</p>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3b82f6',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'ใช่, เช็คอิน',
                        cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });

            // Handle stamp form submissions (for registered users)
            const stampForms = document.querySelectorAll('.stamp-form');
            stampForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Get session details from the card - with better error handling
                    const sessionCard = this.closest('.border-green-200, .border-blue-200, .border-gray-100');
                    if (!sessionCard) {
                        console.error('Could not find session card');
                        return;
                    }

                    const sessionTitle = sessionCard.querySelector('.font-medium')?.textContent || 'Unknown Session';
                    const sessionDate = sessionCard.querySelector('.text-green-600, .text-blue-600')?.textContent || '';
                    const timeElement = sessionCard.querySelector('.fa-clock')?.parentNode;
                    const sessionTimeText = timeElement ? timeElement.textContent.trim() : '';

                    Swal.fire({
                        title: 'ยืนยันการเช็คอิน',
                        html: `
                            <div class="text-left">
                                <p class="mb-3"><strong>โปรแกรม:</strong> {{ $project->project_name }}</p>
                                <p class="mb-2"><strong>เซสชัน:</strong> ${sessionTitle}</p>
                                ${sessionDate ? `<p class="mb-2"><strong>วันที่:</strong> ${sessionDate}</p>` : ''}
                                ${sessionTimeText ? `<p class="mb-3"><strong>เวลา:</strong> ${sessionTimeText}</p>` : ''}
                            </div>
                            <p class="mt-4 text-sm text-gray-600">คุณแน่ใจหรือไม่ที่จะเช็คอินสำหรับเซสชันที่ลงทะเบียนแล้วนี้?</p>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3b82f6',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'ใช่, เช็คอิน',
                        cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });

            // Handle reselect form submissions
            const reselectForms = document.querySelectorAll('.reselect-form');
            reselectForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'ล้างการลงทะเบียน?',
                        html: `
                            <div class="text-left">
                                <p class="mb-3"><strong>โปรแกรม:</strong> {{ $project->project_name }}</p>
                                <p class="mb-3 text-orange-600"><strong>คำเตือน:</strong> การดำเนินการนี้จะลบการลงทะเบียนปัจจุบันของคุณ</p>
                            </div>
                            <p class="mt-4 text-sm text-gray-600">หลังจากล้างแล้ว คุณสามารถลงทะเบียนใหม่ด้วยการเลือกช่วงเวลาใหม่ คุณแน่ใจหรือไม่ที่จะดำเนินการต่อ?</p>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#f59e0b',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'ใช่, ล้างการลงทะเบียน',
                        cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });

            // Handle unregister trigger buttons (new design)
            const unregisterTriggers = document.querySelectorAll('.unregister-trigger');
            unregisterTriggers.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    const registrationId = this.getAttribute('data-registration-id');
                    const sessionCard = this.closest('.border-gray-100, .border-blue-200, .border-green-200');

                    if (!sessionCard) {
                        console.error('Could not find session card for unregister trigger');
                        return;
                    }

                    const sessionTitle = sessionCard.querySelector('.font-medium')?.textContent || 'Unknown Session';
                    const timeElement = sessionCard.querySelector('.fa-clock')?.parentNode;
                    const sessionTimeText = timeElement ? timeElement.textContent.trim() : '';

                    Swal.fire({
                        title: 'ยืนยันการยกเลิกการลงทะเบียน',
                        html: `
                            <div class="text-left">
                                <p class="mb-3"><strong>โปรแกรม:</strong> {{ $project->project_name }}</p>
                                <p class="mb-2"><strong>เซสชัน:</strong> ${sessionTitle}</p>
                                ${sessionTimeText ? `<p class="mb-3"><strong>เวลา:</strong> ${sessionTimeText}</p>` : ''}
                            </div>
                            <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-700">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <strong>คำเตือน:</strong> การดำเนินการนี้จะยกเลิกการลงทะเบียนของคุณสำหรับเซสชันนี้
                                </p>
                            </div>
                            <p class="mt-4 text-sm text-gray-600">คุณแน่ใจหรือไม่ที่จะดำเนินการต่อ?</p>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'ใช่, ยกเลิกการลงทะเบียน',
                        cancelButtonText: 'ไม่, เก็บไว้',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Create and submit the form
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ route("hrd.projects.unregister", [$project->id, ":registrationId"]) }}'.replace(':registrationId', registrationId);

                            const csrfToken = document.createElement('input');
                            csrfToken.type = 'hidden';
                            csrfToken.name = '_token';
                            csrfToken.value = '{{ csrf_token() }}';

                            const methodField = document.createElement('input');
                            methodField.type = 'hidden';
                            methodField.name = '_method';
                            methodField.value = 'DELETE';

                            form.appendChild(csrfToken);
                            form.appendChild(methodField);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
