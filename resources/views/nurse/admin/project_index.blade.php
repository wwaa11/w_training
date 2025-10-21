@extends("layouts.nurse")
@section("content")
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-lg bg-white p-4 shadow-lg sm:p-6">
            <!-- Mobile Header -->
            <div class="mb-6 block sm:hidden">
                <h1 class="mb-4 text-2xl font-bold text-gray-800">การจัดการโครงการฝึกอบรม</h1>
                <div class="space-y-3">
                    <a class="flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-3 font-semibold text-white transition duration-200 hover:bg-blue-700" href="{{ route("nurse.admin.create.index") }}">
                        <i class="fas fa-plus"></i>
                        เพิ่มโครงการฝึกอบรม
                    </a>
                </div>
            </div>

            <!-- Desktop Header -->
            <div class="mb-6 hidden items-center justify-between sm:flex">
                <h1 class="text-3xl font-bold text-gray-800">การจัดการโครงการฝึกอบรม</h1>
                <div class="flex items-center gap-3">
                    <a class="flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white transition duration-200 hover:bg-blue-700" href="{{ route("nurse.admin.create.index") }}">
                        <i class="fas fa-plus"></i>
                        เพิ่มโครงการฝึกอบรม
                    </a>
                    <a class="flex items-center gap-2 rounded-lg bg-green-600 px-6 py-3 font-semibold text-white transition duration-200 hover:bg-green-700" href="{{ route("nurse.admin.score.users") }}?department=null">
                        <i class="fas fa-chart-bar"></i>
                        คะแนนรายแผนก
                    </a>
                    <a class="flex items-center gap-2 rounded-lg bg-yellow-600 px-6 py-3 font-semibold text-white transition duration-200 hover:bg-yellow-700" href="{{ route("nurse.admin.users.index") }}">
                        <i class="fas fa-user-edit"></i>
                        แก้ไขรหัสผ่าน
                    </a>
                </div>
            </div>

            <!-- Search -->
            <form class="mb-6" method="GET" action="{{ route("nurse.admin.index") }}">
                <div class="flex items-center gap-2">
                    <input class="w-full max-w-md rounded-lg border border-gray-300 px-3 py-2 text-sm" type="text" name="q" value="{{ old("q", $search ?? request("q")) }}" placeholder="ค้นหาตามชื่อโครงการ..." />
                    <button class="rounded-lg bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-900" type="submit">ค้นหา</button>
                    @if (request("q"))
                        <a class="text-sm text-blue-600 hover:underline" href="{{ route("nurse.admin.index") }}">ล้างการค้นหา</a>
                    @endif
                </div>
            </form>

            <!-- Mobile Card Layout -->
            <div class="block space-y-4 sm:hidden">
                @forelse($projects as $project)
                    <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm transition-shadow duration-200 hover:shadow-md">
                        <div class="mb-3">
                            <h3 class="break-words text-lg font-semibold text-gray-900">{{ $project->title }}</h3>
                            @if (!empty($project->detail))
                                <p class="mt-1 break-words text-sm text-gray-600">{{ Str::limit($project->detail, 100) }}</p>
                            @endif
                        </div>

                        <div class="mb-4 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">ช่วงเวลาลงทะเบียน:</span>
                                <span class="text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($project->register_start)->format("d/m/Y H:i") }}
                                    <span class="text-gray-500">ถึง</span>
                                    {{ \Carbon\Carbon::parse($project->register_end)->format("d/m/Y H:i") }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">วันที่:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $project->dateData()->count() }} วันที่</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">ผู้เข้าร่วม:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $project->transactionData()->where("active", true)->distinct()->count("user_id") }} คน</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">สถานะ:</span>
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
                            <a class="inline-flex w-full items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" href="{{ route("nurse.admin.project.management", ["project_id" => $project->id]) }}">
                                <i class="fas fa-eye mr-2"></i>
                                จัดการ
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="rounded-lg border border-gray-200 bg-white p-8 text-center">
                        <i class="fas fa-folder-open mb-4 text-4xl text-gray-300"></i>
                        <p class="text-lg font-medium text-gray-500">ไม่พบโครงการ</p>
                        <p class="text-sm text-gray-400">สร้างโครงการแรกของคุณเพื่อเริ่มต้น</p>
                    </div>
                @endforelse
            </div>

            <!-- Desktop Table Layout -->
            <div class="hidden overflow-x-auto sm:block">
                <table class="min-w-full border border-gray-200 bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">ชื่อโครงการ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">ช่วงเวลาลงทะเบียน</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">วันที่</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">ผู้เข้าร่วม</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">สถานะ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($projects as $project)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="break-words text-sm font-medium text-gray-900">{{ $project->title }}</div>
                                        @if (!empty($project->detail))
                                            <div class="break-words text-sm text-gray-500">{{ Str::limit($project->detail, 50) }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                    <div>{{ \Carbon\Carbon::parse($project->register_start)->format("d/m/Y H:i") }}</div>
                                    <div class="text-gray-500">ถึง</div>
                                    <div>{{ \Carbon\Carbon::parse($project->register_end)->format("d/m/Y H:i") }}</div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                    {{ $project->dateData()->count() }} วันที่
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
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
                                        <a class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" href="{{ route("nurse.admin.project.management", ["project_id" => $project->id]) }}">
                                            <i class="fas fa-eye mr-1"></i>
                                            จัดการ
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-8 text-center text-gray-500" colspan="6">
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
