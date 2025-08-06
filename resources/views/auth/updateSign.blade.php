@extends("layouts.layout")
@section("content")
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-icon">
                    <i class="fa-solid fa-signature"></i>
                </div>
                <h1 class="auth-title">เซ็นต์ลายเซ็นต์</h1>
                <p class="auth-subtitle">เนื่องจากระบบมีความผิดพลาดในการบันทึก กรุณาเซ็นต์ลายเซ็นต์อีกครั้ง</p>
            </div>

            <form class="auth-form" id="saveSign" action="{{ route("profile.updateSign") }}" method="POST">
                @csrf
                <input id="sign" type="hidden" name="sign">

                <div class="form-group">
                    <label class="form-label">
                        <i class="fa-solid fa-pen mr-2"></i>ลายเซ็นต์
                    </label>
                    <div class="signature-container">
                        <canvas class="signature-canvas" id="sign_Canvas" width="300" height="150"></canvas>
                        <button class="clear-signature-btn" type="button" onclick="clearSign()">
                            <i class="fa-solid fa-eraser mr-1"></i>ล้างลายเซ็นต์
                        </button>
                    </div>
                </div>

                <button class="auth-button" type="button" onclick="saveSign()">
                    <i class="fa-solid fa-save mr-2"></i>บันทึก
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
            max-width: 500px;
            animation: fadeInUp 0.8s ease-out;
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

        .signature-container {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-md);
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
            align-self: flex-start;
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
        @media (max-width: 480px) {
            .auth-container {
                padding: var(--spacing-md);
            }

            .auth-card {
                padding: var(--spacing-xl);
            }

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
        }
    </style>
@endsection

@section("scripts")
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        const canvas = document.getElementById('sign_Canvas');
        const ctx = canvas.getContext('2d');
        const signaturePad = new SignaturePad(canvas, {
            penColor: "rgb(59, 130, 246)",
            backgroundColor: "rgb(255, 255, 255)"
        });

        function clearSign() {
            signaturePad.clear();
        }

        function saveSign() {
            if (signaturePad.isEmpty()) {
                Swal.fire({
                    title: "กรุณาเซ็นต์ลายเซ็นต์",
                    icon: 'warning',
                    confirmButtonColor: var (--primary - color),
                    confirmButtonText: 'ตกลง'
                });
            } else {
                $('#sign').val(signaturePad.toDataURL());
                $('#saveSign').submit();
            }
        }
    </script>
@endsection
