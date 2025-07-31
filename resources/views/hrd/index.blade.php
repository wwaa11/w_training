@extends("layouts.hrd")

@section("content")
    <div class="container mx-auto px-4">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="mb-2 text-3xl font-bold text-gray-800">โปรแกรมพัฒนาบุคลากร HRD</h1>
                    <p class="text-gray-600">สำรวจและลงทะเบียนสำหรับโปรแกรมการฝึกอบรมและโอกาสในการพัฒนาที่มีอยู่</p>
                </div>
                <a class="inline-flex items-center rounded-lg bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700" href="{{ route("hrd.history") }}">
                    <i class="fas fa-history mr-2"></i>
                    ประวัติการเข้าร่วม
                </a>
            </div>
        </div>

        @if (session("success"))
            <div class="mb-6 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session("success") }}
                </div>
            </div>
        @endif

        @if (session("error"))
            <div class="mb-6 rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session("error") }}
                </div>
            </div>
        @endif

        <!-- Ongoing Projects Section -->
        @php
            $now = now();
            $ongoingProjects = $projects->filter(function ($project) use ($now) {
                // Check if project has dates today and user can check in
                foreach ($project->dates->where("date_delete", false) as $date) {
                    $dateString = $date->date_datetime->format("Y-m-d");
                    $today = $now->format("Y-m-d");

                    if ($dateString === $today) {
                        foreach ($date->times->where("time_delete", false) as $time) {
                            $timeStart = \Carbon\Carbon::parse($time->time_start)->format("H:i:s");
                            $timeEnd = \Carbon\Carbon::parse($time->time_end)->format("H:i:s");
                            $currentTime = $now->format("H:i:s");

                            // Check if current time is within the time slot
                            if ($currentTime >= $timeStart && $currentTime <= $timeEnd) {
                                // For attendance projects, check if user hasn't attended yet
                    if ($project->project_type === "attendance") {
                        $attendanceRecord = $project->attends
                            ->where("user_id", auth()->id())
                            ->where("time_id", $time->id)
                            ->where("attend_delete", false)
                            ->first();

                        if (!$attendanceRecord || !$attendanceRecord->attend_datetime) {
                            return true;
                        }
                    } else {
                        // For registered projects, check if user is registered but hasn't attended
                                    $userRegistration = $project->attends
                                        ->where("user_id", auth()->id())
                                        ->where("time_id", $time->id)
                                        ->where("attend_delete", false)
                                        ->first();

                                    if ($userRegistration && !$userRegistration->attend_datetime) {
                                        return true;
                                    }
                                }
                            }
                        }
                    }
                }
                return false;
            });
        @endphp

        @if ($ongoingProjects->count() > 0)
            <div class="mb-8 rounded-lg bg-blue-50 p-6 shadow-sm">
                <div class="mb-4 flex items-center">
                    <i class="fas fa-clock mr-3 text-2xl text-blue-600"></i>
                    <h2 class="text-xl font-semibold text-blue-900">โปรแกรมที่กำลังดำเนินการ</h2>
                </div>
                <p class="mb-4 text-blue-700">คุณสามารถเช็คอินสำหรับโปรแกรมต่อไปนี้ได้ตอนนี้:</p>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($ongoingProjects as $project)
                        @foreach ($project->dates->where("date_delete", false) as $date)
                            @php
                                $dateString = $date->date_datetime->format("Y-m-d");
                                $today = $now->format("Y-m-d");
                            @endphp
                            @if ($dateString === $today)
                                @foreach ($date->times->where("time_delete", false) as $time)
                                    @php
                                        $timeStart = \Carbon\Carbon::parse($time->time_start)->format("H:i:s");
                                        $timeEnd = \Carbon\Carbon::parse($time->time_end)->format("H:i:s");
                                        $currentTime = $now->format("H:i:s");
                                        $canCheckIn = false;
                                        $checkInRoute = "";
                                        $checkInMethod = "POST";
                                        $checkInData = [];

                                        if ($currentTime >= $timeStart && $currentTime <= $timeEnd) {
                                            if ($project->project_type === "attendance") {
                                                $attendanceRecord = $project->attends
                                                    ->where("user_id", auth()->id())
                                                    ->where("time_id", $time->id)
                                                    ->where("attend_delete", false)
                                                    ->first();

                                                if (!$attendanceRecord || !$attendanceRecord->attend_datetime) {
                                                    $canCheckIn = true;
                                                    $checkInRoute = route("hrd.projects.attend.store", $project->id);
                                                    $checkInData = ["time_id" => $time->id];
                                                }
                                            } else {
                                                $userRegistration = $project->attends
                                                    ->where("user_id", auth()->id())
                                                    ->where("time_id", $time->id)
                                                    ->where("attend_delete", false)
                                                    ->first();

                                                if ($userRegistration && !$userRegistration->attend_datetime) {
                                                    $canCheckIn = true;
                                                    $checkInRoute = route("hrd.projects.stamp.store", [$project->id, $userRegistration->id]);
                                                }
                                            }
                                        }
                                    @endphp

                                    @if ($canCheckIn)
                                        <div class="rounded-lg border border-blue-200 bg-white p-4 shadow-sm">
                                            <div class="mb-3">
                                                <h3 class="font-semibold text-gray-900">{{ $project->project_name }}</h3>
                                                <p class="text-sm text-gray-600">{{ $date->date_title }}</p>
                                                <p class="text-sm text-gray-500">{{ $time->time_title }}</p>
                                                <p class="text-xs text-blue-600">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    {{ \Carbon\Carbon::parse($time->time_start)->format("H:i") }} - {{ \Carbon\Carbon::parse($time->time_end)->format("H:i") }}
                                                </p>
                                            </div>

                                            <form action="{{ $checkInRoute }}" method="{{ $checkInMethod }}">
                                                @csrf
                                                @foreach ($checkInData as $key => $value)
                                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                                @endforeach
                                                <button class="inline-flex w-full items-center justify-center rounded-md border border-transparent bg-green-600 px-3 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2" type="submit">
                                                    <i class="fas fa-user-check mr-2"></i>
                                                    เช็คอินตอนนี้
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Search -->
        <div class="mb-6 rounded-lg bg-white p-4 shadow-sm">
            <div class="flex items-center">
                <label class="mr-2 text-sm font-medium text-gray-700">ค้นหา:</label>
                <input class="rounded border border-gray-300 px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" id="searchInput" type="text" placeholder="ค้นหาโปรเจกต์...">
            </div>
        </div>

        <!-- Projects Grid -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 xl:grid-cols-3" id="projectsGrid">
            @forelse($projects as $project)
                <div class="project-card rounded-lg bg-white shadow-md transition-shadow duration-200 hover:shadow-lg" data-type="{{ $project->project_type }}" data-name="{{ strtolower($project->project_name) }}">

                    <!-- Project Header -->
                    <div class="border-b border-gray-200 p-6">
                        <div class="mb-3 flex items-start justify-between">
                            <span class="@if ($project->project_type === "single") bg-blue-100 text-blue-800
                                @elseif($project->project_type === "multiple") bg-green-100 text-green-800
                                @else bg-purple-100 text-purple-800 @endif inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                                @if ($project->project_type === "single")
                                    เดี่ยว
                                @elseif($project->project_type === "multiple")
                                    หลาย
                                @else
                                    เข้าร่วม
                                @endif
                            </span>
                            @php
                                $now = now();
                                $canRegister = $project->project_active && !$project->project_delete && $now >= $project->project_start_register && $now <= $project->project_end_register && $project->project_type !== "attendance";
                                $isUpcoming = $now < $project->project_start_register;
                                $isExpired = $now > $project->project_end_register;
                            @endphp
                            @if ($project->project_type === "attendance")
                                <span class="inline-flex items-center rounded-full bg-purple-100 px-2 py-1 text-xs font-medium text-purple-800">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    ไม่ต้องลงทะเบียน
                                </span>
                            @elseif($canRegister)
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800">
                                    <i class="fas fa-circle mr-1 text-green-400" style="font-size: 6px;"></i>
                                    เปิดรับลงทะเบียน
                                </span>
                            @elseif($isUpcoming)
                                <span class="inline-flex items-center rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    เร็วๆ นี้
                                </span>
                            @elseif($isExpired)
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-800">
                                    <i class="fas fa-lock mr-1"></i>
                                    ปิดรับลงทะเบียน
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800">
                                    <i class="fas fa-ban mr-1"></i>
                                    ไม่ใช้งาน
                                </span>
                            @endif
                        </div>

                        <h3 class="mb-2 text-lg font-semibold text-gray-900">{{ $project->project_name }}</h3>

                        @if ($project->project_detail)
                            <p class="line-clamp-3 text-sm text-gray-600">{{ $project->project_detail }}</p>
                        @endif
                    </div>

                    <!-- Registration Period -->
                    <div class="bg-gray-50 px-6 py-3">
                        <div class="text-sm text-gray-600">
                            <div class="mb-1 flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                <span class="font-medium">ช่วงเวลาลงทะเบียน</span>
                            </div>
                            <div class="ml-5">
                                {{ $project->project_start_register->format("d M Y, H:i") }} -
                                {{ $project->project_end_register->format("d M Y, H:i") }}
                            </div>
                        </div>
                    </div>

                    <!-- Project Dates -->
                    @if ($project->dates->where("date_delete", false)->count() > 0)
                        <div class="px-6 py-3">
                            <div class="mb-2 text-sm text-gray-600">
                                <i class="fas fa-calendar-check mr-2 text-green-500"></i>
                                <span class="font-medium">วันที่โปรเจกต์ ({{ $project->dates->where("date_delete", false)->count() }})</span>
                            </div>
                            <div class="ml-5 space-y-2">
                                @foreach ($project->dates->where("date_delete", false)->take(3) as $date)
                                    <div class="flex items-center text-sm text-gray-700">
                                        <i class="fas fa-dot-circle mr-2 text-gray-400" style="font-size: 8px;"></i>
                                        <span class="font-medium">{{ $date->date_title }}</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ $date->date_datetime->format("d M Y") }}</span>
                                    </div>
                                @endforeach
                                @if ($project->dates->where("date_delete", false)->count() > 3)
                                    <div class="ml-4 text-xs text-gray-500">
                                        +{{ $project->dates->where("date_delete", false)->count() - 3 }} วันที่เพิ่มเติม
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Project Links -->
                    @if ($project->links->where("link_delete", false)->count() > 0)
                        <div class="border-t border-gray-100 px-6 py-3">
                            <div class="mb-2 text-sm text-gray-600">
                                <i class="fas fa-link mr-2 text-blue-500"></i>
                                <span class="font-medium">ทรัพยากร ({{ $project->links->where("link_delete", false)->count() }})</span>
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="text-xs text-gray-500">
                                @if ($project->attends->where("attend_delete", false)->count() > 0)
                                    <i class="fas fa-users mr-1"></i>
                                    {{ $project->attends->where("attend_delete", false)->count() }} คนลงทะเบียน
                                @endif
                            </div>

                            <div class="flex space-x-2">
                                <a class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" href="{{ route("hrd.projects.show", $project->id) }}">
                                    <i class="fas fa-eye mr-1"></i>
                                    ดูรายละเอียด
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="py-12 text-center">
                        <div class="mx-auto h-12 w-12 text-gray-400">
                            <i class="fas fa-inbox text-4xl"></i>
                        </div>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">ไม่มีโปรเจกต์ที่ใช้งานได้</h3>
                        <p class="mt-1 text-sm text-gray-500">ไม่มีโปรเจกต์ที่เปิดให้ลงทะเบียนในขณะนี้</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($projects->hasPages())
            <div class="mt-8">
                {{ $projects->links() }}
            </div>
        @endif
    </div>
@endsection

@section("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const projectCards = document.querySelectorAll('.project-card');

            function searchProjects() {
                const searchTerm = searchInput.value.toLowerCase();

                projectCards.forEach(card => {
                    const cardName = card.dataset.name;
                    const searchMatch = !searchTerm || cardName.includes(searchTerm);

                    if (searchMatch) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Show/hide empty state
                const visibleCards = document.querySelectorAll('.project-card[style="display: block"], .project-card:not([style*="display: none"])');
                const emptyState = document.querySelector('.col-span-full');

                if (visibleCards.length === 0 && emptyState) {
                    emptyState.style.display = 'block';
                } else if (emptyState) {
                    emptyState.style.display = 'none';
                }
            }

            searchInput.addEventListener('input', searchProjects);
        });
    </script>
@endsection
