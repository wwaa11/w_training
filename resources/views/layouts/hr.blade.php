<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    @yield("meta")
    <title inertia>PR9 HRD</title>
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
            <span class="navbar-title hidden lg:block">HRD Division</span>
        </div>
        <button class="mobile-menu-btn lg:hidden" type="button" onclick="toggleMobileMenu()">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="navbar-links hidden lg:flex">
            <a href="{{ route("index") }}">เลือกแผนกการลงทะเบียน</a>
            <a href="{{ route("hr.index") }}">รายการที่เปิดลงทะเบียน</a>
            <a href="{{ route("hr.history") }}">ประวัติการลงทะเบียน</a>
            @if (auth()->user()->role == "sa" || auth()->user()->role == "hr")
                <a href="{{ route("hr.admin.index") }}">Projects Management</a>
                <a href="{{ route("hr.admin.users") }}">Users Management</a>
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
        <a href="{{ route("hr.index") }}">รายการที่เปิดลงทะเบียน</a>
        <a href="{{ route("hr.history") }}">ประวัติการลงทะเบียน</a>
        @if (auth()->user()->role == "sa" || auth()->user()->role == "hr")
            <a href="{{ route("hr.admin.index") }}">Projects Management</a>
            <a href="{{ route("hr.admin.users") }}">Users Management</a>
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
    <main class="main-content">
        @yield("content")
    </main>
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
