@extends("layouts.hrd")

@section("content")
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
        <!-- Header Section -->
        <div class="border-b border-gray-200 bg-white shadow-lg">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600 transition-colors duration-200 hover:bg-blue-200" href="{{ route("hrd.admin.projects.show", $project->id) }}">
                            <i class="fas fa-arrow-left text-lg"></i>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">จัดการผลการประเมิน</h1>
                            <p class="mt-1 text-lg text-gray-600">{{ $project->project_name }}</p>
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

                @if (session("error"))
                    <div class="mt-4 rounded-lg border border-red-200 bg-red-50 p-4">
                        <div class="flex">
                            <i class="fas fa-exclamation-circle mr-3 mt-0.5 text-red-400"></i>
                            <p class="text-red-800">{{ session("error") }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Import Section -->
            <div class="mb-8 rounded-xl border border-gray-200 bg-white py-6 shadow-sm">
                <div class="border-b border-gray-200 px-6 pb-4">
                    <h2 class="flex items-center text-lg font-semibold text-gray-900">
                        <i class="fas fa-upload mr-3 text-blue-600"></i>
                        นำเข้าผลการประเมิน
                    </h2>
                </div>
                <div class="p-6">
                    <form class="space-y-4" action="{{ route("hrd.admin.projects.results.import", $project->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700" for="excel_file">ไฟล์ Excel</label>
                            <input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" id="excel_file" type="file" name="excel_file" accept=".xlsx,.xls" required>
                            <p class="mt-1 text-sm text-gray-500">รองรับไฟล์ .xlsx และ .xls เท่านั้น</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors duration-200 hover:bg-blue-700" type="submit">
                                <i class="fas fa-upload mr-2"></i>
                                นำเข้าข้อมูล
                            </button>
                            <a class="inline-flex items-center rounded-lg bg-gray-600 px-4 py-2 text-sm font-medium text-white transition-colors duration-200 hover:bg-gray-700" href="{{ route("hrd.admin.projects.results.template", $project->id) }}">
                                <i class="fas fa-download mr-2"></i>
                                ดาวน์โหลดเทมเพลต
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Header Section -->
            @if ($project->resultHeader)
                <div class="mb-8 rounded-xl border border-gray-200 bg-white py-6 shadow-sm">
                    <div class="border-b border-gray-200 px-6 pb-4">
                        <h2 class="flex items-center text-lg font-semibold text-gray-900">
                            <i class="fas fa-list mr-3 text-green-600"></i>
                            หัวข้อการประเมิน
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
                            @for ($i = 1; $i <= 10; $i++)
                                @php $field = "result_{$i}_name"; @endphp
                                @if ($project->resultHeader->$field)
                                    <div class="rounded-lg border border-gray-200 p-4">
                                        <h3 class="text-sm font-medium text-gray-700">ผลการประเมิน {{ $i }}</h3>
                                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $project->resultHeader->$field }}</p>
                                    </div>
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>
            @endif

            <!-- Results Data Section -->
            <div class="rounded-xl border border-gray-200 bg-white py-6 shadow-sm">
                <div class="border-b border-gray-200 px-6 pb-4">
                    <div class="flex items-center justify-between">
                        <h2 class="flex items-center text-lg font-semibold text-gray-900">
                            <i class="fas fa-chart-bar mr-3 text-purple-600"></i>
                            ข้อมูลผลการประเมิน
                        </h2>
                        @if ($results->count() > 0)
                            <form id="clearResultsForm" action="{{ route("hrd.admin.projects.results.clear", $project->id) }}" method="POST">
                                @csrf
                                <button class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition-colors duration-200 hover:bg-red-700" type="button" onclick="confirmClearResults()">
                                    <i class="fas fa-trash mr-2"></i>
                                    ลบข้อมูลทั้งหมด
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="p-6">
                    @if ($results->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">ลำดับ</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">รหัสพนักงาน</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">ชื่อ-นามสกุล</th>
                                        @if ($project->resultHeader)
                                            @for ($i = 1; $i <= 10; $i++)
                                                @php $field = "result_{$i}_name"; @endphp
                                                @if ($project->resultHeader->$field)
                                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ $project->resultHeader->$field }}</th>
                                                @endif
                                            @endfor
                                        @else
                                            @for ($i = 1; $i <= 10; $i++)
                                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">ผลการประเมิน {{ $i }}</th>
                                            @endfor
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach ($results as $index => $result)
                                        <tr class="hover:bg-gray-50">
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $result->attend->user->user_id ?? "-" }}</td>
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $result->attend->user->name ?? "-" }}</td>
                                            @for ($i = 1; $i <= 10; $i++)
                                                @php $field = "result_{$i}"; @endphp
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $result->$field ?? "-" }}</td>
                                            @endfor
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 text-sm text-gray-600">
                            แสดงข้อมูล {{ $results->count() }} รายการ
                        </div>
                    @else
                        <div class="py-12 text-center">
                            <i class="fas fa-chart-bar mb-4 text-4xl text-gray-300"></i>
                            <h3 class="mb-2 text-lg font-medium text-gray-900">ยังไม่มีข้อมูลผลการประเมิน</h3>
                            <p class="text-gray-600">กรุณานำเข้าข้อมูลจากไฟล์ Excel</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmClearResults() {
            Swal.fire({
                title: 'ยืนยันการลบข้อมูล',
                text: 'คุณแน่ใจหรือไม่ที่จะลบข้อมูลผลการประเมินทั้งหมด?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'ลบข้อมูล',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('clearResultsForm').submit();
                }
            });
        }
    </script>
@endsection
