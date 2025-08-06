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
                    <input class="form-input" id="password" name="password" type="password" placeholder="กรุณากรอกรหัสผ่าน" autocomplete="current-password" required>
                </div>

                <button class="auth-button" type="button" onclick="login()">
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
        function login() {
            const userid = $('#userid').val().trim();
            const password = $('#password').val().trim();

            // Get CSS variables
            const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--primary-color').trim();
            const dangerColor = getComputedStyle(document.documentElement).getPropertyValue('--danger-color').trim();

            // Validation
            if (!userid || !password) {
                Swal.fire({
                    title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
                    icon: 'warning',
                    confirmButtonColor: primaryColor,
                    confirmButtonText: 'ตกลง'
                });
                return;
            }

            // Show loading state
            Swal.fire({
                title: 'กำลังเข้าสู่ระบบ',
                allowOutsideClick: false,
                showConfirmButton: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            axios.post('{{ route("login") }}', {
                'userid': userid,
                'password': password,
            }).then((res) => {
                if (res.data.status == 'success') {
                    Swal.fire({
                        title: 'เข้าสู่ระบบสำเร็จ',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '{{ route("index") }}';
                    });
                } else {
                    Swal.fire({
                        title: res.data.message,
                        icon: 'error',
                        confirmButtonColor: dangerColor,
                        confirmButtonText: 'ตกลง'
                    });
                }
            }).catch((error) => {
                Swal.fire({
                    title: 'เกิดข้อผิดพลาดในการเชื่อมต่อ',
                    icon: 'error',
                    confirmButtonColor: dangerColor,
                    confirmButtonText: 'ตกลง'
                });
            });
        }

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
