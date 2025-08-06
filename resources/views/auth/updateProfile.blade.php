@extends("layouts.layout")
@section("content")
    <div class="auth-container">
        <div class="auth-card profile-card">
            <div class="auth-header">
                <div class="auth-icon">
                    <i class="fa-solid fa-user-edit"></i>
                </div>
                <h1 class="auth-title">แก้ไขข้อมูลส่วนตัว</h1>
                <p class="auth-subtitle">อัปเดตข้อมูลส่วนตัวและรหัสผ่านของคุณ</p>
            </div>

            @if ($errors->any())
                <div class="error-message">
                    <i class="fa-solid fa-exclamation-triangle mr-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form class="auth-form" id="changePassword" action="{{ route("profile.changePassword") }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">
                        <i class="fa-solid fa-signature mr-2"></i>ลายเซ็นต์
                    </label>
                    <div class="signature-section">
                        <div class="signature-controls">
                            <span class="signature-label">ลายเซ็นต์ปัจจุบัน</span>
                            <button class="clear-signature-btn" type="button" onclick="clearSign()">
                                <i class="fa-solid fa-eraser mr-1"></i>เซ็นต์ใหม่
                            </button>
                        </div>
                        <input id="sign" type="hidden" name="sign">
                        @if (Auth::user()->sign)
                            <img class="current-signature" id="old_sign" src="{{ Auth::user()->sign }}" alt="ลายเซ็นต์ปัจจุบัน">
                        @endif
                        <canvas class="signature-canvas" id="sign_Canvas" width="300" height="150"></canvas>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="refno">
                        <i class="fa-solid fa-id-card mr-2"></i>เลขบัตรประจำตัวประชาชน
                    </label>
                    <input class="form-input" id="refno" name="refno" type="text" placeholder="กรุณากรอกเลขบัตรประจำตัวประชาชน 13 หลัก" autocomplete="off" value="{{ Auth::user()->refNo }}" required>
                </div>

                @if (!Auth::user()->password_changed)
                    <input id="password_old" name="old_password" type="hidden" value="{{ Auth::user()->userid }}">
                @else
                    <div class="form-group">
                        <label class="form-label" for="password_old">
                            <i class="fa-solid fa-lock mr-2"></i>รหัสผ่านปัจจุบัน
                        </label>
                        <input class="form-input" id="password_old" name="old_password" type="password" placeholder="กรุณากรอกรหัสผ่านปัจจุบัน" autocomplete="current-password" required>
                    </div>
                @endif

                <div class="form-group">
                    <label class="form-label" for="password">
                        <i class="fa-solid fa-key mr-2"></i>รหัสผ่านใหม่
                    </label>
                    <input class="form-input" id="password" name="password" type="password" placeholder="กรุณากรอกรหัสผ่านใหม่" autocomplete="new-password" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_check">
                        <i class="fa-solid fa-check-circle mr-2"></i>ยืนยันรหัสผ่านใหม่
                    </label>
                    <input class="form-input" id="password_check" name="password_check" type="password" placeholder="กรุณากรอกรหัสผ่านใหม่อีกครั้ง" autocomplete="new-password" required>
                </div>

                <button class="auth-button" type="button" onclick="changePassword()">
                    <i class="fa-solid fa-save mr-2"></i>บันทึกการเปลี่ยนแปลง
                </button>
            </form>
        </div>
    </div>

    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--spacing-lg);
            background: var(--background-gradient);
        }

        .auth-card {
            background: linear-gradient(135deg, var(--background-primary) 0%, var(--background-secondary) 100%);
            border-radius: var(--radius-xl);
            padding: var(--spacing-2xl);
            box-shadow: 0 20px 60px var(--shadow-medium);
            border: 1px solid var(--border-color);
            width: 100%;
            max-width: 600px;
            animation: fadeInUp 0.8s ease-out;
        }

        .profile-card {
            max-width: 700px;
        }

        .auth-header {
            text-align: center;
            margin-bottom: var(--spacing-2xl);
        }

        .auth-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto var(--spacing-lg);
            font-size: 2rem;
            color: white;
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }

        .auth-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: var(--spacing-sm);
        }

        .auth-subtitle {
            font-size: 1rem;
            color: var(--text-secondary);
            margin: 0;
            line-height: 1.5;
        }

        .error-message {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border: 1px solid #fecaca;
            border-radius: var(--radius-md);
            padding: var(--spacing-md);
            margin-bottom: var(--spacing-lg);
            color: var(--danger-color);
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .auth-form {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-lg);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-sm);
        }

        .form-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
        }

        .form-input {
            padding: var(--spacing-md);
            border: 2px solid var(--border-color);
            border-radius: var(--radius-md);
            font-size: 1rem;
            transition: all var(--transition-normal);
            background: var(--background-primary);
            color: var(--text-primary);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--primary-light);
            transform: translateY(-1px);
        }

        .form-input::placeholder {
            color: var(--text-muted);
        }

        .signature-section {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-md);
        }

        .signature-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .signature-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .current-signature {
            max-width: 200px;
            height: auto;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            background: white;
            padding: var(--spacing-sm);
        }

        .signature-canvas {
            border: 2px solid var(--border-color);
            border-radius: var(--radius-md);
            background: white;
            cursor: crosshair;
            transition: all var(--transition-normal);
        }

        .signature-canvas:hover {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .clear-signature-btn {
            background: var(--secondary-light);
            color: var(--secondary-color);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: var(--spacing-sm) var(--spacing-md);
            font-size: 0.9rem;
            cursor: pointer;
            transition: all var(--transition-normal);
        }

        .clear-signature-btn:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-1px);
        }

        .auth-button {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            padding: var(--spacing-md);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-normal);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: var(--spacing-md);
        }

        .auth-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }

        .auth-button:active {
            transform: translateY(0);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
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
                padding: var(--spacing-xl);
            }

            .profile-card {
                max-width: 100%;
            }

            .signature-controls {
                flex-direction: column;
                gap: var(--spacing-sm);
                align-items: flex-start;
            }
        }

        @media (max-width: 480px) {
            .auth-title {
                font-size: 1.5rem;
            }

            .auth-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .signature-canvas {
                width: 100% !important;
                height: 120px !important;
            }

            .current-signature {
                max-width: 150px;
            }
        }
    </style>
