@extends("layouts.nurse")
@section("content")
    <div class="m-auto flex">
        <div class="m-auto mt-3 w-full rounded p-3 md:w-3/4">
            <div class="mb-2 text-2xl font-bold">Users Report</div>
            <hr class="mb-4">
            <div class="flex flex-col">
                <div class="mb-4">
                    <label class="mb-2 block font-semibold text-gray-700" for="selectDepartment">เลือกแผนก</label>
                    <select class="mt-1 w-full rounded border border-gray-400 p-3 transition focus:border-blue-400 focus:ring-2 focus:ring-blue-400" id="selectDepartment" onchange="changeDept()">
                        <option disabled selected>โปรดเลือก</option>
                        @foreach ($departmentArray as $dept)
                            <option value="{{ $dept }}" @if ($department == $dept) selected @endif>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
                @foreach ($data as $key => $department)
                    <div class="mb-8">
                        <div class="m-3 flex items-center text-2xl">
                            <div class="flex-1 text-red-600">{{ $key }}</div>
                            {{-- <a class="ml-2 rounded bg-blue-600 px-4 py-2 text-base font-semibold text-white shadow transition hover:bg-blue-700" href="{{ route('nurse.admin.score.users.export', $key) }}">
                                Export
                            </a> --}}
                            <button class="download-table-btn ml-2 rounded bg-green-600 px-4 py-2 text-base font-semibold text-white shadow transition hover:bg-green-700" data-table-id="table-{{ $loop->index }}">Export</button>
                        </div>
                        <div class="overflow-x-auto rounded shadow">
                            <table class="exportable-table my-3 w-full min-w-max rounded bg-white p-3 text-sm" id="table-{{ $loop->index }}">
                                <thead class="sticky top-0 z-10 bg-gray-200">
                                    <tr>
                                        <th class="border border-gray-600 p-2">รหัสพนักงาน</th>
                                        <th class="border border-gray-600 p-2">ชื่อ - สกุล</th>
                                        <th class="border border-gray-600 p-2">ตำแหน่ง</th>
                                        @foreach ($projects as $project)
                                            <th class="border border-gray-600 p-2 text-center" style="writing-mode: sideways-lr;">{{ $project->title }}</th>
                                        @endforeach
                                        <th class="border border-gray-600 p-2">วิทยากร</th>
                                        <th class="border border-gray-600 bg-green-200 p-2">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($department as $user)
                                        <tr class="@if ($loop->even) bg-gray-50 @endif transition hover:bg-blue-50">
                                            <td class="border border-gray-600 p-2">{{ $user["user"] }}</td>
                                            <td class="border border-gray-600 p-2">{{ $user["name"] }}</td>
                                            <td class="border border-gray-600 p-2">{{ $user["position"] }}</td>
                                            @foreach ($projects as $project)
                                                <td class="border border-gray-600 p-2 text-center">{{ $user[$project->title] }}</td>
                                            @endforeach
                                            <td class="border border-gray-600 p-2 text-center">{{ $user["lecture"] }}</td>
                                            <td class="border border-gray-600 bg-green-200 p-2 text-center font-bold text-red-600">{{ $user["total"] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
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
