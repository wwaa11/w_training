@extends("layouts.training")
@section("content")
    <div class="container mx-auto max-w-6xl py-10">
        <a class="mb-3 inline-flex items-center text-gray-600 transition-colors hover:text-gray-800" href="{{ route("training.admin.index") }}">
            <i class="fa-solid fa-arrow-left mr-2"></i>Back to Management
        </a>
        <h1 class="mb-8 flex items-center gap-2 text-3xl font-bold text-blue-800">
            <i class="fa-solid fa-exchange-alt"></i> Move Training User
        </h1>

        <!-- User Search Section -->
        <div class="mb-8 rounded-xl bg-white p-6 shadow-lg">
            <div class="mb-4 flex items-center gap-2 border-b pb-2">
                <i class="fa-solid fa-search text-blue-600"></i>
                <span class="text-lg font-semibold text-blue-800">Search User to Move</span>
            </div>
            <form class="grid grid-cols-1 gap-4 md:grid-cols-3" id="moveUserForm">
                <div class="md:col-span-3">
                    <label class="mb-1 block text-sm font-semibold text-gray-700" for="user_id">User ID</label>
                    <div class="relative">
                        <input class="form-input w-full rounded-md border-gray-300 p-3 focus:border-blue-500 focus:ring-blue-500" id="user_id" type="text" name="user_id" placeholder="Enter User ID" required>
                        <button class="absolute right-2 top-1/2 -translate-y-1/2 transform rounded-md bg-blue-600 px-4 py-2 text-white transition-all duration-200 hover:bg-blue-700" id="searchUserBtn" type="button">
                            <i class="fa-solid fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- User Info Section -->
        <div class="mb-8 rounded-xl bg-white p-6 shadow-lg" id="userInfoSection" style="display: none;">
            <div class="mb-4 flex items-center gap-2 border-b pb-2">
                <i class="fa-solid fa-info-circle text-green-600"></i>
                <span class="text-lg font-semibold text-green-800">User Information</span>
            </div>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3">
                    <span class="text-sm font-medium text-gray-600">User ID:</span>
                    <span class="font-semibold text-gray-800" id="displayUserId"></span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3">
                    <span class="text-sm font-medium text-gray-600">Team:</span>
                    <span class="font-semibold text-gray-800" id="displayUserTeam"></span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3">
                    <span class="text-sm font-medium text-gray-600">Current Time:</span>
                    <span class="font-semibold text-gray-800" id="displayCurrentTime"></span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3">
                    <span class="text-sm font-medium text-gray-600">Teacher:</span>
                    <span class="font-semibold text-gray-800" id="displayCurrentTeacher"></span>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="mb-8 rounded-xl bg-white p-6 shadow-lg">
            <div class="mb-4 flex items-center gap-2 border-b pb-2">
                <i class="fa-solid fa-filter text-blue-600"></i>
                <span class="text-lg font-semibold text-blue-800">Filter Available Times</span>
            </div>
            <form class="grid grid-cols-1 gap-4 md:grid-cols-5" method="GET">
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-semibold text-gray-700" for="filter_team">Group</label>
                    <select class="form-select w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" id="filter_team" name="filter_team" title="Filter by group">
                        <option value="">All Groups</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-semibold text-gray-700" for="filter_teacher">Teacher</label>
                    <select class="form-select w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" id="filter_teacher" name="filter_teacher" title="Filter by teacher">
                        <option value="">All Teachers</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}">Group : {{ $teacher->team->name }} {{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-1">
                    <label class="mb-1 block text-sm font-semibold text-gray-700" for="filter_session">Session</label>
                    <select class="form-select w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" id="filter_session" name="filter_session" title="Filter by session">
                        <option value="">All Sessions</option>
                        @foreach ($sessions as $session)
                            <option value="{{ $session->id }}">Group : {{ $session->teacher->team->name }} Teacher : {{ $session->teacher->name }} {{ $session->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-1 mt-2 flex flex-wrap justify-end gap-2 md:col-span-5">
                    <button class="btn btn-primary flex items-center gap-2 rounded-md px-6 py-2 shadow transition hover:bg-blue-700" id="filterBtn" type="button">
                        <i class="fa-solid fa-filter"></i> <span>Filter</span>
                    </button>
                    <button class="btn btn-secondary flex items-center gap-2 rounded-md px-6 py-2 shadow transition hover:bg-gray-300" id="clearFilterBtn" type="button">
                        <i class="fa-solid fa-rotate-left"></i> <span>Clear</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Available Times Section -->
        <div class="mb-6 flex items-center justify-between">
            <div class="mb-4 flex items-center justify-between">
                <span class="text-lg font-semibold text-gray-700">Available Times: <span class="text-blue-700" id="timesCount">0</span></span>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl bg-white shadow-lg">
            <div class="p-6">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3" id="availableTimesSection">
                    <!-- Times will be loaded here -->
                    <div class="col-span-full py-12 text-center">
                        <div class="text-gray-400">
                            <i class="fa-solid fa-clock mb-4 text-6xl"></i>
                            <p class="text-lg">Please search for a user to see available times</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Move Confirmation Modal -->
    <div class="fixed inset-0 z-50 hidden items-center justify-center" id="moveConfirmation">
        <div class="w-full max-w-md rounded-lg bg-gray-100 p-6 shadow-xl">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Confirm Move</h3>
                <button class="text-gray-400 hover:text-gray-600" onclick="closeMoveModal()">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>
            <div class="mb-6 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-orange-100">
                    <i class="fa-solid fa-exchange-alt text-2xl text-orange-600"></i>
                </div>
                <p class="mb-2 text-gray-700">Do you want to move user</p>
                <p class="mb-2 text-lg font-semibold text-gray-800">
                    <span class="text-indigo-600" id="confirmUserId"></span>
                </p>
                <p class="mb-2 text-gray-700">to time slot</p>
                <p class="text-lg font-semibold text-gray-800">
                    <span class="text-purple-600" id="confirmTimeName"></span>
                </p>
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button class="btn btn-secondary rounded-md px-4 py-2 text-gray-700 hover:bg-gray-200" id="cancelMoveBtn" type="button">
                    Cancel
                </button>
                <button class="btn btn-primary rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700" id="confirmMoveBtn" type="button">
                    Confirm Move
                </button>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" id="loadingOverlay" style="display: none;">
        <div class="mx-4 w-full max-w-sm rounded-xl bg-white p-8 text-center shadow-2xl">
            <div class="mx-auto mb-4 h-16 w-16 animate-spin rounded-full border-b-2 border-blue-600"></div>
            <p class="font-medium text-gray-700">Processing...</p>
            <p class="mt-2 text-sm text-gray-500">Please wait</p>
        </div>
    </div>
@endsection

@section("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let selectedTimeId = null;
            let selectedUserId = null;

            // Search user buttons
            document.getElementById('searchUserBtn').addEventListener('click', searchUser);

            // Enter key on user ID input
            document.getElementById('user_id').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    searchUser();
                }
            });

            // Clear filter button
            document.getElementById('clearFilterBtn').addEventListener('click', function() {
                document.getElementById('filter_team').value = '';
                document.getElementById('filter_teacher').value = '';
                document.getElementById('filter_session').value = '';
                loadAvailableTimes();
            });

            async function searchUser() {
                const userId = document.getElementById('user_id').value.trim();
                if (!userId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Please enter data',
                        text: 'Please enter User ID',
                        confirmButtonColor: '#3B82F6'
                    });
                    return;
                }

                showLoading();

                try {
                    const response = await axios.post('{{ route("training.admin.move.get-user-info") }}', {
                        user_id: userId
                    });

                    hideLoading();

                    if (response.data.status === 'success') {
                        displayUserInfo(response.data.user);
                        selectedUserId = userId;
                        loadAvailableTimes();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'User not found',
                            text: response.data.message,
                            confirmButtonColor: '#EF4444'
                        });
                    }
                } catch (error) {
                    hideLoading();
                    console.error('Search user error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error occurred',
                        text: 'Cannot search for user data',
                        confirmButtonColor: '#EF4444'
                    });
                }
            }

            // Filter times
            document.getElementById('filterBtn').addEventListener('click', loadAvailableTimes);

            // Load available times
            async function loadAvailableTimes() {
                const filters = {
                    team_id: document.getElementById('filter_team').value,
                    teacher_id: document.getElementById('filter_teacher').value,
                    session_id: document.getElementById('filter_session').value
                };

                showLoading();

                try {
                    const response = await axios.post('{{ route("training.admin.move.get-available-times") }}', filters);

                    hideLoading();

                    if (response.data.status === 'success') {
                        displayAvailableTimes(response.data.times);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error occurred',
                            text: 'Cannot load time slot data',
                            confirmButtonColor: '#EF4444'
                        });
                    }
                } catch (error) {
                    hideLoading();
                    console.error('Load times error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error occurred',
                        text: 'Cannot load time slot data',
                        confirmButtonColor: '#EF4444'
                    });
                }
            }

            // Display user info
            function displayUserInfo(user) {
                document.getElementById('displayUserId').textContent = user.user_id;
                document.getElementById('displayUserTeam').textContent = user.team;
                document.getElementById('displayCurrentTime').textContent = user.current_time || 'Not registered';
                document.getElementById('displayCurrentTeacher').textContent = user.current_teacher || '-';

                const userInfoSection = document.getElementById('userInfoSection');
                userInfoSection.style.display = 'block';
                userInfoSection.style.opacity = '0';
                userInfoSection.style.transition = 'opacity 0.3s ease';
                setTimeout(() => {
                    userInfoSection.style.opacity = '1';
                }, 10);
            }

            // Display available times
            function displayAvailableTimes(times) {
                const container = document.getElementById('availableTimesSection');
                container.innerHTML = '';
                document.getElementById('timesCount').textContent = times.length;

                if (times.length === 0) {
                    container.innerHTML = `
                        <div class="col-span-full text-center py-12">
                            <div class="text-gray-400">
                                <i class="fa-solid fa-exclamation-triangle text-6xl mb-4"></i>
                                <p class="text-lg">No time slots found matching the selected criteria</p>
                                <p class="text-sm mt-2">Try changing filters or check data</p>
                            </div>
                        </div>
                    `;
                    return;
                }

                times.forEach(function(time) {
                    const seatStatus = time.available_seat > 0 ?
                        `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fa-solid fa-check-circle mr-1"></i>
                            Available seats: ${time.available_seat}
                        </span>` :
                        `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                            <i class="fa-solid fa-exclamation-circle mr-1"></i>
                            Will add seat automatically
                        </span>`;

                    const timeCard = `
                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-4">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-white font-semibold flex items-center">
                                        <i class="fa-solid fa-clock mr-2"></i>
                                        ${time.name}
                                    </h4>
                                    <div class="text-white text-sm">
                                        <i class="fa-solid fa-users mr-1"></i>
                                        ${time.max_seat} / ${time.available_seat}
                                    </div>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="space-y-3">
                                    <div class="flex items-center text-sm">
                                        <i class="fa-solid fa-layer-group text-blue-500 mr-2 w-4"></i>
                                        <span class="text-gray-600">Group:</span>
                                        <span class="font-medium text-gray-800 ml-1">${time.team_name}</span>
                                    </div>
                                    <div class="flex items-center text-sm">
                                        <i class="fa-solid fa-chalkboard-teacher text-purple-500 mr-2 w-4"></i>
                                        <span class="text-gray-600">Teacher:</span>
                                        <span class="font-medium text-gray-800 ml-1">${time.teacher_name}</span>
                                    </div>
                                    <div class="flex items-center text-sm">
                                        <i class="fa-solid fa-calendar-alt text-green-500 mr-2 w-4"></i>
                                        <span class="text-gray-600">Session:</span>
                                        <span class="font-medium text-gray-800 ml-1">${time.session_name}</span>
                                    </div>
                                    <div class="pt-2">
                                        ${seatStatus}
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 pb-4">
                                <button type="button" 
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 flex items-center justify-center select-time-btn"
                                        data-time-id="${time.id}"
                                        data-time-name="${time.name}">
                                    <i class="fa-solid fa-arrow-right mr-2"></i>
                                    Select this time
                                </button>
                            </div>
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', timeCard);
                });
            }

            // Select time
            document.addEventListener('click', function(e) {
                if (e.target.closest('.select-time-btn')) {
                    const button = e.target.closest('.select-time-btn');

                    if (!selectedUserId) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Please select user',
                            text: 'Please search and select a user first',
                            confirmButtonColor: '#F59E0B'
                        });
                        return;
                    }

                    selectedTimeId = button.dataset.timeId;
                    const timeName = button.dataset.timeName;

                    document.getElementById('confirmUserId').textContent = selectedUserId;
                    document.getElementById('confirmTimeName').textContent = timeName;

                    openMoveModal();
                }
            });

            // Confirm move
            document.getElementById('confirmMoveBtn').addEventListener('click', async function() {
                if (!selectedUserId || !selectedTimeId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Incomplete data',
                        text: 'Please select user and time slot',
                        confirmButtonColor: '#EF4444'
                    });
                    return;
                }

                showLoading();

                try {
                    const response = await axios.post('{{ route("training.admin.move.user") }}', {
                        user_id: selectedUserId,
                        time_id: selectedTimeId
                    });

                    hideLoading();

                    if (response.data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Move successful!',
                            text: response.data.message,
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            background: '#F0FDF4',
                            color: '#166534'
                        }).then(function() {
                            // Reset form
                            document.getElementById('user_id').value = '';

                            const userInfoSection = document.getElementById('userInfoSection');
                            userInfoSection.style.opacity = '0';
                            setTimeout(() => {
                                userInfoSection.style.display = 'none';
                            }, 300);

                            closeMoveModal();

                            selectedUserId = null;
                            selectedTimeId = null;

                            document.getElementById('availableTimesSection').innerHTML = `
                                <div class="col-span-full text-center py-12">
                                    <div class="text-gray-400">
                                        <i class="fa-solid fa-clock text-6xl mb-4"></i>
                                        <p class="text-lg">Please search for a user to see available times</p>
                                    </div>
                                </div>
                            `;
                            document.getElementById('timesCount').textContent = '0';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error occurred',
                            text: response.data.message,
                            confirmButtonColor: '#EF4444'
                        });
                    }
                } catch (error) {
                    hideLoading();
                    console.error('Move user error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error occurred',
                        text: 'Cannot move user',
                        confirmButtonColor: '#EF4444'
                    });
                }
            });

            // Modal functions
            function openMoveModal() {
                const modal = document.getElementById('moveConfirmation');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeMoveModal() {
                const modal = document.getElementById('moveConfirmation');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                selectedTimeId = null;
            }

            // Cancel move
            document.getElementById('cancelMoveBtn').addEventListener('click', closeMoveModal);

            // Close modal when clicking outside
            document.getElementById('moveConfirmation').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeMoveModal();
                }
            });

            // Loading functions
            function showLoading() {
                const overlay = document.getElementById('loadingOverlay');
                overlay.style.display = 'flex';
                overlay.style.opacity = '0';
                overlay.style.transition = 'opacity 0.3s ease';
                setTimeout(() => {
                    overlay.style.opacity = '1';
                }, 10);
            }

            function hideLoading() {
                const overlay = document.getElementById('loadingOverlay');
                overlay.style.opacity = '0';
                setTimeout(() => {
                    overlay.style.display = 'none';
                }, 300);
            }

            // Add smooth animations for time cards
            document.addEventListener('mouseover', function(e) {
                if (e.target.closest('.select-time-btn')) {
                    const card = e.target.closest('.bg-white');
                    card.style.transform = 'scale(1.02)';
                }
            });

            document.addEventListener('mouseout', function(e) {
                if (e.target.closest('.select-time-btn')) {
                    const card = e.target.closest('.bg-white');
                    card.style.transform = 'scale(1)';
                }
            });
        });
    </script>
@endsection
