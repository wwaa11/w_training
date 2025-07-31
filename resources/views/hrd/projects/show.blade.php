@extends("layouts.hrd")

@section("content")
    <div class="container mx-auto px-4 pb-20">
        <!-- Breadcrumb -->
        <nav class="mb-4">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a class="hover:text-blue-600" href="{{ route("hrd.index") }}">โปรแกรม HRD</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li class="text-gray-900">{{ $project->project_name }}</li>
            </ol>
        </nav>

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

        @if (session("info"))
            <div class="mb-4 rounded-lg border border-blue-400 bg-blue-100 px-4 py-3 text-blue-700">
                <div class="flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span class="text-sm">{{ session("info") }}</span>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-400 bg-red-100 px-4 py-3 text-red-700">
                <h4 class="font-bold">กรุณาแก้ไขข้อผิดพลาดต่อไปนี้:</h4>
                <ul class="mt-2 list-inside list-disc text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="space-y-6">
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
                                                    @endphp

                                                    <div class="{{ $timeState["hasAttended"] ? "bg-blue-50 border-blue-200" : ($timeState["userRegistered"] ? "bg-green-50 border-green-200" : "bg-gray-50") }} rounded-lg border border-gray-100 p-3">
                                                        <div class="mb-2 flex flex-col justify-between sm:flex-row sm:items-center">
                                                            <span class="flex items-center font-medium text-gray-900">
                                                                @if ($timeState["hasAttended"])
                                                                    <i class="fas fa-check-circle mr-2 text-blue-500"></i>
                                                                @elseif($timeState["userRegistered"])
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
                                                            @if ($project->links->where("link_delete", false)->count() > 0 && $timeState["showLinks"])
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
                                                            @if ($timeState["hasAttended"])
                                                                <div class="mt-2 text-xs font-medium text-blue-600">
                                                                    <i class="fas fa-check-circle mr-1"></i>
                                                                    @if ($project->project_type === "attendance")
                                                                        เข้าร่วมแล้ว: {{ $timeState["attendanceRecord"]->attend_datetime->format("d M Y, H:i") }}
                                                                    @else
                                                                        เข้าร่วมแล้ว: {{ $registrationData["userRegistrations"]->where("time_id", $time->id)->first()->attend_datetime->format("d M Y, H:i") }}
                                                                    @endif
                                                                </div>
                                                            @elseif($timeState["canStamp"])
                                                                <div class="mb-2 mt-2 text-xs font-medium text-green-600">
                                                                    <i class="fas fa-user-check mr-1"></i>
                                                                    คุณลงทะเบียนสำหรับเซสชันนี้แล้ว
                                                                </div>
                                                                <div class="mt-3">
                                                                    <form class="stamp-form" action="{{ route("hrd.projects.stamp.store", [$project->id, $registrationData["userRegistrations"]->where("time_id", $time->id)->first()->id]) }}" method="POST">
                                                                        @csrf
                                                                        <button class="inline-flex w-full items-center justify-center rounded-md border border-transparent bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" type="submit">
                                                                            <i class="fas fa-stamp mr-2"></i>
                                                                            เช็คอินตอนนี้
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            @elseif($timeState["canCheckIn"])
                                                                <div class="mt-3">
                                                                    <form class="attendance-form" action="{{ route("hrd.projects.attend.store", $project->id) }}" method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="time_id" value="{{ $time->id }}">
                                                                        <button class="inline-flex w-full items-center justify-center rounded-md border border-transparent bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" type="submit">
                                                                            <i class="fas fa-user-check mr-2"></i>
                                                                            เช็คอินตอนนี้
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            @elseif($timeState["userRegistered"])
                                                                <div class="mt-2 text-xs font-medium text-green-600">
                                                                    <i class="fas fa-user-check mr-1"></i>
                                                                    คุณลงทะเบียนสำหรับเซสชันนี้แล้ว
                                                                </div>

                                                                @if ($project->project_type === "multiple")
                                                                    @php
                                                                        $today = now()->format("Y-m-d");
                                                                        $dateIsToday = $date->date_datetime->format("Y-m-d") === $today;
                                                                        $canUnregister = $project->project_register_today || !$dateIsToday;
                                                                    @endphp

                                                                    @if ($canUnregister)
                                                                        <div class="mt-3">
                                                                            <form class="unregister-form" action="{{ route("hrd.projects.unregister", [$project->id, $userRegistration->id]) }}" method="POST">
                                                                                @csrf
                                                                                @method("DELETE")
                                                                                <button class="inline-flex w-full items-center justify-center rounded-md border border-red-300 bg-white px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" type="submit">
                                                                                    <i class="fas fa-user-times mr-2"></i>
                                                                                    ยกเลิกการลงทะเบียน
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    @else
                                                                        <div class="mt-2 text-xs text-red-600">
                                                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                                                            ไม่สามารถยกเลิกการลงทะเบียนในวันเดียวกันได้
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            @endif

                                                            @if ($timeState["timeSlotMessage"])
                                                                <div class="mt-2 text-xs text-gray-500">
                                                                    <i class="fas fa-clock mr-1"></i>
                                                                    {{ $timeState["timeSlotMessage"] }}
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
                                        <button class="inline-flex items-center rounded-md border border-orange-300 bg-white px-3 py-2 text-sm font-medium text-orange-700 hover:bg-orange-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2" type="submit">
                                            <i class="fas fa-redo mr-2"></i>
                                            ล้างการลงทะเบียนและเลือกใหม่
                                        </button>
                                    </form>
                                </div>
                            @endif

                            <div class="mb-4">
                                <h2 class="mb-2 text-lg font-semibold text-gray-900 sm:text-xl">
                                    <i class="fas fa-user-plus mr-2 text-blue-500"></i>
                                    @if ($registrationData["canReselect"] && $registrationData["userRegistrations"]->count() > 0)
                                        เลือกการลงทะเบียนใหม่
                                    @else
                                        ลงทะเบียนสำหรับโปรแกรมนี้
                                    @endif
                                </h2>
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
                                    @foreach ($project->dates->where("date_delete", false) as $date)
                                        @php
                                            $today = now()->format("Y-m-d");
                                            $dateIsToday = $date->date_datetime->format("Y-m-d") === $today;
                                            $showDateInRegistration = $project->project_register_today || !$dateIsToday;
                                        @endphp

                                        @if ($showDateInRegistration)
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
                                                        <h4 class="mb-3 text-sm font-medium text-gray-700">
                                                            @if ($project->project_type === "single")
                                                                เลือกช่วงเวลาหนึ่ง:
                                                            @else
                                                                เลือกช่วงเวลา:
                                                            @endif
                                                        </h4>
                                                        <div class="space-y-3">
                                                            @foreach ($date->times->where("time_delete", false) as $time)
                                                                @php
                                                                    $isLimited = $time->time_limit;
                                                                    $currentRegistrations = $time->activeAttends->count();
                                                                    $available = $isLimited ? $time->time_max - $currentRegistrations : null;
                                                                    $isFull = $isLimited && $available <= 0;
                                                                @endphp

                                                                <div class="{{ $isFull ? "opacity-50" : "" }} rounded-lg border border-gray-100 bg-gray-50 p-3 transition-colors hover:bg-blue-50">
                                                                    <label class="{{ $isFull ? "cursor-not-allowed" : "" }} flex cursor-pointer items-start">
                                                                        @if ($project->project_type === "single")
                                                                            <input class="mt-1 h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500" data-date-id="{{ $date->id }}" type="radio" name="time_ids[]" value="{{ $time->id }}" {{ $isFull ? "disabled" : "" }}>
                                                                        @else
                                                                            <input class="mt-1 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" data-date-id="{{ $date->id }}" type="checkbox" name="time_ids[]" value="{{ $time->id }}" {{ $isFull ? "disabled" : "" }}>
                                                                        @endif

                                                                        <div class="ml-3 flex-1">
                                                                            <div class="mb-1 flex flex-col justify-between sm:flex-row sm:items-center">
                                                                                <span class="font-medium text-gray-900">{{ $time->time_title }}</span>
                                                                                @if ($isLimited)
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

                                                                                @if ($isFull)
                                                                                    <div class="mt-1 text-xs text-red-600">
                                                                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                                                                        ช่วงเวลานี้เต็มแล้ว
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                <!-- User Information -->
                                <div class="mt-4 rounded-lg bg-gray-50 p-3 sm:p-4">
                                    <h3 class="text-md mb-3 font-medium text-gray-900">
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
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="mt-4 flex justify-end">
                                    <button class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:px-6 sm:text-base" type="submit">
                                        <i class="fas fa-user-plus mr-2"></i>
                                        เสร็จสิ้นการลงทะเบียน
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                @endif

            </div>

            <!-- Sidebar -->
            <div>
                <!-- Registration Info -->
                <div class="rounded-lg bg-white p-4 shadow-sm sm:p-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-900">ข้อมูลการลงทะเบียน</h3>

                    <div class="space-y-3">
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

                        <div>
                            <label class="text-sm font-medium text-gray-700">การลงทะเบียนปัจจุบัน</label>
                            <div class="mt-1 text-sm text-gray-900">
                                <i class="fas fa-users mr-1 text-blue-500"></i>
                                {{ $project->activeAttends->count() }} คน
                            </div>
                        </div>

                        @if ($project->getLimitedTimeSlotsCount() > 0)
                            <div>
                                <label class="text-sm font-medium text-gray-700">สถานะความจุ</label>
                                <div class="mt-1 space-y-1 text-sm">
                                    @if ($project->hasAvailableSlots())
                                        <div class="flex items-center text-green-600">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            <span>เหลือที่ว่าง {{ $project->getAvailableSlotsCount() }} ที่</span>
                                        </div>
                                    @else
                                        <div class="flex items-center text-red-600">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            <span>เต็มแล้ว</span>
                                        </div>
                                    @endif

                                    <div class="text-xs text-gray-500">
                                        <div>ความจุทั้งหมด: {{ $project->getTotalCapacity() }} คน</div>
                                        <div>ลงทะเบียนแล้ว: {{ $project->getTotalRegistered() }} คน</div>
                                        @if ($project->getFullTimeSlotsCount() > 0)
                                            <div>ช่วงเวลาที่เต็ม: {{ $project->getFullTimeSlotsCount() }}/{{ $project->getLimitedTimeSlotsCount() }}</div>
                                        @endif
                                    </div>
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

                        <div>
                            @if ($project->project_register_today)
                                <div class="flex items-center text-sm text-green-600">
                                    <i class="fas fa-calendar-day mr-1"></i>
                                    อนุญาตให้ลงทะเบียนในวันเดียวกัน
                                </div>
                            @else
                                <div class="flex items-center text-sm text-red-600">
                                    <i class="fas fa-ban mr-1"></i>
                                    ไม่อนุญาตให้ลงทะเบียนในวันเดียวกัน
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

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
                        const timeSlot = input.closest('.border').querySelector('.font-medium').textContent;
                        const timeSchedule = input.closest('.border').querySelector('.fa-clock').parentNode.textContent.trim();
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

            // Handle unregister form submissions
            const unregisterForms = document.querySelectorAll('.unregister-form');
            unregisterForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Get session details from the card
                    const sessionCard = this.closest('.border-gray-100, .border-blue-200, .border-green-200');
                    if (!sessionCard) {
                        console.error('Could not find session card for unregister form');
                        return;
                    }

                    const sessionTitle = sessionCard.querySelector('.font-medium')?.textContent || 'Unknown Session';
                    const timeElement = sessionCard.querySelector('.fa-clock')?.parentNode;
                    const sessionTimeText = timeElement ? timeElement.textContent.trim() : '';

                    Swal.fire({
                        title: 'ยกเลิกการลงทะเบียน?',
                        html: `
                            <div class="text-left">
                                <p class="mb-3"><strong>โปรแกรม:</strong> {{ $project->project_name }}</p>
                                <p class="mb-2"><strong>เซสชัน:</strong> ${sessionTitle}</p>
                                ${sessionTimeText ? `<p class="mb-3"><strong>เวลา:</strong> ${sessionTimeText}</p>` : ''}
                            </div>
                            <p class="mt-4 text-sm text-gray-600">คุณแน่ใจหรือไม่ที่จะยกเลิกการลงทะเบียนสำหรับเซสชันนี้?</p>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'ใช่, ยกเลิกการลงทะเบียน',
                        cancelButtonText: 'ไม่, เก็บไว้'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
