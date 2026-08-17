@extends("layouts.layout")
@section("content")
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <img class="auth-logo" src="{{ url("images/Side Logo.png") }}" alt="PR9 Logo">
                <p class="auth-title">PR9 Training</p>
                <p class="auth-subtitle">เข้าสู่ระบบเพื่อใช้งาน</p>
            </div>

            <form class="auth-form" id="loginForm">
                <div class="form-group">
                    <label class="form-label" for="userid">
                        <i class="fa-solid fa-user mr-2"></i>รหัสพนักงาน
                    </label>
                    <input class="form-input" id="userid" name="userid" type="text" placeholder="กรุณากรอกรหัสพนักงาน" autocomplete="username" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">
                        <i class="fa-solid fa-lock mr-2"></i>รหัสผ่าน
                    </label>
                    <div class="password-field">
                        <input class="form-input" id="password" name="password" type="password" placeholder="กรุณากรอกรหัสผ่าน" autocomplete="current-password" required>
                        <button class="password-toggle" id="passwordToggle" type="button" aria-label="แสดงรหัสผ่าน" aria-pressed="false">
                            <i class="fa-solid fa-eye" id="passwordToggleIcon"></i>
                        </button>
                    </div>
                </div>

                <button class="auth-button" id="loginButton" type="button">
                    <i class="fa-solid fa-sign-in-alt mr-2"></i>เข้าสู่ระบบ
                </button>
            </form>
        </div>
    </div>

    <style>
        /* Override main-content styles for login page */
        .main-content {
            margin-top: 0 !important;
            padding: 0 !important;
            min-height: 100vh !important;
            max-width: none !important;
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--spacing-md);
            background: var(--background-gradient);
            width: 100%;
        }

        .auth-card {
            background: linear-gradient(135deg, var(--background-primary) 0%, var(--background-secondary) 100%);
            border-radius: var(--radius-lg);
            padding: var(--spacing-xl);
            box-shadow: 0 15px 40px var(--shadow-medium);
            border: 1px solid var(--border-color);
            width: 100%;
            max-width: 450px;
            animation: fadeInUp 0.6s ease-out;
        }

        .auth-header {
            text-align: center;
            margin-bottom: var(--spacing-xl);
        }

        .auth-logo {
            width: 100%;
            height: auto;
            margin-bottom: var(--spacing-md);
            filter: drop-shadow(0 2px 4px var(--shadow-light));
        }

        .auth-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 var(--spacing-xs) 0;
        }

        .auth-subtitle {
            font-size: 1rem;
            color: var(--text-secondary);
            margin: 0;
        }

        .auth-form {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-md);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-xs);
        }

        .form-label {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            margin-bottom: var(--spacing-xs);
        }

        .form-input {
            padding: var(--spacing-md) var(--spacing-lg);
            border: 2px solid var(--border-color);
            border-radius: var(--radius-md);
            font-size: 1rem;
            transition: all var(--transition-normal);
            background: var(--background-primary);
            color: var(--text-primary);
            min-height: 48px;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px var(--primary-light);
            transform: translateY(-1px);
        }

        .form-input::placeholder {
            color: var(--text-muted);
        }

        .password-field {
            position: relative;
            display: flex;
        }

        .password-field .form-input {
            width: 100%;
            padding-right: 3.25rem;
        }

        .password-toggle {
            position: absolute;
            right: var(--spacing-xs);
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: none;
            border-radius: var(--radius-sm);
            color: var(--text-muted);
            cursor: pointer;
            padding: var(--spacing-xs) var(--spacing-sm);
            font-size: 1rem;
            transition: color var(--transition-normal);
        }

        .password-toggle:hover,
        .password-toggle:focus-visible {
            color: var(--primary-color);
        }

        .auth-button {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            padding: var(--spacing-md) var(--spacing-lg);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-normal);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: var(--spacing-md);
            min-height: 52px;
        }

        .auth-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
        }

        .auth-button:active {
            transform: translateY(0);
        }

        .auth-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .auth-container {
                padding: var(--spacing-md);
            }

            .auth-card {
                padding: var(--spacing-lg);
                max-width: 380px;
            }

            .auth-title {
                font-size: 1.3rem;
            }

            .auth-subtitle {
                font-size: 0.9rem;
            }

            .form-input {
                padding: var(--spacing-sm) var(--spacing-md);
                font-size: 0.95rem;
                min-height: 44px;
            }

            .auth-button {
                padding: var(--spacing-sm) var(--spacing-md);
                font-size: 0.95rem;
                min-height: 48px;
            }
        }

        @media (max-width: 480px) {
            .auth-container {
                padding: var(--spacing-sm);
            }

            .auth-card {
                padding: var(--spacing-md);
                max-width: 320px;
            }

            .auth-title {
                font-size: 1.2rem;
            }

            .form-input {
                padding: var(--spacing-sm) var(--spacing-md);
                font-size: 0.9rem;
                min-height: 42px;
            }

            .auth-button {
                padding: var(--spacing-sm) var(--spacing-md);
                font-size: 0.9rem;
                min-height: 46px;
            }
        }
    </style>
