@extends("layouts.layout")
@section("content")
    <div class="index-container">
        <div class="division-grid">
            <a class="division-card hrd-card" href="{{ route("hrd.index") }}">
                <div class="division-icon">
                    <i class="fa-solid fa-users-gear"></i>
                </div>
                <div class="division-content">
                    <h2 class="division-title">HRD Division</h2>
                    <p class="division-description">ระบบจัดการทรัพยากรบุคคลและการพัฒนาบุคลากร</p>
                </div>
                <div class="division-arrow">
                    <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>

            <a class="division-card nurse-card" href="{{ route("nurse.index") }}">
                <div class="division-icon">
                    <i class="fa-solid fa-user-nurse"></i>
                </div>
                <div class="division-content">
                    <h2 class="division-title">Nursing Division</h2>
                    <p class="division-description">ระบบจัดการการฝึกอบรมและพัฒนาบุคลากรทางการพยาบาล</p>
                </div>
                <div class="division-arrow">
                    <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>

            <a class="division-card training-card" href="{{ route("training.index") }}">
                <div class="division-icon">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div class="division-content">
                    <h2 class="division-title">English Training Program</h2>
                    <p class="division-description">โปรแกรมการฝึกอบรมภาษาอังกฤษและการพัฒนาทักษะภาษา</p>
                </div>
                <div class="division-arrow">
                    <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>
        </div>
    </div>

    <style>
        .index-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: var(--spacing-2xl) 0;
        }

        .index-header {
            text-align: center;
            margin-bottom: var(--spacing-2xl);
            animation: fadeInUp 0.8s ease-out;
        }

        .index-title {
            font-size: 3rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: var(--spacing-md);
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 4px 8px var(--shadow-light);
        }

        .index-subtitle {
            font-size: 1.25rem;
            color: var(--text-secondary);
            font-weight: 500;
            margin: 0;
        }

        .division-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: var(--spacing-xl);
            margin-top: var(--spacing-2xl);
        }

        .division-card {
            background: linear-gradient(135deg, var(--background-primary) 0%, var(--background-secondary) 100%);
            border-radius: var(--radius-xl);
            padding: var(--spacing-2xl);
            text-decoration: none;
            color: inherit;
            border: 1px solid var(--border-color);
            box-shadow: 0 8px 32px var(--shadow-light);
            transition: all var(--transition-normal) var(--transition-bezier);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            gap: var(--spacing-lg);
            min-height: 280px;
        }

        .division-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left var(--transition-slow);
        }

        .division-card:hover::before {
            left: 100%;
        }

        .division-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px var(--shadow-medium);
            border-color: var(--primary-color);
        }

        .division-icon {
            width: 80px;
            height: 80px;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: var(--background-primary);
            margin-bottom: var(--spacing-md);
            transition: all var(--transition-normal);
        }

        .hrd-card .division-icon {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        }

        .nurse-card .division-icon {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .training-card .division-icon {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }

        .division-card:hover .division-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .division-content {
            flex: 1;
        }

        .division-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: var(--spacing-md);
            transition: color var(--transition-fast);
        }

        .division-description {
            font-size: 1rem;
            color: var(--text-secondary);
            line-height: 1.6;
            margin: 0;
        }

        .division-arrow {
            align-self: flex-end;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.1rem;
            transition: all var(--transition-normal);
        }

        .division-card:hover .division-arrow {
            background: var(--primary-color);
            color: var(--background-primary);
            transform: translateX(4px);
        }

        /* Animation for cards */
        .division-card:nth-child(1) {
            animation: fadeInUp 0.6s ease-out 0.1s both;
        }

        .division-card:nth-child(2) {
            animation: fadeInUp 0.6s ease-out 0.2s both;
        }

        .division-card:nth-child(3) {
            animation: fadeInUp 0.6s ease-out 0.3s both;
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
            .index-container {
                padding: var(--spacing-xl) var(--spacing-lg);
            }

            .index-title {
                font-size: 2.5rem;
            }

            .index-subtitle {
                font-size: 1.1rem;
            }

            .division-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-lg);
            }

            .division-card {
                padding: var(--spacing-xl);
                min-height: 240px;
            }

            .division-icon {
                width: 60px;
                height: 60px;
                font-size: 2rem;
            }

            .division-title {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .index-container {
                padding: var(--spacing-lg) var(--spacing-md);
            }

            .index-title {
                font-size: 2rem;
            }

            .division-card {
                padding: var(--spacing-lg);
            }
        }

        /* Loading state */
        .division-card.loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .division-card.loading .division-arrow {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>

    <script>
        // Add loading state to division cards
        document.addEventListener('DOMContentLoaded', function() {
            const divisionCards = document.querySelectorAll('.division-card');

            divisionCards.forEach(card => {
                card.addEventListener('click', function() {
                    this.classList.add('loading');
                });
            });
        });
    </script>
@endsection
