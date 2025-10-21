@extends("layouts.nurse")
@section("content")
    <div class="container mx-auto px-3">
        <!-- Header -->
        <div class="mb-4">
            <h1 class="text-xl font-bold text-gray-900 sm:text-2xl">Users Report</h1>
            <p class="mt-1 text-xs text-gray-600 sm:text-sm">สรุปคะแนนและวิทยากรตามแผนก</p>
        </div>

        <!-- Department Filter -->
        <div class="mb-4">
            <label class="mb-2 block text-sm font-medium text-gray-700" for="selectDepartment">เลือกแผนก</label>
            <select class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200" id="selectDepartment" onchange="changeDept()">
                <option disabled selected>โปรดเลือก</option>
                @foreach ($departmentArray as $dept)
                    <option value="{{ $dept }}" @if ($department == $dept) selected @endif>{{ $dept }}</option>
                @endforeach
            </select>
        </div>

        <!-- Reports by Department -->
        @foreach ($data as $key => $department)
            <div class="mb-6 rounded-xl bg-white p-3 shadow-sm sm:p-4">
                <div class="mb-2 flex items-center">
                    <div class="flex-1 text-lg font-semibold text-red-600">{{ $key }}</div>
                    <button class="download-table-btn rounded bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow transition hover:bg-green-700" data-table-id="table-{{ $loop->index }}">Export</button>
                </div>
                <div class="overflow-x-auto rounded border border-gray-200">
                    <table class="exportable-table my-2 w-full min-w-max bg-white text-sm" id="table-{{ $loop->index }}">
                        <thead class="sticky top-0 z-10 bg-gray-100">
                            <tr>
                                <th class="border border-gray-300 p-2">รหัสพนักงาน</th>
                                <th class="border border-gray-300 p-2">ชื่อ - สกุล</th>
                                <th class="border border-gray-300 p-2">ตำแหน่ง</th>
                                @foreach ($projects as $project)
                                    <th class="border border-gray-300 p-2 text-center" style="writing-mode: sideways-lr;">{{ $project->title }}</th>
                                @endforeach
                                <th class="border border-gray-300 p-2">วิทยากร</th>
                                <th class="border border-gray-300 p-2">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($department as $user)
                                <tr class="@if ($loop->even) bg-gray-50 @endif transition hover:bg-blue-50">
                                    <td class="border border-gray-300 p-2">{{ $user["user"] }}</td>
                                    <td class="border border-gray-300 p-2">{{ $user["name"] }}</td>
                                    <td class="border border-gray-300 p-2">{{ $user["position"] }}</td>
                                    @foreach ($projects as $project)
                                        <td class="border border-gray-300 p-2 text-center">{{ $user[$project->title] }}</td>
                                    @endforeach
                                    <td class="border border-gray-300 p-2 text-center">{{ $user["lecture"] }}</td>
                                    <td class="border border-gray-300 bg-blue-200 p-2 text-center font-bold text-red-600">{{ $user["total"] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
@endsection
@section("scripts")
    <!-- SheetJS CDN for Excel export -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script>
        function changeDept() {
            Swal.fire({
                title: 'Please, wait.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
            })

            dept = $('#selectDepartment').find(":selected").val();
            window.location.replace('{{ route("nurse.admin.score.users") }}?department=' + dept);
        }
        // Excel Export Helper using SheetJS
        function downloadTableAsExcel(tableId, filename) {
            const table = document.getElementById(tableId);
            const wb = XLSX.utils.table_to_book(table, {
                sheet: "Sheet1"
            });
            XLSX.writeFile(wb, filename);
        }

        // Attach event listeners to all download buttons
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.download-table-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const tableId = btn.getAttribute('data-table-id');
                    // Get selected department name
                    const deptSelect = document.getElementById('selectDepartment');
                    let deptName = '';
                    if (deptSelect) {
                        deptName = deptSelect.options[deptSelect.selectedIndex].text.trim().replace(/\s+/g, '_');
                    } else {
                        // fallback: use tableId
                        deptName = tableId;
                    }
                    // Get today's date in YYYYMMDD
                    const today = new Date();
                    const yyyy = today.getFullYear();
                    const mm = String(today.getMonth() + 1).padStart(2, '0');
                    const dd = String(today.getDate()).padStart(2, '0');
                    const dateStr = `${yyyy}${mm}${dd}`;
                    const filename = `${deptName}_${dateStr}.xlsx`;
                    downloadTableAsExcel(tableId, filename);
                });
            });
        });
    </script>
@endsection
