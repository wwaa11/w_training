@extends("layouts.hrd")

@section("content")
    <div class="hrd-hospital hrd-page min-h-screen">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        @include("hrd.partials.admin-page-header", [
            "backUrl" => route("hrd.admin.projects.show", $project->id),
            "title" => "จัดการผลการประเมิน",
            "subtitle" => $project->project_name,
        ])

            @if (session("success"))
                <div class="hrd-alert hrd-alert--success mb-6">
                    <div class="flex">
                        <i class="fas fa-check-circle mr-3 mt-0.5"></i>
                        <p>{{ session("success") }}</p>
                    </div>
                </div>
            @endif

            @if (session("error"))
                <div class="hrd-alert hrd-alert--error mb-6">
                    <div class="flex">
                        <i class="fas fa-exclamation-circle mr-3 mt-0.5 text-red-400"></i>
                        <p class="text-red-800">{{ session("error") }}</p>
                    </div>
                </div>
            @endif

            <!-- Import Section -->
            <div class="hrd-card mb-8 py-6">
                <div class="border-b border-slate-200 px-6 pb-4">
                    <h2 class="flex items-center text-lg font-semibold text-slate-900">
                        <i class="fas fa-upload mr-3 text-blue-600"></i>
                        นำเข้าผลการประเมิน
                    </h2>
                </div>
                <div class="p-6">
                    <form class="space-y-4" action="{{ route("hrd.admin.projects.results.import", $project->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="excel_file">ไฟล์ Excel</label>
                            <input class="hrd-input mt-1 py-2" id="excel_file" type="file" name="excel_file" accept=".xlsx,.xls" required>
                            <p class="mt-1 text-sm text-slate-500">รองรับไฟล์ .xlsx และ .xls เท่านั้น</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button class="hrd-btn-primary" type="submit">
                                <i class="fas fa-upload mr-2"></i>
                                นำเข้าข้อมูล
                            </button>
                            <a class="hrd-btn-secondary" href="{{ route("hrd.admin.projects.results.template", $project->id) }}">
                                <i class="fas fa-download mr-2"></i>
                                ดาวน์โหลดเทมเพลต
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Header Section -->
            @if ($project->resultHeader)
                <div class="hrd-card mb-8 py-6">
                    <div class="border-b border-slate-200 px-6 pb-4">
                        <h2 class="flex items-center text-lg font-semibold text-slate-900">
                            <i class="fas fa-list mr-3 text-emerald-600"></i>
                            หัวข้อการประเมิน
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
                            @for ($i = 1; $i <= 10; $i++)
                                @php $field = "result_{$i}_name"; @endphp
                                @if ($project->resultHeader->$field)
                                    <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
                                        <h3 class="text-sm font-medium text-slate-700">ผลการประเมิน {{ $i }}</h3>
                                        <p class="mt-1 text-lg font-semibold text-slate-900">{{ $project->resultHeader->$field }}</p>
                                    </div>
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>
            @endif

            <!-- Results Data Section -->
            <div class="hrd-card py-6">
                <div class="border-b border-slate-200 px-6 pb-4">
                    <div class="flex items-center justify-between">
                        <h2 class="flex items-center text-lg font-semibold text-slate-900">
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
                        <div class="hrd-table-wrap overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">ลำดับ</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">รหัสพนักงาน</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">ชื่อ-นามสกุล</th>
                                        @if ($project->resultHeader)
                                            @for ($i = 1; $i <= 10; $i++)
                                                @php $field = "result_{$i}_name"; @endphp
                                                @if ($project->resultHeader->$field)
                                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">{{ $project->resultHeader->$field }}</th>
                                                @endif
                                            @endfor
                                        @else
                                            @for ($i = 1; $i <= 10; $i++)
                                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">ผลการประเมิน {{ $i }}</th>
                                            @endfor
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 bg-white">
                                    @foreach ($results as $index => $result)
                                        <tr class="hover:bg-slate-50">
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900">{{ $index + 1 }}</td>
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900">{{ $result->attend->user->user_id ?? "-" }}</td>
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900">{{ $result->attend->user->name ?? "-" }}</td>
                                            @for ($i = 1; $i <= 10; $i++)
                                                @php $field = "result_{$i}"; @endphp
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900">{{ $result->$field ?? "-" }}</td>
                                            @endfor
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 text-sm text-slate-600">
                            แสดงข้อมูล {{ $results->count() }} รายการ
                        </div>
                    @else
                        <div class="py-12 text-center">
                            <i class="fas fa-chart-bar mb-4 text-4xl text-slate-300"></i>
                            <h3 class="mb-2 text-lg font-medium text-slate-900">ยังไม่มีข้อมูลผลการประเมิน</h3>
                            <p class="text-slate-600">กรุณานำเข้าข้อมูลจากไฟล์ Excel</p>
                        </div>
                    @endif
                </div>
            </div>
    </div>
    </div>
@endsection

@section("scripts")
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
