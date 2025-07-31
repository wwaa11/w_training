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

                        <div class="p-4 hover:bg-gray-50 sm:p-6">
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

                                    @if (!$hasAttended && !$isPast)
                                        <a class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1 text-xs font-medium text-gray-700 shadow-sm hover:bg-gray-50" href="{{ route("hrd.projects.show", $project->id) }}">
                                            <i class="fas fa-eye mr-1"></i>
                                            ดูรายละเอียด
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
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
    </div>
@endsection

@section("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('HRD History page loaded');
        });
    </script>
@endsection
