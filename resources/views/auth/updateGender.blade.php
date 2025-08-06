@extends("layouts.layout")
@section("content")
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-icon">
                    <i class="fa-solid fa-venus-mars"></i>
                </div>
                <h1 class="auth-title">กรุณาระบุเพศ</h1>
                <p class="auth-subtitle">โปรดเลือกเพศของคุณเพื่อดำเนินการต่อ</p>
            </div>

            <form class="auth-form" action="{{ route("profile.updateGender") }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="gender">
                        <i class="fa-solid fa-user mr-2"></i>เพศ
                    </label>
                    <select class="form-select" id="gender" name="gender" required>
                        <option value="" selected disabled>โปรดเลือกเพศ</option>
                        <option value="ชาย">ชาย</option>
                        <option value="หญิง">หญิง</option>
                    </select>
                </div>

                <button class="auth-button" type="submit">
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
            max-width: 400px;
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

        .form-select {
            padding: var(--spacing-md);
            border: 2px solid var(--border-color);
            border-radius: var(--radius-md);
            font-size: 1rem;
            transition: all var(--transition-normal);
            background: var(--background-primary);
            color: var(--text-primary);
            cursor: pointer;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--primary-light);
            transform: translateY(-1px);
        }

        .form-select option {
            padding: var(--spacing-sm);
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
        }
    </style>
@endsection
