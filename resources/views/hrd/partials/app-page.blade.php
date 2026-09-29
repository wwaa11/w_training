{{-- Shared HRD user app chrome. Set $appPageClose = true before including to close the page wrapper. --}}
@if (empty($appPageClose))
    <div class="hrd-hospital hrd-page {{ $appBottomClass ?? "pb-10" }} text-slate-800 antialiased">
        @if (! empty($appHeader))
            <header class="{{ ($appHeaderSticky ?? false) ? "sticky top-[var(--nav-h)] z-30 border-b border-slate-200/80 bg-white/95 backdrop-blur-sm shadow-sm" : "border-b border-slate-200 bg-white" }}">
                <div class="mx-auto max-w-3xl px-4 py-4 lg:max-w-4xl {{ ($appHeaderSticky ?? false) ? "py-3" : "sm:py-5" }}">
                    <div class="flex items-start gap-3 sm:items-center sm:justify-between">
                        <div class="flex min-w-0 flex-1 items-start gap-3">
                            @if (! empty($appBackUrl))
                                <a class="hrd-back-btn !h-10 !w-10" href="{{ $appBackUrl }}" aria-label="{{ $appBackLabel ?? "กลับ" }}">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            @endif
                            <div class="min-w-0">
                                @if (! empty($appEyebrow))
                                    <p class="text-[11px] font-semibold uppercase tracking-widest text-blue-600">{{ $appEyebrow }}</p>
                                @endif
                                <h1 class="{{ ($appHeaderSticky ?? false) ? "truncate text-base font-semibold sm:text-lg" : "text-xl font-bold tracking-tight sm:text-2xl" }} text-slate-900">{{ $appTitle }}</h1>
                                @if (! empty($appSubtitle))
                                    <p class="mt-0.5 text-sm text-slate-600 {{ ($appHeaderSticky ?? false) ? "hidden sm:block" : "" }}">{{ $appSubtitle }}</p>
                                @endif
                            </div>
                        </div>
                        @if (! empty($appHeaderEnd))
                            <div class="flex shrink-0 flex-wrap items-center gap-2">
                                {!! $appHeaderEnd !!}
                            </div>
                        @endif
                    </div>
                </div>
            </header>
        @endif
        <main class="mx-auto max-w-3xl space-y-4 px-4 pt-4 lg:max-w-4xl lg:space-y-5 lg:pt-6">
@else
        </main>
    </div>
@endif