@endsection

@section("scripts")
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        $(document).ready(function() {
            @if (Auth::user()->password_changed == false)
                Swal.fire({
                    title: 'คำแนะนำการใช้งาน',
                    html: '<img src="{{ url("images/how.png") }}" style="max-width: 100%; height: auto;">',
                    allowOutsideClick: false,
                    showConfirmButton: true,
                    confirmButtonColor: 'var(--primary-color)',
                    confirmButtonText: 'เข้าใจแล้ว'
                });
            @endif
        });

        const img = document.getElementById('old_sign');
        const canvas = document.getElementById('sign_Canvas');
        const ctx = canvas.getContext('2d');
        const signaturePad = new SignaturePad(canvas, {
            penColor: "rgb(59, 130, 246)",
            backgroundColor: "rgb(255, 255, 255)"
        });

        if (img) {
            ctx.drawImage(img, 0, 0);
        }

        function clearSign() {
            signaturePad.clear();
        }

        function changePassword() {
            const old_password = $('#password_old').val().trim();
            const ref = $('#refno').val().trim();
            const password = $('#password').val().trim();
            const password_check = $('#password_check').val().trim();

            let cansend = true;
            let errorMessages = [];

            // Validation
            if (ref === '') {
                cansend = false;
                errorMessages.push('เลขบัตรประจำตัวประชาชน');
            }

            if (ref.length !== 13 && ref !== '-') {
                cansend = false;
                errorMessages.push('เลขบัตรประจำตัวประชาชนไม่ถูกต้อง (ต้องมี 13 หลัก)');
            }

            if (signaturePad.isEmpty() && '{{ Auth::user()->sign }}' === '') {
                cansend = false;
                errorMessages.push('ลายเซ็นต์');
            }

            if (old_password === '') {
                cansend = false;
                errorMessages.push('รหัสผ่านเดิม');
            }

            if (password === '' || password !== password_check) {
                cansend = false;
                errorMessages.push('รหัสผ่านทั้งสองไม่ตรงกัน');
            }

            if (!cansend) {
                const errorList = errorMessages.map(msg => `<li>${msg}</li>`).join('');
                Swal.fire({
                    title: 'กรุณาตรวจสอบข้อมูล',
                    html: `<ul style="text-align: left; margin: 0; padding-left: 20px;">${errorList}</ul>`,
                    icon: 'warning',
                    confirmButtonColor: 'var(--primary-color)',
                    confirmButtonText: 'ตกลง'
                });
                return;
            }

            $('#sign').val(signaturePad.toDataURL());
            $('#changePassword').submit();
        }
    </script>
@endsection
