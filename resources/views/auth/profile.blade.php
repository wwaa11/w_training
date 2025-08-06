@extends("layouts.layout")
@section("content")
    <div class="auth-container">
        <div class="auth-card profile-card">
            <div class="auth-header">
                <div class="auth-icon">
                    <i class="fa-solid fa-user-circle"></i>
                </div>
                <h1 class="auth-title">ข้อมูลผู้ใช้งาน</h1>
                <p class="auth-subtitle">ข้อมูลส่วนตัวและบัญชีผู้ใช้งาน</p>
            </div>

            <div class="profile-content">
                <div class="profile-section">
                    <h3 class="section-title">
                        <i class="fa-solid fa-info-circle mr-2"></i>ข้อมูลพื้นฐาน
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">รหัสพนักงาน:</span>
                            <span class="info-value">{{ Auth::user()->userid }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">ชื่อ-นามสกุล:</span>
                            <span class="info-value">{{ session("name") }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">แผนก/ฝ่าย:</span>
                            <span class="info-value">{{ session("department") }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">เลขบัตรประจำตัวประชาชน:</span>
                            <span class="info-value">{{ Auth::user()->refNo ?: "ยังไม่ได้ระบุ" }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">เพศ:</span>
                            <span class="info-value">{{ Auth::user()->gender ?: "ยังไม่ได้ระบุ" }}</span>
                        </div>
                    </div>
                </div>

                <div class="profile-section">
                    <h3 class="section-title">
                        <i class="fa-solid fa-signature mr-2"></i>ลายเซ็นต์
                    </h3>
                    <div class="signature-display">
                        @if (Auth::user()->sign)
                            <img class="user-signature" src="{{ Auth::user()->sign }}" alt="ลายเซ็นต์">
                        @else
                            <div class="no-signature">
                                <i class="fa-solid fa-signature"></i>
                                <span>ยังไม่มีลายเซ็นต์</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="profile-actions">
                    <a class="action-button primary" href="{{ route("profile.updateProfile") }}">
                        <i class="fa-solid fa-edit mr-2"></i>แก้ไขข้อมูลส่วนตัว
                    </a>
                    @if (!Auth::user()->sign)
                        <a class="action-button secondary" href="{{ route("profile.updateSign") }}">
                            <i class="fa-solid fa-signature mr-2"></i>เพิ่มลายเซ็นต์
                        </a>
                    @endif
                </div>
            </div>
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

        .profile-content {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-xl);
        }

        .profile-section {
            background: var(--background-tertiary);
            border-radius: var(--radius-lg);
            padding: var(--spacing-lg);
            border: 1px solid var(--border-color);
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: var(--spacing-md);
            display: flex;
            align-items: center;
        }

        .info-grid {
            display: grid;
            gap: var(--spacing-md);
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--spacing-sm) 0;
            border-bottom: 1px solid var(--border-color);
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 500;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .info-value {
            font-weight: 600;
            color: var(--text-primary);
            text-align: right;
        }

        .signature-display {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100px;
        }

        .user-signature {
            max-width: 200px;
            max-height: 100px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            background: white;
            padding: var(--spacing-sm);
        }

        .no-signature {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: var(--spacing-sm);
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .no-signature i {
            font-size: 2rem;
            opacity: 0.5;
        }

        .profile-actions {
            display: flex;
            gap: var(--spacing-md);
            flex-wrap: wrap;
        }

        .action-button {
            flex: 1;
            min-width: 200px;
            padding: var(--spacing-md);
            border-radius: var(--radius-md);
            text-decoration: none;
            font-weight: 600;
            text-align: center;
            transition: all var(--transition-normal);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .action-button.primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .action-button.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }

        .action-button.secondary {
            background: var(--secondary-light);
            color: var(--secondary-color);
            border: 1px solid var(--border-color);
        }

        .action-button.secondary:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-2px);
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

            .info-item {
                flex-direction: column;
                align-items: flex-start;
                gap: var(--spacing-xs);
            }

            .info-value {
                text-align: left;
            }

            .profile-actions {
                flex-direction: column;
            }

            .action-button {
                min-width: auto;
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

            .user-signature {
                max-width: 150px;
            }
        }
    </style>
@endsection
