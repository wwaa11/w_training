@extends("layouts.hrd")

@section("content")
    <div class="container mx-auto px-4">
        <div class="rounded-lg bg-white p-6 shadow-lg">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center">
                    <a class="mr-4 text-blue-600 hover:text-blue-800" href="{{ route("hrd.admin.projects.show", $project->id) }}">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">จัดการที่นั่ง - {{ $project->project_name }}</h1>
                        <p class="text-gray-600">จัดการการจัดที่นั่งสำหรับโปรเจกต์</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button class="rounded-lg bg-green-600 px-4 py-2 font-semibold text-white hover:bg-green-700" onclick="refreshSeatData()">
                        <i class="fas fa-sync-alt"></i> รีเฟรชข้อมูล
                    </button>
                    <button class="rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700" onclick="triggerSeatAssignment()">
                        <i class="fas fa-cogs"></i> จัดที่นั่งอัตโนมัติ
                    </button>
                    <button class="rounded-lg bg-purple-600 px-4 py-2 font-semibold text-white hover:bg-purple-700" onclick="exportSeatData()">
                        <i class="fas fa-download"></i> ส่งออกข้อมูล
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-4">
                <div class="rounded-lg bg-blue-50 p-4">
                    <div class="flex items-center">
                        <i class="fas fa-chair mr-3 text-2xl text-blue-600"></i>
                        <div>
                            <p class="text-2xl font-bold text-blue-900" id="totalSeats">0</p>
                            <p class="text-sm text-blue-700">ที่นั่งที่จัดแล้ว</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg bg-green-50 p-4">
                    <div class="flex items-center">
                        <i class="fas fa-users mr-3 text-2xl text-green-600"></i>
                        <div>
                            <p class="text-2xl font-bold text-green-900" id="totalRegistrations">0</p>
                            <p class="text-sm text-green-700">การลงทะเบียนทั้งหมด</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg bg-yellow-50 p-4">
                    <div class="flex items-center">
                        <i class="fas fa-user-clock mr-3 text-2xl text-yellow-600"></i>
                        <div>
                            <p class="text-2xl font-bold text-yellow-900" id="unassignedSeats">0</p>
                            <p class="text-sm text-yellow-700">รอจัดที่นั่ง</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg bg-purple-50 p-4">
                    <div class="flex items-center">
                        <i class="fas fa-building mr-3 text-2xl text-purple-600"></i>
                        <div>
                            <p class="text-2xl font-bold text-purple-900" id="totalDepartments">0</p>
                            <p class="text-sm text-purple-700">แผนกที่เข้าร่วม</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading Spinner -->
            <div class="mb-6 hidden text-center" id="loadingSpinner">
                <div class="inline-flex items-center rounded-lg bg-blue-50 px-4 py-2">
                    <i class="fas fa-spinner fa-spin mr-2 text-blue-600"></i>
                    <span class="text-blue-700">กำลังโหลดข้อมูล...</span>
                </div>
            </div>

            <!-- Error Message -->
            <div class="mb-6 hidden rounded-lg bg-red-100 p-4 text-red-700" id="errorMessage">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span id="errorText"></span>
            </div>

            <!-- Seat Management Content -->
            <div class="space-y-6" id="seatContent">
                <!-- Date and Time Sections -->
                <div id="dateTimeSections"></div>
            </div>

            <!-- Manual Seat Assignment Modal -->
            <div class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black bg-opacity-50" id="manualSeatModal">
                <div class="w-full max-w-2xl rounded-lg bg-white p-6 shadow-xl">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-gray-800">จัดที่นั่งด้วยตนเอง</h3>
                        <button class="text-gray-500 hover:text-gray-700" onclick="closeManualSeatModal()">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">เลือกผู้ใช้:</label>
                        <select class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" id="userSelect">
                            <option value="">เลือกผู้ใช้...</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">หมายเลขที่นั่ง:</label>
                        <input class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" id="seatNumberInput" type="number" min="1">
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button class="rounded-lg bg-gray-500 px-4 py-2 text-white hover:bg-gray-600" onclick="closeManualSeatModal()">
                            ยกเลิก
                        </button>
                        <button class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700" onclick="assignManualSeat()">
                            จัดที่นั่ง
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("scripts")
    <script>
        let currentSeatData = null;
        let currentTimeId = null;

        // Load seat data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadSeatData();
        });

        function showLoading() {
            document.getElementById('loadingSpinner').classList.remove('hidden');
            document.getElementById('seatContent').classList.add('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingSpinner').classList.add('hidden');
            document.getElementById('seatContent').classList.remove('hidden');
        }

        function showError(message) {
            document.getElementById('errorText').textContent = message;
            document.getElementById('errorMessage').classList.remove('hidden');
        }

        function hideError() {
            document.getElementById('errorMessage').classList.add('hidden');
        }

        function loadSeatData() {
            showLoading();
            hideError();

            axios.get(`{{ route("hrd.admin.seats.get", $project->id) }}`)
                .then(response => {
                    currentSeatData = response.data;
                    displaySeatData(response.data);
                    updateStats(response.data);
                })
                .catch(error => {
                    console.error('Error loading seat data:', error);
                    showError('เกิดข้อผิดพลาดในการโหลดข้อมูลการจัดที่นั่ง');
                })
                .finally(() => {
                    hideLoading();
                });
        }

        function displaySeatData(data) {
            const container = document.getElementById('dateTimeSections');
            container.innerHTML = '';

            data.seat_data.forEach(session => {
                const sessionDiv = document.createElement('div');
                sessionDiv.className = 'rounded-lg border border-gray-200 p-6';

                sessionDiv.innerHTML = `
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-calendar text-blue-600 mr-2"></i>
                            ${session.date} - ${session.time}
                        </h3>
                        <div class="flex space-x-2">
                            <button onclick="openManualSeatModal(${session.time_id})" class="rounded bg-green-600 px-3 py-1 text-sm text-white hover:bg-green-700">
                                <i class="fas fa-plus mr-1"></i>จัดที่นั่งด้วยตนเอง
                            </button>
                            <button onclick="clearSeats(${session.time_id})" class="rounded bg-red-600 px-3 py-1 text-sm text-white hover:bg-red-700">
                                <i class="fas fa-trash mr-1"></i>ล้างที่นั่ง
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <!-- Seat Assignments -->
                        <div>
                            <h4 class="mb-3 font-medium text-gray-700">
                                <i class="fas fa-chair mr-2"></i>ที่นั่งที่จัดแล้ว (${session.seats.length})
                            </h4>
                            <div class="rounded-lg bg-gray-50 p-4">
                                ${session.seats.length > 0 ? 
                                    session.seats.map(seat => `
                                                                <div class="mb-2 flex items-center justify-between rounded bg-white p-2 shadow-sm">
                                                                    <div>
                                                                        <span class="font-medium">ที่นั่ง ${seat.seat_number}</span>
                                                                        <span class="ml-2 text-sm text-gray-600">${seat.user_name}</span>
                                                                        <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">${seat.department}</span>
                                                                    </div>
                                                                    <button onclick="removeSeat(${session.time_id}, ${seat.user_id})" class="text-red-600 hover:text-red-800">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </div>
                                                            `).join('') : 
                                    '<p class="text-gray-500 text-center py-4">ยังไม่มีที่นั่งที่จัด</p>'
                                }
                            </div>
                        </div>
                        
                        <!-- Unassigned Registrations -->
                        <div>
                            <h4 class="mb-3 font-medium text-gray-700">
                                <i class="fas fa-users mr-2"></i>รอจัดที่นั่ง (${session.registrations.filter(r => !r.seat_number).length})
                            </h4>
                            <div class="rounded-lg bg-gray-50 p-4">
                                ${session.registrations.filter(r => !r.seat_number).length > 0 ? 
                                    session.registrations.filter(r => !r.seat_number).map(reg => `
                                                                <div class="mb-2 flex items-center justify-between rounded bg-white p-2 shadow-sm">
                                                                    <div>
                                                                        <span class="font-medium">${reg.user_name}</span>
                                                                        <span class="ml-2 text-xs bg-green-100 text-green-800 px-2 py-1 rounded">${reg.department}</span>
                                                                    </div>
                                                                    <button onclick="assignSeat(${session.time_id}, ${reg.user_id})" class="text-blue-600 hover:text-blue-800">
                                                                        <i class="fas fa-plus"></i>
                                                                    </button>
                                                                </div>
                                                            `).join('') : 
                                    '<p class="text-gray-500 text-center py-4">ไม่มีผู้ใช้ที่รอจัดที่นั่ง</p>'
                                }
                            </div>
                        </div>
                    </div>
                `;

                container.appendChild(sessionDiv);
            });
        }

        function updateStats(data) {
            let totalSeats = 0;
            let totalRegistrations = 0;
            let unassignedSeats = 0;
            const departments = new Set();

            data.seat_data.forEach(session => {
                totalSeats += session.seats.length;
                totalRegistrations += session.registrations.length;
                unassignedSeats += session.registrations.filter(r => !r.seat_number).length;

                session.seats.forEach(seat => departments.add(seat.department));
                session.registrations.forEach(reg => departments.add(reg.department));
            });

            document.getElementById('totalSeats').textContent = totalSeats;
            document.getElementById('totalRegistrations').textContent = totalRegistrations;
            document.getElementById('unassignedSeats').textContent = unassignedSeats;
            document.getElementById('totalDepartments').textContent = departments.size;
        }

        function refreshSeatData() {
            loadSeatData();
        }

        function triggerSeatAssignment() {
            if (confirm('คุณแน่ใจหรือไม่ที่จะเริ่มการจัดที่นั่งอัตโนมัติสำหรับโปรเจกต์นี้?')) {
                showLoading();

                axios.post('{{ route("hrd.admin.seats.trigger_assignment") }}')
                    .then(response => {
                        alert('เริ่มการจัดที่นั่งอัตโนมัติสำเร็จ!');
                        loadSeatData(); // Reload data after assignment
                    })
                    .catch(error => {
                        console.error('Error triggering seat assignment:', error);
                        alert('เกิดข้อผิดพลาดในการเริ่มการจัดที่นั่ง กรุณาลองใหม่อีกครั้ง');
                    })
                    .finally(() => {
                        hideLoading();
                    });
            }
        }

        function exportSeatData() {
            if (!currentSeatData) {
                alert('ไม่มีข้อมูลที่จะส่งออก');
                return;
            }

            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "วันที่,เวลา,หมายเลขที่นั่ง,ชื่อผู้ใช้,แผนก\n";

            currentSeatData.seat_data.forEach(session => {
                session.seats.forEach(seat => {
                    csvContent += `${session.date},${session.time},${seat.seat_number},"${seat.user_name}","${seat.department}"\n`;
                });
            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `seat_assignments_${currentSeatData.project}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function openManualSeatModal(timeId) {
            currentTimeId = timeId;
            const session = currentSeatData.seat_data.find(s => s.time_id === timeId);

            if (!session) return;

            const userSelect = document.getElementById('userSelect');
            userSelect.innerHTML = '<option value="">เลือกผู้ใช้...</option>';

            session.registrations.filter(r => !r.seat_number).forEach(reg => {
                const option = document.createElement('option');
                option.value = reg.user_id;
                option.textContent = `${reg.user_name} (${reg.department})`;
                userSelect.appendChild(option);
            });

            document.getElementById('manualSeatModal').classList.remove('hidden');
        }

        function closeManualSeatModal() {
            document.getElementById('manualSeatModal').classList.add('hidden');
            document.getElementById('userSelect').value = '';
            document.getElementById('seatNumberInput').value = '';
        }

        function assignManualSeat() {
            const userId = document.getElementById('userSelect').value;
            const seatNumber = document.getElementById('seatNumberInput').value;

            if (!userId || !seatNumber) {
                alert('กรุณาเลือกผู้ใช้และหมายเลขที่นั่ง');
                return;
            }

            axios.post(`{{ route("hrd.admin.projects.seat.assign", $project->id) }}`, {
                    time_id: currentTimeId,
                    user_id: userId,
                    seat_number: seatNumber
                })
                .then(response => {
                    alert('จัดที่นั่งสำเร็จ!');
                    closeManualSeatModal();
                    loadSeatData();
                })
                .catch(error => {
                    console.error('Error assigning seat:', error);
                    const message = error.response?.data?.error || 'เกิดข้อผิดพลาดในการจัดที่นั่ง';
                    alert(message);
                });
        }

        function assignSeat(timeId, userId) {
            if (confirm('คุณแน่ใจหรือไม่ที่จะจัดที่นั่งให้ผู้ใช้นี้?')) {
                // Find the next available seat number
                const session = currentSeatData.seat_data.find(s => s.time_id === timeId);
                if (!session) return;

                let nextSeatNumber = 1;
                const usedSeats = session.seats.map(s => s.seat_number).sort((a, b) => a - b);

                for (let seat of usedSeats) {
                    if (seat === nextSeatNumber) {
                        nextSeatNumber++;
                    } else {
                        break;
                    }
                }

                axios.post(`{{ route("hrd.admin.projects.seat.assign", $project->id) }}`, {
                        time_id: timeId,
                        user_id: userId,
                        seat_number: nextSeatNumber
                    })
                    .then(response => {
                        alert('จัดที่นั่งสำเร็จ!');
                        loadSeatData();
                    })
                    .catch(error => {
                        console.error('Error assigning seat:', error);
                        const message = error.response?.data?.error || 'เกิดข้อผิดพลาดในการจัดที่นั่ง';
                        alert(message);
                    });
            }
        }

        function removeSeat(timeId, userId) {
            if (confirm('คุณแน่ใจหรือไม่ที่จะลบที่นั่งของผู้ใช้นี้?')) {
                axios.delete(`{{ route("hrd.admin.projects.seat.remove", $project->id) }}`, {
                        data: {
                            time_id: timeId,
                            user_id: userId
                        }
                    })
                    .then(response => {
                        alert('ลบการจัดที่นั่งสำเร็จ!');
                        loadSeatData();
                    })
                    .catch(error => {
                        console.error('Error removing seat:', error);
                        const message = error.response?.data?.error || 'เกิดข้อผิดพลาดในการลบการจัดที่นั่ง';
                        alert(message);
                    });
            }
        }

        function clearSeats(timeId) {
            if (confirm('คุณแน่ใจหรือไม่ที่จะล้างที่นั่งทั้งหมดสำหรับช่วงเวลานี้?')) {
                axios.delete(`{{ route("hrd.admin.projects.seat.clear", $project->id) }}`, {
                        data: {
                            time_id: timeId
                        }
                    })
                    .then(response => {
                        alert('ล้างที่นั่งทั้งหมดสำเร็จ!');
                        loadSeatData();
                    })
                    .catch(error => {
                        console.error('Error clearing seats:', error);
                        const message = error.response?.data?.error || 'เกิดข้อผิดพลาดในการล้างที่นั่ง';
                        alert(message);
                    });
            }
        }
    </script>
@endsection
