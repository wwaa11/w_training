@extends("layouts.hrd")

@section("content")
    <div class="container mx-auto px-4 pb-20">
        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-800 sm:text-3xl">โปรแกรมพัฒนาบุคลากร HRD</h1>
                    <p class="mt-1 text-sm text-gray-600 sm:text-base">สำรวจและลงทะเบียนสำหรับโปรแกรมการฝึกอบรม</p>
                </div>
                <a class="ml-4 inline-flex items-center rounded-lg bg-gray-600 px-3 py-2 text-sm font-medium text-white hover:bg-gray-700 sm:px-4" href="{{ route("hrd.history") }}">
                    <i class="fas fa-history mr-2"></i>
                    <span class="hidden sm:inline">ประวัติการเข้าร่วม</span>
                    <span class="sm:hidden">ประวัติ</span>
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

        <!-- Ongoing Projects Section -->
        @if ($ongoingProjects->count() > 0)
            <div class="mb-6 rounded-lg bg-gradient-to-r from-blue-50 to-blue-100 p-4 shadow-sm">
                <div class="mb-3 flex items-center">
                    <i class="fas fa-clock mr-2 text-xl text-blue-600"></i>
                    <h2 class="text-lg font-semibold text-blue-900">โปรแกรมที่กำลังดำเนินการ</h2>
                </div>
                <p class="mb-4 text-sm text-blue-700">คุณสามารถเช็คอินสำหรับโปรแกรมต่อไปนี้ได้ตอนนี้:</p>

                <div class="space-y-3">
                    @foreach ($ongoingProjects as $project)
                        @foreach ($project->dates as $date)
                            @php
                                $dateString = $date->date_datetime->format("Y-m-d");
                                $today = now()->format("Y-m-d");
                            @endphp
                            @if ($dateString === $today)
                                @foreach ($date->times as $time)
                                    @php
                                        $timeStart = \Carbon\Carbon::parse($time->time_start)->format("H:i:s");
                                        $timeEnd = \Carbon\Carbon::parse($time->time_end)->format("H:i:s");
                                        $currentTime = now()->format("H:i:s");
                                        $canCheckIn = false;
                                        $checkInRoute = "";
                                        $checkInMethod = "POST";
                                        $checkInData = [];

                                        if ($currentTime >= $timeStart && $currentTime <= $timeEnd) {
                                            if ($project->project_type === "attendance") {
                                                $attendanceRecord = $project->attends
                                                    ->where("user_id", auth()->id())
                                                    ->where("time_id", $time->id)
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

                                            <form class="checkin-form" action="{{ $checkInRoute }}" method="{{ $checkInMethod }}">
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

        <!-- Search Section -->
        <div class="mb-6 rounded-lg bg-white p-4 shadow-sm">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input class="w-full rounded-lg border border-gray-300 py-2 pl-10 pr-4 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" id="searchInput" type="text" placeholder="ค้นหาโปรเจกต์...">
            </div>
        </div>

        <!-- Projects Grid -->
        <div class="space-y-4" id="projectsGrid">
            @forelse($projects as $project)
                <div class="project-card rounded-lg bg-white shadow-sm transition-shadow duration-200 hover:shadow-md" data-type="{{ $project->project_type }}" data-name="{{ strtolower($project->project_name) }}">

                    <!-- Project Header -->
                    <div class="border-b border-gray-100 p-4">
                        <div class="mb-3 flex items-start justify-between">
                            <span class="@if ($project->project_type === "single") bg-blue-100 text-blue-800
                                @elseif($project->project_type === "multiple") bg-green-100 text-green-800
                                @else bg-purple-100 text-purple-800 @endif inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                                @if ($project->project_type === "single")
                                    ลงทะเบียน 1 ครั้ง
                                @elseif($project->project_type === "multiple")
                                    ลงทะเบียนได้มากกว่า 1 ครั้ง
                                @else
                                    ไม่ต้องลงทะเบียน
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
                            <p class="line-clamp-2 text-sm text-gray-600">{{ $project->project_detail }}</p>
                        @endif
                    </div>

                    <!-- Registration Period -->
                    <div class="bg-gray-50 px-4 py-3">
                        <div class="text-sm text-gray-600">
                            <div class="mb-1 flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                <span class="font-medium">ช่วงเวลาลงทะเบียน</span>
                            </div>
                            <div class="ml-5 text-xs">
                                {{ $project->project_start_register->format("d M Y, H:i") }} -
                                {{ $project->project_end_register->format("d M Y, H:i") }}
                            </div>
                        </div>
                    </div>

                    <!-- Project Dates -->
                    @if ($project->dates->count() > 0)
                        <div class="px-4 py-3">
                            <div class="mb-2 text-sm text-gray-600">
                                <i class="fas fa-calendar-check mr-2 text-green-500"></i>
                                <span class="font-medium">วันที่โปรเจกต์ ({{ $project->dates->count() }})</span>
                            </div>
                            <div class="ml-5 space-y-1">
                                @foreach ($project->dates->take(2) as $date)
                                    <div class="flex items-center text-xs text-gray-700">
                                        <i class="fas fa-dot-circle mr-2 text-gray-400" style="font-size: 6px;"></i>
                                        <span class="font-medium">{{ $date->date_title }}</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ $date->date_datetime->format("d M Y") }}</span>
                                    </div>
                                @endforeach
                                @if ($project->dates->count() > 2)
                                    <div class="ml-4 text-xs text-gray-500">
                                        +{{ $project->dates->count() - 2 }} วันที่เพิ่มเติม
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Project Links -->
                    @if ($project->links->count() > 0)
                        <div class="border-t border-gray-100 px-4 py-3">
                            <div class="text-sm text-gray-600">
                                <i class="fas fa-link mr-2 text-blue-500"></i>
                                <span class="font-medium">ทรัพยากร ({{ $project->links->count() }})</span>
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="border-t border-gray-200 bg-gray-50 px-4 py-3">
                        <div class="flex items-center justify-between">
                            <div class="text-xs text-gray-500">
                                @if ($project->attends->count() > 0)
                                    <i class="fas fa-users mr-1"></i>
                                    {{ $project->attends->count() }} คนลงทะเบียน
                                @endif
                            </div>

                            <a class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" href="{{ route("hrd.projects.show", $project->id) }}">
                                <i class="fas fa-eye mr-1"></i>
                                ดูรายละเอียด
                            </a>
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

            // Handle check-in form submissions with confirmation
            const checkinForms = document.querySelectorAll('.checkin-form');
            checkinForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Get session details from the card
                    const sessionCard = this.closest('.rounded-lg');
                    if (!sessionCard) {
                        console.error('Could not find session card for check-in form');
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
        });
    </script>
@endsection
