@extends("layouts.hrd")

@section("content")
    <div class="container mx-auto px-4">
        <div class="rounded-lg bg-white p-6 shadow-lg">
            <div class="mb-6 flex items-center">
                <a class="mr-4 text-blue-600 hover:text-blue-800" href="{{ route("hrd.admin.index") }}">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Create New Project</h1>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700">
                    <h4 class="font-bold">Please correct the following errors:</h4>
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
                <div class="mb-6 rounded-lg bg-gray-50 p-6">
                    <h2 class="mb-4 text-xl font-semibold text-gray-800">
                        <i class="fas fa-info-circle text-blue-600"></i> Basic Information
                    </h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">Project Name *</label>
                            <input class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" type="text" name="project_name" value="{{ old("project_name") }}" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">Project Type *</label>
                            <select class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" name="project_type" required>
                                <option value="">Select Type</option>
                                <option value="single" {{ old("project_type") === "single" ? "selected" : "" }}>Single</option>
                                <option value="multiple" {{ old("project_type") === "multiple" ? "selected" : "" }}>Multiple</option>
                                <option value="attendance" {{ old("project_type") === "attendance" ? "selected" : "" }}>Attendance</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Project Detail</label>
                            <textarea class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" name="project_detail" rows="3">{{ old("project_detail") }}</textarea>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">Registration Start *</label>
                            <input class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" type="datetime-local" name="project_start_register" value="{{ old("project_start_register", now()->format("Y-m-d 08:00")) }}" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">Registration End *</label>
                            <input class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" type="datetime-local" name="project_end_register" value="{{ old("project_end_register") }}" required>
                        </div>
                        <div class="flex items-center space-x-6">
                            <label class="flex items-center">
                                <input class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" type="checkbox" name="project_seat_assign" value="1" {{ old("project_seat_assign") ? "checked" : "" }}>
                                <span class="ml-2 text-sm text-gray-700">Enable Seat Assignment</span>
                            </label>
                            <label class="flex items-center">
                                <input class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" type="checkbox" name="project_register_today" value="1" {{ old("project_register_today", true) ? "checked" : "" }}>
                                <span class="ml-2 text-sm text-gray-700">Allow Today Registration</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Dates Section -->
                <div class="mb-6 rounded-lg bg-gray-50 p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-calendar text-blue-600"></i> Project Dates
                        </h2>
                        <button class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700" type="button" onclick="addDate()">
                            <i class="fas fa-plus"></i> Add Date
                        </button>
                    </div>
                    <div id="datesContainer">
                        <!-- Dates will be added here dynamically -->
                    </div>
                </div>

                <!-- Links Section -->
                <div class="mb-6 rounded-lg bg-gray-50 p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-link text-blue-600"></i> Project Links
                        </h2>
                        <button class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700" type="button" onclick="addLink()">
                            <i class="fas fa-plus"></i> Add Link
                        </button>
                    </div>
                    <div id="linksContainer">
                        <!-- Links will be added here dynamically -->
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <a class="rounded-lg bg-gray-500 px-6 py-3 font-semibold text-white hover:bg-gray-600" href="{{ route("hrd.admin.index") }}">
                        Cancel
                    </a>
                    <button class="rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-700" type="submit">
                        Create Project
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
            <div class="date-item border border-gray-200 rounded-lg p-4 mb-4" data-date-index="${dateIndex}">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-800">Date ${dateIndex + 1}</h3>
                    <button type="button" onclick="removeDate(${dateIndex})" 
                            class="text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                        <input type="date" name="dates[${dateIndex}][date_datetime]" id="dateDateTime${dateIndex}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               required onchange="updateDateTitle(${dateIndex})">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date Title *</label>
                        <input type="text" name="dates[${dateIndex}][date_title]" id="dateTitle${dateIndex}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input type="text" name="dates[${dateIndex}][date_location]" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <input type="text" name="dates[${dateIndex}][date_detail]" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <div class="border-t pt-4">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="text-md font-medium text-gray-700">Time Slots</h4>
                        <button type="button" onclick="addTime(${dateIndex})" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                            <i class="fas fa-plus"></i> Add Time
                        </button>
                    </div>
                    <div id="timesContainer${dateIndex}">
                        <!-- Times will be added here -->
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

        function removeDate(index) {
            const dateItem = document.querySelector(`[data-date-index="${index}"]`);
            if (dateItem) {
                dateItem.remove();
            }
        }

        function addTime(dateIndex) {
            const container = document.getElementById(`timesContainer${dateIndex}`);
            const timeIndex = container.children.length;
            const timeHtml = `
            <div class="time-item bg-white border border-gray-200 rounded p-3 mb-3">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm font-medium text-gray-600">Time Slot ${timeIndex + 1}</span>
                    <button type="button" onclick="removeTime(this)" 
                            class="text-red-600 hover:text-red-800 text-sm">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Title *</label>
                        <input type="text" name="dates[${dateIndex}][times][${timeIndex}][time_title]" 
                               class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Start Time *</label>
                        <input type="time" name="dates[${dateIndex}][times][${timeIndex}][time_start]" 
                               class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" 
                               value="08:00" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">End Time *</label>
                        <input type="time" name="dates[${dateIndex}][times][${timeIndex}][time_end]" 
                               class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" 
                               value="17:00" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Max Participants</label>
                        <input type="number" name="dates[${dateIndex}][times][${timeIndex}][time_max]" min="0"
                               class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 bg-gray-100" 
                               value="1" disabled id="maxParticipants_${dateIndex}_${timeIndex}">
                    </div>
                </div>
                <div class="mt-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                    <input type="text" name="dates[${dateIndex}][times][${timeIndex}][time_detail]" 
                           class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="mt-2">
                    <label class="flex items-center">
                        <input type="hidden" name="dates[${dateIndex}][times][${timeIndex}][time_limit]" value="0">
                        <input type="checkbox" name="dates[${dateIndex}][times][${timeIndex}][time_limit]" value="1"
                               class="w-3 h-3 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                               onchange="toggleMaxParticipants(this, '${dateIndex}_${timeIndex}')">
                        <span class="ml-2 text-xs text-gray-700">Limit participants</span>
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

        function addLink() {
            const container = document.getElementById('linksContainer');
            const linkHtml = `
            <div class="link-item border border-gray-200 rounded-lg p-4 mb-4">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-md font-medium text-gray-700">Link ${linkIndex + 1}</h4>
                    <button type="button" onclick="removeLink(this)" 
                            class="text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Link Name</label>
                        <input type="text" name="links[${linkIndex}][link_name]" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                        <input type="url" name="links[${linkIndex}][link_url]" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Available From</label>
                        <input type="time" name="links[${linkIndex}][link_time_start]" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" 
                               disabled id="linkTimeStart_${linkIndex}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Available Until</label>
                        <input type="time" name="links[${linkIndex}][link_time_end]" 
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
                        <span class="ml-2 text-sm text-gray-700">Time limited access</span>
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
                timesContainer.innerHTML = '';

                dateData.times.forEach((timeData, timeIndex) => {
                    addTime(index);
                    restoreTimeData(index, timeIndex, timeData);
                });
            }
        }

        // Function to restore time data
        function restoreTimeData(dateIndex, timeIndex, timeData) {
            const timeItem = document.querySelector(`#timesContainer${dateIndex} .time-item:nth-child(${timeIndex + 1})`);
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
