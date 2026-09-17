@extends("layouts.nurse")
@section("content")
    @php
        $totalAvailable = count($departmentArray);
        $totalSelected = count($selected);
        $pct = $totalAvailable > 0 ? (int) round(($totalSelected / $totalAvailable) * 100) : 0;
    @endphp

    <div class="mx-auto max-w-6xl px-3 py-4 sm:px-6 sm:py-8">
        {{-- Header --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="min-w-0 flex-1">
                <a class="mb-3 inline-flex items-center gap-1.5 text-sm text-slate-500 transition hover:text-slate-800" href="{{ route("nurse.admin.score.users") }}">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    รายงานคะแนนรายแผนก
                </a>
                <div class="flex items-start gap-3">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-200">
                        <i class="fa-solid fa-building-user text-lg"></i>
                    </span>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">ตั้งค่าแผนกรายงานคะแนน</h1>
                        <p class="mt-1 max-w-xl text-sm leading-relaxed text-slate-500">
                            กำหนดแผนกที่ปรากฏในหน้ารายงาน · ตัวเลือก <span class="font-medium text-slate-700">กลุ่มแผนก</span> จะรวมทุกแผนกที่เลือกไว้ในตารางเดียว
                        </p>
                    </div>
                </div>
            </div>
            <a class="inline-flex min-h-[44px] shrink-0 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50" href="{{ route("nurse.admin.score.users") }}">
                <i class="fa-solid fa-chart-column text-slate-400"></i>
                เปิดรายงาน
            </a>
        </div>

        @if (session("success"))
            <div class="mb-5 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm text-emerald-900" role="status">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                    <i class="fa-solid fa-check"></i>
                </span>
                <div>
                    <p class="font-semibold">บันทึกสำเร็จ</p>
                    <p class="mt-0.5 text-emerald-800">{{ session("success") }}</p>
                </div>
            </div>
        @endif

        {{-- Stats --}}
        <div class="mb-6 grid grid-cols-2 gap-3 lg:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">เลือกแล้ว</p>
                <p class="mt-1 text-2xl font-bold text-blue-700" id="statSelected">{{ $totalSelected }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">แผนกในระบบ</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($totalAvailable) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">ความครอบคลุม</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ $pct }}<span class="text-base font-semibold text-slate-400">%</span></p>
            </div>
            <div class="col-span-2 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm lg:col-span-1">
                <p class="mb-2 text-xs font-medium uppercase tracking-wide text-slate-400">ความคืบหน้า</p>
                <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 transition-all duration-300" id="progressBar" style="width: {{ $pct }}%"></div>
                </div>
            </div>
        </div>

        <form action="{{ route("nurse.admin.score.departments.store") }}" method="POST" id="deptForm">
            @csrf

            <div class="grid gap-6 lg:grid-cols-[1fr_280px]">
                {{-- Main picker --}}
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-4 py-4 sm:px-5">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 class="text-base font-semibold text-slate-900">เลือกแผนก</h2>
                                <p class="text-xs text-slate-500">ค้นหาแล้วติ๊กเลือกแผนกที่ต้องการแสดงในรายงาน</p>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <button class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100" type="button" id="selectAllBtn">
                                    <i class="fa-solid fa-check-double text-[10px]"></i>
                                    ทั้งหมด
                                </button>
                                <button class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50" type="button" id="clearAllBtn">
                                    <i class="fa-solid fa-eraser text-[10px]"></i>
                                    ล้าง
                                </button>
                                <button class="inline-flex items-center gap-1.5 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 transition hover:bg-blue-100" type="button" id="selectVisibleBtn">
                                    <i class="fa-solid fa-filter text-[10px]"></i>
                                    เลือกที่แสดง
                                </button>
                            </div>
                        </div>
                        <div class="relative mt-4">
                            <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                            <input class="w-full min-h-[44px] rounded-xl border border-slate-300 bg-slate-50/80 py-2.5 pl-10 pr-3 text-sm text-slate-800 transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200" id="deptSearch" type="search" placeholder="ค้นหาชื่อแผนก..." autocomplete="off" aria-label="ค้นหาแผนก">
                        </div>
                    </div>

                    <div class="px-4 py-3 sm:px-5">
                        @if ($totalAvailable === 0)
                            <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-6 py-12 text-center">
                                <i class="fa-solid fa-inbox mb-3 text-3xl text-slate-300"></i>
                                <p class="font-medium text-slate-700">ไม่พบแผนกจากข้อมูลผู้ใช้</p>
                                <p class="mt-1 text-sm text-slate-500">ตรวจสอบว่าผู้ใช้มีฟิลด์แผนกในระบบแล้ว</p>
                            </div>
                        @else
                            <p class="mb-3 text-xs text-slate-400" id="visibleHint">แสดง {{ $totalAvailable }} แผนก</p>
                            <div class="max-h-[min(52vh,480px)] space-y-2 overflow-y-auto pr-1" id="deptList">
                                @foreach ($departmentArray as $dept)
                                    @php
                                        $isOn = in_array($dept, $selected, true);
                                        $staff = (int) ($staffCounts[$dept] ?? 0);
                                    @endphp
                                    <label class="dept-card flex cursor-pointer items-center gap-3 rounded-xl border px-3 py-3 transition {{ $isOn ? "border-blue-200 bg-blue-50/60" : "border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50" }}" data-dept-name="{{ strtolower($dept) }}">
                                        <input class="dept-checkbox h-4 w-4 shrink-0 rounded border-slate-300 text-blue-600 focus:ring-blue-500" type="checkbox" name="departments[]" value="{{ $dept }}" @checked($isOn)>
                                        <span class="flex min-w-0 flex-1 flex-col sm:flex-row sm:items-center sm:justify-between sm:gap-2">
                                            <span class="text-sm font-medium text-slate-900">{{ $dept }}</span>
                                            <span class="inline-flex w-fit items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-600">
                                                <i class="fa-solid fa-user text-[9px]"></i>
                                                {{ number_format($staff) }} คน
                                            </span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            <p class="mt-3 hidden text-center text-sm text-slate-500" id="noMatchHint">ไม่พบแผนกที่ตรงกับคำค้นหา</p>
                        @endif
                    </div>
                </div>

                {{-- Sidebar --}}
                <aside class="space-y-4 lg:sticky lg:top-4 lg:self-start">
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <h3 class="text-sm font-semibold text-slate-900">สรุปการเลือก</h3>
                        <p class="mt-1 text-xs text-slate-500">แผนกที่จะแสดงในตัวกรองรายงาน</p>
                        <ul class="mt-3 max-h-48 space-y-1.5 overflow-y-auto text-xs" id="selectedPreview">
                            @forelse ($selected as $dept)
                                <li class="flex items-center gap-2 rounded-lg bg-slate-50 px-2.5 py-1.5 text-slate-700">
                                    <i class="fa-solid fa-circle-check text-[10px] text-blue-500"></i>
                                    <span class="truncate">{{ $dept }}</span>
                                </li>
                            @empty
                                <li class="rounded-lg border border-dashed border-slate-200 px-3 py-4 text-center text-slate-400" id="emptyPreview">ยังไม่ได้เลือกแผนก</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="rounded-2xl border border-indigo-100 bg-gradient-to-br from-indigo-50 to-blue-50 p-4">
                        <h3 class="flex items-center gap-2 text-sm font-semibold text-indigo-900">
                            <i class="fa-regular fa-circle-question"></i>
                            วิธีใช้งาน
                        </h3>
                        <ol class="mt-3 space-y-2.5 text-xs leading-relaxed text-indigo-900/80">
                            <li class="flex gap-2">
                                <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white/80 text-[10px] font-bold text-indigo-600">1</span>
                                <span>เลือกแผนกที่ต้องการให้แอดมินเห็นในรายงานคะแนน</span>
                            </li>
                            <li class="flex gap-2">
                                <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white/80 text-[10px] font-bold text-indigo-600">2</span>
                                <span>กดบันทึก — รายการจะไปที่หน้ารายงานทันที</span>
                            </li>
                            <li class="flex gap-2">
                                <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white/80 text-[10px] font-bold text-indigo-600">3</span>
                                <span>ใช้ <strong>กลุ่มแผนก</strong> เพื่อดูทุกแผนกที่เลือกในตารางเดียว</span>
                            </li>
                        </ol>
                    </div>

                    <div class="hidden flex-col gap-2 lg:flex">
                        <button class="inline-flex min-h-[48px] w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300" type="submit">
                            <i class="fa-solid fa-floppy-disk"></i>
                            บันทึกแผนกรายงาน
                        </button>
                        <a class="inline-flex min-h-[44px] w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50" href="{{ route("nurse.admin.score.users") }}">
                            ยกเลิก
                        </a>
                    </div>
                </aside>
            </div>

            {{-- Mobile sticky save --}}
            @if ($totalAvailable > 0)
                <div class="fixed inset-x-0 bottom-0 z-30 border-t border-slate-200 bg-white/95 px-4 py-3 shadow-[0_-8px_30px_rgba(15,23,42,0.08)] backdrop-blur-sm lg:hidden">
                    <div class="mx-auto flex max-w-6xl gap-2">
                        <a class="inline-flex min-h-[48px] flex-1 items-center justify-center rounded-xl border border-slate-300 bg-white text-sm font-semibold text-slate-700" href="{{ route("nurse.admin.score.users") }}">
                            ยกเลิก
                        </a>
                        <button class="inline-flex min-h-[48px] flex-[2] items-center justify-center gap-2 rounded-xl bg-blue-600 text-sm font-semibold text-white" type="submit">
                            <i class="fa-solid fa-floppy-disk"></i>
                            บันทึก ({{ $totalSelected }})
                        </button>
                    </div>
                </div>
                <div class="h-20 lg:hidden" aria-hidden="true"></div>
            @endif
        </form>
    </div>
@endsection

@section("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const boxes = document.querySelectorAll('.dept-checkbox');
            const cards = document.querySelectorAll('.dept-card');
            const search = document.getElementById('deptSearch');
            const statSelected = document.getElementById('statSelected');
            const progressBar = document.getElementById('progressBar');
            const visibleHint = document.getElementById('visibleHint');
            const noMatchHint = document.getElementById('noMatchHint');
            const selectedPreview = document.getElementById('selectedPreview');
            const totalAvailable = {{ $totalAvailable }};

            function selectedValues() {
                return Array.from(document.querySelectorAll('.dept-checkbox:checked')).map((el) => el.value);
            }

            function updateCardStyles() {
                cards.forEach((card) => {
                    const box = card.querySelector('.dept-checkbox');
                    if (!box) return;
                    card.classList.toggle('border-blue-200', box.checked);
                    card.classList.toggle('bg-blue-50/60', box.checked);
                    card.classList.toggle('border-slate-200', !box.checked);
                    card.classList.toggle('bg-white', !box.checked);
                });
            }

            function updateStats() {
                const count = selectedValues().length;
                if (statSelected) statSelected.textContent = count;
                const pct = totalAvailable > 0 ? Math.round((count / totalAvailable) * 100) : 0;
                if (progressBar) progressBar.style.width = pct + '%';

                const mobileSave = document.querySelector('.fixed button[type="submit"]');
                if (mobileSave) {
                    mobileSave.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> บันทึก (' + count + ')';
                }

                if (!selectedPreview) return;
                const selected = selectedValues().sort((a, b) => a.localeCompare(b, 'th'));
                selectedPreview.innerHTML = '';
                if (selected.length === 0) {
                    selectedPreview.innerHTML = '<li class="rounded-lg border border-dashed border-slate-200 px-3 py-4 text-center text-slate-400">ยังไม่ได้เลือกแผนก</li>';
                    return;
                }
                selected.forEach((dept) => {
                    const li = document.createElement('li');
                    li.className = 'flex items-center gap-2 rounded-lg bg-slate-50 px-2.5 py-1.5 text-slate-700';
                    li.innerHTML = '<i class="fa-solid fa-circle-check text-[10px] text-blue-500"></i><span class="truncate"></span>';
                    li.querySelector('span').textContent = dept;
                    selectedPreview.appendChild(li);
                });
            }

            function applySearch() {
                const q = (search?.value || '').trim().toLowerCase();
                let visible = 0;
                cards.forEach((card) => {
                    const name = card.getAttribute('data-dept-name') || '';
                    const show = q === '' || name.includes(q);
                    card.classList.toggle('hidden', !show);
                    if (show) visible += 1;
                });
                if (visibleHint) {
                    visibleHint.textContent = q
                        ? `แสดง ${visible} จาก ${totalAvailable} แผนก`
                        : `แสดง ${totalAvailable} แผนก`;
                }
                if (noMatchHint) {
                    noMatchHint.classList.toggle('hidden', visible > 0);
                }
            }

            boxes.forEach((box) => {
                box.addEventListener('change', function() {
                    updateCardStyles();
                    updateStats();
                });
            });

            search?.addEventListener('input', applySearch);

            document.getElementById('selectAllBtn')?.addEventListener('click', function() {
                boxes.forEach((box) => { box.checked = true; });
                updateCardStyles();
                updateStats();
            });

            document.getElementById('clearAllBtn')?.addEventListener('click', function() {
                boxes.forEach((box) => { box.checked = false; });
                updateCardStyles();
                updateStats();
            });

            document.getElementById('selectVisibleBtn')?.addEventListener('click', function() {
                cards.forEach((card) => {
                    if (card.classList.contains('hidden')) return;
                    const box = card.querySelector('.dept-checkbox');
                    if (box) box.checked = true;
                });
                updateCardStyles();
                updateStats();
            });

            applySearch();
            updateStats();
        });
    </script>
@endsection
