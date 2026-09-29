{{-- Shared app theme (slate + blue) for HRD, Nurse, and related modules. --}}
@once
    @push('styles')
        <style>
            .hrd-hospital {
                --hrd-bg: rgb(248 250 252 / 0.8);
                --hrd-primary: #2563eb;
                --hrd-primary-dark: #1d4ed8;
                --hrd-primary-light: #eff6ff;
                --hrd-emerald: #059669;
                --hrd-emerald-dark: #047857;
                --hrd-slate-border: #e2e8f0;
                --hrd-radius: 0.75rem;
                --hrd-radius-lg: 1rem;
                --hrd-ring: rgb(59 130 246 / 0.25);
            }

            body:has(.hrd-hospital) {
                background: var(--hrd-bg);
            }

            body:has(.hrd-hospital) .main-content {
                background: transparent;
            }

            .hrd-hospital details summary:focus-visible {
                outline: 2px solid var(--hrd-primary);
                outline-offset: 2px;
                border-radius: var(--hrd-radius);
            }

            .hrd-page {
                min-height: 50vh;
            }

            .hrd-card,
            .hrd-user-card {
                border-radius: var(--hrd-radius-lg);
                border: 1px solid var(--hrd-slate-border);
                background: #fff;
                box-shadow: 0 1px 2px rgb(15 23 42 / 0.04);
            }

            .hrd-panel {
                overflow: hidden;
                border-radius: var(--hrd-radius-lg);
                border: 1px solid var(--hrd-slate-border);
                background: #fff;
                box-shadow: 0 1px 2px rgb(15 23 42 / 0.04);
            }

            .hrd-back-btn {
                display: flex;
                height: 2.75rem;
                width: 2.75rem;
                flex-shrink: 0;
                align-items: center;
                justify-content: center;
                border-radius: var(--hrd-radius-lg);
                background: #fff;
                color: var(--hrd-primary);
                box-shadow: 0 0 0 1px var(--hrd-slate-border);
                transition: background 0.15s ease;
            }

            .hrd-back-btn:hover {
                background: var(--hrd-primary-light);
            }

            .hrd-btn-primary {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                border-radius: var(--hrd-radius-lg);
                background: var(--hrd-primary);
                padding: 0.625rem 1rem;
                font-size: 0.875rem;
                font-weight: 600;
                color: #fff;
                transition: background 0.15s ease;
            }

            .hrd-btn-primary:hover {
                background: var(--hrd-primary-dark);
            }

            .hrd-btn-secondary {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                border-radius: var(--hrd-radius-lg);
                border: 1px solid var(--hrd-slate-border);
                background: #fff;
                padding: 0.625rem 1rem;
                font-size: 0.875rem;
                font-weight: 600;
                color: #1d4ed8;
                transition: background 0.15s ease;
            }

            .hrd-btn-secondary:hover {
                background: var(--hrd-primary-light);
            }

            .hrd-btn-success {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                border-radius: var(--hrd-radius-lg);
                background: var(--hrd-emerald);
                padding: 0.625rem 1rem;
                font-size: 0.875rem;
                font-weight: 600;
                color: #fff;
            }

            .hrd-btn-success:hover {
                background: var(--hrd-emerald-dark);
            }

            .hrd-input {
                width: 100%;
                min-height: 2.75rem;
                border-radius: var(--hrd-radius-lg);
                border: 1px solid var(--hrd-slate-border);
                background: #fff;
                padding: 0.5rem 0.875rem;
                font-size: 0.875rem;
            }

            .hrd-input:focus {
                outline: none;
                border-color: var(--hrd-primary);
                box-shadow: 0 0 0 2px var(--hrd-ring);
            }

            .hrd-search-wrap {
                position: relative;
            }

            .hrd-search-wrap__icon {
                pointer-events: none;
                position: absolute;
                top: 0;
                bottom: 0;
                left: 0;
                display: flex;
                align-items: center;
                padding-left: 0.875rem;
                color: #94a3b8;
                font-size: 0.875rem;
            }

            .hrd-search-wrap .hrd-input {
                padding-left: 2.5rem;
            }

            .hrd-alert {
                display: flex;
                align-items: flex-start;
                gap: 0.625rem;
                border-radius: var(--hrd-radius-lg);
                border: 1px solid var(--hrd-slate-border);
                padding: 0.875rem 1rem;
                font-size: 0.875rem;
            }

            /* Tailwind .hidden loses to .hrd-alert { display:flex } without this */
            .hrd-alert.hidden {
                display: none !important;
            }

            .hrd-alert--success {
                border-color: #a7f3d0;
                background: #ecfdf5;
                color: #065f46;
            }

            .hrd-alert--error {
                border-color: #fecaca;
                background: #fef2f2;
                color: #991b1b;
            }

            .hrd-alert--warning {
                border-color: #fde68a;
                background: #fffbeb;
                color: #92400e;
            }

            .hrd-table-wrap {
                overflow: hidden;
                border-radius: var(--hrd-radius-lg);
                border: 1px solid var(--hrd-slate-border);
            }

            .hrd-table-wrap thead {
                background: #f8fafc;
            }

            .hrd-stat-card {
                border-radius: var(--hrd-radius-lg);
                border: 1px solid var(--hrd-slate-border);
                background: #fff;
                padding: 1rem 1.25rem;
                box-shadow: 0 1px 2px rgb(15 23 42 / 0.04);
            }

            .hrd-stat-card--blue {
                border-width: 2px;
                border-color: #2563eb;
                background: #fff;
                color: #0f172a;
            }

            .hrd-stat-card--emerald {
                border-width: 2px;
                border-color: #059669;
                background: #fff;
                color: #0f172a;
            }

            .hrd-stat-card--violet {
                border-width: 2px;
                border-color: #7c3aed;
                background: #fff;
                color: #0f172a;
            }

            .hrd-stat-card--amber {
                border-width: 2px;
                border-color: #d97706;
                background: #fff;
                color: #0f172a;
            }

            .hrd-hero-banner {
                overflow: hidden;
                border-radius: var(--hrd-radius-lg);
                border: 1px solid #1d4ed8;
                background: linear-gradient(135deg, #2563eb, #1e40af);
                color: #fff;
                box-shadow: 0 4px 14px rgb(37 99 235 / 0.25);
            }

            body.hrd-shell .main-content,
            body.app-shell .main-content {
                max-width: none;
                padding: 0;
                background: transparent;
            }

            body.hrd-shell .main-content > *,
            body.app-shell .main-content > * {
                margin-bottom: 0;
            }

            body.app-shell .main-content:not(:has(.hrd-page)) {
                padding: 1rem 0.75rem 1.5rem;
            }

            @media (min-width: 640px) {
                body.app-shell .main-content:not(:has(.hrd-page)) {
                    padding: 1.25rem 1rem 2rem;
                }
            }

            .hrd-reg-actions-spacer {
                height: 5.5rem;
            }

            .hrd-reg-actions-bar {
                position: fixed;
                right: 0;
                bottom: 0;
                left: 0;
                z-index: 40;
                border-top: 1px solid var(--hrd-slate-border);
                background: rgb(255 255 255 / 0.96);
                padding: 0.75rem 1rem;
                padding-bottom: max(0.75rem, env(safe-area-inset-bottom));
                box-shadow: 0 -8px 24px rgb(15 23 42 / 0.08);
                backdrop-filter: blur(10px);
            }

            .hrd-reg-actions-bar__inner {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                margin: 0 auto;
                max-width: 56rem;
            }

            .hrd-reg-date summary:focus-visible {
                outline: 2px solid var(--hrd-primary);
                outline-offset: -2px;
            }
        </style>
    @endpush
@endonce
