@extends("layouts.hrd")

@section("content")
    <div class="container mx-auto px-4">
        <div class="rounded-lg bg-white p-6 shadow-lg">
            <div class="mb-6 flex items-center">
                <a class="mr-4 text-blue-600 hover:text-blue-800" href="{{ route("hrd.admin.index") }}">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">สร้างโปรเจกต์ใหม่</h1>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700">
                    <h4 class="font-bold">กรุณาแก้ไขข้อผิดพลาดต่อไปนี้:</h4>
                    <ul class="mt-2 list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="projectForm" action="{{ route("hrd.admin.projects.store") }}" method="POST">
                @csrf

                <!-- Basic Project Information -->
                <div class="mb-6 rounded-lg border border-green-200 bg-gradient-to-br from-green-50 to-emerald-50 p-6">
                    <div class="mb-6">
                        <h2 class="flex items-center text-2xl font-bold text-gray-800">
                            <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-full bg-green-600">
                                <i class="fas fa-info-circle text-lg text-white"></i>
                            </div>
                            ข้อมูลพื้นฐาน
                        </h2>
                        <p class="mt-2 text-gray-600">กรอกข้อมูลพื้นฐานของโปรเจกต์</p>
                    </div>

                    <div class="rounded-xl bg-white p-6 shadow-lg">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700">ชื่อโปรเจกต์ *</label>
                                <input class="w-full rounded-lg border-2 border-gray-200 px-4 py-3 transition-all duration-200 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" type="text" name="project_name" value="{{ old("project_name") }}" placeholder="ชื่อโปรเจกต์" required>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700">ประเภทโปรเจกต์ *</label>
                                <select class="w-full rounded-lg border-2 border-gray-200 px-4 py-3 transition-all duration-200 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" name="project_type" required onchange="showProjectTypeHint(this.value)">
                                    <option value="">เลือกประเภท</option>
                                    <option value="single" {{ old("project_type") === "single" ? "selected" : "" }}>ลงทะเบียน 1 ครั้ง</option>
                                    <option value="multiple" {{ old("project_type") === "multiple" ? "selected" : "" }}>ลงทะเบียนได้มากกว่า 1 ครั้ง</option>
                                    <option value="attendance" {{ old("project_type") === "attendance" ? "selected" : "" }}>ไม่ต้องลงทะเบียน</option>
                                </select>
                                <div class="mt-2 hidden text-sm text-gray-600" id="projectTypeHint">
                                    <!-- Hints will be shown here -->
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-2 block text-sm font-semibold text-gray-700">รายละเอียดโปรเจกต์</label>
                                <textarea class="w-full rounded-lg border-2 border-gray-200 px-4 py-3 transition-all duration-200 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" name="project_detail" rows="3" placeholder="รายละเอียดโปรเจกต์">{{ old("project_detail") }}</textarea>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700">เริ่มลงทะเบียน *</label>
                                <input class="w-full rounded-lg border-2 border-gray-200 px-4 py-3 transition-all duration-200 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" type="datetime-local" name="project_start_register" value="{{ old("project_start_register", now()->format("Y-m-d 08:00")) }}" required>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700">สิ้นสุดลงทะเบียน *</label>
                                <input class="w-full rounded-lg border-2 border-gray-200 px-4 py-3 transition-all duration-200 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" type="datetime-local" name="project_end_register" value="{{ old("project_end_register") }}" required>
                            </div>
                        </div>

                        <!-- Special Settings -->
                        <div class="mt-8 border-t border-gray-200 pt-6">
                            <div class="mb-4 flex items-center">
                                <div class="mr-3 flex h-8 w-8 items-center justify-center rounded-full bg-purple-600">
                                    <i class="fas fa-cog text-sm text-white"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">การตั้งค่าพิเศษ</h3>
                            </div>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <label class="flex cursor-pointer items-center rounded-lg bg-gray-50 p-4 transition-colors duration-200 hover:bg-gray-100">
                                    <input class="h-5 w-5 rounded border-gray-300 text-green-600 focus:ring-green-500" type="checkbox" name="project_seat_assign" value="1" {{ old("project_seat_assign") ? "checked" : "" }}>
                                    <span class="ml-3 text-sm font-medium text-gray-700">เปิดใช้งานการจัดที่นั่ง</span>
                                </label>
                                <label class="flex cursor-pointer items-center rounded-lg bg-gray-50 p-4 transition-colors duration-200 hover:bg-gray-100">
                                    <input class="h-5 w-5 rounded border-gray-300 text-green-600 focus:ring-green-500" type="checkbox" name="project_register_today" value="1" {{ old("project_register_today", true) ? "checked" : "" }}>
                                    <span class="ml-3 text-sm font-medium text-gray-700">เปิดให้ลงทะเบียนในวันที่มีการจัดหลักสูตร</span>
                                </label>
                                <label class="flex cursor-pointer items-center rounded-lg bg-gray-50 p-4 transition-colors duration-200 hover:bg-gray-100">
                                    <input type="hidden" name="project_group_assign" value="0">
                                    <input class="h-5 w-5 rounded border-gray-300 text-green-600 focus:ring-green-500" type="checkbox" name="project_group_assign" value="1" {{ old("project_group_assign") ? "checked" : "" }}>
                                    <span class="ml-3 text-sm font-medium text-gray-700">เปิดใช้งานการจัดกลุ่ม</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dates Section -->
                <div class="mb-6 rounded-lg border border-blue-200 bg-gradient-to-br from-blue-50 to-indigo-50 p-6">
                    <div class="mb-6">
                        <h2 class="flex items-center text-2xl font-bold text-gray-800">
                            <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-full bg-blue-600">
                                <i class="fas fa-calendar text-lg text-white"></i>
                            </div>
                            วันที่ของโปรเจกต์
                        </h2>
                        <p class="mt-2 text-gray-600">กำหนดวันที่และช่วงเวลาของโปรเจกต์</p>
                    </div>

                    <!-- Dates Container -->
                    <div class="space-y-6" id="datesContainer">
                        <!-- Dates will be added here dynamically -->
                    </div>

                    <!-- Add Date Button -->
                    <div class="mt-8 flex justify-center">
                        <button class="group relative inline-flex transform items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-4 text-lg font-medium text-white shadow-lg transition-all duration-200 hover:scale-105 hover:from-blue-700 hover:to-indigo-700" type="button" onclick="addDate()">
                            <i class="fas fa-plus mr-2 transition-transform duration-200 group-hover:rotate-90"></i>
                            เพิ่มวันที่ใหม่
                        </button>
                    </div>
                </div>

                <!-- Links Section -->
                <div class="mb-6 rounded-lg border border-orange-200 bg-gradient-to-br from-orange-50 to-red-50 p-6">
                    <div class="mb-6">
                        <h2 class="flex items-center text-2xl font-bold text-gray-800">
                            <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-full bg-orange-600">
                                <i class="fas fa-link text-lg text-white"></i>
                            </div>
                            ลิงก์ของโปรเจกต์
                        </h2>
                        <p class="mt-2 text-gray-600">เพิ่มลิงก์ที่เกี่ยวข้องกับโปรเจกต์</p>
                    </div>

                    <div class="space-y-4" id="linksContainer">
                        <!-- Links will be added here dynamically -->
                    </div>

                    <!-- Add Link Button -->
                    <div class="mt-6 flex justify-center">
                        <button class="group relative inline-flex transform items-center justify-center rounded-xl bg-gradient-to-r from-orange-600 to-red-600 px-6 py-3 text-base font-medium text-white shadow-lg transition-all duration-200 hover:scale-105 hover:from-orange-700 hover:to-red-700" type="button" onclick="addLink()">
                            <i class="fas fa-plus mr-2 transition-transform duration-200 group-hover:rotate-90"></i>
                            เพิ่มลิงก์ใหม่
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <a class="inline-flex transform items-center rounded-xl border-2 border-gray-300 bg-white px-8 py-4 text-lg font-semibold text-gray-700 shadow-lg transition-all duration-200 hover:scale-105 hover:border-gray-400 hover:bg-gray-50" href="{{ route("hrd.admin.index") }}">
                        <i class="fas fa-times mr-2"></i>
                        ยกเลิก
                    </a>
                    <button class="inline-flex transform items-center rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-4 text-lg font-semibold text-white shadow-lg transition-all duration-200 hover:scale-105 hover:from-blue-700 hover:to-indigo-700" type="submit">
                        <i class="fas fa-save mr-2"></i>
                        สร้างโปรเจกต์
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section("scripts")
    <script>
        let dateIndex = 0;
        let linkIndex = 0;

        // Thai month names
        const thaiMonths = [
            'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
            'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
        ];

        // Convert date to Thai format
        function formatThaiDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            const day = date.getDate();
            const month = thaiMonths[date.getMonth()];
            const year = date.getFullYear() + 543; // Convert to Buddhist year
            return `${day} ${month} ${year}`;
        }

        // Add initial date on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Check if there are old values (validation errors)
            const oldDates = @json(old("dates", []));
            const oldLinks = @json(old("links", []));

            if (oldDates.length > 0) {
                // Restore old dates
                oldDates.forEach((dateData, index) => {
                    addDate();
                    restoreDateData(index, dateData);
                });
            } else {
                // Add initial empty date
                addDate();
            }

            if (oldLinks.length > 0) {
                // Restore old links
                oldLinks.forEach((linkData, index) => {
                    addLink();
                    restoreLinkData(index, linkData);
                });
            }

            // Show project type hint if there's an old value
            const oldProjectType = @json(old("project_type", ""));
            if (oldProjectType) {
                showProjectTypeHint(oldProjectType);
            }
        });

        function showProjectTypeHint(projectType) {
            const hintDiv = document.getElementById('projectTypeHint');
            let hintText = '';
            let hintClass = '';

            switch (projectType) {
                case 'single':
                    hintText = '💡 ผู้ใช้สามารถลงทะเบียนได้เพียง 1 ครั้งเท่านั้น เหมาะสำหรับกิจกรรมที่ต้องการจำกัดจำนวนผู้เข้าร่วม';
                    hintClass = 'bg-blue-50 border-blue-200 text-blue-800';
                    break;
                case 'multiple':
                    hintText = '💡 ผู้ใช้สามารถลงทะเบียนได้หลายครั้ง เหมาะสำหรับกิจกรรมที่จัดหลายวันหรือหลายรอบ';
                    hintClass = 'bg-green-50 border-green-200 text-green-800';
                    break;
                case 'attendance':
                    hintText = '💡 ไม่มีการลงทะเบียนล่วงหน้า ผู้ใช้สามารถเข้าร่วมได้โดยตรง เหมาะสำหรับกิจกรรมที่เปิดให้เข้าร่วมได้ทันที';
                    hintClass = 'bg-purple-50 border-purple-200 text-purple-800';
                    break;
                default:
                    hintDiv.classList.add('hidden');
                    return;
            }

            hintDiv.innerHTML = `<div class="p-3 rounded-lg border ${hintClass}">${hintText}</div>`;
            hintDiv.classList.remove('hidden');
        }

        // Form submission with SweetAlert confirmation
        document.getElementById('projectForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate required fields
            const requiredFields = this.querySelectorAll('[required]');
            let missingFields = [];

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    missingFields.push(field.previousElementSibling.textContent.replace('*', '').trim());
                }
            });

            if (missingFields.length > 0) {
                Swal.fire({
                    title: 'ข้อมูลไม่ครบถ้วน',
                    html: 'กรุณากรอกข้อมูลในฟิลด์ต่อไปนี้:<br><ul style="text-align: left; margin-top: 10px;">' +
                        missingFields.map(field => `<li>• ${field}</li>`).join('') + '</ul>',
                    icon: 'warning',
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: '#f59e0b'
                });
                return;
            }

            // Show confirmation dialog
            Swal.fire({
                title: 'ยืนยันการสร้างโครงการ',
                text: 'คุณต้องการสร้างโครงการนี้ใช่หรือไม่?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form
                    this.submit();
                }
            });
        });

        // Handle server-side errors
        @if ($errors->any())
            Swal.fire({
                title: 'เกิดข้อผิดพลาด',
                html: 'กรุณาตรวจสอบข้อมูลอีกครั้ง:<br><ul style="text-align: left; margin-top: 10px;">' +
                    '@foreach ($errors->all() as $error)<li>• {{ $error }}</li>@endforeach' + '</ul>',
                icon: 'error',
                confirmButtonText: 'ตกลง',
                confirmButtonColor: '#ef4444'
            });
        @endif

        function addDate() {
            const container = document.getElementById('datesContainer');
            const dateHtml = `
            <div class="date-item bg-white rounded-xl shadow-lg border border-gray-200 hover:shadow-xl transition-shadow duration-300" data-date-index="${dateIndex}">
                <!-- Date Header -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-4 rounded-t-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                                <span class="text-sm font-bold">${dateIndex + 1}</span>
                            </div>
                            <h3 class="text-lg font-semibold">วันที่ ${dateIndex + 1}</h3>
                        </div>
                        <button type="button" onclick="removeDate(${dateIndex})" 
                                class="text-white hover:text-red-200 transition-colors duration-200">
                            <i class="fas fa-trash text-lg"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Date Content -->
                <div class="p-6">
                    <!-- Basic Date Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">วันที่ *</label>
                            <input type="date" name="dates[${dateIndex}][date_datetime]" id="dateDateTime${dateIndex}"
                                   class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200" 
                                   required onchange="updateDateTitle(${dateIndex})">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">ชื่อวันที่ *</label>
                            <input type="text" name="dates[${dateIndex}][date_title]" id="dateTitle${dateIndex}"
                                   class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200" 
                                   placeholder="ชื่อวันที่" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">สถานที่</label>
                            <input type="text" name="dates[${dateIndex}][date_location]" 
                                   class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="สถานที่">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">รายละเอียด</label>
                            <input type="text" name="dates[${dateIndex}][date_detail]" 
                                   class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="รายละเอียด">
                        </div>
                    </div>
                    
                    <!-- Time Slots Section -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-clock text-blue-600 mr-2"></i>
                                ช่วงเวลา
                            </h4>
                        </div>
                        <div id="timesContainer${dateIndex}" class="space-y-4">
                            <!-- Times will be added here -->
                        </div>
                        <div class="mt-6 flex justify-center">
                            <button type="button" onclick="addTime(${dateIndex})" 
                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-lg hover:from-green-600 hover:to-emerald-600 transform hover:scale-105 transition-all duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                เพิ่มช่วงเวลา
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
            container.insertAdjacentHTML('beforeend', dateHtml);
            addTime(dateIndex); // Add first time slot automatically
            dateIndex++;
        }

        function updateDateTitle(dateIndex) {
            const dateTimeInput = document.getElementById(`dateDateTime${dateIndex}`);
            const dateTitleInput = document.getElementById(`dateTitle${dateIndex}`);

            if (dateTimeInput.value && !dateTitleInput.value) {
                dateTitleInput.value = formatThaiDate(dateTimeInput.value);
            }
        }

        function updateTimeTitle(dateIndex, timeIndex) {
            const timeStartInput = document.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_start]"]`);
            const timeEndInput = document.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_end]"]`);
            const timeTitleInput = document.getElementById(`timeTitle_${dateIndex}_${timeIndex}`);

            if (timeStartInput && timeEndInput && timeTitleInput) {
                const startTime = timeStartInput.value;
                const endTime = timeEndInput.value;

                if (startTime && endTime) {
                    // Use 24-hour format directly
                    timeTitleInput.value = `${startTime} - ${endTime}`;
                }
            }
        }



        function removeDate(index) {
            const dateItem = document.querySelector(`[data-date-index="${index}"]`);
            if (dateItem) {
                dateItem.remove();
                // Update row numbers for remaining dates
                updateDateRowNumbers();
            }
        }

        function updateDateRowNumbers() {
            const dateItems = document.querySelectorAll('.date-item');
            dateItems.forEach((item, index) => {
                const numberBadge = item.querySelector('.w-8.h-8 span');
                const dateTitle = item.querySelector('h3');
                if (numberBadge) {
                    numberBadge.textContent = index + 1;
                }
                if (dateTitle) {
                    dateTitle.textContent = `วันที่ ${index + 1}`;
                }
            });
        }

        function addTime(dateIndex) {
            const container = document.getElementById(`timesContainer${dateIndex}`);
            const timeIndex = container.children.length;
            const timeHtml = `
            <div class="time-item bg-gradient-to-r from-gray-50 to-blue-50 border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200" data-time-index="${timeIndex}">
                <!-- Time Header -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                            <span class="text-white text-xs font-bold">${timeIndex + 1}</span>
                        </div>
                        <h5 class="text-sm font-semibold text-gray-800">ช่วงเวลา ${timeIndex + 1}</h5>
                    </div>
                    <button type="button" onclick="removeTime(this)" 
                            class="text-red-500 hover:text-red-700 transition-colors duration-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <!-- Time Information -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ชื่อช่วงเวลา *</label>
                        <input type="text" name="dates[${dateIndex}][times][${timeIndex}][time_title]" 
                               class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200" 
                               placeholder="ชื่อช่วงเวลา" required id="timeTitle_${dateIndex}_${timeIndex}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">เวลาเริ่ม *</label>
                        <input type="time" name="dates[${dateIndex}][times][${timeIndex}][time_start]" 
                               class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200" 
                               value="08:00" required onchange="updateTimeTitle(${dateIndex}, ${timeIndex})">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">เวลาสิ้นสุด *</label>
                        <input type="time" name="dates[${dateIndex}][times][${timeIndex}][time_end]" 
                               class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200" 
                               value="17:00" required onchange="updateTimeTitle(${dateIndex}, ${timeIndex})">
                    </div>
                </div>
                
                <!-- Details -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">รายละเอียด</label>
                    <input type="text" name="dates[${dateIndex}][times][${timeIndex}][time_detail]" 
                           class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                           placeholder="รายละเอียด">
                </div>
                
                <!-- Participant Limits -->
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center justify-between mb-3">
                        <h6 class="text-sm font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-users text-blue-600 mr-2"></i>
                            การจำกัดจำนวนผู้เข้าร่วม
                        </h6>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <input type="hidden" name="dates[${dateIndex}][times][${timeIndex}][time_limit]" value="0">
                            <input type="checkbox" name="dates[${dateIndex}][times][${timeIndex}][time_limit]" value="1"
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                   onchange="toggleMaxParticipants(this, '${dateIndex}_${timeIndex}')">
                            <span class="ml-3 text-sm text-gray-700">เปิดใช้งานการจำกัดจำนวน</span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">จำนวนผู้เข้าร่วมสูงสุด</label>
                            <input type="number" name="dates[${dateIndex}][times][${timeIndex}][time_max]" min="0"
                                   class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 bg-gray-100 transition-all duration-200" 
                                   value="1" disabled id="maxParticipants_${dateIndex}_${timeIndex}"
                                   placeholder="ระบุจำนวน">
                        </div>
                    </div>
                </div>
            </div>
        `;
            container.insertAdjacentHTML('beforeend', timeHtml);
            // Set initial time title
            updateTimeTitle(dateIndex, timeIndex);
        }

        function toggleMaxParticipants(checkbox, id) {
            const maxParticipantsField = document.getElementById(`maxParticipants_${id}`);
            if (checkbox.checked) {
                maxParticipantsField.disabled = false;
                maxParticipantsField.classList.remove('bg-gray-100');
                maxParticipantsField.classList.add('bg-white');
            } else {
                maxParticipantsField.disabled = true;
                maxParticipantsField.classList.remove('bg-white');
                maxParticipantsField.classList.add('bg-gray-100');
            }
        }

        function removeTime(button) {
            const timeItem = button.closest('.time-item');
            if (timeItem) {
                timeItem.remove();
            }
        }

        function addLink() {
            const container = document.getElementById('linksContainer');
            const linkHtml = `
            <div class="link-item bg-white rounded-xl shadow-lg border border-gray-200 hover:shadow-xl transition-shadow duration-300">
                <!-- Link Header -->
                <div class="bg-gradient-to-r from-orange-600 to-red-600 text-white px-6 py-4 rounded-t-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                                <span class="text-sm font-bold">${linkIndex + 1}</span>
                            </div>
                            <h4 class="text-lg font-semibold">ลิงก์ ${linkIndex + 1}</h4>
                        </div>
                        <button type="button" onclick="removeLink(this)" 
                                class="text-white hover:text-red-200 transition-colors duration-200">
                            <i class="fas fa-trash text-lg"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Link Content -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">ชื่อลิงก์</label>
                            <input type="text" name="links[${linkIndex}][link_name]" 
                                   class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all duration-200"
                                   placeholder="ชื่อลิงก์">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">URL</label>
                            <input type="url" name="links[${linkIndex}][link_url]" 
                                   class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all duration-200"
                                   placeholder="https://example.com">
                        </div>
                    </div>
                    
                    <!-- Time Access Settings -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center mb-4">
                            <div class="w-6 h-6 bg-orange-600 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-clock text-white text-xs"></i>
                            </div>
                            <h5 class="text-sm font-semibold text-gray-800">การจำกัดเวลาการเข้าถึง</h5>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">เปิดใช้งานตั้งแต่</label>
                                <input type="time" name="links[${linkIndex}][link_time_start]" 
                                       class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 bg-gray-100 transition-all duration-200" 
                                       disabled id="linkTimeStart_${linkIndex}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">เปิดใช้งานจนถึง</label>
                                <input type="time" name="links[${linkIndex}][link_time_end]" 
                                       class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 bg-gray-100 transition-all duration-200" 
                                       disabled id="linkTimeEnd_${linkIndex}">
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="hidden" name="links[${linkIndex}][link_limit]" value="0">
                            <input type="checkbox" name="links[${linkIndex}][link_limit]" value="1"
                                   class="w-5 h-5 text-orange-600 border-gray-300 rounded focus:ring-orange-500"
                                   onchange="toggleLinkTimeFields(this, '${linkIndex}')">
                            <span class="ml-3 text-sm font-medium text-gray-700">เปิดใช้งานการจำกัดเวลาการเข้าถึง</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
            container.insertAdjacentHTML('beforeend', linkHtml);
            linkIndex++;
        }

        function toggleLinkTimeFields(checkbox, index) {
            const startField = document.getElementById(`linkTimeStart_${index}`);
            const endField = document.getElementById(`linkTimeEnd_${index}`);

            if (checkbox.checked) {
                startField.disabled = false;
                endField.disabled = false;
                startField.classList.remove('bg-gray-100');
                endField.classList.remove('bg-gray-100');
                startField.classList.add('bg-white');
                endField.classList.add('bg-white');
                startField.classList.remove('border-gray-200');
                endField.classList.remove('border-gray-200');
                startField.classList.add('border-orange-200');
                endField.classList.add('border-orange-200');
            } else {
                startField.disabled = true;
                endField.disabled = true;
                startField.classList.remove('bg-white');
                endField.classList.remove('bg-white');
                startField.classList.add('bg-gray-100');
                endField.classList.add('bg-gray-100');
                startField.classList.remove('border-orange-200');
                endField.classList.remove('border-orange-200');
                startField.classList.add('border-gray-200');
                endField.classList.add('border-gray-200');
                startField.value = '';
                endField.value = '';
            }
        }

        function removeLink(button) {
            const linkItem = button.closest('.link-item');
            if (linkItem) {
                linkItem.remove();
            }
        }

        // Function to restore date data from old values
        function restoreDateData(index, dateData) {
            const dateItem = document.querySelector(`[data-date-index="${index}"]`);
            if (!dateItem) return;

            // Fill basic date fields
            if (dateData.date_title) {
                dateItem.querySelector(`#dateTitle${index}`).value = dateData.date_title;
            }
            if (dateData.date_datetime) {
                dateItem.querySelector(`#dateDateTime${index}`).value = dateData.date_datetime;
            }
            if (dateData.date_location) {
                dateItem.querySelector(`input[name="dates[${index}][date_location]"]`).value = dateData.date_location;
            }
            if (dateData.date_detail) {
                dateItem.querySelector(`input[name="dates[${index}][date_detail]"]`).value = dateData.date_detail;
            }

            // Restore time slots
            if (dateData.times) {
                // Remove the default empty time slot first
                const timesContainer = document.getElementById(`timesContainer${index}`);
                if (timesContainer) {
                    timesContainer.innerHTML = '';

                    dateData.times.forEach((timeData, timeIndex) => {
                        addTime(index);
                        restoreTimeData(index, timeIndex, timeData);
                    });
                }
            }
        }

        // Function to restore time data
        function restoreTimeData(dateIndex, timeIndex, timeData) {
            const timeItem = document.querySelector(`#timesContainer${dateIndex} .time-item[data-time-index="${timeIndex}"]`);
            if (!timeItem) return;

            if (timeData.time_title) {
                timeItem.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_title]"]`).value = timeData.time_title;
            }
            if (timeData.time_start) {
                timeItem.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_start]"]`).value = timeData.time_start;
            }
            if (timeData.time_end) {
                timeItem.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_end]"]`).value = timeData.time_end;
            }
            if (timeData.time_max) {
                timeItem.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_max]"]`).value = timeData.time_max;
            }
            if (timeData.time_detail) {
                timeItem.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_detail]"]`).value = timeData.time_detail;
            }
            if (timeData.time_limit == '1') {
                const checkbox = timeItem.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_limit]"][type="checkbox"]`);
                checkbox.checked = true;
                toggleMaxParticipants(checkbox, `${dateIndex}_${timeIndex}`);
            }
        }

        // Function to restore link data from old values
        function restoreLinkData(index, linkData) {
            const linkItem = document.querySelector('.link-item:last-child');
            if (!linkItem) return;

            if (linkData.link_name) {
                linkItem.querySelector(`input[name="links[${index}][link_name]"]`).value = linkData.link_name;
            }
            if (linkData.link_url) {
                linkItem.querySelector(`input[name="links[${index}][link_url]"]`).value = linkData.link_url;
            }
            if (linkData.link_time_start) {
                linkItem.querySelector(`input[name="links[${index}][link_time_start]"]`).value = linkData.link_time_start;
            }
            if (linkData.link_time_end) {
                linkItem.querySelector(`input[name="links[${index}][link_time_end]"]`).value = linkData.link_time_end;
            }
            if (linkData.link_limit == '1') {
                const checkbox = linkItem.querySelector(`input[name="links[${index}][link_limit]"][type="checkbox"]`);
                checkbox.checked = true;
                toggleLinkTimeFields(checkbox, index);
            }
        }
    </script>
@endsection
