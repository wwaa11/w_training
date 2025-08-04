@extends("layouts.hrd")

@section("content")
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center">
                <a class="mr-4 text-blue-600 hover:text-blue-800" href="{{ route("hrd.admin.projects.show", $project->id) }}">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">จัดการกลุ่ม - {{ $project->project_name }}</h1>
                    <p class="text-gray-600">จัดการการจัดกลุ่มผู้เข้าร่วมโปรเจกต์</p>
                </div>
            </div>
        </div>

        @if (session("success"))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4">
                <div class="flex">
                    <i class="fas fa-check-circle mr-3 mt-0.5 text-green-400"></i>
                    <p class="text-green-800">{{ session("success") }}</p>
                </div>
            </div>
        @endif

        @if (session("error"))
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
                <div class="flex">
                    <i class="fas fa-exclamation-circle mr-3 mt-0.5 text-red-400"></i>
                    <p class="text-red-800">{{ session("error") }}</p>
                </div>
            </div>
        @endif

        @if (session("warning"))
            <div class="mb-6 rounded-lg border border-yellow-200 bg-yellow-50 p-4">
                <div class="flex">
                    <i class="fas fa-exclamation-triangle mr-3 mt-0.5 text-yellow-400"></i>
                    <p class="text-yellow-800">{{ session("warning") }}</p>
                </div>
            </div>
        @endif

        @if (session("import_errors"))
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
                <div class="flex">
                    <i class="fas fa-exclamation-circle mr-3 mt-0.5 text-red-400"></i>
                    <div class="flex-1">
                        <p class="mb-2 font-medium text-red-800">ข้อผิดพลาดในการนำเข้าข้อมูล:</p>
                        <ul class="list-inside list-disc space-y-1">
                            @foreach (session("import_errors") as $error)
                                <li class="text-sm text-red-700">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <!-- Add New Group Assignment -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg">
                <h2 class="mb-6 flex items-center text-xl font-semibold text-gray-800">
                    <i class="fas fa-plus-circle mr-3 text-green-600"></i>
                    เพิ่มผู้เข้าร่วมเข้ากลุ่ม
                </h2>

                <form action="{{ route("hrd.admin.projects.groups.store", $project->id) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700" for="user_id">รหัสพนักงาน</label>
                            <input class="@error("user_id") border-red-500 @else border-gray-200 @enderror w-full rounded-lg border-2 px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" id="user_id" type="text" name="user_id" placeholder="กรอกรหัสผู้เข้าร่วม" value="{{ old("user_id") }}" required>
                            <p class="mt-1 text-xs text-gray-500">ระบบจะค้นหาผู้ใช้จากรหัสพนักงานในระบบ สามารถจัดกลุ่มได้ก่อนการเข้าร่วม</p>
                            @error("user_id")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700" for="group">ชื่อกลุ่ม</label>
                            <input class="@error("group") border-red-500 @else border-gray-200 @enderror w-full rounded-lg border-2 px-4 py-3 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" id="group" type="text" name="group" placeholder="ชื่อกลุ่ม" value="{{ old("group") }}" required>
                            @error("group")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button class="w-full rounded-lg bg-green-600 px-4 py-3 font-medium text-white transition-colors duration-200 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2" type="submit">
                            <i class="fas fa-plus mr-2"></i>
                            เพิ่มเข้ากลุ่ม
                        </button>
                    </div>
                </form>

                <!-- Import Excel Section -->
                <div class="mt-8 border-t border-gray-200 pt-6">
                    <h3 class="mb-4 flex items-center text-lg font-semibold text-gray-800">
                        <i class="fas fa-file-excel mr-3 text-blue-600"></i>
                        นำเข้าจากไฟล์ Excel
                    </h3>

                    <div class="mb-4 rounded-lg bg-blue-50 p-4">
                        <div class="flex">
                            <i class="fas fa-info-circle mr-3 mt-0.5 text-blue-400"></i>
                            <div class="text-sm text-blue-800">
                                <p class="mb-1 font-medium">คำแนะนำการนำเข้า:</p>
                                <ul class="list-inside list-disc space-y-1">
                                    <li>ระบบจะตรวจสอบว่าผู้ใช้มีอยู่ในระบบ</li>
                                    <li>สามารถจัดกลุ่มได้ก่อนการเข้าร่วมโปรเจกต์</li>
                                    <li>ผู้ใช้ที่ถูกจัดกลุ่มแล้วจะไม่ถูกนำเข้า</li>
                                    <li>ดาวน์โหลดเทมเพลตเพื่อดูตัวอย่างรูปแบบข้อมูล</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex space-x-2">
                            <a class="flex-1 rounded-lg bg-blue-600 px-4 py-2 text-center text-sm font-medium text-white transition-colors duration-200 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" href="{{ route("hrd.admin.projects.groups.template", $project->id) }}">
                                <i class="fas fa-download mr-2"></i>
                                ดาวน์โหลดเทมเพลต
                            </a>
                        </div>

                        <form action="{{ route("hrd.admin.projects.groups.import", $project->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700" for="import_file">เลือกไฟล์ Excel</label>
                                    <input class="w-full rounded-lg border-2 border-gray-200 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" id="import_file" type="file" name="import_file" accept=".xlsx,.xls,.csv" required>
                                    <p class="mt-1 text-xs text-gray-500">รองรับไฟล์ .xlsx, .xls, .csv</p>
                                </div>

                                <button class="w-full rounded-lg bg-blue-600 px-4 py-3 font-medium text-white transition-colors duration-200 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" type="submit">
                                    <i class="fas fa-upload mr-2"></i>
                                    นำเข้าข้อมูล
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Current Groups -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg">
                <h2 class="mb-6 flex items-center text-xl font-semibold text-gray-800">
                    <i class="fas fa-users mr-3 text-blue-600"></i>
                    กลุ่มปัจจุบัน
                </h2>

                @if ($groups->count() > 0)
                    <div class="space-y-4">
                        @foreach ($groups as $groupName => $groupMembers)
                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                <div class="mb-3 flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $groupName }}</h3>
                                    <span class="rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800">
                                        {{ $groupMembers->count() }} คน
                                    </span>
                                </div>

                                <div class="space-y-2">
                                    @foreach ($groupMembers as $member)
                                        <div class="flex items-center justify-between rounded-lg bg-white p-3 shadow-sm">
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $member->user->name }}</p>
                                                <p class="text-sm text-gray-600">{{ $member->user->userid }}</p>
                                            </div>
                                            <form class="ml-4" action="{{ route("hrd.admin.projects.groups.delete", [$project->id, $member->id]) }}" method="POST">
                                                @csrf
                                                @method("DELETE")
                                                <button class="text-red-600 transition-colors duration-200 hover:text-red-800" type="submit" onclick="return confirm('คุณต้องการลบผู้เข้าร่วมนี้ออกจากกลุ่มใช่หรือไม่?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-8 text-center">
                        <i class="fas fa-users mb-4 text-4xl text-gray-300"></i>
                        <p class="text-gray-500">ยังไม่มีกลุ่มที่ถูกสร้าง</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistics -->
        <div class="mt-8 rounded-xl border border-gray-200 bg-white p-6 shadow-lg">
            <h2 class="mb-6 flex items-center text-xl font-semibold text-gray-800">
                <i class="fas fa-chart-bar mr-3 text-purple-600"></i>
                สถิติการจัดกลุ่ม
            </h2>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="rounded-lg bg-blue-50 p-4">
                    <div class="flex items-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100">
                            <i class="fas fa-users text-xl text-blue-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-blue-600">ผู้เข้าร่วมทั้งหมด</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $attendedUsers->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-green-50 p-4">
                    <div class="flex items-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                            <i class="fas fa-user-check text-xl text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-600">ผู้เข้าร่วมที่จัดกลุ่มแล้ว</p>
                            <p class="text-2xl font-bold text-green-900">{{ \App\Models\HrGroup::where("project_id", $project->id)->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-orange-50 p-4">
                    <div class="flex items-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-orange-100">
                            <i class="fas fa-layer-group text-xl text-orange-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-orange-600">จำนวนกลุ่ม</p>
                            <p class="text-2xl font-bold text-orange-900">{{ $groups->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
