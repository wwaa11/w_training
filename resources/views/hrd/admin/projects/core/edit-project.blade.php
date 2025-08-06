@extends("layouts.hrd")

@section("content")
    <div class="container mx-auto px-4">
        <div class="rounded-lg bg-white p-6 shadow-lg">
            <div class="mb-6 flex items-center">
                <a class="mr-4 text-blue-600 hover:text-blue-800" href="{{ route("hrd.admin.projects.show", $project->id) }}">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">แก้ไขโปรเจกต์: {{ $project->project_name }}</h1>
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

            <form id="projectForm" action="{{ route("hrd.admin.projects.update", $project->id) }}" method="POST">
                @csrf

                <!-- Project Active Status - Top Section -->
                <div class="mb-6 rounded-lg border-2 border-green-200 bg-gradient-to-r from-green-50 to-emerald-50 p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-full bg-green-600">
                                <i class="fas fa-toggle-on text-lg text-white"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">สถานะโปรเจกต์</h2>
                        </div>
                        <button class="inline-flex items-center rounded-lg bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-3 text-lg font-semibold text-white shadow-lg transition-all duration-200 hover:scale-105 hover:from-green-700 hover:to-emerald-700" type="submit" form="projectForm">
                            <i class="fas fa-save mr-2"></i>
                            อัปเดตโปรเจกต์
                        </button>
                    </div>
                    <div class="rounded-xl border-2 border-green-200 bg-white p-6">
                        <label class="flex cursor-pointer items-center">
                            <input class="h-6 w-6 rounded border-gray-300 text-green-600 focus:ring-2 focus:ring-green-500" type="checkbox" name="project_active" value="1" {{ old("project_active", $project->project_active) ? "checked" : "" }}>
                            <div class="ml-4">
                                <span class="text-lg font-bold text-green-800">โปรเจกต์ใช้งาน</span>
                                <p class="mt-1 text-sm text-green-600">เปิดใช้งานโปรเจกต์นี้เพื่อให้ผู้ใช้สามารถเข้าถึงและลงทะเบียนได้</p>
                            </div>
                        </label>
                    </div>
                </div>

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
                                <input class="w-full rounded-lg border-2 border-gray-200 px-4 py-3 transition-all duration-200 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" type="text" name="project_name" value="{{ old("project_name", $project->project_name) }}" placeholder="ชื่อโปรเจกต์" required>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700">ประเภทโปรเจกต์ *</label>
                                <select class="w-full rounded-lg border-2 border-gray-200 px-4 py-3 transition-all duration-200 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" name="project_type" required onchange="showProjectTypeHint(this.value)">
                                    <option value="">เลือกประเภท</option>
                                    <option value="single" {{ old("project_type", $project->project_type) === "single" ? "selected" : "" }}>ลงทะเบียน 1 ครั้ง</option>
                                    <option value="multiple" {{ old("project_type", $project->project_type) === "multiple" ? "selected" : "" }}>ลงทะเบียนได้มากกว่า 1 ครั้ง</option>
                                    <option value="attendance" {{ old("project_type", $project->project_type) === "attendance" ? "selected" : "" }}>ไม่ต้องลงทะเบียน</option>
                                </select>
                                <div class="mt-2 hidden text-sm text-gray-600" id="projectTypeHint">
                                    <!-- Hints will be shown here -->
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-2 block text-sm font-semibold text-gray-700">รายละเอียดโปรเจกต์</label>
                                <textarea class="w-full rounded-lg border-2 border-gray-200 px-4 py-3 transition-all duration-200 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" name="project_detail" rows="3" placeholder="รายละเอียดโปรเจกต์">{{ old("project_detail", $project->project_detail) }}</textarea>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700">เริ่มลงทะเบียน *</label>
                                <input class="w-full rounded-lg border-2 border-gray-200 px-4 py-3 transition-all duration-200 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" type="datetime-local" name="project_start_register" value="{{ old("project_start_register", $project->project_start_register ? $project->project_start_register->format("Y-m-d\TH:i") : "") }}" required>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700">สิ้นสุดลงทะเบียน *</label>
                                <input class="w-full rounded-lg border-2 border-gray-200 px-4 py-3 transition-all duration-200 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" type="datetime-local" name="project_end_register" value="{{ old("project_end_register", $project->project_end_register ? $project->project_end_register->format("Y-m-d\TH:i") : "") }}" required>
                            </div>
                        </div>

                        <!-- Other Special Settings -->
                        <div class="mt-8 border-t border-gray-200 pt-6">
                            <div class="mb-4 flex items-center">
                                <div class="mr-3 flex h-8 w-8 items-center justify-center rounded-full bg-purple-600">
                                    <i class="fas fa-cog text-sm text-white"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">การตั้งค่าพิเศษ</h3>
                            </div>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <label class="flex cursor-pointer items-center rounded-lg bg-gray-50 p-4 transition-colors duration-200 hover:bg-gray-100">
                                    <input class="h-5 w-5 rounded border-gray-300 text-green-600 focus:ring-green-500" type="checkbox" name="project_seat_assign" value="1" {{ old("project_seat_assign", $project->project_seat_assign) ? "checked" : "" }}>
                                    <span class="ml-3 text-sm font-medium text-gray-700">เปิดใช้งานการจัดที่นั่ง</span>
                                </label>
                                <label class="flex cursor-pointer items-center rounded-lg bg-gray-50 p-4 transition-colors duration-200 hover:bg-gray-100">
                                    <input class="h-5 w-5 rounded border-gray-300 text-green-600 focus:ring-green-500" type="checkbox" name="project_register_today" value="1" {{ old("project_register_today", $project->project_register_today) ? "checked" : "" }}>
                                    <span class="ml-3 text-sm font-medium text-gray-700">เปิดให้ลงทะเบียนในวันที่มีการจัดหลักสูตร</span>
                                </label>
                                <label class="flex cursor-pointer items-center rounded-lg bg-gray-50 p-4 transition-colors duration-200 hover:bg-gray-100">
                                    <input class="h-5 w-5 rounded border-gray-300 text-green-600 focus:ring-green-500" type="checkbox" name="project_group_assign" value="1" {{ old("project_group_assign", $project->project_group_assign) ? "checked" : "" }}>
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
                        <!-- Dates will be populated here -->
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
                        <!-- Links will be populated here -->
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
                    <a class="inline-flex transform items-center rounded-xl border-2 border-gray-300 bg-white px-8 py-4 text-lg font-semibold text-gray-700 shadow-lg transition-all duration-200 hover:scale-105 hover:border-gray-400 hover:bg-gray-50" href="{{ route("hrd.admin.projects.show", $project->id) }}">
                        <i class="fas fa-times mr-2"></i>
                        ยกเลิก
                    </a>
                    <button class="inline-flex transform items-center rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-4 text-lg font-semibold text-white shadow-lg transition-all duration-200 hover:scale-105 hover:from-blue-700 hover:to-indigo-700" type="submit">
                        <i class="fas fa-save mr-2"></i>
                        อัปเดตโปรเจกต์
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

        // Get formatted data from backend
        const editData = @json($editData);

        // Convert date to Thai format
        function formatThaiDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            const day = date.getDate();
            const month = editData.thaiMonths[date.getMonth()];
            const year = date.getFullYear() + 543; // Convert to Buddhist year
            return `${day} ${month} ${year}`;
        }

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            loadExistingData();

            // Show project type hint for current project type
            const currentProjectType = @json($project->project_type);
            if (currentProjectType) {
                showProjectTypeHint(currentProjectType);
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
                title: 'ยืนยันการอัปเดตโครงการ',
                text: 'คุณต้องการอัปเดตโครงการนี้ใช่หรือไม่?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
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

        function loadExistingData() {
            // Load existing dates
            if (editData.dates && editData.dates.length > 0) {
                editData.dates.forEach((dateData, index) => {
                    addDate(dateData.id); // Pass existing ID
                    loadDateData(index, dateData);
                });
            } else {
                addDate();
            }

            // Load existing links
            if (editData.links && editData.links.length > 0) {
                editData.links.forEach((linkData, index) => {
                    addLink(linkData.id); // Pass existing ID
                    loadLinkData(index, linkData);
                });
            } else {
                // Add at least one empty link field
                addLink();
            }


        }

        function loadDateData(index, dateData) {
            setTimeout(() => {
                const dateItem = document.querySelector(`[data-date-index="${index}"]`);
                if (!dateItem) return;

                const dateTitleInput = dateItem.querySelector(`#dateTitle${index}`);
                const dateTimeInput = dateItem.querySelector(`#dateDateTime${index}`);
                const locationInput = dateItem.querySelector(`input[name="dates[${index}][date_location]"]`);
                const detailInput = dateItem.querySelector(`input[name="dates[${index}][date_detail]"]`);

                if (dateTitleInput) dateTitleInput.value = dateData.date_title || '';
                if (locationInput) locationInput.value = dateData.date_location || '';
                if (detailInput) detailInput.value = dateData.date_detail || '';
                if (dateData.date_datetime && dateTimeInput) {
                    dateTimeInput.value = dateData.date_datetime;
                }

                // Load time slots
                if (dateData.times && dateData.times.length > 0) {
                    const timesContainer = document.getElementById(`timesContainer${index}`);
                    if (timesContainer) {
                        timesContainer.innerHTML = '';

                        dateData.times.forEach((timeData, timeIndex) => {
                            addTime(index, timeData.id, timeData); // Pass existing ID and timeData
                            // Increase delay to ensure DOM is ready
                            setTimeout(() => {
                                loadTimeData(index, timeIndex, timeData);
                            }, 100 * (timeIndex + 1));
                        });
                    }
                } else {
                    addTime(index);
                }
            }, 30);
        }

        function loadTimeData(dateIndex, timeIndex, timeData) {
            const timeItem = document.querySelector(`#timesContainer${dateIndex} .time-item:nth-child(${timeIndex + 1})`);
            if (!timeItem) {
                console.log(`Time item not found for dateIndex: ${dateIndex}, timeIndex: ${timeIndex}`);
                return;
            }

            const titleInput = timeItem.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_title]"]`);
            const startInput = timeItem.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_start]"]`);
            const endInput = timeItem.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_end]"]`);
            const maxInput = timeItem.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_max]"]`);
            const detailInput = timeItem.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_detail]"]`);

            console.log(`Loading time data for dateIndex: ${dateIndex}, timeIndex: ${timeIndex}`, timeData);

            if (titleInput) titleInput.value = timeData.time_title || '';

            // Handle time values with proper formatting for 24-hour format
            if (startInput) {
                let startTime = timeData.time_start || '08:00';
                console.log(`Original start time: ${startTime}`);

                // Convert to 24-hour format
                startTime = convertTo24HourFormat(startTime);
                startInput.value = startTime;
                console.log(`Set start time to: ${startTime}`);
            } else {
                console.log(`Start input not found for dateIndex: ${dateIndex}, timeIndex: ${timeIndex}`);
            }

            if (endInput) {
                let endTime = timeData.time_end || '17:00';
                console.log(`Original end time: ${endTime}`);

                // Convert to 24-hour format
                endTime = convertTo24HourFormat(endTime);
                endInput.value = endTime;
                console.log(`Set end time to: ${endTime}`);
            } else {
                console.log(`End input not found for dateIndex: ${dateIndex}, timeIndex: ${timeIndex}`);
            }

            if (maxInput) maxInput.value = timeData.time_max || '1';
            if (detailInput) detailInput.value = timeData.time_detail || '';

            if (timeData.time_limit) {
                const checkbox = timeItem.querySelector(`input[name="dates[${dateIndex}][times][${timeIndex}][time_limit]"][type="checkbox"]`);
                if (checkbox) {
                    checkbox.checked = true;
                    toggleMaxParticipants(checkbox, `${dateIndex}_${timeIndex}`);
                }
            }

            // Fallback: ensure time values are set after a short delay
            setTimeout(() => {
                if (startInput && !startInput.value) {
                    startInput.value = '08:00';
                    console.log(`Fallback: Set start time to 08:00`);
                }
                if (endInput && !endInput.value) {
                    endInput.value = '17:00';
                    console.log(`Fallback: Set end time to 17:00`);
                }
            }, 200);
        }

        // Helper function to convert time to 24-hour format
        function convertTo24HourFormat(timeString) {
            if (!timeString || typeof timeString !== 'string') {
                return '08:00';
            }

            console.log(`Converting time: ${timeString}`);

            // Remove any extra spaces and convert to uppercase
            timeString = timeString.trim().toUpperCase();

            // If it's a full datetime string with timezone (UTC), convert to local time
            if (timeString.includes('T') && (timeString.includes('Z') || timeString.includes('+') || timeString.includes('-'))) {
                try {
                    const date = new Date(timeString);
                    console.log(`Parsed date: ${date.toISOString()}, Local: ${date.toString()}`);
                    if (!isNaN(date.getTime())) {
                        // Extract time in local timezone
                        const hours = date.getHours().toString().padStart(2, '0');
                        const minutes = date.getMinutes().toString().padStart(2, '0');
                        const result = `${hours}:${minutes}`;
                        console.log(`Converted to local time: ${result}`);
                        return result;
                    }
                } catch (e) {
                    console.log('Error parsing datetime:', e);
                }
            }

            // If it's already in 24-hour format (HH:MM), return as is
            if (/^\d{1,2}:\d{2}$/.test(timeString)) {
                const [hours, minutes] = timeString.split(':');
                const hour = parseInt(hours);
                const minute = parseInt(minutes);

                // Validate hours and minutes
                if (hour >= 0 && hour <= 23 && minute >= 0 && minute <= 59) {
                    const result = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
                    console.log(`Already in 24-hour format: ${result}`);
                    return result;
                }
            }

            // If it's in 12-hour format with AM/PM
            if (timeString.includes('AM') || timeString.includes('PM')) {
                const timeMatch = timeString.match(/(\d{1,2}):(\d{2})\s*(AM|PM)/);
                if (timeMatch) {
                    let hours = parseInt(timeMatch[1]);
                    const minutes = parseInt(timeMatch[2]);
                    const period = timeMatch[3];

                    // Convert to 24-hour format
                    if (period === 'PM' && hours !== 12) {
                        hours += 12;
                    } else if (period === 'AM' && hours === 12) {
                        hours = 0;
                    }

                    const result = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
                    console.log(`Converted from 12-hour format: ${result}`);
                    return result;
                }
            }

            // If it's a datetime string without timezone, extract time part
            if (timeString.includes('T') && !timeString.includes('Z') && !timeString.includes('+') && !timeString.includes('-')) {
                const timePart = timeString.split('T')[1]?.split(' ')[0];
                if (timePart) {
                    console.log(`Extracting time from datetime: ${timePart}`);
                    return convertTo24HourFormat(timePart);
                }
            }

            // Default fallback
            console.log(`Using default time: 08:00`);
            return '08:00';
        }

        function loadLinkData(index, linkData) {
            setTimeout(() => {
                // Select the specific link item by its index position
                const linkItems = document.querySelectorAll('.link-item');
                const linkItem = linkItems[index];
                if (!linkItem) return;

                const nameInput = linkItem.querySelector(`input[name="links[${index}][link_name]"]`);
                const urlInput = linkItem.querySelector(`input[name="links[${index}][link_url]"]`);
                const startInput = linkItem.querySelector(`input[name="links[${index}][link_time_start]"]`);
                const endInput = linkItem.querySelector(`input[name="links[${index}][link_time_end]"]`);
                const limitCheckbox = linkItem.querySelector(`input[name="links[${index}][link_limit]"][type="checkbox"]`);

                if (nameInput) nameInput.value = linkData.link_name || '';
                if (urlInput) urlInput.value = linkData.link_url || '';

                if (linkData.link_limit && limitCheckbox) {
                    limitCheckbox.checked = true;
                    toggleLinkTimeFields(limitCheckbox, index);

                    // Set time values after enabling fields
                    setTimeout(() => {
                        if (startInput && linkData.link_time_start) {
                            startInput.value = linkData.link_time_start;
                        }
                        if (endInput && linkData.link_time_end) {
                            endInput.value = linkData.link_time_end;
                        }
                    }, 10);
                }
            }, 30);
        }

        function addDate(existingDateId = null) {
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
                    ${existingDateId ? `<input type="hidden" name="dates[${dateIndex}][id]" value="${existingDateId}">` : ''}
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
            dateIndex++;
        }

        function updateDateTitle(dateIndex) {
            const dateTimeInput = document.getElementById(`dateDateTime${dateIndex}`);
            const dateTitleInput = document.getElementById(`dateTitle${dateIndex}`);

            if (dateTimeInput.value) {
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

        function addTime(dateIndex, existingTimeId = null, timeData = null) {
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
                
                ${existingTimeId ? `<input type="hidden" name="dates[${dateIndex}][times][${timeIndex}][id]" value="${existingTimeId}">` : ''}
                
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



        function addLink(existingLinkId = null) {
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
                    ${existingLinkId ? `<input type="hidden" name="links[${linkIndex}][id]" value="${existingLinkId}">` : ''}
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
    </script>
@endsection
