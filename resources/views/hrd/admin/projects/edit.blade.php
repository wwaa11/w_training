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

                <!-- Basic Project Information -->
                <div class="mb-6 rounded-lg bg-gray-50 p-6">
                    <h2 class="mb-4 text-xl font-semibold text-gray-800">
                        <i class="fas fa-info-circle text-blue-600"></i> ข้อมูลพื้นฐาน
                    </h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">ชื่อโปรเจกต์ *</label>
                            <input class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" type="text" name="project_name" value="{{ old("project_name", $project->project_name) }}" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">ประเภทโปรเจกต์ *</label>
                            <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" name="project_type" required>
                                <option value="">เลือกประเภท</option>
                                <option value="single" {{ old("project_type", $project->project_type) === "single" ? "selected" : "" }}>เดี่ยว</option>
                                <option value="multiple" {{ old("project_type", $project->project_type) === "multiple" ? "selected" : "" }}>หลาย</option>
                                <option value="attendance" {{ old("project_type", $project->project_type) === "attendance" ? "selected" : "" }}>เข้าร่วม</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-gray-700">รายละเอียดโปรเจกต์</label>
                            <textarea class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" name="project_detail" rows="3">{{ old("project_detail", $project->project_detail) }}</textarea>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">เริ่มลงทะเบียน *</label>
                            <input class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" type="datetime-local" name="project_start_register" value="{{ old("project_start_register", $project->project_start_register ? $project->project_start_register->format("Y-m-d\TH:i") : "") }}" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">สิ้นสุดลงทะเบียน *</label>
                            <input class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" type="datetime-local" name="project_end_register" value="{{ old("project_end_register", $project->project_end_register ? $project->project_end_register->format("Y-m-d\TH:i") : "") }}" required>
                        </div>
                        <div class="flex items-center space-x-6">
                            <label class="flex items-center">
                                <input class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" type="checkbox" name="project_seat_assign" value="1" {{ old("project_seat_assign", $project->project_seat_assign) ? "checked" : "" }}>
                                <span class="ml-2 text-sm text-gray-700">เปิดใช้งานการจัดที่นั่ง</span>
                            </label>
                            <label class="flex items-center">
                                <input class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" type="checkbox" name="project_register_today" value="1" {{ old("project_register_today", $project->project_register_today) ? "checked" : "" }}>
                                <span class="ml-2 text-sm text-gray-700">อนุญาตให้ลงทะเบียนวันเดียวกัน</span>
                            </label>
                            <label class="flex items-center">
                                <input class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" type="checkbox" name="project_active" value="1" {{ old("project_active", $project->project_active) ? "checked" : "" }}>
                                <span class="ml-2 text-sm text-gray-700">โปรเจกต์ใช้งาน</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Dates Section -->
                <div class="mb-6 rounded-lg bg-gray-50 p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-calendar text-blue-600"></i> วันที่โปรเจกต์
                        </h2>
                        <div class="flex items-center space-x-3">
                            <button class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700" type="button" onclick="addDate()">
                                <i class="fas fa-plus"></i> เพิ่มวันที่
                            </button>
                        </div>
                    </div>
                    <div id="datesContainer">
                        <!-- Dates will be populated here -->
                    </div>
                </div>

                <!-- Links Section -->
                <div class="mb-6 rounded-lg bg-gray-50 p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-link text-blue-600"></i> ลิงก์โปรเจกต์
                        </h2>
                        <button class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700" type="button" onclick="addLink()">
                            <i class="fas fa-plus"></i> เพิ่มลิงก์
                        </button>
                    </div>
                    <div id="linksContainer">
                        <!-- Links will be populated here -->
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <a class="rounded-lg bg-gray-500 px-6 py-3 font-semibold text-white hover:bg-gray-600" href="{{ route("hrd.admin.projects.show", $project->id) }}">
                        ยกเลิก
                    </a>
                    <button class="rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-700" type="submit">
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
        });



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

                        // Filter out deleted times - don't show them in edit
                        const filteredTimes = dateData.times.filter(timeData => !timeData.time_delete);

                        filteredTimes.forEach((timeData, timeIndex) => {
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
            <div class="date-item border border-gray-200 rounded-lg p-4 mb-4" data-date-index="${dateIndex}">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-800">วันที่ ${dateIndex + 1}</h3>
                    <button type="button" onclick="removeDate(${dateIndex})" 
                            class="text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                ${existingDateId ? `<input type="hidden" name="dates[${dateIndex}][id]" value="${existingDateId}">` : ''}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">วันที่ *</label>
                        <input type="date" name="dates[${dateIndex}][date_datetime]" id="dateDateTime${dateIndex}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               required onchange="updateDateTitle(${dateIndex})">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อวันที่ *</label>
                        <input type="text" name="dates[${dateIndex}][date_title]" id="dateTitle${dateIndex}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">สถานที่</label>
                        <input type="text" name="dates[${dateIndex}][date_location]" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">รายละเอียด</label>
                        <input type="text" name="dates[${dateIndex}][date_detail]" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <div class="border-t pt-4">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="text-md font-medium text-gray-700">ช่วงเวลา</h4>
                        <button type="button" onclick="addTime(${dateIndex})" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                            <i class="fas fa-plus"></i> เพิ่มเวลา
                        </button>
                    </div>
                    <div id="timesContainer${dateIndex}">
                        <!-- Times will be added here -->
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

        function removeDate(index) {
            const dateItem = document.querySelector(`[data-date-index="${index}"]`);
            if (dateItem) {
                dateItem.remove();
            }
        }

        function addTime(dateIndex, existingTimeId = null, timeData = null) {
            const container = document.getElementById(`timesContainer${dateIndex}`);
            const timeIndex = container.children.length;
            const timeHtml = `
            <div class="time-item bg-white border border-gray-200 rounded p-3 mb-3">
                <div class="flex justify-between items-center mb-3">
                    <div class="flex items-center">
                        <span class="text-sm font-medium text-gray-600">ช่วงเวลา ${timeIndex + 1}</span>
                    </div>
                    <div class="flex space-x-2">
                        <button type="button" onclick="removeTime(this)" 
                                class="text-red-600 hover:text-red-800 text-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                ${existingTimeId ? `<input type="hidden" name="dates[${dateIndex}][times][${timeIndex}][id]" value="${existingTimeId}">` : ''}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">ชื่อ *</label>
                        <input type="text" name="dates[${dateIndex}][times][${timeIndex}][time_title]" 
                               class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" 
                               required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">เวลาเริ่ม *</label>
                        <input type="time" name="dates[${dateIndex}][times][${timeIndex}][time_start]" 
                               class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" 
                               value="08:00" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">เวลาสิ้นสุด *</label>
                        <input type="time" name="dates[${dateIndex}][times][${timeIndex}][time_end]" 
                               class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" 
                               value="17:00" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">จำนวนผู้เข้าร่วมสูงสุด</label>
                        <input type="number" name="dates[${dateIndex}][times][${timeIndex}][time_max]" min="0"
                               class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 bg-gray-100" 
                               value="1" disabled id="maxParticipants_${dateIndex}_${timeIndex}">
                    </div>
                </div>
                <div class="mt-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">รายละเอียด</label>
                    <input type="text" name="dates[${dateIndex}][times][${timeIndex}][time_detail]" 
                           class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="mt-2">
                    <label class="flex items-center">
                        <input type="hidden" name="dates[${dateIndex}][times][${timeIndex}][time_limit]" value="0">
                        <input type="checkbox" name="dates[${dateIndex}][times][${timeIndex}][time_limit]" value="1"
                               class="w-3 h-3 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                               onchange="toggleMaxParticipants(this, '${dateIndex}_${timeIndex}')">
                        <span class="ml-2 text-xs text-gray-700">จำกัดจำนวนผู้เข้าร่วม</span>
                    </label>
                </div>
            </div>
        `;
            container.insertAdjacentHTML('beforeend', timeHtml);
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
            <div class="link-item border border-gray-200 rounded-lg p-4 mb-4">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-md font-medium text-gray-700">ลิงก์ ${linkIndex + 1}</h4>
                    <button type="button" onclick="removeLink(this)" 
                            class="text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                ${existingLinkId ? `<input type="hidden" name="links[${linkIndex}][id]" value="${existingLinkId}">` : ''}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อลิงก์</label>
                        <input type="text" name="links[${linkIndex}][link_name]" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                        <input type="url" name="links[${linkIndex}][link_url]" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ใช้งานได้ตั้งแต่</label>
                        <input type="datetime-local" name="links[${linkIndex}][link_time_start]" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" 
                               disabled id="linkTimeStart_${linkIndex}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ใช้งานได้จนถึง</label>
                        <input type="datetime-local" name="links[${linkIndex}][link_time_end]" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" 
                               disabled id="linkTimeEnd_${linkIndex}">
                    </div>
                </div>
                <div class="mt-3">
                    <label class="flex items-center">
                        <input type="hidden" name="links[${linkIndex}][link_limit]" value="0">
                        <input type="checkbox" name="links[${linkIndex}][link_limit]" value="1"
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                               onchange="toggleLinkTimeFields(this, '${linkIndex}')">
                        <span class="ml-2 text-sm text-gray-700">จำกัดเวลาการเข้าถึง</span>
                    </label>
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
            } else {
                startField.disabled = true;
                endField.disabled = true;
                startField.classList.remove('bg-white');
                endField.classList.remove('bg-white');
                startField.classList.add('bg-gray-100');
                endField.classList.add('bg-gray-100');
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
