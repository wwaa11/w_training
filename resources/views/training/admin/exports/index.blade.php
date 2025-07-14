@extends("layouts.training")
@section("content")
    <div class="mx-auto mt-8 max-w-4xl px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <a class="inline-flex items-center text-gray-600 transition-colors hover:text-blue-600 hover:underline" href="{{ route("training.admin.index") }}">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back to Management
            </a>
            <div class="mt-4">
                <h1 class="text-3xl font-bold text-gray-900">Export Data</h1>
                <p class="mt-2 text-gray-600">Export training attendance and hospital forms data</p>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <h2 class="text-xl font-semibold text-white">Export Options</h2>
                <p class="mt-1 text-sm text-blue-100">Select a date and choose your export type</p>
            </div>

            <!-- Card Body -->
            <div class="p-6">
                <!-- Date Selection -->
                <div class="mb-8">
                    <label class="mb-2 block text-sm font-medium text-gray-700" for="date">
                        <i class="fa-solid fa-calendar mr-2 text-blue-600"></i>
                        เลือกวันที่ (Select Date)
                    </label>
                    <select class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 transition-colors focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500" id="date" name="date" required>
                        <option value="">-- กรุณาเลือกวันที่ --</option>
                        @foreach ($arrayDates as $date)
                            <option value="{{ $date }}">{{ date("d/m/Y", strtotime($date)) }}</option>
                        @endforeach
                    </select>
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <i class="fa-solid fa-info-circle mr-1 text-blue-500"></i>
                        กรุณาเลือกวันที่ที่ต้องการส่งออกข้อมูล
                    </div>
                </div>

                <!-- Export Buttons Grid -->
                <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Attendance Export Card -->
                    <div class="rounded-lg border border-green-200 bg-gradient-to-br from-green-50 to-emerald-50 p-6 transition-shadow hover:shadow-md">
                        <div class="mb-4 flex items-start justify-between">
                            <div class="flex items-center">
                                <div class="mr-4 rounded-lg bg-green-100 p-3">
                                    <i class="fa-solid fa-users text-xl text-green-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">บันทึกการเข้าเรียน</h3>
                                    <p class="text-sm text-gray-600">Training Attendance Record</p>
                                </div>
                            </div>
                        </div>
                        <p class="mb-4 text-sm text-gray-600">Export detailed attendance records for the selected date</p>
                        <button class="flex w-full transform items-center justify-center rounded-lg bg-green-600 px-6 py-3 font-medium text-white transition-all hover:scale-[1.02] hover:bg-green-700 hover:shadow-lg disabled:cursor-not-allowed disabled:opacity-60" id="exportBtn" type="submit">
                            <span id="btnText">
                                <i class="fa-solid fa-download mr-2"></i>
                                Export Attendance
                            </span>
                            <span class="ml-2 hidden" id="btnSpinner">
                                <i class="fa-solid fa-spinner fa-spin h-5 w-5"></i>
                            </span>
                        </button>
                    </div>

                    <!-- Hospital Forms Export Card -->
                    <div class="rounded-lg border border-blue-200 bg-gradient-to-br from-blue-50 to-indigo-50 p-6 transition-shadow hover:shadow-md">
                        <div class="mb-4 flex items-start justify-between">
                            <div class="flex items-center">
                                <div class="mr-4 rounded-lg bg-blue-100 p-3">
                                    <i class="fa-solid fa-hospital text-xl text-blue-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">ใบบันทึกฝึกอบรมส่วนกลาง</h3>
                                    <p class="text-sm text-gray-600">Hospital Training Forms</p>
                                </div>
                            </div>
                        </div>
                        <p class="mb-4 text-sm text-gray-600">Export hospital training forms for the selected date</p>
                        <button class="flex w-full transform items-center justify-center rounded-lg bg-blue-600 px-6 py-3 font-medium text-white transition-all hover:scale-[1.02] hover:bg-blue-700 hover:shadow-lg disabled:cursor-not-allowed disabled:opacity-60" id="exportBtn_2" type="submit">
                            <span id="btnText_2">
                                <i class="fa-solid fa-download mr-2"></i>
                                Export Hospital Forms
                            </span>
                            <span class="ml-2 hidden" id="btnSpinner_2">
                                <i class="fa-solid fa-spinner fa-spin h-5 w-5"></i>
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Month Selection Section -->
                <div class="mb-8 border-t border-gray-200 pt-8">
                    <h3 class="mb-6 flex items-center text-lg font-semibold text-gray-900">
                        <i class="fa-solid fa-calendar-days mr-2 text-purple-600"></i>
                        Monthly Export Options
                    </h3>

                    <div class="mb-6">
                        <label class="mb-2 block text-sm font-medium text-gray-700" for="month">
                            <i class="fa-solid fa-calendar-alt mr-2 text-purple-600"></i>
                            เลือกเดือน (Select Month)
                        </label>
                        <select class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 transition-colors focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500" id="month" name="month">
                            <option value="">-- กรุณาเลือกเดือน --</option>
                            <option value="01" {{ date("m") == "01" ? "selected" : "" }}>มกราคม (January)</option>
                            <option value="02" {{ date("m") == "02" ? "selected" : "" }}>กุมภาพันธ์ (February)</option>
                            <option value="03" {{ date("m") == "03" ? "selected" : "" }}>มีนาคม (March)</option>
                            <option value="04" {{ date("m") == "04" ? "selected" : "" }}>เมษายน (April)</option>
                            <option value="05" {{ date("m") == "05" ? "selected" : "" }}>พฤษภาคม (May)</option>
                            <option value="06" {{ date("m") == "06" ? "selected" : "" }}>มิถุนายน (June)</option>
                            <option value="07" {{ date("m") == "07" ? "selected" : "" }}>กรกฎาคม (July)</option>
                            <option value="08" {{ date("m") == "08" ? "selected" : "" }}>สิงหาคม (August)</option>
                            <option value="09" {{ date("m") == "09" ? "selected" : "" }}>กันยายน (September)</option>
                            <option value="10" {{ date("m") == "10" ? "selected" : "" }}>ตุลาคม (October)</option>
                            <option value="11" {{ date("m") == "11" ? "selected" : "" }}>พฤศจิกายน (November)</option>
                            <option value="12" {{ date("m") == "12" ? "selected" : "" }}>ธันวาคม (December)</option>
                        </select>
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <i class="fa-solid fa-info-circle mr-1 text-purple-500"></i>
                            กรุณาเลือกเดือนที่ต้องการส่งออกข้อมูล One Book
                        </div>
                    </div>

                    <!-- One Book Export Card -->
                    <div class="rounded-lg border border-purple-200 bg-gradient-to-br from-purple-50 to-pink-50 p-6 transition-shadow hover:shadow-md">
                        <div class="mb-4 flex items-start justify-between">
                            <div class="flex items-center">
                                <div class="mr-4 rounded-lg bg-purple-100 p-3">
                                    <i class="fa-solid fa-book text-xl text-purple-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">One Book Export</h3>
                                    <p class="text-sm text-gray-600">Monthly Training Summary Report</p>
                                </div>
                            </div>
                        </div>
                        <p class="mb-4 text-sm text-gray-600">Export comprehensive monthly training summary in One Book format</p>
                        <button class="flex w-full transform items-center justify-center rounded-lg bg-purple-600 px-6 py-3 font-medium text-white transition-all hover:scale-[1.02] hover:bg-purple-700 hover:shadow-lg disabled:cursor-not-allowed disabled:opacity-60" id="exportBtn_3" type="submit">
                            <span id="btnText_3">
                                <i class="fa-solid fa-download mr-2"></i>
                                Export One Book
                            </span>
                            <span class="ml-2 hidden" id="btnSpinner_3">
                                <i class="fa-solid fa-spinner fa-spin h-5 w-5"></i>
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="mt-8 rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <div class="flex items-start">
                        <i class="fa-solid fa-lightbulb mr-3 mt-1 text-yellow-500"></i>
                        <div>
                            <h4 class="mb-1 font-medium text-gray-900">Export Instructions</h4>
                            <ul class="space-y-1 text-sm text-gray-600">
                                <li>• <strong>Daily Exports:</strong> Select a date for attendance and hospital forms</li>
                                <li>• <strong>Monthly Export:</strong> Select a month for One Book summary report</li>
                                <li>• Files will be downloaded automatically</li>
                                <li>• Supported format: Excel (.xlsx)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("scripts")
    <script>
        function showToast(message, type = 'success') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type,
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: type === 'success' ? '#10B981' : type === 'warning' ? '#F59E0B' : '#EF4444',
                color: '#FFFFFF'
            });
        }

        async function exportAttend(e) {
            e.preventDefault();
            const dateSelect = document.getElementById('date');
            const exportBtn = document.getElementById('exportBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            const date = dateSelect.value;

            if (!date) {
                showToast('กรุณาเลือกวันที่', 'warning');
                dateSelect.focus();
                return;
            }

            exportBtn.disabled = true;
            btnText.classList.add('hidden');
            btnSpinner.classList.remove('hidden');

            try {
                const response = await axios.get('{{ route("training.admin.exports.attends") }}', {
                    params: {
                        date
                    },
                    responseType: 'blob',
                });

                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;

                // Extract filename from Content-Disposition header
                const disposition = response.headers['content-disposition'];
                let filename = 'attend.xlsx';
                if (disposition) {
                    const utf8Match = disposition.match(/filename\*=UTF-8''([^;\n]+)/i);
                    if (utf8Match) {
                        filename = decodeURIComponent(utf8Match[1]);
                    } else {
                        const filenameMatch = disposition.match(/filename="?([^";\n]+)"?/i);
                        if (filenameMatch) {
                            filename = filenameMatch[1];
                        }
                    }
                }
                link.setAttribute('download', filename);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                window.URL.revokeObjectURL(url);

                showToast(`ดาวน์โหลดไฟล์สำหรับวันที่ ${date} สำเร็จ!`, 'success');
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่พบข้อมูล ในวันที่เลือก',
                    confirmButtonColor: '#EF4444',
                    confirmButtonText: 'ตกลง'
                });
            } finally {
                exportBtn.disabled = false;
                btnText.classList.remove('hidden');
                btnSpinner.classList.add('hidden');
            }
        }

        async function exportHospital(e) {
            e.preventDefault();
            const dateSelect = document.getElementById('date');
            const exportBtn = document.getElementById('exportBtn_2');
            const btnText = document.getElementById('btnText_2');
            const btnSpinner = document.getElementById('btnSpinner_2');
            const date = dateSelect.value;

            if (!date) {
                showToast('กรุณาเลือกวันที่', 'warning');
                dateSelect.focus();
                return;
            }

            exportBtn.disabled = true;
            btnText.classList.add('hidden');
            btnSpinner.classList.remove('hidden');

            try {
                const response = await axios.get('{{ route("training.admin.exports.hospitals") }}', {
                    params: {
                        date
                    },
                    responseType: 'blob',
                });

                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;

                // Extract filename from Content-Disposition header
                const disposition = response.headers['content-disposition'];
                let filename = 'attend.xlsx';
                if (disposition) {
                    const utf8Match = disposition.match(/filename\*=UTF-8''([^;\n]+)/i);
                    if (utf8Match) {
                        filename = decodeURIComponent(utf8Match[1]);
                    } else {
                        const filenameMatch = disposition.match(/filename="?([^";\n]+)"?/i);
                        if (filenameMatch) {
                            filename = filenameMatch[1];
                        }
                    }
                }
                link.setAttribute('download', filename);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                window.URL.revokeObjectURL(url);

                showToast(`ดาวน์โหลดไฟล์สำหรับวันที่ ${date} สำเร็จ!`, 'success');
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่พบข้อมูล ในวันที่เลือก',
                    confirmButtonColor: '#EF4444',
                    confirmButtonText: 'ตกลง'
                });
            } finally {
                exportBtn.disabled = false;
                btnText.classList.remove('hidden');
                btnSpinner.classList.add('hidden');
            }
        }

        async function exportOneBook(e) {
            e.preventDefault();
            const monthSelect = document.getElementById('month');
            const exportBtn = document.getElementById('exportBtn_3');
            const btnText = document.getElementById('btnText_3');
            const btnSpinner = document.getElementById('btnSpinner_3');
            const month = monthSelect.value;

            if (!month) {
                showToast('กรุณาเลือกเดือน', 'warning');
                monthSelect.focus();
                return;
            }

            exportBtn.disabled = true;
            btnText.classList.add('hidden');
            btnSpinner.classList.remove('hidden');

            try {
                const response = await axios.get('{{ route("training.admin.exports.onebooks") }}', {
                    params: {
                        month
                    },
                    responseType: 'blob',
                });

                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;

                // Extract filename from Content-Disposition header
                const disposition = response.headers['content-disposition'];
                let filename = 'onebook.xlsx';
                if (disposition) {
                    const utf8Match = disposition.match(/filename\*=UTF-8''([^;\n]+)/i);
                    if (utf8Match) {
                        filename = decodeURIComponent(utf8Match[1]);
                    } else {
                        const filenameMatch = disposition.match(/filename="?([^";\n]+)"?/i);
                        if (filenameMatch) {
                            filename = filenameMatch[1];
                        }
                    }
                }
                link.setAttribute('download', filename);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                window.URL.revokeObjectURL(url);

                const monthNames = {
                    '01': 'มกราคม',
                    '02': 'กุมภาพันธ์',
                    '03': 'มีนาคม',
                    '04': 'เมษายน',
                    '05': 'พฤษภาคม',
                    '06': 'มิถุนายน',
                    '07': 'กรกฎาคม',
                    '08': 'สิงหาคม',
                    '09': 'กันยายน',
                    '10': 'ตุลาคม',
                    '11': 'พฤศจิกายน',
                    '12': 'ธันวาคม'
                };
                showToast(`ดาวน์โหลดไฟล์ One Book สำหรับเดือน ${monthNames[month]} สำเร็จ!`, 'success');
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่พบข้อมูล ในเดือนที่เลือก',
                    confirmButtonColor: '#EF4444',
                    confirmButtonText: 'ตกลง'
                });
            } finally {
                exportBtn.disabled = false;
                btnText.classList.remove('hidden');
                btnSpinner.classList.add('hidden');
            }
        }

        // Add event listeners
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('exportBtn').addEventListener('click', exportAttend);
            document.getElementById('exportBtn_2').addEventListener('click', exportHospital);
            document.getElementById('exportBtn_3').addEventListener('click', exportOneBook);

            // Add focus styles to date select
            const dateSelect = document.getElementById('date');
            dateSelect.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-blue-500', 'ring-opacity-50');
            });

            // Add focus styles to month select
            const monthSelect = document.getElementById('month');
            monthSelect.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-purple-500', 'ring-opacity-50');
            });
        });
    </script>
@endsection
