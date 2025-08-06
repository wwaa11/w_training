<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    @yield("meta")
    <title inertia>PR9 Training</title>
    <link href="{{ url("images/Logo.ico") }}" rel="shortcut icon">
    <link rel="stylesheet" type="text/css" href="{{ asset("css/all.min.css") }}?v=1.0.2">
    <link rel="stylesheet" type="text/css" href="{{ asset("css/theme.css") }}?v=1.0.2">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite("resources/css/app.css")
    <style>
        @font-face {
            font-family: 'Prompt';
            src: url({{ asset("fonts/Prompt.ttf") }});
        }

        .prompt {
            font-family: "Prompt", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
    </style>
</head>

<body class="prompt relative bg-[#fff]">
    <div class="h-20"></div>
    <nav class="navbar">
        <div class="navbar-logo">
            <a href="{{ route("index") }}">
                <img class="my-auto aspect-auto max-h-16" src="{{ url("images/Side Logo.png") }}" alt="">
            </a>
            <span class="navbar-title hidden lg:block">Trainings </span>
        </div>
        <button class="mobile-menu-btn lg:hidden" type="button" onclick="toggleMobileMenu()">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="navbar-links hidden lg:flex">
            <a href="{{ route("index") }}">Approve Check-in</a>
        </div>
        <div class="navbar-user hidden lg:flex">
            <div class="navbar-user-info">
                <div class="userid">{{ session("name") }}</div>
                <div class="department">{{ session("department") }}</div>
            </div>
            <div class="navbar-user-actions">
                <button class="logout" onclick="confirmLogout()">Logout</button>
            </div>
        </div>
    </nav>
    <div class="mobile-menu fade-in" id="mobileMenu">
        <a href="{{ route("index") }}">Approve Check-in</a>
        <div class="user-block">
            {{ session("name") }}
            <div class="department">{{ session("department") }}</div>
            <div class="user-actions">
                <button class="logout" onclick="confirmLogout()">Logout</button>
            </div>
        </div>
    </div>
    <div class="mx-auto my-6 w-[80%] p-3 shadow">
        <form class="mb-6 flex flex-col items-center gap-4 sm:flex-row" method="GET" action="">
            <label class="font-medium text-gray-700" for="filterAdmin">Status:</label>
            <select class="rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" id="filterAdmin" name="admin">
                <option value="all" {{ $filterAdmin == "all" ? "selected" : "" }}>All</option>
                <option value="false" {{ $filterAdmin == "false" ? "selected" : "" }}>Not Approved</option>
                <option value="true" {{ $filterAdmin == "true" ? "selected" : "" }}>Approved</option>
            </select>
        </form>
        @if (!$attendances->isEmpty() && $attendances->where("admin", false)->count() > 0)
            <div class="mb-4 flex justify-end">
                <button class="flex items-center gap-2 rounded bg-green-600 px-5 py-2 text-white shadow transition hover:bg-green-700" id="approve-all-btn" type="button" onclick="approveusers()">
                    <span id="approve-all-text">Approve All records.</span>
                    <svg class="hidden h-5 w-5 animate-spin text-white" id="approve-all-spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                </button>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full rounded-lg border border-gray-200 bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach ($attendances as $attendance)
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $attendance->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $attendance->user->userid }}</div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $attendance->user->department }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $attendance->date }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $attendance->time }}</td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if ($attendance->admin)
                                    <span class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">Approved</span>
                                @else
                                    <span class="inline-flex rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800">Pending</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                @if (!$attendance->admin)
                                    <button class="rounded bg-blue-600 px-3 py-1 text-white transition hover:bg-blue-700" onclick="approveuser({{ $attendance->id }})">Approve</button>
                                @else
                                    <span class="text-gray-500">Already Approved</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Logout Modal -->
    <div id="logoutModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3); z-index:100; align-items:center; justify-content:center;">
        <div style="background:var(--background-primary); border-radius:var(--radius-lg); padding:var(--spacing-xl); min-width:300px; box-shadow:0 2px 16px var(--shadow-medium); text-align:center; border:1px solid var(--border-color);">
            <div style="font-size:1.2rem; font-weight:600; margin-bottom:var(--spacing-lg); color:var(--text-primary);">ยืนยันการออกจากระบบ?</div>
            <div style="display:flex; gap:var(--spacing-md); justify-content:center;">
                <button onclick="hideLogoutModal()" style="background:var(--secondary-color); color:var(--background-primary); border:none; border-radius:var(--radius-md); padding:var(--spacing-md) var(--spacing-lg); font-size:1rem; cursor:pointer; transition:all var(--transition-fast);">ยกเลิก</button>
                <button onclick="logout()" style="background:var(--danger-color); color:var(--background-primary); border:none; border-radius:var(--radius-md); padding:var(--spacing-md) var(--spacing-lg); font-size:1rem; font-weight:600; cursor:pointer; transition:all var(--transition-fast);">ออกจากระบบ</button>
            </div>
        </div>
    </div>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            if (menu.style.display === 'flex') {
                menu.style.display = 'none';
            } else {
                menu.style.display = 'flex';
            }
        }

        function hideLogoutModal() {
            document.getElementById('logoutModal').style.display = 'none';
        }

        function confirmLogout() {
            document.getElementById('logoutModal').style.display = 'flex';
        }

        function logout() {
            axios.post('{{ route("logout") }}').then((res) => {
                window.location.href = '{{ route("login") }}';
            });
        }

        function approveuser(id) {
            axios.post('{{ route("training.teacher.approve") }}', {
                id: id
            }).then((res) => {
                window.location.reload();
            });
        }

        function approveusers() {
            const btn = document.getElementById('approve-all-btn');
            const text = document.getElementById('approve-all-text');
            const spinner = document.getElementById('approve-all-spinner');

            btn.disabled = true;
            text.textContent = 'Approving...';
            spinner.classList.remove('hidden');

            axios.post('{{ route("training.teacher.approve-all") }}').then((res) => {
                window.location.reload();
            }).catch((error) => {
                btn.disabled = false;
                text.textContent = 'Approve All records.';
                spinner.classList.add('hidden');
            });
        }

        // Hide mobile menu on resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                document.getElementById('mobileMenu').style.display = 'none';
            }
        });
    </script>
    @yield("scripts")
</body>

</html>
