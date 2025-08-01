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
            <a href="{{ route("index") }}">เลือกแผนกการลงทะเบียน</a>
            <a href="{{ route("training.index") }}">Schedule</a>
            <a href="{{ route("training.history") }}">History</a>
            @if (auth()->user()->role == "sa" || auth()->user()->role == "hr")
                <a href="{{ route("training.admin.index") }}">Management</a>
            @endif
        </div>
        <div class="navbar-user hidden lg:flex">
            <div class="navbar-user-info">
                <div class="userid">{{ Auth::user()->userid }} {{ session("name") }}</div>
                <div class="department">{{ session("department") }}</div>
            </div>
            <div class="navbar-user-actions">
                <a href="{{ route("profile.index") }}">ข้อมูลผู้ใช้งาน</a>
                <button class="logout" onclick="confirmLogout()">ออกจากระบบ</button>
            </div>
        </div>
    </nav>
    <div class="mobile-menu fade-in" id="mobileMenu">
        <a href="{{ route("index") }}">เลือกแผนกการลงทะเบียน</a>
        <a href="{{ route("training.index") }}">Schedule</a>
        <a href="{{ route("training.history") }}">History</a>
        @if (auth()->user()->role == "sa" || auth()->user()->role == "hr")
            <a href="{{ route("training.admin.index") }}">Management</a>
        @endif
        <div class="user-block">
            {{ Auth::user()->userid }} {{ session("name") }}
            <div class="department">{{ session("department") }}</div>
            <div class="user-actions">
                <a href="{{ route("profile.index") }}">ข้อมูลผู้ใช้งาน</a>
                <button class="logout" onclick="confirmLogout()">ออกจากระบบ</button>
            </div>
        </div>
    </div>
    <div class="my-6">
        @yield("content")
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
