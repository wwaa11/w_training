<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    @yield("meta")
    <title inertia>PR9 Lesson</title>
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
                <img class="my-auto aspect-auto max-h-16" src="{{ url("images/Side Logo.png") }}" alt="PR9 Logo">
            </a>
            <span class="navbar-title hidden lg:block">Trainings</span>
        </div>
        <button class="mobile-menu-btn lg:hidden" type="button" onclick="toggleMobileMenu()" aria-label="Toggle mobile menu">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="navbar-links hidden lg:flex">
            <a class="{{ request()->routeIs("index") ? "active" : "" }}" href="{{ route("index") }}">
                <i class="fa-solid fa-home mr-2"></i>Home
            </a>
            <a class="{{ request()->routeIs("training.index") ? "active" : "" }}" href="{{ route("training.index") }}">
                <i class="fa-solid fa-calendar mr-2"></i>Schedule
            </a>
            <a class="{{ request()->routeIs("training.history") ? "active" : "" }}" href="{{ route("training.history") }}">
                <i class="fa-solid fa-history mr-2"></i>History
            </a>
            @if (auth()->user()->role == "sa" || auth()->user()->role == "hr")
                <a class="{{ request()->routeIs("training.admin.*") ? "active" : "" }}" href="{{ route("training.admin.index") }}">
                    <i class="fa-solid fa-cog mr-2"></i>Management
                </a>
            @endif
        </div>
        <div class="navbar-user hidden lg:flex">
            <div class="navbar-user-info">
                <div class="userid">{{ Auth::user()->userid }} {{ session("name") }}</div>
                <div class="department">{{ session("department") }}</div>
            </div>
            <div class="navbar-user-actions">
                <a href="{{ route("profile.index") }}">
                    <i class="fa-solid fa-user mr-1"></i>ข้อมูลผู้ใช้งาน
                </a>
                <button class="logout" onclick="confirmLogout()">
                    <i class="fa-solid fa-sign-out-alt mr-1"></i>ออกจากระบบ
                </button>
            </div>
        </div>
    </nav>

    <div class="mobile-menu fade-in" id="mobileMenu">
        <a class="{{ request()->routeIs("index") ? "active" : "" }}" href="{{ route("index") }}">
            <i class="fa-solid fa-home mr-2"></i>Home
        </a>
        <a class="{{ request()->routeIs("training.index") ? "active" : "" }}" href="{{ route("training.index") }}">
            <i class="fa-solid fa-calendar mr-2"></i>Schedule
        </a>
        <a class="{{ request()->routeIs("training.history") ? "active" : "" }}" href="{{ route("training.history") }}">
            <i class="fa-solid fa-history mr-2"></i>History
        </a>
        @if (auth()->user()->role == "sa" || auth()->user()->role == "hr")
            <a class="{{ request()->routeIs("training.admin.*") ? "active" : "" }}" href="{{ route("training.admin.index") }}">
                <i class="fa-solid fa-cog mr-2"></i>Management
            </a>
        @endif
        <div class="user-block">
            <div class="userid">{{ Auth::user()->userid }} {{ session("name") }}</div>
            <div class="department">{{ session("department") }}</div>
            <div class="user-actions">
                <a href="{{ route("profile.index") }}">
                    <i class="fa-solid fa-user mr-1"></i>ข้อมูลผู้ใช้งาน
                </a>
                <button class="logout" onclick="confirmLogout()">
                    <i class="fa-solid fa-sign-out-alt mr-1"></i>ออกจากระบบ
                </button>
            </div>
        </div>
    </div>

    <div class="main-content">
        @yield("content")
    </div>

    <!-- Enhanced Logout Modal -->
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
            const btn = document.querySelector('.mobile-menu-btn i');

            if (menu.style.display === 'flex') {
                menu.style.display = 'none';
                btn.className = 'fa-solid fa-bars';
            } else {
                menu.style.display = 'flex';
                btn.className = 'fa-solid fa-times';
            }
        }

        function hideLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.style.animation = 'modalFadeOut 0.3s ease-out';
            setTimeout(() => {
                modal.style.display = 'none';
                modal.style.animation = '';
            }, 300);
        }

        function confirmLogout() {
            document.getElementById('logoutModal').style.display = 'flex';
        }

        function logout() {
            const logoutBtn = document.querySelector('.logout-modal-btn.confirm');
            logoutBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i>กำลังออกจากระบบ...';
            logoutBtn.disabled = true;

            axios.post('{{ route("logout") }}')
                .then((res) => {
                    window.location.href = '{{ route("login") }}';
                })
                .catch((error) => {
                    console.error('Logout error:', error);
                    logoutBtn.innerHTML = '<i class="fa-solid fa-sign-out-alt mr-1"></i>ออกจากระบบ';
                    logoutBtn.disabled = false;
                    hideLogoutModal();
                });
        }

        // Hide mobile menu on resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                document.getElementById('mobileMenu').style.display = 'none';
                document.querySelector('.mobile-menu-btn i').className = 'fa-solid fa-bars';
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobileMenu');
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');

            if (mobileMenu.style.display === 'flex' &&
                !mobileMenu.contains(event.target) &&
                !mobileMenuBtn.contains(event.target)) {
                toggleMobileMenu();
            }
        });

        // Add loading state to navigation links
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.navbar-links a, .mobile-menu a');

            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (!this.classList.contains('active')) {
                        document.body.classList.add('loading');
                    }
                });
            });
        });

        // Smooth scroll to top when clicking logo
        document.querySelector('.navbar-logo a').addEventListener('click', function(e) {
            if (window.location.pathname === '{{ route("index") }}') {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });
    </script>
    @yield("scripts")
</body>

</html>
