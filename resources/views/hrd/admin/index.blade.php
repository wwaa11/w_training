@extends("layouts.hrd")

@section("content")
    <div class="container mx-auto px-4">
        <div class="rounded-lg bg-white p-6 shadow-lg">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-800">การจัดการโปรเจกต์ HRD</h1>
                <a class="flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white transition duration-200 hover:bg-blue-700" href="{{ route("hrd.admin.projects.create") }}">
                    <i class="fas fa-plus"></i>
                    สร้างโปรเจกต์ใหม่
                </a>
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
                            <tr class="hover:bg-gray-50">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $project->project_name }}</div>
                                        @if ($project->project_detail)
                                            <div class="text-sm text-gray-500">{{ Str::limit($project->project_detail, 50) }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span class="@if ($project->project_type === "single") bg-blue-100 text-blue-800
                                    @elseif($project->project_type === "multiple") bg-green-100 text-green-800
                                    @else bg-purple-100 text-purple-800 @endif inline-flex rounded-full px-2 py-1 text-xs font-semibold">
                                        @if ($project->project_type === "single")
                                            เดี่ยว
                                        @elseif($project->project_type === "multiple")
                                            หลาย
                                        @else
                                            เข้าร่วม
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
                                        <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">
                                            ใช้งาน
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-800">
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