@endsection

@section("scripts")
    <script>
        const CSRF_EXPIRED_STATUS = 419;

        function themeColor(name) {
            return getComputedStyle(document.documentElement).getPropertyValue(name).trim();
        }

        function showError(title) {
            Swal.fire({
                title: title,
                icon: 'error',
                confirmButtonColor: themeColor('--danger-color'),
                confirmButtonText: 'ตกลง'
            });
        }

        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('passwordToggleIcon');
            const button = document.getElementById('passwordToggle');
            const showPassword = input.type === 'password';

            input.type = showPassword ? 'text' : 'password';
            icon.className = showPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
            button.setAttribute('aria-label', showPassword ? 'ซ่อนรหัสผ่าน' : 'แสดงรหัสผ่าน');
            button.setAttribute('aria-pressed', showPassword ? 'true' : 'false');
            input.focus();
        }

        async function refreshCsrfToken() {
            const res = await axios.get('{{ route("session.check") }}');
            window.setCsrfToken(res.data.token);
        }

        function sendLogin(payload) {
            return axios.post('{{ route("login.post") }}', payload);
        }

        // A login page left open longer than the session lifetime submits an expired CSRF token,
        // so renew it once and resend instead of forcing the user to reload the page.
        async function sendLoginWithTokenRetry(payload) {
            try {
                return await sendLogin(payload);
            } catch (error) {
                if (error.response && error.response.status === CSRF_EXPIRED_STATUS) {
                    await refreshCsrfToken();
                    return sendLogin(payload);
                }

                throw error;
            }
        }

        function connectionErrorMessage(error) {
            if (error.response && error.response.status === CSRF_EXPIRED_STATUS) {
                return 'เซสชันหมดอายุ กรุณารีเฟรชหน้าเว็บแล้วเข้าสู่ระบบใหม่อีกครั้ง';
            }

            if (error.response) {
                return 'ระบบไม่สามารถดำเนินการได้ กรุณาลองใหม่อีกครั้ง';
            }

            return 'เกิดข้อผิดพลาดในการเชื่อมต่อ';
        }

        function handleLoginResult(data) {
            if (data.status != 'success') {
                showError(data.message);
                return;
            }

            Swal.fire({
                title: 'เข้าสู่ระบบสำเร็จ',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.location.href = '{{ route("index") }}';
            });
        }

        async function login() {
            const userid = $('#userid').val().trim();
            const password = $('#password').val().trim();
            const loginButton = document.getElementById('loginButton');

            if (!userid || !password) {
                Swal.fire({
                    title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
                    icon: 'warning',
                    confirmButtonColor: themeColor('--primary-color'),
                    confirmButtonText: 'ตกลง'
                });
                return;
            }

            if (loginButton.disabled) {
                return;
            }

            loginButton.disabled = true;
            Swal.fire({
                title: 'กำลังเข้าสู่ระบบ',
                allowOutsideClick: false,
                showConfirmButton: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const res = await sendLoginWithTokenRetry({
                    'userid': userid,
                    'password': password,
                });
                handleLoginResult(res.data);
            } catch (error) {
                showError(connectionErrorMessage(error));
            } finally {
                loginButton.disabled = false;
            }
        }

        document.getElementById('passwordToggle').addEventListener('click', togglePassword);
        document.getElementById('loginButton').addEventListener('click', login);
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            login();
        });

        // Keep the CSRF token (and the session behind it) alive while the page stays open.
        setInterval(() => {
            refreshCsrfToken().catch(() => {});
        }, 10 * 60 * 1000);

        // Enter key support
        $('#password').keyup(function(e) {
            if (e.keyCode === 13) {
                login();
            }
        });

        $('#userid').keyup(function(e) {
            if (e.keyCode === 13) {
                $('#password').focus();
            }
        });

        // Add SweetAlert modal scrolling styles
        const style = document.createElement('style');
        style.textContent = `
            /* Ensure SweetAlert modal allows scrolling */
            .swal2-container {
                overflow-y: auto !important;
            }
            
            .swal2-popup {
                max-height: 90vh;
                overflow-y: auto;
            }
        `;
        document.head.appendChild(style);
    </script>
@endsection
