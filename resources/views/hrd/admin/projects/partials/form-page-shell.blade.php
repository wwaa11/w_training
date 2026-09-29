@include('hrd.admin.projects.partials.form-styles')

<div class="hrd-hospital hrd-admin-project-form hrd-page mx-auto max-w-4xl px-4 py-6 text-slate-800 sm:px-6">
    <header class="mb-4 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
        <div class="flex min-w-0 items-center gap-3">
            <a class="hrd-back-btn" href="{{ $backUrl }}" aria-label="กลับ">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div class="min-w-0">
                <p class="text-[11px] font-semibold uppercase tracking-widest text-blue-600">HRD Admin</p>
                <h1 class="truncate text-lg font-bold text-slate-900 sm:text-xl">{{ $pageTitle }}</h1>
            </div>
        </div>
        @if (! empty($headerActions))
            <div class="flex shrink-0 items-center gap-2">{!! $headerActions !!}</div>
        @endif
    </header>

    @if ($errors->any())
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-800">
            <p class="font-semibold">กรุณาแก้ไขข้อผิดพลาดต่อไปนี้</p>
            <ul class="mt-1 list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{ $slot }}
</div>
