@extends("layouts.hrd")

@section("content")
    <div class="container mx-auto px-4 pb-20">
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
                    <button class="group relative inline-flex items-center overflow-hidden rounded-lg bg-gradient-to-r from-green-500 to-green-600 px-4 py-2 text-white shadow-lg transition-all duration-300 hover:from-green-600 hover:to-green-700 hover:shadow-xl" onclick="refreshSeatData()">
                        <i class="fas fa-sync-alt mr-2"></i> รีเฟรชข้อมูล
                    </button>
                    <button class="group relative inline-flex items-center overflow-hidden rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-2 text-white shadow-lg transition-all duration-300 hover:from-blue-600 hover:to-blue-700 hover:shadow-xl" onclick="triggerSeatAssignment()">
                        <i class="fas fa-cogs mr-2"></i> จัดที่นั่งอัตโนมัติ
                    </button>
                    <button class="group relative inline-flex items-center overflow-hidden rounded-lg bg-gradient-to-r from-purple-500 to-purple-600 px-4 py-2 text-white shadow-lg transition-all duration-300 hover:from-purple-600 hover:to-purple-700 hover:shadow-xl" onclick="exportSeatData()">
                        <i class="fas fa-download mr-2"></i> ส่งออกข้อมูล
                    </button>
                </div>
            </div>

            <!-- Project Info -->
            <div class="mb-6 rounded-lg bg-gradient-to-r from-blue-50 to-blue-100 p-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle mr-3 text-2xl text-blue-600"></i>
                        <div>
                            <p class="font-semibold text-blue-900">สถานะการจัดที่นั่ง</p>
                            <p class="text-sm text-blue-700">
                                @if ($project->project_seat_assign)
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> เปิดใช้งาน
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i> ปิดใช้งาน
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-calendar mr-3 text-2xl text-blue-600"></i>
                        <div>
                            <p class="font-semibold text-blue-900">วันที่โปรเจกต์</p>
                            <p class="text-sm text-blue-700">{{ $project->dates->count() }} วันที่</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-clock mr-3 text-2xl text-blue-600"></i>
                        <div>
                            <p class="font-semibold text-blue-900">ช่วงเวลา</p>
                            <p class="text-sm text-blue-700">{{ $project->dates->sum(function ($date) {return $date->times->count();}) }} ช่วงเวลา</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-4">
                <div class="group relative overflow-hidden rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 p-4 text-white shadow-lg transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center">
                        <i class="fas fa-chair mr-3 text-2xl"></i>
                        <div>
                            <p class="text-2xl font-bold" id="totalSeats">0</p>
                            <p class="text-sm opacity-90">ที่นั่งที่จัดแล้ว</p>
                        </div>
                    </div>
                </div>
                <div class="group relative overflow-hidden rounded-lg bg-gradient-to-r from-green-500 to-green-600 p-4 text-white shadow-lg transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center">
                        <i class="fas fa-users mr-3 text-2xl"></i>
                        <div>
                            <p class="text-2xl font-bold" id="totalRegistrations">0</p>
                            <p class="text-sm opacity-90">การลงทะเบียนทั้งหมด</p>
                        </div>
                    </div>
                </div>
                <div class="group relative overflow-hidden rounded-lg bg-gradient-to-r from-yellow-500 to-yellow-600 p-4 text-white shadow-lg transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center">
                        <i class="fas fa-user-clock mr-3 text-2xl"></i>
                        <div>
                            <p class="text-2xl font-bold" id="unassignedSeats">0</p>
                            <p class="text-sm opacity-90">รอจัดที่นั่ง</p>
                        </div>
                    </div>
                </div>
                <div class="group relative overflow-hidden rounded-lg bg-gradient-to-r from-purple-500 to-purple-600 p-4 text-white shadow-lg transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center">
                        <i class="fas fa-building mr-3 text-2xl"></i>
                        <div>
                            <p class="text-2xl font-bold" id="totalDepartments">0</p>
                            <p class="text-sm opacity-90">แผนกที่เข้าร่วม</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading Spinner -->
            <div class="mb-6 hidden text-center" id="loadingSpinner">
                <div class="inline-flex items-center rounded-lg bg-blue-50 px-6 py-3">
                    <i class="fas fa-spinner fa-spin mr-3 text-xl text-blue-600"></i>
                    <span class="font-medium text-blue-700">กำลังโหลดข้อมูล...</span>
                </div>
            </div>

            <!-- Error Message -->
            <div class="mb-6 hidden rounded-lg bg-red-100 p-4 text-red-700" id="errorMessage">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span id="errorText"></span>
                </div>
            </div>

            <!-- Success Message -->
            <div class="mb-6 hidden rounded-lg bg-green-100 p-4 text-green-700" id="successMessage">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span id="successText"></span>
                </div>
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
                        <select class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" id="userSelect">
                            <option value="">เลือกผู้ใช้...</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">หมายเลขที่นั่ง:</label>
                        <input class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" id="seatNumberInput" type="number" min="1" placeholder="ระบุหมายเลขที่นั่ง">
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button class="rounded-lg bg-gray-500 px-4 py-2 text-white hover:bg-gray-600" onclick="closeManualSeatModal()">
                            ยกเลิก
                        </button>
                        <button class="group relative inline-flex items-center overflow-hidden rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-2 text-white shadow-lg transition-all duration-300 hover:from-blue-600 hover:to-blue-700" onclick="assignManualSeat()">
                            <i class="fas fa-chair mr-2"></i> จัดที่นั่ง
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
            document.getElementById('successMessage').classList.add('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingSpinner').classList.add('hidden');
            document.getElementById('seatContent').classList.remove('hidden');
        }

        function showError(message) {
            document.getElementById('errorText').textContent = message;
            document.getElementById('errorMessage').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('errorMessage').classList.add('hidden');
            }, 5000);
        }

        function showSuccess(message) {
            document.getElementById('successText').textContent = message;
            document.getElementById('successMessage').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('successMessage').classList.add('hidden');
            }, 5000);
        }

        function loadSeatData() {
            showLoading();

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

            if (!data.seat_data || data.seat_data.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-12">
                        <i class="fas fa-chair text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">ไม่มีข้อมูลการจัดที่นั่ง</h3>
                        <p class="text-gray-500">ยังไม่มีช่วงเวลาหรือการลงทะเบียนสำหรับโปรเจกต์นี้</p>
                    </div>
                `;
                return;
            }

            data.seat_data.forEach(session => {
                const seats = Array.isArray(session.seats) ? session.seats : [];
                const registrations = Array.isArray(session.registrations) ? session.registrations : [];
                const unassignedCount = registrations.filter(r => !r.seat_number).length;

                const sessionDiv = document.createElement('div');
                sessionDiv.className = 'rounded-lg border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow duration-200';

                sessionDiv.innerHTML = `
                    <div class="mb-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                <i class="fas fa-calendar text-blue-600 mr-2"></i>
                                ${session.date} - ${session.time}
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">
                                <i class="fas fa-clock mr-1"></i>
                                ${session.time_start} - ${session.time_end}
                            </p>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="openManualSeatModal(${session.time_id})" class="group relative inline-flex items-center overflow-hidden rounded-lg bg-gradient-to-r from-green-500 to-green-600 px-3 py-2 text-white shadow-lg transition-all duration-300 hover:from-green-600 hover:to-green-700">
                                <i class="fas fa-plus mr-1"></i>จัดที่นั่งด้วยตนเอง
                            </button>
                            <button onclick="clearSeats(${session.time_id})" class="group relative inline-flex items-center overflow-hidden rounded-lg bg-gradient-to-r from-red-500 to-red-600 px-3 py-2 text-white shadow-lg transition-all duration-300 hover:from-red-600 hover:to-red-700">
                                <i class="fas fa-trash mr-1"></i>ล้างที่นั่ง
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <!-- Seat Assignments -->
                        <div>
                            <h4 class="mb-3 font-medium text-gray-700 flex items-center">
                                <i class="fas fa-chair mr-2 text-green-600"></i>
                                ที่นั่งที่จัดแล้ว (${seats.length})
                            </h4>
                            <div class="rounded-lg bg-gray-50 p-4 max-h-64 overflow-y-auto">
                                ${seats.length > 0 ? 
                                    seats.map(seat => `
                                                <div class="mb-2 flex items-center justify-between rounded-lg bg-white p-3 shadow-sm border-l-4 border-green-500">
                                                    <div class="flex items-center">
                                                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                                            <span class="text-sm font-bold text-green-600">${seat.seat_number}</span>
                                                        </div>
                                                        <div>
                                                            <span class="font-medium text-gray-900">${seat.user_name}</span>
                                                            <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">${seat.department}</span>
                                                        </div>
                                                    </div>
                                                    <button onclick="removeSeat(${session.time_id}, ${seat.user_id})" class="text-red-600 hover:text-red-800 p-1 rounded hover:bg-red-50">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            `).join('') : 
                                    '<div class="text-center py-8 text-gray-500"><i class="fas fa-chair text-3xl mb-2"></i><p>ยังไม่มีที่นั่งที่จัด</p></div>'
                                }
                            </div>
                        </div>
                        
                        <!-- Unassigned Registrations -->
                        <div>
                            <h4 class="mb-3 font-medium text-gray-700 flex items-center">
                                <i class="fas fa-users mr-2 text-yellow-600"></i>
                                รอจัดที่นั่ง (${unassignedCount})
                            </h4>
                            <div class="rounded-lg bg-gray-50 p-4 max-h-64 overflow-y-auto">
                                ${unassignedCount > 0 ? 
                                    registrations.filter(r => !r.seat_number).map(reg => `
                                                <div class="mb-2 flex items-center justify-between rounded-lg bg-white p-3 shadow-sm border-l-4 border-yellow-500">
                                                    <div class="flex items-center">
                                                        <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center mr-3">
                                                            <i class="fas fa-user text-yellow-600 text-sm"></i>
                                                        </div>
                                                        <div>
                                                            <span class="font-medium text-gray-900">${reg.user_name}</span>
                                                            <span class="ml-2 text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">${reg.department}</span>
                                                        </div>
                                                    </div>
                                                    <button onclick="assignSeat(${session.time_id}, ${reg.user_id})" class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            `).join('') : 
                                    '<div class="text-center py-8 text-gray-500"><i class="fas fa-check-circle text-3xl mb-2"></i><p>ไม่มีผู้ใช้ที่รอจัดที่นั่ง</p></div>'
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
                const seats = Array.isArray(session.seats) ? session.seats : [];
                const registrations = Array.isArray(session.registrations) ? session.registrations : [];

                totalSeats += seats.length;
                totalRegistrations += registrations.length;
                unassignedSeats += registrations.filter(r => !r.seat_number).length;

                seats.forEach(seat => departments.add(seat.department));
                registrations.forEach(reg => departments.add(reg.department));
            });

            document.getElementById('totalSeats').textContent = totalSeats;
            document.getElementById('totalRegistrations').textContent = totalRegistrations;
            document.getElementById('unassignedSeats').textContent = unassignedSeats;
            document.getElementById('totalDepartments').textContent = departments.size;
        }

        function refreshSeatData() {
            loadSeatData();
            showSuccess('ข้อมูลได้รับการอัปเดตแล้ว');
        }

        function triggerSeatAssignment() {
            if (confirm('คุณแน่ใจหรือไม่ที่จะเริ่มการจัดที่นั่งอัตโนมัติสำหรับโปรเจกต์นี้?')) {
                showLoading();

                axios.post('{{ route("hrd.admin.seats.trigger_assignment") }}')
                    .then(response => {
                        showSuccess('เริ่มการจัดที่นั่งอัตโนมัติสำเร็จ!');
                        setTimeout(() => {
                            loadSeatData();
                        }, 2000);
                    })
                    .catch(error => {
                        console.error('Error triggering seat assignment:', error);
                        showError('เกิดข้อผิดพลาดในการเริ่มการจัดที่นั่ง กรุณาลองใหม่อีกครั้ง');
                    })
                    .finally(() => {
                        hideLoading();
                    });
            }
        }

        function exportSeatData() {
            if (!currentSeatData) {
                showError('ไม่มีข้อมูลที่จะส่งออก');
                return;
            }

            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "วันที่,เวลา,หมายเลขที่นั่ง,ชื่อผู้ใช้,แผนก,รหัสพนักงาน\n";

            currentSeatData.seat_data.forEach(session => {
                session.seats.forEach(seat => {
                    csvContent += `${session.date},${session.time},${seat.seat_number},"${seat.user_name}","${seat.department}","${seat.user_id}"\n`;
                });
            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `seat_assignments_${currentSeatData.project}_${new Date().toISOString().split('T')[0]}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            showSuccess('ส่งออกข้อมูลสำเร็จ');
        }

        function openManualSeatModal(timeId) {
            currentTimeId = timeId;
            const session = currentSeatData.seat_data.find(s => s.time_id === timeId);

            if (!session) return;

            const registrations = Array.isArray(session.registrations) ? session.registrations : [];
            const userSelect = document.getElementById('userSelect');
            userSelect.innerHTML = '<option value="">เลือกผู้ใช้...</option>';

            registrations.filter(r => !r.seat_number).forEach(reg => {
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
                showError('กรุณาเลือกผู้ใช้และหมายเลขที่นั่ง');
                return;
            }

            axios.post(`{{ route("hrd.admin.projects.seat.assign", $project->id) }}`, {
                    time_id: currentTimeId,
                    user_id: userId,
                    seat_number: seatNumber
                })
                .then(response => {
                    showSuccess('จัดที่นั่งสำเร็จ!');
                    closeManualSeatModal();
                    loadSeatData();
                })
                .catch(error => {
                    console.error('Error assigning seat:', error);
                    const message = error.response?.data?.error || 'เกิดข้อผิดพลาดในการจัดที่นั่ง';
                    showError(message);
                });
        }

        function assignSeat(timeId, userId) {
            if (confirm('คุณแน่ใจหรือไม่ที่จะจัดที่นั่งให้ผู้ใช้นี้?')) {
                const session = currentSeatData.seat_data.find(s => s.time_id === timeId);
                if (!session) return;

                const seats = Array.isArray(session.seats) ? session.seats : [];
                let nextSeatNumber = 1;
                const usedSeats = seats.map(s => s.seat_number).sort((a, b) => a - b);

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
                        showSuccess('จัดที่นั่งสำเร็จ!');
                        loadSeatData();
                    })
                    .catch(error => {
                        console.error('Error assigning seat:', error);
                        const message = error.response?.data?.error || 'เกิดข้อผิดพลาดในการจัดที่นั่ง';
                        showError(message);
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
                        showSuccess('ลบการจัดที่นั่งสำเร็จ!');
                        loadSeatData();
                    })
                    .catch(error => {
                        console.error('Error removing seat:', error);
                        const message = error.response?.data?.error || 'เกิดข้อผิดพลาดในการลบการจัดที่นั่ง';
                        showError(message);
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
                        showSuccess('ล้างที่นั่งทั้งหมดสำเร็จ!');
                        loadSeatData();
                    })
                    .catch(error => {
                        console.error('Error clearing seats:', error);
                        const message = error.response?.data?.error || 'เกิดข้อผิดพลาดในการล้างที่นั่ง';
                        showError(message);
                    });
            }
        }
    </script>
@endsection
