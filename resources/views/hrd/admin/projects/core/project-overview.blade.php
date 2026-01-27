@extends("layouts.hrd")

@section("content")
    <div class="min-h-screen">
        <!-- Header Section -->
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-4">
                <a class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600 transition-colors duration-200 hover:bg-blue-200" href="{{ route("hrd.admin.index") }}">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
                <div class="flex-1">
                    <h1 class="break-words font-bold text-gray-900" id="projectName" style="font-size: 1.875rem;">{{ $project->project_name }}</h1>
                    <div class="mt-2 flex items-center space-x-3">
                        <span class="@if ($project->project_type === "single") bg-blue-100 text-blue-800
                            @elseif($project->project_type === "multiple") bg-green-100 text-green-800
                            @else bg-purple-100 text-purple-800 @endif inline-flex items-center rounded-full px-3 py-1 text-sm font-medium">
                            <i class="fas fa-{{ $project->project_type === "single" ? "user" : ($project->project_type === "multiple" ? "users" : "calendar") }} mr-2"></i>
                            @if ($project->project_type === "single")
                                ลงทะเบียน 1 ครั้ง
                            @elseif($project->project_type === "multiple")
                                ลงทะเบียนได้มากกว่า 1 ครั้ง
                            @else
                                ไม่ต้องลงทะเบียน
                            @endif
                        </span>
                        <span class="{{ $project->project_active ? "bg-green-100 text-green-800" : "bg-red-100 text-red-800" }} inline-flex items-center rounded-full px-3 py-1 text-sm font-medium">
                            <i class="fas fa-{{ $project->project_active ? "check-circle" : "times-circle" }} mr-2"></i>
                            {{ $project->project_active ? "ใช้งาน" : "ไม่ใช้งาน" }}
                        </span>
                    </div>
                </div>
            </div>

            @if (session("success"))
                <div class="mt-4 rounded-lg border border-green-200 bg-green-50 p-4">
                    <div class="flex">
                        <i class="fas fa-check-circle mr-3 mt-0.5 text-green-400"></i>
                        <p class="text-green-800">{{ session("success") }}</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            <!-- Action Buttons Section -->
            <div class="mb-8 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 flex items-center text-lg font-semibold text-gray-900">
                    <i class="fas fa-cogs mr-3 text-blue-600"></i>
                    การจัดการโปรเจกต์
                </h2>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <a class="flex h-12 w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-3 text-sm font-medium text-white transition-colors duration-200 hover:bg-blue-700" href="{{ route("hrd.admin.projects.registrations.index", $project->id) }}">
                        <i class="fas fa-users mr-2"></i>
                        จัดการการลงทะเบียน
                    </a>
                    <a class="flex h-12 w-full items-center justify-center rounded-lg bg-green-600 px-4 py-3 text-sm font-medium text-white transition-colors duration-200 hover:bg-green-700" href="{{ route("hrd.admin.projects.approvals.index", $project->id) }}">
                        <i class="fas fa-check-circle mr-2"></i>
                        จัดการการอนุมัติ
                    </a>
                    <a class="flex h-12 w-full items-center justify-center rounded-lg bg-purple-600 px-4 py-3 text-sm font-medium text-white transition-colors duration-200 hover:bg-purple-700" href="{{ route("hrd.admin.projects.results.index", $project->id) }}">
                        <i class="fas fa-chart-bar mr-2"></i>
                        จัดการผลการประเมิน
                    </a>
                    @if ($project->project_seat_assign)
                        <a class="flex h-12 w-full items-center justify-center rounded-lg bg-indigo-600 px-4 py-3 text-sm font-medium text-white transition-colors duration-200 hover:bg-indigo-700" href="{{ route("hrd.admin.projects.seat.management", $project->id) }}">
                            <i class="fas fa-cogs mr-2"></i>
                            จัดการที่นั่ง
                        </a>
                    @endif
                    @if ($project->project_group_assign)
                        <a class="flex h-12 w-full items-center justify-center rounded-lg bg-teal-600 px-4 py-3 text-sm font-medium text-white transition-colors duration-200 hover:bg-teal-700" href="{{ route("hrd.admin.projects.groups.index", $project->id) }}">
                            <i class="fas fa-users mr-2"></i>
                            จัดการกลุ่ม
                        </a>
                    @endif
                    <a class="flex h-12 w-full items-center justify-center rounded-lg bg-yellow-600 px-4 py-3 text-sm font-medium text-white transition-colors duration-200 hover:bg-yellow-700" href="{{ route("hrd.admin.projects.edit", $project->id) }}">
                        <i class="fas fa-edit mr-2"></i>
                        แก้ไข
                    </a>
                </div>
            </div>

            <!-- Project Information -->
            <div class="rounded-xl border border-gray-200 bg-white py-6 shadow-sm">
                <div class="border-b border-gray-200 px-6 pb-4">
                    <h2 class="flex items-center text-lg font-semibold text-gray-900">
                        <i class="fas fa-info-circle mr-3 text-blue-600"></i>
                        ข้อมูลโปรเจกต์
                    </h2>
                </div>
                <div class="p-6">
                    @if ($project->project_detail)
                        <div class="mb-6">
                            <h3 class="mb-2 text-sm font-medium text-gray-700">รายละเอียด</h3>
                            <p class="leading-relaxed text-gray-900">{{ $project->project_detail }}</p>
                        </div>
                    @endif
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <h3 class="mb-2 text-sm font-medium text-gray-700">เริ่มลงทะเบียน</h3>
                            <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($project->project_start_register)->format("d/m/Y H:i") }}</p>
                        </div>
                        <div>
                            <h3 class="mb-2 text-sm font-medium text-gray-700">สิ้นสุดลงทะเบียน</h3>
                            <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($project->project_end_register)->format("d/m/Y H:i") }}</p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <h3 class="mb-4 text-sm font-medium text-gray-700">การตั้งค่าพิเศษ</h3>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="{{ $project->project_seat_assign ? "border-green-200 bg-green-50" : "border-red-200 bg-red-50" }} rounded-lg border-2 p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="{{ $project->project_seat_assign ? "bg-green-100" : "bg-red-100" }} flex h-10 w-10 items-center justify-center rounded-full">
                                            <i class="fas fa-{{ $project->project_seat_assign ? "chair text-green-600" : "times text-red-600" }} text-lg"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="{{ $project->project_seat_assign ? "text-green-900" : "text-red-900" }} font-medium">การจัดที่นั่ง</p>
                                            <p class="{{ $project->project_seat_assign ? "text-green-700" : "text-red-700" }} text-sm">
                                                {{ $project->project_seat_assign ? "เปิดใช้งาน" : "ปิดใช้งาน" }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="{{ $project->project_seat_assign ? "bg-green-100 text-green-800" : "bg-red-100 text-red-800" }} inline-flex items-center rounded-full px-3 py-1 text-xs font-medium">
                                        <i class="fas fa-{{ $project->project_seat_assign ? "check-circle" : "times-circle" }} mr-1"></i>
                                        {{ $project->project_seat_assign ? "เปิด" : "ปิด" }}
                                    </span>
                                </div>
                            </div>

                            <div class="{{ $project->project_register_today ? "border-green-200 bg-green-50" : "border-red-200 bg-red-50" }} rounded-lg border-2 p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="{{ $project->project_register_today ? "bg-green-100" : "bg-red-100" }} flex h-10 w-10 items-center justify-center rounded-full">
                                            <i class="fas fa-{{ $project->project_register_today ? "calendar-day text-green-600" : "times text-red-600" }} text-lg"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="{{ $project->project_register_today ? "text-green-900" : "text-red-900" }} font-medium">เปิดให้ลงทะเบียนในวันที่มีการจัดหลักสูตร</p>
                                            <p class="{{ $project->project_register_today ? "text-green-700" : "text-red-700" }} text-sm">
                                                {{ $project->project_register_today ? "เปิดใช้งาน" : "ปิดใช้งาน" }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="{{ $project->project_register_today ? "bg-green-100 text-green-800" : "bg-red-100 text-red-800" }} inline-flex items-center rounded-full px-3 py-1 text-xs font-medium">
                                        <i class="fas fa-{{ $project->project_register_today ? "check-circle" : "times-circle" }} mr-1"></i>
                                        {{ $project->project_register_today ? "เปิด" : "ปิด" }}
                                    </span>
                                </div>
                            </div>

                            <div class="{{ $project->project_group_assign ? "border-green-200 bg-green-50" : "border-red-200 bg-red-50" }} rounded-lg border-2 p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="{{ $project->project_group_assign ? "bg-green-100" : "bg-red-100" }} flex h-10 w-10 items-center justify-center rounded-full">
                                            <i class="fas fa-{{ $project->project_group_assign ? "users text-green-600" : "times text-red-600" }} text-lg"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="{{ $project->project_group_assign ? "text-green-900" : "text-red-900" }} font-medium">การจัดกลุ่ม</p>
                                            <p class="{{ $project->project_group_assign ? "text-green-700" : "text-red-700" }} text-sm">
                                                {{ $project->project_group_assign ? "เปิดใช้งาน" : "ปิดใช้งาน" }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="{{ $project->project_group_assign ? "bg-green-100 text-green-800" : "bg-red-100 text-red-800" }} inline-flex items-center rounded-full px-3 py-1 text-xs font-medium">
                                        <i class="fas fa-{{ $project->project_group_assign ? "check-circle" : "times-circle" }} mr-1"></i>
                                        {{ $project->project_group_assign ? "เปิด" : "ปิด" }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Cards -->
            <div class="grid grid-cols-1 gap-6 py-6 md:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition-shadow duration-200 hover:shadow-md">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100">
                                <i class="fas fa-calendar text-xl text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">วันที่ทั้งหมด</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $project->dates->where("date_delete", false)->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition-shadow duration-200 hover:shadow-md">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100">
                                <i class="fas fa-clock text-xl text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">ช่วงเวลาทั้งหมด</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $project->dates->where("date_delete", false)->sum(function ($date) {return $date->times->where("time_delete", false)->count();}) }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition-shadow duration-200 hover:shadow-md">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100">
                                <i class="fas fa-users text-xl text-purple-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">ผู้เข้าร่วมทั้งหมด</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $project->getUniqueParticipantsCount() }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition-shadow duration-200 hover:shadow-md">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-yellow-100">
                                <i class="fas fa-link text-xl text-yellow-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">ลิงก์ทั้งหมด</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $project->links->where("link_delete", false)->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reports and Management Section -->
            <div class="space-y-6">
                <!-- Export Reports Section -->
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <h2 class="mb-6 flex items-center text-lg font-semibold text-gray-900">
                        <i class="fas fa-chart-line mr-3 text-blue-600"></i>
                        รายงานการส่งออก
                    </h2>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <a class="group flex items-center rounded-lg bg-green-50 p-4 transition-colors duration-200 hover:bg-green-100" href="{{ route("hrd.admin.export.excel.all_date", $project->id) }}">
                            <div class="flex-shrink-0">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 transition-colors duration-200 group-hover:bg-green-200">
                                    <i class="fas fa-file-excel text-green-600"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="font-semibold text-green-900">รายงานผู้ลงทะเบียนทั้งหมด</p>
                                <p class="text-sm text-green-700">ดาวน์โหลดข้อมูลผู้ลงทะเบียนทั้งหมด</p>
                            </div>
                            <i class="fas fa-download me-3 text-green-600 transition-transform duration-200 group-hover:translate-x-1"></i>
                        </a>

                        <a class="group flex items-center rounded-lg bg-blue-50 p-4 transition-colors duration-200 hover:bg-blue-100" href="{{ route("hrd.admin.export.excel.dbd", $project->id) }}">
                            <div class="flex-shrink-0">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 transition-colors duration-200 group-hover:bg-blue-200">
                                    <i class="fas fa-file-excel text-blue-600"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="font-semibold text-blue-900">แบบฟอร์มกรมพัฒน์</p>
                                <p class="text-sm text-blue-700">รายงานแบบฟอร์มกรมพัฒน์</p>
                            </div>
                            <i class="fas fa-download me-3 text-blue-600 transition-transform duration-200 group-hover:translate-x-1"></i>
                        </a>

                        <div class="group flex-col items-center rounded-lg bg-purple-50 p-4 transition-colors duration-200 hover:bg-purple-100">
                            <a href="{{ route("hrd.admin.export.excel.onebook", $project->id) }}">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 transition-colors duration-200 group-hover:bg-purple-200">
                                            <i class="fas fa-file-excel text-purple-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <p class="font-semibold text-purple-900">รายงาน Onebook</p>
                                        <p class="text-sm text-purple-700">รายงาน Onebook สำหรับโปรเจกต์</p>
                                    </div>
                                    <i class="fas fa-download me-3 text-purple-600 transition-transform duration-200 group-hover:translate-x-1"></i>
                                </div>
                            </a>
                            <div class="mt-3">
                                <form class="flex items-center space-x-4" action="{{ route("hrd.admin.export.hours.onebook") }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                                    <div class="flex flex-1 flex-col">
                                        <label class="whitespace-nowrap text-sm font-bold text-gray-700">
                                            Break Time <span class="text-red-500">*</span>
                                        </label>
                                    </div>
                                    <div class="flex items-stretch">
                                        <div class="relative flex items-center">
                                            <input class="w-32 rounded-l-lg border-2 border-r-0 border-gray-200 px-4 py-2 text-sm transition-all focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-200" min="0" type="number" name="input" value="{{ $project->onebook?->skip_hours }}" placeholder="0">
                                            <span class="absolute right-3 text-xs text-gray-400">hrs</span>
                                        </div>
                                        <button class="flex items-center justify-center rounded-r-lg bg-purple-600 px-4 py-2 text-white transition-all hover:bg-purple-700 active:scale-95" type="submit" title="Save Changes">
                                            <i class="fa fa-save mr-2"></i>
                                            <span class="text-sm font-medium">Save</span>
                                        </button>
                                    </div>
                                </form>

                                <p class="mt-1 flex items-center text-[11px] text-gray-500">
                                    <i class="fa fa-info-circle mr-1 text-blue-500"></i>
                                    เวลาจะนำไปลบออกออกจากชั่วโมงการฝึกอบรม
                                </p>
                            </div>
                        </div>

                        @if ($project->dms_id !== null)
                            <a class="group flex items-center rounded-lg bg-yellow-50 p-4 transition-colors duration-200 hover:bg-yellow-100" href="https://pr9web.praram9.com/dms/training/download-pdf/{{ $project->dms_id }}">
                                <div class="flex-shrink-0">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-yellow-100 transition-colors duration-200 group-hover:bg-yellow-200">
                                        <i class="fas fa-file-excel text-yellow-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <p class="font-semibold text-yellow-900">ใบบันทึกการฝึกอบรมภาคอิสระ</p>
                                    <p class="text-sm text-yellow-700">ดาวน์โหลดใบบันทึกการฝึกอบรมภาคอิสระ</p>
                                </div>
                                <i class="fas fa-download me-3 text-yellow-600 transition-transform duration-200 group-hover:translate-x-1"></i>
                            </a>
                        @endif
                    </div>

                    <!-- Dates and Times Section -->
                    <div class="rounded-xl bg-white p-6 shadow-sm">
                        <h2 class="mb-6 flex items-center text-lg font-semibold text-gray-900">
                            <i class="fas fa-calendar-alt mr-3 text-blue-600"></i>
                            วันที่และเวลา
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">วันที่</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">สถานะ</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">เวลา</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">สถานที่</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">รายละเอียด</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">การดำเนินการ</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach ($project->dates->where("date_delete", false) as $date)
                                        <tr class="bg-gray-50">
                                            <td class="whitespace-nowrap px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $date->date_title }}</div>
                                                <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($date->date_datetime)->format("l, d F Y") }}</div>
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4">
                                                <span class="{{ $date->date_active ? "bg-green-100 text-green-800" : "bg-red-100 text-red-800" }} inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                                                    <i class="fas fa-{{ $date->date_active ? "check-circle" : "times-circle" }} mr-1"></i>
                                                    {{ $date->date_active ? "ใช้งาน" : "ไม่ใช้งาน" }}
                                                </span>
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4">
                                                @if ($date->times->where("time_delete", false)->count() > 0)
                                                    <div class="text-sm text-gray-900">
                                                        {{ $date->times->where("time_delete", false)->count() }} ช่วงเวลา
                                                    </div>
                                                @else
                                                    <div class="text-sm text-gray-500">ไม่มีช่วงเวลา</div>
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4">
                                                @if ($date->date_location)
                                                    <div class="text-sm text-gray-900">{{ $date->date_location }}</div>
                                                @else
                                                    <div class="text-sm text-gray-500">ไม่ระบุ</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @if ($date->date_detail)
                                                    <div class="max-w-xs truncate text-sm text-gray-900" title="{{ $date->date_detail }}">{{ $date->date_detail }}</div>
                                                @else
                                                    <div class="text-sm text-gray-500">ไม่มีรายละเอียด</div>
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4">
                                                <a class="inline-flex items-center rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-white transition-colors duration-200 hover:bg-orange-700" href="{{ route("hrd.admin.export.excel.date", $date->id) }}">
                                                    <i class="fas fa-file-excel mr-2"></i>
                                                    Export
                                                </a>
                                            </td>
                                        </tr>
                                        @if ($date->times->where("time_delete", false)->count() > 0)
                                            @foreach ($date->times->where("time_delete", false) as $time)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="whitespace-nowrap px-6 py-4">
                                                        <div class="ml-6 text-sm font-medium text-gray-900">{{ $time->time_title }}</div>
                                                        <div class="ml-6 text-sm text-gray-500">{{ $time->time_start }} - {{ $time->time_end }}</div>
                                                    </td>
                                                    <td class="whitespace-nowrap px-6 py-4">
                                                        <span class="{{ $time->time_active ? "bg-green-100 text-green-800" : "bg-red-100 text-red-800" }} inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                                                            <i class="fas fa-{{ $time->time_active ? "check-circle" : "times-circle" }} mr-1"></i>
                                                            {{ $time->time_active ? "ใช้งาน" : "ไม่ใช้งาน" }}
                                                        </span>
                                                    </td>
                                                    <td class="whitespace-nowrap px-6 py-4">
                                                        @if ($time->time_limit)
                                                            <div class="text-sm text-gray-900">สูงสุด: {{ $time->time_max }} คน</div>
                                                            <div class="text-sm text-gray-500">ลงทะเบียนแล้ว: {{ $time->activeAttendsCount($project->id) }} คน</div>
                                                        @else
                                                            <div class="text-sm text-gray-500">ไม่จำกัด</div>
                                                        @endif
                                                    </td>
                                                    <td class="whitespace-nowrap px-6 py-4">
                                                        <div class="text-sm text-gray-500">-</div>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        @if ($time->time_detail)
                                                            <div class="max-w-xs truncate text-sm text-gray-900" title="{{ $time->time_detail }}">{{ $time->time_detail }}</div>
                                                        @else
                                                            <div class="text-sm text-gray-500">ไม่มีรายละเอียด</div>
                                                        @endif
                                                    </td>
                                                    <td class="whitespace-nowrap px-6 py-4">
                                                        <a class="inline-flex items-center rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-white transition-colors duration-200 hover:bg-red-700" href="{{ route("hrd.admin.export.pdf.time", ["project_id" => $project->id, "time_id" => $time->id]) }}">
                                                            <i class="fas fa-file-pdf mr-2"></i>
                                                            ใบลงทะเบียน
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Links Section -->
                @if ($project->links->where("link_delete", false)->count() > 0)
                    <div class="mt-8 rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <h2 class="flex items-center text-lg font-semibold text-gray-900">
                                    <i class="fas fa-link mr-3 text-blue-600"></i>
                                    ลิงก์โปรเจกต์
                                </h2>
                                <button class="text-sm font-medium text-blue-600 hover:text-blue-800" onclick="toggleSection('linksSection')">
                                    <i class="fas fa-eye mr-1"></i>
                                    แสดง/ซ่อน
                                </button>
                            </div>
                        </div>
                        <div class="hidden p-6" id="linksSection">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                @foreach ($project->links->where("link_delete", false) as $link)
                                    <div class="rounded-lg border border-gray-200 p-4 transition-shadow duration-200 hover:shadow-md">
                                        <div class="mb-3 flex items-start justify-between">
                                            <h3 class="font-semibold text-gray-900">{{ $link->link_name }}</h3>
                                            <span class="{{ !$link->link_delete ? "bg-green-100 text-green-800" : "bg-red-100 text-red-800" }} inline-flex items-center rounded-full px-2 py-1 text-xs font-medium">
                                                <i class="fas fa-{{ !$link->link_delete ? "check-circle" : "times-circle" }} mr-1"></i>
                                                {{ !$link->link_delete ? "ใช้งาน" : "ถูกลบ" }}
                                            </span>
                                        </div>
                                        <a class="mb-3 block break-all text-sm text-blue-600 hover:text-blue-800" href="{{ $link->link_url }}" target="_blank">
                                            {{ $link->link_url }}
                                        </a>
                                        @if ($link->link_limit)
                                            <div class="space-y-1 text-xs text-gray-600">
                                                <p class="flex items-center">
                                                    <i class="fas fa-clock mr-2"></i>
                                                    ใช้งานได้:
                                                </p>
                                                <p class="ml-4">ตั้งแต่: {{ $link->link_time_start ? \Carbon\Carbon::parse($link->link_time_start)->format("d/m/Y H:i") : "ไม่จำกัด" }}</p>
                                                <p class="ml-4">จนถึง: {{ $link->link_time_end ? \Carbon\Carbon::parse($link->link_time_end)->format("d/m/Y H:i") : "ไม่จำกัด" }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Recent Attendees Section -->
                @if ($project->attends->where("attend_delete", false)->count() > 0)
                    <div class="mt-8 rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <h2 class="flex items-center text-lg font-semibold text-gray-900">
                                    <i class="fas fa-users mr-3 text-blue-600"></i>
                                    ผู้เข้าร่วมล่าสุด
                                </h2>
                                <button class="text-sm font-medium text-blue-600 hover:text-blue-800" onclick="toggleSection('attendeesSection')">
                                    <i class="fas fa-eye mr-1"></i>
                                    แสดง/ซ่อน
                                </button>
                            </div>
                        </div>
                        <div class="hidden p-6" id="attendeesSection">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">ผู้ใช้</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">วันที่</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">เวลา</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">ลงทะเบียนเมื่อ</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">สถานะ</th>

                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @foreach ($project->attends->where("attend_delete", false)->take(10) as $attend)
                                            <tr class="hover:bg-gray-50">
                                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                                    {{ $attend->user_display_name }}
                                                </td>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $attend->date->date_title ?? "N/A" }}</td>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $attend->time->time_title ?? "N/A" }}</td>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ \Carbon\Carbon::parse($attend->created_at)->format("d/m/Y H:i") }}</td>
                                                <td class="whitespace-nowrap px-6 py-4">
                                                    @if ($attend->approve_datetime)
                                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                                            <i class="fas fa-check-circle mr-1"></i>
                                                            Check in
                                                        </span>
                                                        <div class="mt-1 text-xs text-gray-500">
                                                            {{ \Carbon\Carbon::parse($attend->approve_datetime)->format("d/m/Y H:i") }}
                                                        </div>
                                                    @else
                                                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            waiting
                                                        </span>
                                                    @endif
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if ($project->attends->where("attend_delete", false)->count() > 10)
                                <div class="mt-4 text-center">
                                    <span class="text-sm text-gray-600">แสดง 10 จาก {{ $project->attends->where("attend_delete", false)->count() }} คน</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Advanced Settings Section -->
                <div class="mt-8 rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h2 class="flex items-center text-lg font-semibold text-red-800">
                                <i class="fas fa-cog mr-3 text-red-600"></i>
                                การตั้งค่าขั้นสูง
                            </h2>
                            <button class="text-sm font-medium text-red-600 hover:text-red-800" onclick="toggleAdvancedSettings()">
                                <i class="fas fa-eye mr-1"></i>
                                แสดง/ซ่อน
                            </button>
                        </div>
                    </div>
                    <div class="hidden" id="advancedSettingsSection">
                        <div class="border-l-4 border-red-400 bg-red-50 p-6">
                            <div class="mb-4">
                                <h3 class="mb-2 flex items-center text-lg font-semibold text-red-800">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    โปรดระวัง: การตั้งค่าขั้นสูง
                                </h3>
                                <p class="text-sm text-red-700">
                                    การดำเนินการในส่วนนี้อาจส่งผลกระทบอย่างร้ายแรงต่อโปรเจกต์ กรุณาใช้ด้วยความระมัดระวัง
                                </p>
                            </div>

                            <!-- Delete Project Section -->
                            <div class="rounded-lg border border-red-300 bg-white p-4">
                                <h4 class="mb-3 flex items-center font-semibold text-red-800">
                                    <i class="fas fa-trash mr-2"></i>
                                    ลบโปรเจกต์
                                </h4>
                                <p class="mb-4 text-sm text-gray-700">
                                    การลบโปรเจกต์จะลบข้อมูลทั้งหมดที่เกี่ยวข้องอย่างถาวร ไม่สามารถกู้คืนได้
                                </p>

                                <!-- First confirmation -->
                                <div class="mb-4">
                                    <label class="flex items-center">
                                        <input class="mr-2 rounded border-gray-300 text-red-600 focus:ring-red-500" id="confirmDelete1" type="checkbox">
                                        <span class="text-sm text-gray-700">ฉันเข้าใจว่าการลบโปรเจกต์จะลบข้อมูลทั้งหมดอย่างถาวร</span>
                                    </label>
                                </div>

                                <!-- Second confirmation -->
                                <div class="mb-4">
                                    <label class="flex items-center">
                                        <input class="mr-2 rounded border-gray-300 text-red-600 focus:ring-red-500" id="confirmDelete2" type="checkbox">
                                        <span class="text-sm text-gray-700">ฉันได้สำรองข้อมูลที่จำเป็นแล้ว</span>
                                    </label>
                                </div>

                                <!-- Type confirmation -->
                                <div class="mb-4">
                                    <label class="mb-2 block text-sm font-medium text-gray-700">
                                        พิมพ์ "DELETE" เพื่อยืนยันการลบ:
                                    </label>
                                    <input class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-red-500 focus:outline-none focus:ring-red-500" id="deleteConfirmation" type="text" placeholder="พิมพ์ DELETE">
                                </div>

                                <!-- Delete button (disabled by default) -->
                                <button class="cursor-not-allowed rounded-lg bg-red-600 px-4 py-2 font-semibold text-white opacity-50" id="deleteProjectBtn" disabled onclick="confirmDelete()">
                                    <i class="fas fa-trash mr-2"></i>
                                    ลบโปรเจกต์
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); z-index:100; align-items:center; justify-content:center;">
            <div style="background:#fff; border-radius:1rem; padding:2rem; min-width:400px; box-shadow:0 2px 16px rgba(0,0,0,0.15); text-align:center;">
                <div style="font-size:1.2rem; font-weight:600; margin-bottom:1rem; color:#dc2626;">
                    <i class="fas fa-exclamation-triangle"></i> ยืนยันการลบ
                </div>
                <div style="margin-bottom:1.5rem; color:#374151;">
                    คุณแน่ใจหรือไม่ที่จะลบโปรเจกต์นี้? การดำเนินการนี้ไม่สามารถยกเลิกได้
                </div>
                <div style="display:flex; gap:1rem; justify-content:center;">
                    <button onclick="hideDeleteModal()" style="background:#6b7280; color:#fff; border:none; border-radius:0.5rem; padding:0.75rem 1.5rem; font-size:1rem; cursor:pointer;">
                        ยกเลิก
                    </button>
                    <button id="modalDeleteBtn" onclick="deleteProject()" style="background:#dc2626; color:#fff; border:none; border-radius:0.5rem; padding:0.75rem 1.5rem; font-size:1rem; font-weight:600; cursor:pointer;">
                        ลบโปรเจกต์
                    </button>
                </div>
            </div>
        </div>

    @endsection

    @section("scripts")
        <script>
            function toggleAdvancedSettings() {
                const section = document.getElementById('advancedSettingsSection');
                if (section.classList.contains('hidden')) {
                    section.classList.remove('hidden');
                } else {
                    section.classList.add('hidden');
                }
            }

            function toggleSection(sectionId) {
                const section = document.getElementById(sectionId);
                if (section.classList.contains('hidden')) {
                    section.classList.remove('hidden');
                } else {
                    section.classList.add('hidden');
                }
            }

            function switchTab(tabId) {
                // Hide all tab contents
                const tabContents = document.querySelectorAll('.tab-content');
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });

                // Remove active class from all tab buttons
                const tabButtons = document.querySelectorAll('.tab-button');
                tabButtons.forEach(button => {
                    button.classList.remove('border-blue-500', 'text-blue-600');
                    button.classList.add('border-transparent', 'text-gray-500');
                });

                // Show selected tab content
                const selectedTab = document.getElementById(tabId);
                if (selectedTab) {
                    selectedTab.classList.remove('hidden');
                }

                // Add active class to clicked button
                const clickedButton = event.target.closest('.tab-button');
                if (clickedButton) {
                    clickedButton.classList.remove('border-transparent', 'text-gray-500');
                    clickedButton.classList.add('border-blue-500', 'text-blue-600');
                }
            }

            // Delete confirmation logic
            function checkDeleteConfirmation() {
                const checkbox1 = document.getElementById('confirmDelete1');
                const checkbox2 = document.getElementById('confirmDelete2');
                const textInput = document.getElementById('deleteConfirmation');
                const deleteBtn = document.getElementById('deleteProjectBtn');

                if (checkbox1.checked && checkbox2.checked && textInput.value === 'DELETE') {
                    deleteBtn.disabled = false;
                    deleteBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    deleteBtn.classList.add('hover:bg-red-700');
                } else {
                    deleteBtn.disabled = true;
                    deleteBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    deleteBtn.classList.remove('hover:bg-red-700');
                }
            }

            // Add event listeners for delete confirmation
            document.addEventListener('DOMContentLoaded', function() {
                const checkbox1 = document.getElementById('confirmDelete1');
                const checkbox2 = document.getElementById('confirmDelete2');
                const textInput = document.getElementById('deleteConfirmation');

                if (checkbox1) checkbox1.addEventListener('change', checkDeleteConfirmation);
                if (checkbox2) checkbox2.addEventListener('change', checkDeleteConfirmation);
                if (textInput) textInput.addEventListener('input', checkDeleteConfirmation);

                // Adjust project name font size based on length
                adjustProjectNameFontSize();
            });

            function adjustProjectNameFontSize() {
                const projectNameElement = document.getElementById('projectName');
                if (!projectNameElement) return;

                const projectName = projectNameElement.textContent.trim();
                const length = projectName.length;

                let fontSize = '1.875rem'; // Default 3xl (30px)

                if (length > 50) {
                    fontSize = '1.5rem'; // text-2xl (24px)
                } else if (length > 30) {
                    fontSize = '1.25rem'; // text-xl (20px)
                } else if (length > 20) {
                    fontSize = '1.125rem'; // text-lg (18px)
                } else if (length > 10) {
                    fontSize = '1rem'; // text-base (16px)
                } else {
                    fontSize = '1.875rem'; // text-3xl (30px) for short names
                }

                projectNameElement.style.fontSize = fontSize;
            }

            function confirmDelete() {
                document.getElementById('deleteModal').style.display = 'flex';
            }

            function hideDeleteModal() {
                document.getElementById('deleteModal').style.display = 'none';
            }

            function deleteProject() {
                // Show loading state with SweetAlert
                Swal.fire({
                    title: 'กำลังลบโปรเจกต์...',
                    text: 'กรุณารอสักครู่',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Show loading state on button - with null check
                const deleteBtn = document.getElementById('modalDeleteBtn');
                let originalText = '';
                if (deleteBtn) {
                    originalText = deleteBtn.innerHTML;
                    deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>กำลังลบ...';
                    deleteBtn.disabled = true;
                } else {
                    console.warn('Delete button not found in modal');
                }

                axios.post(`{{ route("hrd.admin.projects.delete", $project->id) }}`)
                    .then(response => {
                        // Close loading SweetAlert first
                        Swal.close();

                        // Handle successful deletion
                        if (response.data && response.data.success) {
                            // Show success message with SweetAlert
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ!',
                                text: response.data.message || 'Project deleted successfully',
                                confirmButtonText: 'ตกลง',
                                confirmButtonColor: '#3085d6'
                            }).then((result) => {
                                // Redirect to admin index
                                window.location.href = response.data.redirect_url || '{{ route("hrd.admin.index") }}';
                            });
                        } else {
                            // Handle unexpected response
                            const errorMsg = response.data && response.data.message ? response.data.message : 'เกิดข้อผิดพลาดที่ไม่ทราบสาเหตุ';
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: errorMsg,
                                confirmButtonText: 'ตกลง',
                                confirmButtonColor: '#d33'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting project:', error);

                        // Close loading SweetAlert first
                        Swal.close();

                        // Get detailed error information
                        let errorMessage = 'เกิดข้อผิดพลาดในการลบโปรเจกต์';

                        if (error.response) {
                            // Server responded with error status
                            const status = error.response.status;
                            const data = error.response.data;

                            switch (status) {
                                case 403:
                                    errorMessage = 'ไม่มีสิทธิ์ในการลบโปรเจกต์นี้\nคุณไม่มีสิทธิ์ในการลบโปรเจกต์นี้ กรุณาติดต่อผู้ดูแลระบบ';
                                    break;
                                case 404:
                                    errorMessage = 'ไม่พบโปรเจกต์ที่ต้องการลบ\n' + (data.message || 'โปรเจกต์นี้อาจถูกลบไปแล้วหรือไม่พบในระบบ');
                                    break;
                                case 422:
                                    errorMessage = 'ไม่สามารถลบโปรเจกต์ได้\n' + (data.message || 'ไม่สามารถลบโปรเจกต์ได้ กรุณาตรวจสอบรายละเอียด');
                                    break;
                                case 500:
                                    errorMessage = 'เกิดข้อผิดพลาดที่เซิร์ฟเวอร์\n' + (data.message || 'เกิดข้อผิดพลาดภายในเซิร์ฟเวอร์ กรุณาลองใหม่อีกครั้งในภายหลัง');
                                    break;
                                default:
                                    errorMessage = `เกิดข้อผิดพลาด (รหัส: ${status})\n` + (data.message || 'เกิดข้อผิดพลาดที่ไม่ทราบสาเหตุ');
                            }

                            // Show additional details if available
                            if (data.errors) {
                                errorMessage += '\n\nรายละเอียดเพิ่มเติม:\n';
                                Object.keys(data.errors).forEach(key => {
                                    const errorValue = data.errors[key];
                                    if (Array.isArray(errorValue)) {
                                        errorMessage += `- ${key}: ${errorValue.join(', ')}\n`;
                                    } else if (typeof errorValue === 'string') {
                                        errorMessage += `- ${key}: ${errorValue}\n`;
                                    } else {
                                        errorMessage += `- ${key}: ${JSON.stringify(errorValue)}\n`;
                                    }
                                });
                            }
                        } else if (error.request) {
                            // Network error
                            errorMessage = 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์\nกรุณาตรวจสอบการเชื่อมต่ออินเทอร์เน็ตและลองใหม่อีกครั้ง';
                        } else {
                            // Other error
                            errorMessage = 'เกิดข้อผิดพลาดที่ไม่ทราบสาเหตุ\n' + (error.message || 'เกิดข้อผิดพลาดที่ไม่สามารถระบุได้');
                        }

                        // Show error with SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            html: errorMessage.replace(/\n/g, '<br>'),
                            confirmButtonText: 'ตกลง',
                            confirmButtonColor: '#d33',
                            width: '600px'
                        });
                    })
                    .finally(() => {
                        // Reset button state - with null check
                        if (deleteBtn) {
                            deleteBtn.innerHTML = originalText;
                            deleteBtn.disabled = false;
                        } else {
                            console.warn('Delete button not found when resetting state');
                        }
                        hideDeleteModal();
                    });
            }
        </script>
    @endsection
