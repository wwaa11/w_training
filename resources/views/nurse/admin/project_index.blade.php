@extends("layouts.nurse")
@section("content")
    <div class="hrd-hospital hrd-page mx-auto max-w-7xl px-4 py-6 text-slate-800 antialiased sm:px-6 lg:px-8">
        @include("hrd.partials.admin-page-header", [
            "title" => "การจัดการโครงการฝึกอบรม",
            "headerActions" => '
                <a class="hrd-btn-primary" href="' . route("nurse.admin.create.index") . '"><i class="fas fa-plus mr-2"></i>เพิ่มโครงการฝึกอบรม</a>
                <a class="hrd-btn-success hidden sm:inline-flex" href="' . route("nurse.admin.score.users") . '?department=null"><i class="fas fa-chart-bar mr-2"></i>คะแนนรายแผนก</a>
                <a class="hrd-btn-secondary hidden sm:inline-flex" href="' . route("nurse.admin.users.index") . '"><i class="fas fa-user-edit mr-2"></i>แก้ไขรหัสผ่าน</a>
            ',
        ])

        <div class="mb-4 flex flex-col gap-2 sm:hidden">
            <a class="hrd-btn-success w-full justify-center" href="{{ route("nurse.admin.score.users") }}?department=null"><i class="fas fa-chart-bar mr-2"></i>คะแนนรายแผนก</a>
            <a class="hrd-btn-secondary w-full justify-center" href="{{ route("nurse.admin.users.index") }}"><i class="fas fa-user-edit mr-2"></i>แก้ไขรหัสผ่าน</a>
        </div>

        <div class="hrd-card p-4 sm:p-6">
            <form class="mb-6" method="GET" action="{{ route("nurse.admin.index") }}">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <div class="hrd-search-wrap max-w-md flex-1">
                        <span class="hrd-search-wrap__icon" aria-hidden="true">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input class="hrd-input w-full" type="text" name="q" value="{{ old("q", $search ?? request("q")) }}" placeholder="ค้นหาตามชื่อโครงการ..." autocomplete="off" />
                    </div>
                    <button class="hrd-btn-primary shrink-0" type="submit">ค้นหา</button>
                    @if (request("q"))
                        <a class="text-sm font-medium text-blue-600 hover:text-blue-700" href="{{ route("nurse.admin.index") }}">ล้างการค้นหา</a>
                    @endif
                </div>
            </form>

            <!-- Mobile Card Layout -->
            <div class="block space-y-4 sm:hidden">
                @forelse($projects as $project)
                    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm transition-shadow duration-200 hover:shadow-md">
                        <div class="mb-3">
                            <h3 class="break-words text-lg font-semibold text-slate-900">{{ $project->title }}</h3>
                            @if (!empty($project->detail))
                                <p class="mt-1 break-words text-sm text-slate-600">{{ Str::limit($project->detail, 100) }}</p>
                            @endif
                        </div>

                        <div class="mb-4 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-600">ช่วงเวลาลงทะเบียน:</span>
                                <span class="text-sm font-medium text-slate-900">
                                    {{ \Carbon\Carbon::parse($project->register_start)->format("d/m/Y H:i") }}
                                    <span class="text-slate-500">ถึง</span>
                                    {{ \Carbon\Carbon::parse($project->register_end)->format("d/m/Y H:i") }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-600">วันที่:</span>
                                <span class="text-sm font-medium text-slate-900">{{ $project->dateData()->count() }} วันที่</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-600">ผู้เข้าร่วม:</span>
                                <span class="text-sm font-medium text-slate-900">{{ $project->transactionData()->where("active", true)->distinct()->count("user_id") }} คน</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-600">สถานะ:</span>
                                @php
                                    $now = date("Y-m-d");
                                    $start = date("Y-m-d", strtotime($project->register_start));
                                    $end = date("Y-m-d", strtotime($project->register_end));
                                    $registering = $now >= $start && $now <= $end;
                                @endphp
                                @if ($registering)
                                    <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">เปิดลงทะเบียน </span>
                                @elseif($now < $start)
                                    <span class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-800">รอเปิดลงทะเบียน</span>
                                @else
                                    <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-800">ปิดลงทะเบียน</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <a class="hrd-btn-secondary w-full justify-center" href="{{ route("nurse.admin.project.management", ["project_id" => $project->id]) }}">
                                <i class="fas fa-eye mr-2"></i>
                                จัดการ
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="rounded-lg border border-slate-200 bg-white p-8 text-center">
                        <i class="fas fa-folder-open mb-4 text-4xl text-slate-300"></i>
                        <p class="text-lg font-medium text-slate-500">ไม่พบโครงการ</p>
                        <p class="text-sm text-slate-400">สร้างโครงการแรกของคุณเพื่อเริ่มต้น</p>
                    </div>
                @endforelse
            </div>

            <div class="hrd-table-wrap hidden sm:block">
                <table class="min-w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">ชื่อโครงการ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">ช่วงเวลาลงทะเบียน</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">วันที่</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">ผู้เข้าร่วม</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">สถานะ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($projects as $project)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="break-words text-sm font-medium text-slate-900">{{ $project->title }}</div>
                                        @if (!empty($project->detail))
                                            <div class="break-words text-sm text-slate-500">{{ Str::limit($project->detail, 50) }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900">
                                    <div>{{ \Carbon\Carbon::parse($project->register_start)->format("d/m/Y H:i") }}</div>
                                    <div class="text-slate-500">ถึง</div>
                                    <div>{{ \Carbon\Carbon::parse($project->register_end)->format("d/m/Y H:i") }}</div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900">
                                    {{ $project->dateData()->count() }} วันที่
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900">
                                    {{ $project->transactionData()->where("active", true)->distinct()->count("user_id") }} คน
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @php
                                        $now = date("Y-m-d");
                                        $start = date("Y-m-d", strtotime($project->register_start));
                                        $end = date("Y-m-d", strtotime($project->register_end));
                                        $registering = $now >= $start && $now <= $end;
                                    @endphp
                                    @if ($registering)
                                        <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">เปิดลงทะเบียน</span>
                                    @elseif($now < $start)
                                        <span class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-800">รอเปิดลงทะเบียน</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-800">ปิดลงทะเบียน</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a class="hrd-btn-secondary text-sm" href="{{ route("nurse.admin.project.management", ["project_id" => $project->id]) }}">
                                            <i class="fas fa-eye mr-1"></i>
                                            จัดการ
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-8 text-center text-slate-500" colspan="6">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-folder-open mb-4 text-4xl"></i>
                                        <p class="text-lg font-medium">ไม่พบโครงการ</p>
                                        <p class="text-sm">สร้างโครงการแรกของคุณเพื่อเริ่มต้น</p>
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
        // No additional JS needed.
    </script>
@endsection
