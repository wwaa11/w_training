<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    @yield("meta")
    <title inertia>PR9 HRD</title>
    <link href="{{ url("images/Logo.ico") }}" rel="shortcut icon">
    <link rel="stylesheet" type="text/css" href="{{ url("css/all.min.css") }}">
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

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #c1dccd;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            padding: 0.5rem 2rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 20;
            min-height: 4.5rem;
            transition: background 0.2s;
        }

        .navbar-logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .navbar-logo img {
            max-height: 2.5rem;
        }

        .navbar-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2563eb;
            letter-spacing: 1px;
        }

        .navbar-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .navbar-links a {
            color: #143429;
            font-size: 1rem;
            font-weight: 500;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: background 0.2s, color 0.2s;
        }

        .navbar-links a:hover,
        .navbar-links a.active {
            background: #2563eb;
            color: #fff;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            padding: 0.5rem 1rem;
            min-width: 180px;
        }

        .navbar-user-info {
            flex: 1;
            text-align: left;
        }

        .navbar-user-info .userid {
            font-weight: 600;
            color: #2563eb;
        }

        .navbar-user-info .department {
            font-size: 0.9rem;
            color: #64748b;
        }

        .navbar-user-actions {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .navbar-user-actions a,
        .navbar-user-actions button {
            background: none;
            border: none;
            color: #2563eb;
            font-size: 0.95rem;
            cursor: pointer;
            padding: 0.25rem 0;
            text-align: left;
            transition: color 0.2s;
        }

        .navbar-user-actions button.logout {
            color: #dc2626;
            font-weight: 600;
        }

        .navbar-user-actions button.logout:hover {
            text-decoration: underline;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 2rem;
            color: #1a3f34;
            cursor: pointer;
        }

        @media (max-width: 1024px) {

            .navbar-links,
            .navbar-user {
                display: none;
            }

            .mobile-menu-btn {
                display: block;
            }
        }

        .mobile-menu {
            display: none;
            position: fixed;
            top: 4.5rem;
            left: 0;
            right: 0;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            z-index: 30;
            padding: 1.5rem 1rem 1rem 1rem;
            flex-direction: column;
            gap: 1rem;
        }

        .mobile-menu a {
            color: #143429;
            font-size: 1.1rem;
            font-weight: 500;
            text-decoration: none;
            padding: 0.75rem 0.5rem;
            border-radius: 0.375rem;
            transition: background 0.2s, color 0.2s;
        }

        .mobile-menu a:hover,
        .mobile-menu a.active {
            background: #2563eb;
            color: #fff;
        }

        .mobile-menu .user-block {
            margin-top: 1rem;
            padding: 1rem;
            background: #f1f5f9;
            border-radius: 0.5rem;
            color: #2563eb;
            font-weight: 600;
        }

        .mobile-menu .department {
            color: #64748b;
            font-size: 0.95rem;
        }

        .mobile-menu .user-actions {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .mobile-menu .user-actions a,
        .mobile-menu .user-actions button {
            background: none;
            border: none;
            color: #2563eb;
            font-size: 1rem;
            cursor: pointer;
            padding: 0.25rem 0;
        }

        .mobile-menu .user-actions button.logout {
            color: #dc2626;
            font-weight: 600;
        }

        .fade-in {
            animation: fadeIn 0.2s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
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
                <thead class="bg-blue-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-700">UserID</th>
                        <th class="px-4 py-2 text-left text-gray-700">DATE</th>
                        <th class="px-4 py-2 text-left text-gray-700">Check-in Time</th>
                        <th class="px-4 py-2 text-left text-gray-700">Group</th>
                        <th class="px-4 py-2 text-left text-gray-700">Teacher</th>
                        <th class="px-4 py-2 text-left text-gray-700">Time</th>
                        <th class="px-4 py-2 text-center text-gray-700">User Status</th>
                        <th class="px-4 py-2 text-center text-gray-700">Admin Status</th>
                        <th class="px-4 py-2 text-center text-gray-700">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $attend_array = [];
                    @endphp
                    @if ($attendances->isEmpty())
                        <tr class="py-8 text-center text-gray-500">
                            <td colspan="9">Not found check-in record.</td>
                        </tr>
                    @else
                        @foreach ($attendances as $attend)
                            @if (isset($attend->date) && $attend->date->time->session->teacher->name === auth()->user()->name)
                                @php
                                    $attend_array[] = $attend->id;
                                @endphp
                                <tr class="border-t border-gray-100 hover:bg-blue-50" id="row-{{ $attend->id }}">
                                    <td class="px-4 py-2">{{ $attend->user_id }}</td>
                                    <td class="px-4 py-2">{{ $attend->date_name }}</td>
                                    <td class="px-4 py-2">{{ date("H:i", strtotime($attend->user_date)) }}</td>
                                    <td class="px-4 py-2">
                                        {{ $attend->date && $attend->date->time && $attend->date->time->session && $attend->date->time->session->teacher && $attend->date->time->session->teacher->team ? $attend->date->time->session->teacher->team->name : "-" }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ $attend->date && $attend->date->time && $attend->date->time->session && $attend->date->time->session->teacher ? $attend->date->time->session->teacher->name : "-" }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ $attend->date && $attend->date->time ? $attend->date->time->name : "-" }}
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        @if ($attend->user)
                                            <span class="m-auto flex h-6 w-6 items-center justify-center rounded-full bg-green-100 text-green-600">✔</span>
                                        @else
                                            <span class="m-auto flex h-6 w-6 items-center justify-center rounded-full bg-red-100 text-red-600">✗</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-center" id="admin-status-{{ $attend->id }}">
                                        @if ($attend->admin)
                                            <span class="m-auto flex h-6 w-6 items-center justify-center rounded-full bg-green-100 text-green-600">✔</span>
                                        @else
                                            <span class="m-auto flex h-6 w-6 items-center justify-center rounded-full bg-red-100 text-red-600">✗</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-center" id="action-{{ $attend->id }}">
                                        @if (!$attend->admin)
                                            <button class="approve-btn rounded bg-green-500 px-3 py-1 text-white transition hover:bg-green-600" data-id="{{ $attend->id }}" onclick="approveuser('{{ $attend->id }}')" type="button">อนุมัติ</button>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <!-- Logout Modal -->
    <div id="logoutModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3); z-index:100; align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:1rem; padding:2rem; min-width:300px; box-shadow:0 2px 16px rgba(0,0,0,0.15); text-align:center;">
            <div style="font-size:1.2rem; font-weight:600; margin-bottom:1rem;">ยืนยันการออกจากระบบ?</div>
            <div style="display:flex; gap:1rem; justify-content:center;">
                <button onclick="hideLogoutModal()" style="background:#64748b; color:#fff; border:none; border-radius:0.5rem; padding:0.5rem 1.5rem; font-size:1rem; cursor:pointer;">ยกเลิก</button>
                <button onclick="logout()" style="background:#dc2626; color:#fff; border:none; border-radius:0.5rem; padding:0.5rem 1.5rem; font-size:1rem; font-weight:600; cursor:pointer;">ออกจากระบบ</button>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('filterAdmin').addEventListener('change', function() {
            this.form.submit();
        });

        function approveuser(id) {
            const button = document.querySelector('button[data-id="' + id + '"]');
            if (button) {
                button.disabled = true;
                button.innerHTML = '<svg class="animate-spin h-5 w-5 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>';
            }
            axios.post('{{ route("training.admin.approve.user") }}', {
                'id': id,
            }).then((res) => {
                if (res.data.status === 'success') {
                    document.getElementById('admin-status-' + id).innerHTML = '<span class="flex h-6 w-6 items-center justify-center rounded-full bg-green-100 text-green-600">✔</span>';
                    document.getElementById('action-' + id).innerHTML = '<span class="text-gray-400">-</span>';
                } else {
                    if (button) {
                        button.disabled = false;
                        button.innerHTML = 'Approve';
                    }
                    alert('Error: ' + (res.data.message || 'could not approve selected user.'));
                }
            }).catch(() => {
                if (button) {
                    button.disabled = false;
                    button.innerHTML = 'Approve';
                }
                alert('Error occurred while connecting.');
            });
        }

        function approveusers() {
            Swal.fire({
                title: 'Confirm Approval',
                text: 'Do you want to approve all items?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve all!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const btn = document.getElementById('approve-all-btn');
                    const text = document.getElementById('approve-all-text');
                    const spinner = document.getElementById('approve-all-spinner');
                    btn.disabled = true;
                    text.classList.add('hidden');
                    spinner.classList.remove('hidden');
                    axios.post('{{ route("training.admin.approve.teacher") }}', {
                        ids: '{{ json_encode($attend_array) }}'
                    }).then((res) => {
                        if (res.data.status === 'success') {
                            window.location.reload();
                        } else {
                            btn.disabled = false;
                            text.classList.remove('hidden');
                            spinner.classList.add('hidden');
                            alert('Error: ' + (res.data.message || 'could not approve all records.'));
                        }
                    }).catch(() => {
                        btn.disabled = false;
                        text.classList.remove('hidden');
                        spinner.classList.add('hidden');
                        alert('Error occurred while connecting.');
                    });
                }
            });
        }

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
