@once
    @push('styles')
        <style>
            .hrd-admin-project-overview {
                --hrd-ov-radius: 1rem;
            }

            .hrd-ov-action-tile {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                border-radius: var(--hrd-ov-radius);
                border: 1px solid #e2e8f0;
                background: #fff;
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
                font-weight: 600;
                transition: background 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
            }

            .hrd-ov-action-tile i.hrd-ov-action-icon {
                display: flex;
                height: 2.25rem;
                width: 2.25rem;
                flex-shrink: 0;
                align-items: center;
                justify-content: center;
                border-radius: var(--hrd-ov-radius);
                font-size: 0.9rem;
            }

            .hrd-ov-action-tile--registrations {
                border-color: #93c5fd;
                color: #1e40af;
            }

            .hrd-ov-action-tile--registrations:hover {
                background: #eff6ff;
                border-color: #3b82f6;
            }

            .hrd-ov-action-tile--registrations i.hrd-ov-action-icon {
                background: #dbeafe;
                color: #2563eb;
            }

            .hrd-ov-action-tile--approvals {
                border-color: #6ee7b7;
                color: #047857;
            }

            .hrd-ov-action-tile--approvals:hover {
                background: #ecfdf5;
                border-color: #10b981;
            }

            .hrd-ov-action-tile--approvals i.hrd-ov-action-icon {
                background: #d1fae5;
                color: #059669;
            }

            .hrd-ov-action-tile--results {
                border-color: #c4b5fd;
                color: #5b21b6;
            }

            .hrd-ov-action-tile--results:hover {
                background: #f5f3ff;
                border-color: #8b5cf6;
            }

            .hrd-ov-action-tile--results i.hrd-ov-action-icon {
                background: #ede9fe;
                color: #7c3aed;
            }

            .hrd-ov-action-tile--seats {
                border-color: #93c5fd;
                color: #1e40af;
            }

            .hrd-ov-action-tile--seats:hover {
                background: #eff6ff;
                border-color: #3b82f6;
            }

            .hrd-ov-action-tile--seats i.hrd-ov-action-icon {
                background: #dbeafe;
                color: #2563eb;
            }

            .hrd-ov-action-tile--groups {
                border-color: #c4b5fd;
                color: #5b21b6;
            }

            .hrd-ov-action-tile--groups:hover {
                background: #f5f3ff;
                border-color: #8b5cf6;
            }

            .hrd-ov-action-tile--groups i.hrd-ov-action-icon {
                background: #ede9fe;
                color: #7c3aed;
            }

            .hrd-ov-stat {
                border-radius: var(--hrd-ov-radius);
                border: 1px solid #e2e8f0;
                background: #fff;
                padding: 0.875rem 1rem;
            }

            .hrd-ov-export-layout {
                display: grid;
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            @media (min-width: 768px) {
                .hrd-ov-export-layout {
                    grid-template-columns: repeat(2, 1fr);
                }

            }

            .hrd-ov-export-block__title {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                margin-bottom: 0.5rem;
                font-size: 0.6875rem;
                font-weight: 700;
                letter-spacing: 0.06em;
                text-transform: uppercase;
                color: #64748b;
            }

            .hrd-ov-export-block__title i {
                color: #2563eb;
                font-size: 0.75rem;
            }

            @media (min-width: 768px) {
                .hrd-ov-export-block--wide {
                    grid-column: 1 / -1;
                }
            }

            .hrd-ov-export-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            @media (min-width: 640px) {
                .hrd-ov-export-grid--2 {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            a.hrd-ov-export-card {
                display: flex;
                flex-direction: column;
                overflow: hidden;
                border-radius: var(--hrd-ov-radius);
                border: 1px solid #e2e8f0;
                background: #fff;
                text-align: left;
                transition: border-color 0.15s ease, box-shadow 0.15s ease, transform 0.15s ease;
            }

            a.hrd-ov-export-card:hover {
                box-shadow: 0 4px 12px rgb(15 23 42 / 0.08);
                transform: translateY(-1px);
            }

            .hrd-ov-export-card__head {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 0.5rem;
                padding: 0.65rem 0.75rem 0.5rem;
            }

            .hrd-ov-export-card__icon {
                display: flex;
                height: 2.5rem;
                width: 2.5rem;
                align-items: center;
                justify-content: center;
                border-radius: var(--hrd-ov-radius);
                font-size: 1rem;
            }

            .hrd-ov-export-card__format {
                border-radius: 9999px;
                padding: 0.1rem 0.45rem;
                font-size: 0.625rem;
                font-weight: 700;
                letter-spacing: 0.04em;
                text-transform: uppercase;
            }

            .hrd-ov-export-card__body {
                flex: 1;
                padding: 0 0.75rem 0.5rem;
            }

            .hrd-ov-export-card__title {
                font-size: 0.8125rem;
                font-weight: 700;
                line-height: 1.3;
            }

            .hrd-ov-export-card__desc {
                margin-top: 0.2rem;
                font-size: 0.6875rem;
                line-height: 1.35;
                opacity: 0.85;
            }

            .hrd-ov-export-card__foot {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 0.5rem;
                border-top: 1px solid rgb(0 0 0 / 0.06);
                padding: 0.45rem 0.75rem;
                font-size: 0.6875rem;
                font-weight: 600;
            }

            .hrd-ov-export-card--all {
                border-color: #a7f3d0;
            }

            a.hrd-ov-export-card--all:hover {
                border-color: #34d399;
            }

            .hrd-ov-export-card--all .hrd-ov-export-card__stripe {
                height: 3px;
                background: linear-gradient(90deg, #10b981, #34d399);
            }

            .hrd-ov-export-card--all .hrd-ov-export-card__icon {
                background: #d1fae5;
                color: #047857;
            }

            .hrd-ov-export-card--all .hrd-ov-export-card__format {
                background: #ecfdf5;
                color: #047857;
            }

            .hrd-ov-export-card--all .hrd-ov-export-card__title {
                color: #064e3b;
            }

            .hrd-ov-export-card--all .hrd-ov-export-card__desc {
                color: #047857;
            }

            .hrd-ov-export-card--all .hrd-ov-export-card__foot {
                color: #059669;
                background: #f0fdf4;
            }

            .hrd-ov-export-card--dbd {
                border-color: #bfdbfe;
            }

            a.hrd-ov-export-card--dbd:hover {
                border-color: #60a5fa;
            }

            .hrd-ov-export-card--dbd .hrd-ov-export-card__stripe {
                height: 3px;
                background: linear-gradient(90deg, #2563eb, #60a5fa);
            }

            .hrd-ov-export-card--dbd .hrd-ov-export-card__icon {
                background: #dbeafe;
                color: #1d4ed8;
            }

            .hrd-ov-export-card--dbd .hrd-ov-export-card__format {
                background: #eff6ff;
                color: #1d4ed8;
            }

            .hrd-ov-export-card--dbd .hrd-ov-export-card__title {
                color: #1e3a8a;
            }

            .hrd-ov-export-card--dbd .hrd-ov-export-card__desc {
                color: #1d4ed8;
            }

            .hrd-ov-export-card--dbd .hrd-ov-export-card__foot {
                color: #2563eb;
                background: #eff6ff;
            }

            .hrd-ov-export-card--lectures {
                border-color: #bfdbfe;
            }

            a.hrd-ov-export-card--lectures:hover {
                border-color: #60a5fa;
            }

            .hrd-ov-export-card--lectures .hrd-ov-export-card__stripe {
                height: 3px;
                background: linear-gradient(90deg, #2563eb, #60a5fa);
            }

            .hrd-ov-export-card--lectures .hrd-ov-export-card__icon {
                background: #dbeafe;
                color: #1d4ed8;
            }

            .hrd-ov-export-card--lectures .hrd-ov-export-card__format {
                background: #eff6ff;
                color: #1d4ed8;
            }

            .hrd-ov-export-card--lectures .hrd-ov-export-card__title {
                color: #1e3a8a;
            }

            .hrd-ov-export-card--lectures .hrd-ov-export-card__desc {
                color: #1d4ed8;
            }

            .hrd-ov-export-card--lectures .hrd-ov-export-card__foot {
                color: #2563eb;
                background: #eff6ff;
            }

            .hrd-ov-export-card--dms {
                border-color: #fde68a;
            }

            a.hrd-ov-export-card--dms:hover {
                border-color: #fbbf24;
            }

            .hrd-ov-export-card--dms .hrd-ov-export-card__stripe {
                height: 3px;
                background: linear-gradient(90deg, #d97706, #fbbf24);
            }

            .hrd-ov-export-card--dms .hrd-ov-export-card__icon {
                background: #fef3c7;
                color: #b45309;
            }

            .hrd-ov-export-card--dms .hrd-ov-export-card__format {
                background: #fffbeb;
                color: #b45309;
            }

            .hrd-ov-export-card--dms .hrd-ov-export-card__title {
                color: #78350f;
            }

            .hrd-ov-export-card--dms .hrd-ov-export-card__desc {
                color: #b45309;
            }

            .hrd-ov-export-card--dms .hrd-ov-export-card__foot {
                color: #d97706;
                background: #fffbeb;
            }

            .hrd-ov-onebook-panel {
                overflow: hidden;
                border-radius: var(--hrd-ov-radius);
                border: 1px solid #ddd6fe;
                background: linear-gradient(180deg, #faf5ff 0%, #fff 40%);
            }

            .hrd-ov-onebook-panel__top {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: space-between;
                gap: 0.75rem;
                border-bottom: 1px solid #ede9fe;
                padding: 0.75rem;
            }

            .hrd-ov-onebook-panel__settings {
                padding: 0.75rem;
                background: #fff;
            }

            a.hrd-ov-onebook-download {
                display: inline-flex;
                align-items: center;
                gap: 0.35rem;
                border-radius: var(--hrd-ov-radius);
                background: #7c3aed;
                padding: 0.4rem 0.75rem;
                font-size: 0.75rem;
                font-weight: 600;
                color: #fff;
            }

            a.hrd-ov-onebook-download:hover {
                background: #6d28d9;
            }

            .hrd-ov-date > summary {
                list-style: none;
                cursor: pointer;
            }

            .hrd-ov-date > summary::-webkit-details-marker {
                display: none;
            }

            .hrd-ov-date[open] .js-ov-date-chevron {
                transform: rotate(180deg);
            }

            .hrd-ov-pill-on {
                display: inline-flex;
                align-items: center;
                gap: 0.25rem;
                border-radius: 9999px;
                background: #d1fae5;
                padding: 0.15rem 0.5rem;
                font-size: 0.6875rem;
                font-weight: 600;
                color: #065f46;
            }

            .hrd-ov-pill-off {
                display: inline-flex;
                align-items: center;
                gap: 0.25rem;
                border-radius: 9999px;
                background: #f1f5f9;
                padding: 0.15rem 0.5rem;
                font-size: 0.6875rem;
                font-weight: 600;
                color: #64748b;
            }

            .hrd-ov-btn-sm {
                display: inline-flex;
                align-items: center;
                gap: 0.35rem;
                border-radius: var(--hrd-ov-radius);
                border: 1px solid #e2e8f0;
                background: #fff;
                padding: 0.35rem 0.65rem;
                font-size: 0.75rem;
                font-weight: 600;
            }

            .hrd-ov-btn-sm--lecturer {
                border-color: #c4b5fd;
                color: #6d28d9;
            }

            .hrd-ov-btn-sm--lecturer:hover {
                background: #f5f3ff;
            }

            .hrd-ov-btn-sm--excel-reg {
                border-color: #6ee7b7;
                color: #047857;
            }

            .hrd-ov-btn-sm--excel-reg:hover {
                background: #ecfdf5;
            }

            .hrd-ov-btn-sm--excel-lecture {
                border-color: #93c5fd;
                color: #1d4ed8;
            }

            .hrd-ov-btn-sm--excel-lecture:hover {
                background: #eff6ff;
            }

            .hrd-ov-btn-sm--pdf {
                border-color: #fca5a5;
                color: #dc2626;
            }

            .hrd-ov-btn-sm--pdf:hover {
                background: #fef2f2;
            }

            .hrd-ov-btn-edit {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                border-radius: var(--hrd-ov-radius);
                border: 1px solid #fcd34d;
                background: #fef3c7;
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
                font-weight: 600;
                color: #92400e;
            }

            .hrd-ov-btn-edit:hover {
                background: #fde68a;
                border-color: #f59e0b;
            }
        </style>
    @endpush
@endonce
