@extends("layouts.hrd")

@section("content")
    <div class="hrd-hospital hrd-page mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        @include("hrd.partials.admin-page-header", [
            "title" => "การจัดการโปรเจกต์ HRD",
            "subtitle" => "สร้างโปรเจกต์และติดตามการลงทะเบียนทั้งหมด",
            "headerActions" =>
                '<a class="inline-flex items-center gap-2 rounded-2xl bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-amber-700" href="' . route("hr.admin.index") . '"><i class="fas fa-history"></i>ระบบ HR เก่า</a>' .
                '<a class="hrd-btn-secondary" href="' . route("hrd.admin.users.index") . '"><i class="fas fa-users"></i>จัดการผู้ใช้</a>' .
                '<a class="hrd-btn-primary" href="' . route("hrd.admin.projects.create") . '"><i class="fas fa-plus"></i>สร้างโปรเจกต์ใหม่</a>',
        ])
        <div class="hrd-card p-4 sm:p-6">

            @if (session("success"))
                <div class="hrd-alert hrd-alert--success mb-6">
                    {{ session("success") }}
                </div>
            @endif

            @if (session("error"))
                <div class="hrd-alert hrd-alert--error mb-6">
                    {{ session("error") }}
                </div>
            @endif

            <!-- Quick Help Section -->
            <div class="mb-6 rounded-2xl border border-blue-100 bg-blue-50 p-4">
                <div class="flex flex-col items-start justify-between space-y-4 sm:flex-row sm:space-y-0">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-blue-800">💡 ต้องการความช่วยเหลือ?</h3>
                        <p class="mt-1 text-sm text-blue-700">
                            ดูคู่มือการใช้งานแบบละเอียดเพื่อเรียนรู้วิธีการใช้ระบบ HRD อย่างมีประสิทธิภาพ
                        </p>
                    </div>
                    <a class="hrd-btn-primary w-full sm:w-auto" href="{{ route("hrd.admin.documentation") }}">
                        <i class="fas fa-book"></i>
                        เปิดคู่มือ
                    </a>
                </div>
            </div>

            <form action="{{ route("hrd.admin.index") }}" method="GET">
                <div class="flex flex-row gap-2 space-y-4">
                    @csrf
                    <input class="hrd-input" value="{{ $search }}" type="text" placeholder="Press Enter to search..." name="name">
                    <button class="hidden" type="submit"></button>
                </div>
            </form>

            <!-- Mobile Card Layout -->
            <div class="block space-y-4 sm:hidden">
                @forelse($projects as $project)
                    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm transition-shadow duration-200 hover:shadow-md">
                        <div class="mb-3">
                            <h3 class="break-words text-lg font-semibold text-slate-900">{{ $project->project_name }}</h3>
                            @if ($project->project_detail)
                                <p class="mt-1 break-words text-sm text-slate-600">{{ Str::limit($project->project_detail, 100) }}</p>
                            @endif
                        </div>

                        <div class="mb-4 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-600">ประเภท:</span>
                                <span class="@if ($project->project_type === "single") bg-blue-100 text-blue-800
                                @elseif($project->project_type === "multiple") bg-emerald-100 text-emerald-800
                                @else bg-violet-100 text-violet-800 @endif inline-flex rounded-full px-2 py-1 text-xs font-semibold">
                                    @if ($project->project_type === "single")
                                        ลงทะเบียน 1 ครั้ง
                                    @elseif($project->project_type === "multiple")
                                        ลงทะเบียนได้มากกว่า 1 ครั้ง
                                    @else
                                        ไม่ต้องลงทะเบียน
                                    @endif
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-600">สถานะ:</span>
                                @if ($project->project_active)
                                    <span class="inline-flex rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-800">
                                        ใช้งาน
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-800">
                                        ไม่ใช้งาน
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-600">วันที่:</span>
                                <span class="text-sm font-medium text-slate-900">{{ $project->dates->where("date_delete", false)->count() }} วันที่</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-600">ผู้เข้าร่วม:</span>
                                <span class="text-sm font-medium text-slate-900">{{ $project->getUniqueParticipantsCount() }} คน</span>
                            </div>

                            <div class="border-t border-slate-100 pt-2">
                                <div class="text-xs text-slate-500">
                                    <div>เริ่มลงทะเบียน: {{ \Carbon\Carbon::parse($project->project_start_register)->format("d/m/Y H:i") }}</div>
                                    <div>สิ้นสุดลงทะเบียน: {{ \Carbon\Carbon::parse($project->project_end_register)->format("d/m/Y H:i") }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <a class="hrd-btn-secondary w-full justify-center" href="{{ route("hrd.admin.projects.show", $project->id) }}">
                                <i class="fas fa-eye mr-2"></i>
                                ดูรายละเอียด
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="rounded-lg border border-slate-200 bg-white p-8 text-center">
                        <i class="fas fa-folder-open mb-4 text-4xl text-slate-300"></i>
                        <p class="text-lg font-medium text-slate-500">ไม่พบโปรเจกต์</p>
                        <p class="text-sm text-slate-400">สร้างโปรเจกต์แรกของคุณเพื่อเริ่มต้น</p>
                    </div>
                @endforelse
            </div>

            <!-- Desktop Table Layout -->
            <div class="hidden overflow-x-auto sm:block">
                <table class="min-w-full border border-slate-200 bg-white">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                                ชื่อโปรเจกต์
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                                ประเภท
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                                ช่วงเวลาลงทะเบียน
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                                วันที่
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                                ผู้เข้าร่วม
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                                สถานะ
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                                การดำเนินการ
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($projects as $project)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="break-words text-sm font-medium text-slate-900">{{ $project->project_name }}</div>
                                        @if ($project->project_detail)
                                            <div class="break-words text-sm text-slate-500">{{ Str::limit($project->project_detail, 50) }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span class="@if ($project->project_type === "single") bg-blue-100 text-blue-800
                                    @elseif($project->project_type === "multiple") bg-emerald-100 text-emerald-800
                                    @else bg-violet-100 text-violet-800 @endif inline-flex rounded-full px-2 py-1 text-xs font-semibold">
                                        @if ($project->project_type === "single")
                                            ลงทะเบียน 1 ครั้ง
                                        @elseif($project->project_type === "multiple")
                                            ลงทะเบียนได้มากกว่า 1 ครั้ง
                                        @else
                                            ไม่ต้องลงทะเบียน
                                        @endif
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900">
                                    <div>{{ \Carbon\Carbon::parse($project->project_start_register)->format("d/m/Y H:i") }}</div>
                                    <div class="text-slate-500">ถึง</div>
                                    <div>{{ \Carbon\Carbon::parse($project->project_end_register)->format("d/m/Y H:i") }}</div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900">
                                    {{ $project->dates->where("date_delete", false)->count() }} วันที่
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900">
                                    {{ $project->getUniqueParticipantsCount() }} คน
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @if ($project->project_active)
                                        <span class="inline-flex rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-800">
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
                                        <a class="hrd-btn-secondary px-3 py-2 text-sm" href="{{ route("hrd.admin.projects.show", $project->id) }}">
                                            <i class="fas fa-eye mr-1"></i>
                                            ดูรายละเอียด
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-8 text-center text-slate-500" colspan="7">
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
                <div class="flex justify-center">
                    {{ $projects->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section("scripts")
    <script>
        // No delete functionality needed since we only have view action
    </script>
@endsection
