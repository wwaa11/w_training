@extends("layouts.hrd")
@section("content")
    <div class="m-auto flex">
        <div class="m-auto mt-3 w-full rounded p-3 md:w-3/4">
            <div class="flex items-center justify-between">
                <div class="text-2xl font-bold">ประวัติการเข้าร่วม - {{ $user->userid }} {{ $user->name }}</div>
                <a class="cursor-pointer rounded bg-gray-500 p-2 text-white hover:bg-gray-600" href="{{ route("hrd.admin.users.index") }}">
                    <i class="fa-solid fa-arrow-left"></i> กลับไปยังรายการผู้ใช้
                </a>
            </div>
            <hr class="my-4">

            <div class="mb-4 rounded bg-blue-50 p-4">
                <h3 class="text-lg font-semibold text-blue-800">ข้อมูลผู้ใช้</h3>
                <div class="mt-2 grid grid-cols-1 gap-2 md:grid-cols-3">
                    <div><strong>รหัสพนักงาน:</strong> {{ $user->userid }}</div>
                    <div><strong>ชื่อ-สกุล:</strong> {{ $user->name }}</div>
                    <div><strong>ตำแหน่ง:</strong> {{ $user->position }}</div>
                    <div><strong>แผนก:</strong> {{ $user->department }}</div>
                    <div><strong>วันที่สมัคร:</strong> {{ $user->created_at ? $user->created_at->format("d/m/Y H:i") : "-" }}</div>
                    <div><strong>อัปเดตล่าสุด:</strong> {{ $user->updated_at ? $user->updated_at->format("d/m/Y H:i") : "-" }}</div>
                </div>
            </div>

            <div class="mt-4">
                {{ $attendances->links() }}
            </div>

            @if ($attendances->count() > 0)
                <table class="my-3 w-full rounded bg-white p-3">
                    <thead class="bg-gray-200">
                        <th class="border p-3">โครงการ</th>
                        <th class="border p-3">วันที่</th>
                        <th class="border p-3">เวลา</th>
                        <th class="border p-3">สถานะการลงทะเบียน</th>
                        <th class="border p-3">สถานะการเข้าร่วม</th>
                        <th class="border p-3">วันที่เข้าร่วม</th>
                        <th class="border p-3">สถานะการอนุมัติ</th>
                    </thead>
                    <tbody>
                        @foreach ($attendances as $attendance)
                            <tr>
                                <td class="border p-2">
                                    <div class="font-semibold">{{ $attendance->project->project_name ?? "N/A" }}</div>
                                    <div class="text-sm text-gray-600">{{ $attendance->project->project_description ?? "" }}</div>
                                </td>
                                <td class="border p-2 text-center">
                                    @if ($attendance->date)
                                        {{ $attendance->date->date_datetime ? $attendance->date->date_datetime->format("d/m/Y") : "N/A" }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="border p-2 text-center">
                                    @if ($attendance->time)
                                        {{ $attendance->time->time_start ? \Carbon\Carbon::parse($attendance->time->time_start)->format("H:i") : "N/A" }} -
                                        {{ $attendance->time->time_end ? \Carbon\Carbon::parse($attendance->time->time_end)->format("H:i") : "N/A" }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="border p-2 text-center">
                                    @if ($attendance->created_at)
                                        <span class="rounded bg-green-100 px-2 py-1 text-sm text-green-800">
                                            <i class="fa-solid fa-check"></i> ลงทะเบียนแล้ว
                                        </span>
                                        <div class="mt-1 text-xs text-gray-600">
                                            {{ $attendance->created_at->format("d/m/Y H:i") }}
                                        </div>
                                    @else
                                        <span class="rounded bg-red-100 px-2 py-1 text-sm text-red-800">
                                            <i class="fa-solid fa-times"></i> ไม่ได้ลงทะเบียน
                                        </span>
                                    @endif
                                </td>
                                <td class="border p-2 text-center">
                                    @if ($attendance->attend_datetime)
                                        <span class="rounded bg-green-100 px-2 py-1 text-sm text-green-800">
                                            <i class="fa-solid fa-check"></i> เข้าร่วมแล้ว
                                        </span>
                                    @else
                                        <span class="rounded bg-yellow-100 px-2 py-1 text-sm text-yellow-800">
                                            <i class="fa-solid fa-clock"></i> ยังไม่เข้าร่วม
                                        </span>
                                    @endif
                                </td>
                                <td class="border p-2 text-center">
                                    @if ($attendance->attend_datetime)
                                        {{ $attendance->attend_datetime->format("d/m/Y H:i") }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="border p-2 text-center">
                                    @if ($attendance->approve_datetime)
                                        <span class="rounded bg-green-100 px-2 py-1 text-sm text-green-800">
                                            <i class="fa-solid fa-check"></i> อนุมัติแล้ว
                                        </span>
                                        <div class="mt-1 text-xs text-gray-600">
                                            {{ $attendance->approve_datetime->format("d/m/Y H:i") }}
                                        </div>
                                    @else
                                        <span class="rounded bg-yellow-100 px-2 py-1 text-sm text-yellow-800">
                                            <i class="fa-solid fa-clock"></i> รอการอนุมัติ
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="my-8 text-center">
                    <div class="text-lg text-gray-500">
                        <i class="fa-solid fa-calendar-times mb-4 text-4xl"></i>
                        <p>ไม่พบประวัติการเข้าร่วมสำหรับผู้ใช้นี้</p>
                    </div>
                </div>
            @endif

            <div class="mt-4">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
@endsection
