@once
    @push('styles')
        <style>
            .hrd-admin-project-form {
                --hrd-af-radius: 0.75rem;
                --hrd-af-primary: #2563eb;
                --hrd-af-primary-dark: #1d4ed8;
                --hrd-af-ring: rgb(59 130 246 / 0.25);
                --hrd-af-border: #e2e8f0;
                --hrd-af-label: #334155;
            }

            .hrd-af-input {
                width: 100%;
                border-radius: var(--hrd-af-radius);
                border: 1px solid var(--hrd-af-border);
                background: #fff;
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
                line-height: 1.25rem;
            }

            .hrd-af-input:focus {
                outline: none;
                border-color: var(--hrd-af-primary);
                box-shadow: 0 0 0 2px var(--hrd-af-ring);
            }

            .hrd-af-input:disabled {
                background: #f8fafc;
            }

            .hrd-af-label {
                display: block;
                margin-bottom: 0.25rem;
                font-size: 0.75rem;
                font-weight: 600;
                color: var(--hrd-af-label);
            }

            .hrd-af-section + .hrd-af-section {
                margin-top: 0.75rem;
            }

            .hrd-af-section--collapsible > summary {
                list-style: none;
            }

            .hrd-af-section--collapsible[open] > summary {
                border-bottom-width: 1px;
            }

            .hrd-af-section--collapsible[open] > summary .js-section-chevron {
                transform: rotate(180deg);
            }

            .hrd-af-section--collapsible:not([open]) > summary {
                border-bottom-width: 0;
            }

            .hrd-date-collapse > summary {
                list-style: none;
                cursor: pointer;
            }

            .hrd-date-collapse > summary::-webkit-details-marker {
                display: none;
            }

            .hrd-date-collapse[open] .js-date-chevron {
                transform: rotate(180deg);
            }

            .hrd-time-collapse > summary {
                list-style: none;
                cursor: pointer;
            }

            .hrd-time-collapse > summary::-webkit-details-marker {
                display: none;
            }

            .hrd-af-btn-primary {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                border-radius: var(--hrd-af-radius);
                background: var(--hrd-af-primary);
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
                font-weight: 600;
                color: #fff;
            }

            .hrd-af-btn-primary:hover {
                background: var(--hrd-af-primary-dark);
            }

            .hrd-af-btn-secondary {
                display: inline-flex;
                align-items: center;
                gap: 0.375rem;
                border-radius: var(--hrd-af-radius);
                border: 1px solid var(--hrd-af-border);
                background: #fff;
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
                font-weight: 500;
                color: #1d4ed8;
            }

            .hrd-af-btn-secondary:hover {
                background: #eff6ff;
            }

            .hrd-af-btn-add {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                border-radius: var(--hrd-af-radius);
                border: 2px dashed #93c5fd;
                background: #eff6ff;
                padding: 0.625rem 1rem;
                font-size: 0.875rem;
                font-weight: 600;
                color: #1d4ed8;
                transition: background 0.15s ease, border-color 0.15s ease;
            }

            .hrd-af-btn-add:hover {
                background: #dbeafe;
                border-color: #3b82f6;
                color: #1e40af;
            }

            .hrd-af-btn-add-sm {
                border-width: 1px;
                padding: 0.375rem 0.75rem;
                font-size: 0.8125rem;
            }

            .hrd-af-btn-remove {
                display: inline-flex;
                align-items: center;
                gap: 0.35rem;
                border-radius: var(--hrd-af-radius);
                border: 1px solid #fca5a5;
                background: #fff;
                padding: 0.35rem 0.6rem;
                font-size: 0.75rem;
                font-weight: 600;
                color: #dc2626;
                transition: background 0.15s ease;
            }

            .hrd-af-btn-remove:hover {
                background: #fef2f2;
                border-color: #f87171;
            }
        </style>
    @endpush
@endonce
