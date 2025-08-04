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

        <!-- Quick Help Section -->
        <div class="mb-6 rounded-lg bg-gradient-to-r from-purple-50 to-blue-50 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="mr-3 rounded-full bg-purple-100 p-2">
                        <i class="fas fa-question-circle text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">ไม่แน่ใจว่าจะใช้งานอย่างไร?</h3>
                        <p class="text-sm text-gray-600">ดูคู่มือการใช้งานเพื่อเรียนรู้วิธีการลงทะเบียนและเช็คอิน</p>
                    </div>
                </div>
                <a class="inline-flex items-center rounded-lg bg-purple-600 px-4 py-2 text-sm font-medium text-white hover:bg-purple-700" href="{{ route("hrd.user-guide") }}">
                    <i class="fas fa-book-open mr-2"></i>
                    ดูคู่มือ
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
                    @foreach ($ongoingProjects as $ongoingProject)
                        @foreach ($ongoingProject["sessions"] as $session)
                            <div class="rounded-lg border border-blue-200 bg-white p-4 shadow-sm">
                                <div class="mb-3">
                                    <h3 class="font-semibold text-gray-900">{{ $ongoingProject["project"]->project_name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $session["date"]->date_title }}</p>
                                    <p class="text-sm text-gray-500">{{ $session["time"]->time_title }}</p>
                                    <p class="text-xs text-blue-600">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ \Carbon\Carbon::parse($session["time"]->time_start)->format("H:i") }} - {{ \Carbon\Carbon::parse($session["time"]->time_end)->format("H:i") }}
                                    </p>
                                    @if ($ongoingProject["project"]->project_seat_assign && $session["userSeat"])
                                        <div class="mt-3 transform animate-pulse">
                                            <div class="inline-flex items-center rounded-lg bg-gradient-to-r from-purple-500 to-purple-600 px-4 py-2 shadow-lg">
                                                <i class="fas fa-chair mr-3 text-lg text-white"></i>
                                                <div class="text-center">
                                                    <div class="text-xs font-medium text-purple-100">ที่นั่งของคุณ</div>
                                                    <div class="text-xl font-bold text-white">{{ $session["userSeat"]->seat_number }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($ongoingProject["project"]->project_group_assign)
                                        @php
                                            $userGroup = \App\Models\HrGroup::where("project_id", $ongoingProject["project"]->id)
                                                ->where("user_id", auth()->id())
                                                ->first();
                                        @endphp
                                        @if ($userGroup)
                                            <div class="mt-3 transform animate-pulse">
                                                <div class="inline-flex items-center rounded-lg bg-gradient-to-r from-indigo-500 to-indigo-600 px-4 py-2 shadow-lg">
                                                    <i class="fas fa-users mr-3 text-lg text-white"></i>
                                                    <div class="text-center">
                                                        <div class="text-xs font-medium text-indigo-100">กลุ่มของคุณ</div>
                                                        <div class="text-xl font-bold text-white">{{ $userGroup->group }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                <form class="checkin-form" action="{{ $session["checkInRoute"] }}" method="{{ $session["checkInMethod"] }}">
                                    @csrf
                                    @foreach ($session["checkInData"] as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach
                                    <button class="group relative inline-flex w-full items-center justify-center overflow-hidden rounded-lg bg-gradient-to-r from-green-500 to-green-600 px-6 py-3 text-white shadow-lg transition-all duration-300 hover:from-green-600 hover:to-green-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 active:scale-95" type="submit">
                                        <div class="absolute inset-0 bg-gradient-to-r from-green-400 to-green-500 opacity-0 transition-opacity duration-300 group-hover:opacity-20"></div>
                                        <i class="fas fa-user-check mr-3 text-lg"></i>
                                        <span class="text-base font-semibold">เช็คอินตอนนี้</span>
                                        <i class="fas fa-arrow-right ml-2 transition-transform duration-300 group-hover:translate-x-1"></i>
                                    </button>
                                </form>
                            </div>
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
        <div class="space-y-6" id="projectsGrid">
            @forelse($projectsWithStates as $projectData)
                @php
                    $project = $projectData["project"];
                    $state = $projectData["registrationState"];
                @endphp
                <a class="block" href="{{ route("hrd.projects.show", $project->id) }}">
                    <div class="project-card cursor-pointer rounded-lg bg-white shadow-sm transition-all duration-200 hover:scale-[1.02] hover:shadow-lg" data-type="{{ $project->project_type }}" data-name="{{ strtolower($project->project_name) }}">

                        <!-- Project Header -->
                        <div class="border-b border-gray-100 p-6">
                            <div class="mb-4 flex items-start justify-between">
                                <span class="@if ($project->project_type === "single") bg-blue-100 text-blue-800
                                    @elseif($project->project_type === "multiple") bg-green-100 text-green-800
                                    @else bg-purple-100 text-purple-800 @endif inline-flex items-center rounded-full px-3 py-1 text-xs font-medium">
                                    @if ($project->project_type === "single")
                                        ลงทะเบียน 1 ครั้ง
                                    @elseif($project->project_type === "multiple")
                                        ลงทะเบียนได้มากกว่า 1 ครั้ง
                                    @else
                                        ไม่ต้องลงทะเบียน
                                    @endif
                                </span>
                                @if ($project->project_type === "attendance")
                                    <span class="inline-flex items-center rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-800">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        ไม่ต้องลงทะเบียน
                                    </span>
                                @elseif($state["canRegister"])
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">
                                        <i class="fas fa-circle mr-1 text-green-400" style="font-size: 6px;"></i>
                                        เปิดรับลงทะเบียน
                                    </span>
                                @elseif($state["isUpcoming"])
                                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        เร็วๆ นี้
                                    </span>
                                @elseif($state["isExpired"])
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-800">
                                        <i class="fas fa-lock mr-1"></i>
                                        ปิดรับลงทะเบียน
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-800">
                                        <i class="fas fa-ban mr-1"></i>
                                        ไม่ใช้งาน
                                    </span>
                                @endif
                            </div>

                            <h3 class="mb-3 text-xl font-bold text-gray-900">{{ $project->project_name }}</h3>

                            @if ($project->project_detail)
                                <p class="line-clamp-2 text-sm text-gray-600">{{ $project->project_detail }}</p>
                            @endif
                        </div>

                        <!-- Project Information -->
                        <div class="space-y-4 p-6">
                            <!-- Registration Period -->
                            <div class="flex items-start">
                                <div class="mr-3 mt-1 rounded-full bg-blue-100 p-1">
                                    <i class="fas fa-calendar-alt text-xs text-blue-600"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-gray-900">ช่วงเวลาลงทะเบียน</h4>
                                    <p class="text-sm text-gray-600">
                                        {{ $project->project_start_register->format("d M Y, H:i") }} - {{ $project->project_end_register->format("d M Y, H:i") }}
                                    </p>
                                </div>
                            </div>

                            <!-- Project Dates -->
                            @if ($project->dates->count() > 0)
                                <div class="flex items-start">
                                    <div class="mr-3 mt-1 rounded-full bg-green-100 p-1">
                                        <i class="fas fa-calendar-check text-xs text-green-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-semibold text-gray-900">วันที่โปรเจกต์ ({{ $project->dates->count() }})</h4>
                                        <div class="mt-1 space-y-1">
                                            @foreach ($project->dates->take(3) as $date)
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <i class="fas fa-dot-circle mr-2 text-gray-400" style="font-size: 6px;"></i>
                                                    <span class="font-medium">{{ $date->date_title }}</span>
                                                    <span class="mx-2">•</span>
                                                    <span>{{ $date->date_datetime->format("d M Y") }}</span>
                                                </div>
                                            @endforeach
                                            @if ($project->dates->count() > 3)
                                                <div class="ml-4 text-sm text-gray-500">
                                                    +{{ $project->dates->count() - 3 }} วันที่เพิ่มเติม
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Project Links -->
                            @if ($project->links->count() > 0)
                                <div class="flex items-start">
                                    <div class="mr-3 mt-1 rounded-full bg-blue-100 p-1">
                                        <i class="fas fa-link text-xs text-blue-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-semibold text-gray-900">ทรัพยากร ({{ $project->links->count() }})</h4>
                                        <p class="text-sm text-gray-600">เอกสารและลิงก์ที่เกี่ยวข้อง</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Seat Assignment Info -->
                            @if ($project->project_seat_assign)
                                <div class="flex items-start">
                                    <div class="mr-3 mt-1 rounded-full bg-purple-100 p-1">
                                        <i class="fas fa-chair text-xs text-purple-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-semibold text-gray-900">เปิดใช้งานการจัดที่นั่ง</h4>
                                        <div class="mt-1 inline-flex items-center rounded-lg bg-gradient-to-r from-purple-500 to-purple-600 px-3 py-1 shadow-md">
                                            <i class="fas fa-star mr-2 text-xs text-yellow-300"></i>
                                            <span class="text-xs font-bold text-white">ที่นั่ง</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Registration Count -->
                            @if ($state["registrationCount"] > 0)
                                <div class="flex items-start">
                                    <div class="mr-3 mt-1 rounded-full bg-gray-100 p-1">
                                        <i class="fas fa-users text-xs text-gray-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-semibold text-gray-900">{{ $state["registrationCount"] }} คนลงทะเบียน</h4>
                                        <p class="text-sm text-gray-600">จำนวนผู้ลงทะเบียนในปัจจุบัน</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Action Footer -->
                        <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-500">
                                    คลิกเพื่อดูรายละเอียดเพิ่มเติม
                                </div>
                                <div class="inline-flex items-center text-sm font-medium text-blue-600">
                                    <i class="fas fa-arrow-right mr-2 transition-transform duration-200 group-hover:translate-x-1"></i>
                                    ดูรายละเอียด
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
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
