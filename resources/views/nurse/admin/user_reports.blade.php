@extends("layouts.nurse")
@section("content")
    @php
        $hasDepartment = filled($department) && $department !== "null";
        $userCount = 0;
        $scoreSum = 0;
        $topScore = 0;
        foreach ($data as $deptUsers) {
            $userCount += count($deptUsers);
            foreach ($deptUsers as $u) {
                $scoreSum += (int) ($u["total"] ?? 0);
                $topScore = max($topScore, (int) ($u["total"] ?? 0));
            }
        }
        $avgScore = $userCount > 0 ? round($scoreSum / $userCount, 1) : 0;
        $projectCount = $projects->count();
    @endphp

    <div class="mx-auto max-w-[1600px] px-3 py-4 sm:px-6 sm:py-6">
        {{-- Header --}}
        <div class="mb-5 flex flex-col gap-3 sm:mb-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <a class="mb-2 inline-flex items-center gap-1.5 text-sm text-slate-500 transition hover:text-slate-800" href="{{ route("nurse.admin.index") }}">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    กลับไปจัดการโครงการ
                </a>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">รายงานคะแนนรายแผนก</h1>
                <p class="mt-1 text-sm text-slate-500">ดูสรุปคะแนนการอบรมและวิทยากร ตามปีและแผนก</p>
            </div>
            @if ($hasDepartment && $userCount > 0)
                <button class="download-table-btn inline-flex min-h-[44px] cursor-pointer items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-300" type="button" data-table-id="table-0" aria-label="ส่งออก Excel">
                    <i class="fa-solid fa-file-excel"></i>
                    ส่งออก Excel
                </button>
            @endif
        </div>

        {{-- Filters --}}
        <div class="mb-5 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
            <div class="mb-3 flex items-center gap-2 text-sm font-semibold text-slate-700">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                    <i class="fa-solid fa-filter text-sm"></i>
                </span>
                ตัวกรองรายงาน
            </div>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700" for="selectYear">ปีงบประมาณ / ปีโครงการ</label>
                    <select class="w-full min-h-[44px] cursor-pointer rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" id="selectYear" onchange="changeFilter()">
                        @forelse ($years as $y)
                            <option value="{{ $y }}" @if ((int) $year === (int) $y) selected @endif>
                                พ.ศ. {{ $y + 543 }} (ค.ศ. {{ $y }})
                            </option>
                        @empty
                            <option value="{{ $year }}" selected>พ.ศ. {{ $year + 543 }} (ค.ศ. {{ $year }})</option>
                        @endforelse
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700" for="selectDepartment">แผนก</label>
                    <select class="w-full min-h-[44px] cursor-pointer rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" id="selectDepartment" onchange="changeFilter()">
                        <option value="" disabled @if (!$hasDepartment) selected @endif>เลือกแผนกเพื่อดูรายงาน</option>
                        @foreach ($departmentArray as $dept)
                            <option value="{{ $dept }}" @if ($department == $dept) selected @endif>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if ($hasDepartment)
                <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-4">
                    <span class="text-xs font-medium uppercase tracking-wide text-slate-400">กำลังดู</span>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">
                        <i class="fa-regular fa-calendar"></i>
                        พ.ศ. {{ $year + 543 }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">
                        <i class="fa-solid fa-building"></i>
                        {{ $department }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">
                        <i class="fa-solid fa-folder-open"></i>
                        {{ $projectCount }} โครงการ
                    </span>
                </div>
            @endif
        </div>

        @if (!$hasDepartment)
            {{-- Guided empty state --}}
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center shadow-sm">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                    <i class="fa-solid fa-chart-column text-2xl"></i>
                </div>
                <h2 class="text-lg font-semibold text-slate-900">เริ่มต้นดูรายงาน</h2>
                <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-slate-500">
                    เลือกปีและแผนกด้านบน เพื่อแสดงตารางคะแนนพนักงานในแผนกนั้น สำหรับโครงการในปีที่เลือกเท่านั้น
                </p>
            </div>
        @elseif ($userCount === 0)
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center shadow-sm">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-50 text-amber-600">
                    <i class="fa-solid fa-inbox text-2xl"></i>
                </div>
                <h2 class="text-lg font-semibold text-slate-900">ไม่พบข้อมูล</h2>
                <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-slate-500">
                    ไม่มีพนักงานในแผนกนี้ หรือไม่มีคะแนนในปี พ.ศ. {{ $year + 543 }}
                </p>
            </div>
        @elseif ($projectCount === 0)
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center shadow-sm">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-50 text-amber-600">
                    <i class="fa-solid fa-folder-open text-2xl"></i>
                </div>
                <h2 class="text-lg font-semibold text-slate-900">ไม่มีโครงการในปีนี้</h2>
                <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-slate-500">
                    ไม่พบโครงการที่เปิดใช้งานในปี พ.ศ. {{ $year + 543 }} — ลองเลือกปีอื่น
                </p>
            </div>
        @else
            {{-- Summary --}}
            <div class="mb-5 grid grid-cols-2 gap-3 lg:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="text-xs font-medium text-slate-500">พนักงาน</div>
                    <div class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($userCount) }}</div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="text-xs font-medium text-slate-500">โครงการในปีนี้</div>
                    <div class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($projectCount) }}</div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="text-xs font-medium text-slate-500">คะแนนเฉลี่ย</div>
                    <div class="mt-1 text-2xl font-bold text-slate-900">{{ $avgScore }}</div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="text-xs font-medium text-slate-500">คะแนนสูงสุด</div>
                    <div class="mt-1 text-2xl font-bold text-blue-700">{{ number_format($topScore) }}</div>
                </div>
            </div>

            {{-- Table tools --}}
            <div class="mb-3 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="relative w-full sm:max-w-sm">
                    <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                    <input class="w-full min-h-[44px] rounded-xl border border-slate-300 bg-white py-2.5 pl-9 pr-3 text-sm text-slate-800 transition placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" id="rowSearch" type="search" placeholder="ค้นหารหัส / ชื่อพนักงาน..." autocomplete="off" aria-label="ค้นหาพนักงาน">
                </div>
                <div class="relative w-full sm:max-w-sm">
                    <i class="fa-solid fa-table pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                    <input class="w-full min-h-[44px] rounded-xl border border-slate-300 bg-white py-2.5 pl-9 pr-3 text-sm text-slate-800 transition placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" id="colSearch" type="search" placeholder="กรองคอลัมน์โครงการ..." autocomplete="off" aria-label="กรองคอลัมน์โครงการ">
                </div>
            </div>

            <p class="mb-3 text-xs text-slate-500">
                <i class="fa-solid fa-arrows-left-right mr-1"></i>
                เลื่อนตารางแนวนอนเพื่อดูโครงการทั้งหมด · คอลัมน์ข้อมูลพนักงานและ Total ถูกตรึงไว้
            </p>

            @foreach ($data as $key => $deptUsers)
                <div class="mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-col gap-3 border-b border-slate-100 px-4 py-3 sm:flex-row sm:items-center sm:justify-between sm:px-5">
                        <div>
                            <h2 class="text-base font-semibold text-slate-900 sm:text-lg">{{ $key }}</h2>
                            <p class="text-xs text-slate-500">เรียงตามคะแนนรวมจากมากไปน้อย · {{ count($deptUsers) }} คน</p>
                        </div>
                        <button class="download-table-btn inline-flex min-h-[40px] cursor-pointer items-center justify-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-200 sm:hidden" type="button" data-table-id="table-{{ $loop->index }}">
                            <i class="fa-solid fa-file-excel"></i>
                            Export
                        </button>
                    </div>

                    <div class="report-scroll overflow-auto">
                        <table class="exportable-table report-table w-full min-w-max border-collapse text-sm" id="table-{{ $loop->index }}">
                            <thead>
                                <tr>
                                    <th class="sticky-col sticky-col-1">รหัส</th>
                                    <th class="sticky-col sticky-col-2">ชื่อ - สกุล</th>
                                    <th class="sticky-col sticky-col-3">ตำแหน่ง</th>
                                    @foreach ($projects as $project)
                                        <th class="project-col score-head" data-project-title="{{ strtolower($project->title) }}" title="{{ $project->title }}">
                                            <span class="project-title">{{ $project->title }}</span>
                                        </th>
                                    @endforeach
                                    <th class="sticky-tail lecture-head" title="คะแนนวิทยากร">วิทยากร</th>
                                    <th class="sticky-tail sticky-total total-head">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deptUsers as $user)
                                    <tr class="report-row" data-search="{{ strtolower($user["user"] . " " . $user["name"]) }}">
                                        <td class="sticky-col sticky-col-1 font-mono text-xs text-slate-600">{{ $user["user"] }}</td>
                                        <td class="sticky-col sticky-col-2 font-medium text-slate-900">{{ $user["name"] }}</td>
                                        <td class="sticky-col sticky-col-3 text-slate-600">{{ $user["position"] }}</td>
                                        @foreach ($projects as $project)
                                            @php $val = $user[$project->title] ?? null; @endphp
                                            <td class="project-col score-cell text-center" data-project-title="{{ strtolower($project->title) }}">
                                                @if ($val)
                                                    <span class="score-pill">{{ $val }}</span>
                                                @else
                                                    <span class="score-empty">·</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="sticky-tail text-center">
                                            @if ($user["lecture"])
                                                <span class="lecture-pill">{{ $user["lecture"] }}</span>
                                            @else
                                                <span class="score-empty">·</span>
                                            @endif
                                        </td>
                                        <td class="sticky-tail sticky-total text-center">
                                            <span class="total-pill">{{ $user["total"] }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="border-t border-slate-100 px-4 py-2 text-xs text-slate-400" id="rowFilterHint">
                        แสดงทั้งหมด {{ count($deptUsers) }} รายการ
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <style>
        .report-scroll {
            max-height: min(70vh, 720px);
        }

        .report-table th,
        .report-table td {
            border-bottom: 1px solid #e2e8f0;
            border-right: 1px solid #f1f5f9;
            padding: 0.55rem 0.65rem;
            white-space: nowrap;
            background: #fff;
        }

        .report-table thead th {
            position: sticky;
            top: 0;
            z-index: 20;
            background: #f8fafc;
            color: #334155;
            font-size: 0.75rem;
            font-weight: 600;
            text-align: left;
            box-shadow: inset 0 -1px 0 #e2e8f0;
        }

        .report-table tbody tr:hover td {
            background: #f8fafc;
        }

        .report-table tbody tr:hover .sticky-col,
        .report-table tbody tr:hover .sticky-tail {
            background: #f1f5f9;
        }

        .sticky-col,
        .sticky-tail {
            position: sticky;
            z-index: 10;
            background: #fff;
        }

        thead .sticky-col,
        thead .sticky-tail {
            z-index: 30;
            background: #f8fafc;
        }

        .sticky-col-1 {
            left: 0;
            min-width: 5.5rem;
            box-shadow: 1px 0 0 #e2e8f0;
        }

        .sticky-col-2 {
            left: 5.5rem;
            min-width: 10rem;
            max-width: 14rem;
            box-shadow: 1px 0 0 #e2e8f0;
        }

        .sticky-col-3 {
            left: 15.5rem;
            min-width: 8rem;
            max-width: 11rem;
            box-shadow: 4px 0 8px -4px rgba(15, 23, 42, 0.12);
        }

        .sticky-tail.lecture-head,
        td.sticky-tail:not(.sticky-total) {
            right: 4.5rem;
            min-width: 4.5rem;
            box-shadow: -1px 0 0 #e2e8f0;
        }

        .sticky-total {
            right: 0;
            min-width: 4.5rem;
            box-shadow: -4px 0 8px -4px rgba(15, 23, 42, 0.12);
        }

        thead .sticky-total,
        td.sticky-total {
            background: #eff6ff;
        }

        .score-head {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            height: 10rem;
            max-width: 2.75rem;
            min-width: 2.5rem;
            padding: 0.5rem 0.25rem !important;
            text-align: left;
            vertical-align: bottom;
            font-weight: 500 !important;
            color: #64748b !important;
        }

        .project-title {
            display: -webkit-box;
            -webkit-line-clamp: 6;
            -webkit-box-orient: vertical;
            overflow: hidden;
            max-height: 9rem;
            line-height: 1.25;
            font-size: 0.7rem;
        }

        .score-cell {
            min-width: 2.5rem;
        }

        .score-pill {
            display: inline-flex;
            min-width: 1.5rem;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: #ecfdf5;
            color: #047857;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.1rem 0.4rem;
        }

        .lecture-pill {
            display: inline-flex;
            min-width: 1.5rem;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: #fef3c7;
            color: #b45309;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.1rem 0.4rem;
        }

        .total-pill {
            display: inline-flex;
            min-width: 1.75rem;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            background: #2563eb;
            color: #fff;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 0.15rem 0.45rem;
        }

        .score-empty {
            color: #cbd5e1;
            font-weight: 500;
        }

        .project-col.is-hidden,
        .report-row.is-hidden {
            display: none !important;
        }

        @media (max-width: 768px) {
            .sticky-col-2,
            .sticky-col-3 {
                position: static;
                box-shadow: none;
                max-width: none;
            }

            .sticky-col-1 {
                left: 0;
            }

            .sticky-tail.lecture-head,
            td.sticky-tail:not(.sticky-total) {
                position: static;
                box-shadow: none;
            }

            .score-head {
                height: 7.5rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                transition: none !important;
            }
        }
    </style>
@endsection

@section("scripts")
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script>
        function changeFilter() {
            const year = document.getElementById('selectYear').value;
            const deptSelect = document.getElementById('selectDepartment');
            const dept = deptSelect.options[deptSelect.selectedIndex]?.value || '';

            Swal.fire({
                title: 'กำลังโหลดรายงาน...',
                text: 'โปรดรอสักครู่',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading(),
            });

            const params = new URLSearchParams({ year });
            if (dept) {
                params.set('department', dept);
            }

            window.location.replace('{{ route("nurse.admin.score.users") }}?' + params.toString());
        }

        function downloadTableAsExcel(tableId, filename) {
            const table = document.getElementById(tableId);
            if (!table) return;
            const wb = XLSX.utils.table_to_book(table, { sheet: 'Sheet1' });
            XLSX.writeFile(wb, filename);
        }

        function applyFilters() {
            const rowQuery = (document.getElementById('rowSearch')?.value || '').trim().toLowerCase();
            const colQuery = (document.getElementById('colSearch')?.value || '').trim().toLowerCase();

            document.querySelectorAll('.report-row').forEach((row) => {
                const hay = row.getAttribute('data-search') || '';
                row.classList.toggle('is-hidden', rowQuery !== '' && !hay.includes(rowQuery));
            });

            document.querySelectorAll('.project-col').forEach((cell) => {
                const title = cell.getAttribute('data-project-title') || '';
                cell.classList.toggle('is-hidden', colQuery !== '' && !title.includes(colQuery));
            });

            const visibleRows = document.querySelectorAll('.report-row:not(.is-hidden)').length;
            const hint = document.getElementById('rowFilterHint');
            if (hint) {
                hint.textContent = rowQuery || colQuery
                    ? `แสดง ${visibleRows} รายการตามตัวกรอง`
                    : `แสดงทั้งหมด ${document.querySelectorAll('.report-row').length} รายการ`;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const rowSearch = document.getElementById('rowSearch');
            const colSearch = document.getElementById('colSearch');
            if (rowSearch) rowSearch.addEventListener('input', applyFilters);
            if (colSearch) colSearch.addEventListener('input', applyFilters);

            document.querySelectorAll('.download-table-btn').forEach((btn) => {
                btn.addEventListener('click', function() {
                    const tableId = btn.getAttribute('data-table-id');
                    const deptSelect = document.getElementById('selectDepartment');
                    const yearSelect = document.getElementById('selectYear');
                    let deptName = 'report';
                    if (deptSelect && deptSelect.selectedIndex >= 0) {
                        deptName = deptSelect.options[deptSelect.selectedIndex].text.trim().replace(/\s+/g, '_');
                    }
                    const yearVal = yearSelect ? yearSelect.value : '';
                    const today = new Date();
                    const dateStr = [
                        today.getFullYear(),
                        String(today.getMonth() + 1).padStart(2, '0'),
                        String(today.getDate()).padStart(2, '0'),
                    ].join('');
                    downloadTableAsExcel(tableId, `${deptName}_${yearVal}_${dateStr}.xlsx`);
                });
            });
        });
    </script>
@endsection
