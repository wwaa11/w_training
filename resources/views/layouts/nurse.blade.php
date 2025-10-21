<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    @yield("meta")
    <title inertia>PR9 Nurse Training</title>
    <link href="{{ url("images/Logo.ico") }}" rel="shortcut icon">
    <link rel="stylesheet" type="text/css" href="{{ asset("css/all.min.css") }}?v=1.0.2">
    <link rel="stylesheet" type="text/css" href="{{ asset("css/theme.css") }}?v=1.0.2">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite("resources/css/app.css")
</head>

<body class="prompt">
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
            <a href="{{ route("index") }}"><i class="fa-solid fa-home mr-2"></i>หน้าหลัก</a>
            <a href="{{ route("nurse.index") }}"><i class="fa-solid fa-list mr-2"></i>รายการที่เปิดลงทะเบียน</a>
            <a href="{{ route("nurse.history") }}"><i class="fa-solid fa-history mr-2"></i>ประวัติการลงทะเบียน</a>
            @if (auth()->user()->role == "sa" || auth()->user()->role == "nurse")
                <a href="{{ route("nurse.admin.index") }}"><i class="fa-solid fa-gear mr-2"></i>Admin Panel</a>
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
        <a href="{{ route("index") }}">หน้าหลัก</a>
        <a href="{{ route("nurse.index") }}">รายการที่เปิดลงทะเบียน</a>
        <a href="{{ route("nurse.history") }}">ประวัติการลงทะเบียน</a>
        @if (auth()->user()->role == "sa" || auth()->user()->role == "nurse")
            <a href="{{ route("nurse.admin.index") }}">Management</a>
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
        // Session validation function
        function checkSessionValidity() {
            axios.get('{{ route("session.check") }}', {
                    timeout: 5000
                })
                .then((response) => {
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
