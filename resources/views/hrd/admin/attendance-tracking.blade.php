@extends("layouts.hrd")
@section("content")
    <div class="min-h-screen bg-gray-50 py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100">
                            <i class="fa-solid fa-user text-xl text-blue-600"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">ประวัติการเข้าร่วม</h1>
                            <p class="text-sm text-gray-600">{{ $user->userid }} - {{ $user->name }}</p>
                        </div>
                    </div>
                    <a class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 transition-all hover:bg-gray-50 hover:shadow-md" href="{{ route("hrd.admin.users.index") }}">
                        <i class="fa-solid fa-arrow-left"></i>
                        กลับไปยังรายการผู้ใช้
                    </a>
                </div>
            </div>

            <!-- User Information Card -->
            <div class="mb-8 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
                <div class="border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">
                        <i class="fa-solid fa-info-circle mr-2 text-blue-600"></i>
                        ข้อมูลผู้ใช้
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100">
                                <i class="fa-solid fa-id-card text-sm text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">รหัสพนักงาน</p>
                                <p class="text-sm text-gray-900">{{ $user->userid }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100">
                                <i class="fa-solid fa-user text-sm text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">ชื่อ-สกุล</p>
                                <p class="text-sm text-gray-900">{{ $user->name }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-purple-100">
                                <i class="fa-solid fa-briefcase text-sm text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">ตำแหน่ง</p>
                                <p class="text-sm text-gray-900">{{ $user->position }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-orange-100">
                                <i class="fa-solid fa-building text-sm text-orange-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">แผนก</p>
                                <p class="text-sm text-gray-900">{{ $user->department }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-teal-100">
                                <i class="fa-solid fa-calendar-plus text-sm text-teal-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">วันที่สมัคร</p>
                                <p class="text-sm text-gray-900">{{ $user->created_at ? $user->created_at->format("d/m/Y H:i") : "-" }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100">
                                <i class="fa-solid fa-clock text-sm text-indigo-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">อัปเดตล่าสุด</p>
                                <p class="text-sm text-gray-900">{{ $user->updated_at ? $user->updated_at->format("d/m/Y H:i") : "-" }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <div class="flex items-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100">
                            <i class="fa-solid fa-calendar-check text-xl text-blue-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">ทั้งหมด</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $attendances->total() }}</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <div class="flex items-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                            <i class="fa-solid fa-check-circle text-xl text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">เข้าร่วมแล้ว</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $attendances->where("attend_datetime", "!=", null)->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <div class="flex items-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-100">
                            <i class="fa-solid fa-clock text-xl text-yellow-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">รอเข้าร่วม</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $attendances->where("attend_datetime", null)->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <div class="flex items-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-100">
                            <i class="fa-solid fa-thumbs-up text-xl text-purple-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">อนุมัติแล้ว</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $attendances->where("approve_datetime", "!=", null)->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Records -->
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
                <div class="border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">
                        <i class="fa-solid fa-list-ul mr-2 text-gray-600"></i>
                        รายการเข้าร่วม ({{ $attendances->total() }} รายการ)
                    </h2>
                </div>

                @if ($attendances->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">โครงการ</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">วันที่</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">เวลา</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">การลงทะเบียน</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">การเข้าร่วม</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">การอนุมัติ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach ($attendances as $attendance)
                                    <tr class="transition-colors hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-start">
                                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100">
                                                    <i class="fa-solid fa-project-diagram text-blue-600"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ $attendance->project->project_name ?? "N/A" }}</div>
                                                    @if ($attendance->project->project_description)
                                                        <div class="mt-1 line-clamp-2 text-xs text-gray-500">{{ $attendance->project->project_description }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100">
                                                    <i class="fa-solid fa-calendar text-sm text-green-600"></i>
                                                </div>
                                                <div class="ml-2 text-sm text-gray-900">
                                                    @if ($attendance->date && $attendance->date->date_datetime)
                                                        {{ $attendance->date->date_datetime->format("d/m/Y") }}
                                                    @else
                                                        <span class="text-gray-400">N/A</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-purple-100">
                                                    <i class="fa-solid fa-clock text-sm text-purple-600"></i>
                                                </div>
                                                <div class="ml-2 text-sm text-gray-900">
                                                    @if ($attendance->time)
                                                        {{ \Carbon\Carbon::parse($attendance->time->time_start)->format("H:i") }} -
                                                        {{ \Carbon\Carbon::parse($attendance->time->time_end)->format("H:i") }}
                                                    @else
                                                        <span class="text-gray-400">N/A</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($attendance->created_at)
                                                <div class="flex items-center">
                                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                                        <i class="fa-solid fa-check mr-1"></i>
                                                        ลงทะเบียนแล้ว
                                                    </span>
                                                </div>
                                                <div class="mt-1 text-xs text-gray-500">
                                                    {{ $attendance->created_at->format("d/m/Y H:i") }}
                                                </div>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                                    <i class="fa-solid fa-times mr-1"></i>
                                                    ไม่ได้ลงทะเบียน
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($attendance->attend_datetime)
                                                <div class="flex items-center">
                                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                                        <i class="fa-solid fa-check mr-1"></i>
                                                        เข้าร่วมแล้ว
                                                    </span>
                                                </div>
                                                <div class="mt-1 text-xs text-gray-500">
                                                    {{ $attendance->attend_datetime->format("d/m/Y H:i") }}
                                                </div>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">
                                                    <i class="fa-solid fa-clock mr-1"></i>
                                                    ยังไม่เข้าร่วม
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($attendance->approve_datetime)
                                                <div class="flex items-center">
                                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                                        <i class="fa-solid fa-check mr-1"></i>
                                                        อนุมัติแล้ว
                                                    </span>
                                                </div>
                                                <div class="mt-1 text-xs text-gray-500">
                                                    {{ $attendance->approve_datetime->format("d/m/Y H:i") }}
                                                </div>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">
                                                    <i class="fa-solid fa-clock mr-1"></i>
                                                    รอการอนุมัติ
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12">
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">
                            <i class="fa-solid fa-calendar-times text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">ไม่พบประวัติการเข้าร่วม</h3>
                        <p class="mt-2 text-sm text-gray-500">ผู้ใช้นี้ยังไม่มีประวัติการเข้าร่วมโครงการใดๆ</p>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if ($attendances->hasPages())
                <div class="mt-8 flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        แสดง {{ $attendances->firstItem() ?? 0 }} ถึง {{ $attendances->lastItem() ?? 0 }} จาก {{ $attendances->total() }} รายการ
                    </div>
                    <div class="flex items-center space-x-2">
                        {{ $attendances->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
