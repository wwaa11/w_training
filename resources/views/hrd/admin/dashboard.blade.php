@extends("layouts.hrd")

@section("content")
    <div class="container mx-auto px-4">
        <div class="rounded-lg bg-white p-6 shadow-lg">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-800">การจัดการโปรเจกต์ HRD</h1>
                <div class="flex items-center gap-3">
                    <a class="flex items-center gap-2 rounded-lg bg-orange-600 px-6 py-3 font-semibold text-white transition duration-200 hover:bg-orange-700" href="{{ route("hr.admin.index") }}">
                        <i class="fas fa-history"></i>
                        ระบบ HR เก่า
                    </a>
                    <a class="flex items-center gap-2 rounded-lg bg-purple-600 px-6 py-3 font-semibold text-white transition duration-200 hover:bg-purple-700" href="{{ route("hrd.admin.users.index") }}">
                        <i class="fas fa-users"></i>
                        จัดการผู้ใช้
                    </a>
                    <a class="flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white transition duration-200 hover:bg-blue-700" href="{{ route("hrd.admin.projects.create") }}">
                        <i class="fas fa-plus"></i>
                        สร้างโปรเจกต์ใหม่
                    </a>
                </div>
            </div>

            @if (session("success"))
                <div class="mb-6 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700">
                    {{ session("success") }}
                </div>
            @endif

            @if (session("error"))
                <div class="mb-6 rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700">
                    {{ session("error") }}
                </div>
            @endif

            <!-- Quick Help Section -->
            <div class="mb-6 rounded-lg bg-blue-50 p-4">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-blue-800">💡 ต้องการความช่วยเหลือ?</h3>
                        <p class="mt-1 text-sm text-blue-700">
                            ดูคู่มือการใช้งานแบบละเอียดเพื่อเรียนรู้วิธีการใช้ระบบ HRD อย่างมีประสิทธิภาพ
                        </p>
                    </div>
                    <a class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition duration-200 hover:bg-blue-700" href="{{ route("hrd.admin.documentation") }}">
                        <i class="fas fa-book"></i>
                        เปิดคู่มือ
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                ชื่อโปรเจกต์
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                ประเภท
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                ช่วงเวลาลงทะเบียน
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                วันที่
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                ผู้เข้าร่วม
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                สถานะ
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                การดำเนินการ
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($projects as $project)
                            <tr class="{{ $loop->even ? "bg-gray-50" : "bg-white" }} {{ $project->project_active ? "border-green-500" : "border-red-500" }} border-l-4 hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="{{ $project->project_active ? "bg-green-100" : "bg-red-100" }} flex h-8 w-8 items-center justify-center rounded-full">
                                                <i class="fas fa-{{ $project->project_active ? "check-circle" : "times-circle" }} {{ $project->project_active ? "text-green-600" : "text-red-600" }} text-sm"></i>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="break-words text-sm font-semibold text-gray-900">{{ $project->project_name }}</div>
                                            @if ($project->project_detail)
                                                <div class="mt-1 break-words text-sm text-gray-500">{{ Str::limit($project->project_detail, 50) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span class="@if ($project->project_type === "single") bg-blue-100 text-blue-800
                                    @elseif($project->project_type === "multiple") bg-green-100 text-green-800
                                    @else bg-purple-100 text-purple-800 @endif inline-flex rounded-full px-2 py-1 text-xs font-semibold">
                                        @if ($project->project_type === "single")
                                            ลงทะเบียน 1 ครั้ง
                                        @elseif($project->project_type === "multiple")
                                            ลงทะเบียนได้มากกว่า 1 ครั้ง
                                        @else
                                            ไม่ต้องลงทะเบียน
                                        @endif
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                    <div>{{ \Carbon\Carbon::parse($project->project_start_register)->format("d/m/Y H:i") }}</div>
                                    <div class="text-gray-500">ถึง</div>
                                    <div>{{ \Carbon\Carbon::parse($project->project_end_register)->format("d/m/Y H:i") }}</div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                    {{ $project->dates->where("date_delete", false)->count() }} วันที่
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                    {{ $project->activeAttends->count() }} คน
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @if ($project->project_active)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-2 text-sm font-semibold text-green-800 shadow-sm">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            ใช้งาน
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-2 text-sm font-semibold text-red-800 shadow-sm">
                                            <i class="fas fa-times-circle mr-2"></i>
                                            ไม่ใช้งาน
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" href="{{ route("hrd.admin.projects.show", $project->id) }}">
                                            <i class="fas fa-eye mr-1"></i>
                                            ดูรายละเอียด
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-8 text-center text-gray-500" colspan="7">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-folder-open mb-4 text-4xl"></i>
                                        <p class="text-lg font-medium">ไม่พบโปรเจกต์</p>
                                        <p class="text-sm">สร้างโปรเจกต์แรกของคุณเพื่อเริ่มต้น</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $projects->links() }}
            </div>
        </div>
    </div>
@endsection

@section("scripts")
    <script>
        // No delete functionality needed since we only have view action
    </script>
@endsection
