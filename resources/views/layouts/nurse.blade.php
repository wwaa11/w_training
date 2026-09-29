<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield("meta")
    <title inertia>PR9 Nurse Training</title>
    <link href="{{ url("images/Logo.ico") }}" rel="shortcut icon">
    <link rel="stylesheet" type="text/css" href="{{ asset("css/all.min.css") }}?v=1.0.2">
    <link rel="stylesheet" type="text/css" href="{{ asset("css/theme.css") }}?v=1.1.0">
    <script src="{{ asset("js/axios.min.js") }}"></script>
    <script src="{{ asset("js/jquery.min.js") }}"></script>
    <script src="{{ asset("js/sweetalert2.js") }}"></script>
    <script>
        // The XSRF-TOKEN cookie expires with the session, so send the token rendered with the page.
        window.setCsrfToken = function(token) {
            if (!token) {
                return;
            }

            window.csrfToken = token;
            document.querySelector('meta[name="csrf-token"]').setAttribute('content', token);
            axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
            if (window.jQuery) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                });
            }
        };

        window.setCsrfToken(document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    </script>
    @vite("resources/css/app.css")
    @include("hrd.partials.hospital-theme")
    @stack("styles")
</head>

<body class="prompt app-shell">
    <nav class="navbar">
        <div class="navbar-logo">
            <a href="{{ route("index") }}">
                <img src="{{ url("images/Side Logo.png") }}" alt="Logo">
            </a>
            <span class="navbar-title hidden cursor-pointer lg:block">Nursing</span>
        </div>
        <button class="mobile-menu-btn lg:hidden" type="button" onclick="toggleMobileMenu()">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="navbar-links hidden lg:flex">
            <a class="{{ request()->routeIs("index") ? "active" : "" }}" href="{{ route("index") }}"><i class="fa-solid fa-home mr-2"></i>หน้าหลัก</a>
            <a class="{{ request()->routeIs("nurse.index") ? "active" : "" }}" href="{{ route("nurse.index") }}"><i class="fa-solid fa-list mr-2"></i>รายการที่เปิดลงทะเบียน</a>
            <a class="{{ request()->routeIs("nurse.history") ? "active" : "" }}" href="{{ route("nurse.history") }}"><i class="fa-solid fa-history mr-2"></i>ประวัติการลงทะเบียน</a>
            @if (auth()->user()->role == "sa" || auth()->user()->role == "nurse")
                <a class="{{ request()->routeIs("nurse.admin.*") ? "active" : "" }}" href="{{ route("nurse.admin.index") }}"><i class="fa-solid fa-gear mr-2"></i>Admin Panel</a>
            @endif
        </div>
        <div class="navbar-user hidden lg:flex">
            <div class="navbar-user-info">
                <div class="userid">{{ Auth::user()->userid }} {{ session("name") }}</div>
                <div class="department">{{ session("department") }}</div>
            </div>
            <div class="navbar-user-actions">
                <a href="{{ route("profile.index") }}"><i class="fa-solid fa-user mr-1"></i>ข้อมูลผู้ใช้งาน</a>
                <button class="logout" onclick="confirmLogout()"><i class="fa-solid fa-sign-out-alt mr-1"></i>ออกจากระบบ</button>
            </div>
        </div>
    </nav>

    <div class="mobile-menu fade-in" id="mobileMenu">
        <a class="{{ request()->routeIs("index") ? "active" : "" }}" href="{{ route("index") }}"><i class="fa-solid fa-home mr-2"></i>หน้าหลัก</a>
        <a class="{{ request()->routeIs("nurse.index") ? "active" : "" }}" href="{{ route("nurse.index") }}"><i class="fa-solid fa-list mr-2"></i>รายการที่เปิดลงทะเบียน</a>
        <a class="{{ request()->routeIs("nurse.history") ? "active" : "" }}" href="{{ route("nurse.history") }}"><i class="fa-solid fa-history mr-2"></i>ประวัติการลงทะเบียน</a>
        @if (auth()->user()->role == "sa" || auth()->user()->role == "nurse")
            <a class="{{ request()->routeIs("nurse.admin.*") ? "active" : "" }}" href="{{ route("nurse.admin.index") }}"><i class="fa-solid fa-gear mr-2"></i>Admin Panel</a>
        @endif
        <div class="user-block">
            <div class="userid">{{ Auth::user()->userid }} {{ session("name") }}</div>
            <div class="department">{{ session("department") }}</div>
            <div class="user-actions">
                <a href="{{ route("profile.index") }}"><i class="fa-solid fa-user mr-1"></i>ข้อมูลผู้ใช้งาน</a>
                <button class="logout" onclick="confirmLogout()"><i class="fa-solid fa-sign-out-alt mr-1"></i>ออกจากระบบ</button>
            </div>
        </div>
    </div>
    <main class="main-content hrd-hospital">
        @yield("content")
    </main>

    <div class="logout-modal" id="logoutModal">
        <div class="logout-modal-content">
            <div class="logout-modal-title">
                <i class="fa-solid fa-sign-out-alt mr-2"></i>ยืนยันการออกจากระบบ
            </div>
            <div class="logout-modal-description">
                คุณต้องการออกจากระบบหรือไม่? การดำเนินการนี้จะทำให้คุณต้องเข้าสู่ระบบใหม่
            </div>
            <div class="logout-modal-buttons">
                <button class="logout-modal-btn cancel" onclick="hideLogoutModal()">
                    <i class="fa-solid fa-times mr-1"></i>ยกเลิก
                </button>
                <button class="logout-modal-btn confirm" onclick="logout()">
                    <i class="fa-solid fa-sign-out-alt mr-1"></i>ออกจากระบบ
                </button>
            </div>
        </div>
    </div>
    <script>
        // Session validation function
        function checkSessionValidity() {
            axios.get('{{ route("session.check") }}', {
                    timeout: 5000
                })
                .then((response) => {
                    window.setCsrfToken(response.data.token);

                    if (response.data.valid === false) {
                        console.log('Session expired, refreshing page...');
                        window.location.reload();
                    }
                })
                .catch((error) => {
                    console.error('Session check failed:', error);
                    // If we can't reach the server, assume session might be invalid
                    if (error.code === 'ECONNABORTED' || error.response?.status === 401) {
                        console.log('Session check timeout or unauthorized, refreshing page...');
                        window.location.reload();
                    }
                });
        }

        // Check session validity every 5 minutes
        setInterval(checkSessionValidity, 5 * 60 * 1000);

        // Also check when the page becomes visible (user returns to tab)
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                checkSessionValidity();
            }
        });

        // Check session when user interacts with the page after being idle
        let sessionCheckTimeout;

        function resetSessionCheck() {
            clearTimeout(sessionCheckTimeout);
            sessionCheckTimeout = setTimeout(checkSessionValidity, 30 * 1000); // Check after 30 seconds of inactivity
        }

        // Add event listeners for user activity
        ['click', 'keypress', 'scroll', 'mousemove'].forEach(event => {
            document.addEventListener(event, resetSessionCheck, true);
        });

        // Initial session check after page load
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(checkSessionValidity, 1000); // Check 1 second after page load
        });

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
