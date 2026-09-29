@extends("layouts.hrd")

@section("content")
    @php
        $isAuto = $project->usesAutoGroupMode();
    @endphp

    <div class="hrd-hospital hrd-page min-h-screen">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            @include("hrd.partials.admin-page-header", [
                "backUrl" => route("hrd.admin.projects.show", $project->id),
                "title" => "จัดการกลุ่ม",
                "subtitle" => $project->project_name,
            ])

            {{-- Mode selection --}}
            <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">
                    <i class="fas fa-sliders-h mr-2 text-blue-600"></i>
                    โหมดการจัดกลุ่ม
                </h2>
                <p class="mt-1 text-sm text-slate-600">เลือกวิธีจัดกลุ่มสำหรับโปรเจกต์นี้ (กลุ่มแยกตามวันและช่วงเวลาลงทะเบียน)</p>

                <form class="mt-5 grid gap-4 md:grid-cols-2" action="{{ route("hrd.admin.projects.groups.mode", $project->id) }}" method="POST">
                    @csrf
                    <label class="relative cursor-pointer rounded-xl border-2 p-5 transition {{ ! $isAuto ? "border-blue-500 bg-blue-50/50 ring-2 ring-blue-200" : "border-slate-200 hover:border-slate-300" }}">
                        <input class="sr-only" type="radio" name="project_group_mode" value="manual" {{ ! $isAuto ? "checked" : "" }} onchange="this.form.submit()">
                        <div class="flex items-start gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
                                <i class="fas fa-user-cog"></i>
                            </span>
                            <div>
                                <p class="font-semibold text-slate-900">กำหนดเอง (Manual)</p>
                                <p class="mt-1 text-sm text-slate-600">สร้างกลุ่มต่อช่วงเวลา พร้อมจำนวนที่นั่ง แล้วมอบหมายสมาชิก / นำเข้า Excel</p>
                            </div>
                        </div>
                    </label>

                    <label class="relative cursor-pointer rounded-xl border-2 p-5 transition {{ $isAuto ? "border-violet-500 bg-violet-50/50 ring-2 ring-violet-200" : "border-slate-200 hover:border-slate-300" }}">
                        <input class="sr-only" type="radio" name="project_group_mode" value="auto" {{ $isAuto ? "checked" : "" }} onchange="this.form.submit()">
                        <div class="flex items-start gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-violet-100 text-violet-700">
                                <i class="fas fa-magic"></i>
                            </span>
                            <div>
                                <p class="font-semibold text-slate-900">อัตโนมัติ (Auto)</p>
                                <p class="mt-1 text-sm text-slate-600">สร้างชื่อกลุ่มต่อช่วงเวลา ระบบจัดกลุ่มเมื่อลงทะเบียนในรอบนั้น</p>
                            </div>
                        </div>
                    </label>
                </form>
            </div>

            @if ($timeSlots->isEmpty())
                <div class="mt-8 rounded-2xl border border-amber-200 bg-amber-50 p-6 text-amber-950">
                    <p class="font-semibold"><i class="fas fa-calendar-times mr-2"></i>ยังไม่มีวันและช่วงเวลาในโปรเจกต์</p>
                    <p class="mt-2 text-sm text-amber-900">เพิ่มวันที่และช่วงเวลาก่อนสร้างกลุ่ม — กลุ่มจัดแยกตามแต่ละช่วงลงทะเบียน</p>
                </div>
            @else
            {{-- Workflow overview --}}
            <div class="mt-6 rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 to-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">ลำดับการทำงาน</p>
                <ol class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <li class="flex gap-3 rounded-xl border border-slate-200 bg-white p-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">1</span>
                        <div class="min-w-0 text-sm">
                            <p class="font-semibold text-slate-900">เลือกช่วงเวลา</p>
                            <p class="mt-0.5 text-slate-600">กลุ่มแยกตามรอบลงทะเบียน</p>
                        </div>
                    </li>
                    <li class="flex gap-3 rounded-xl border border-slate-200 bg-white p-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-sm font-bold text-white">2</span>
                        <div class="min-w-0 text-sm">
                            <p class="font-semibold text-slate-900">สร้างกลุ่ม</p>
                            <p class="mt-0.5 text-slate-600">ชื่อกลุ่ม (ที่นั่งไม่บังคับ)</p>
                        </div>
                    </li>
                    <li class="flex gap-3 rounded-xl border border-slate-200 bg-white p-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full {{ $isAuto ? "bg-violet-600" : "bg-blue-600" }} text-sm font-bold text-white">3</span>
                        <div class="min-w-0 text-sm">
                            @if ($isAuto)
                                <p class="font-semibold text-slate-900">รอลงทะเบียน / สุ่มใหม่</p>
                                <p class="mt-0.5 text-slate-600">ระบบจัดให้เมื่อลงทะเบียน หรือกดสุ่มทั้งช่วง</p>
                            @else
                                <p class="font-semibold text-slate-900">ใส่สมาชิก</p>
                                <p class="mt-0.5 text-slate-600">ทีละคน หรือนำเข้า Excel</p>
                            @endif
                        </div>
                    </li>
                    <li class="flex gap-3 rounded-xl border border-slate-200 bg-white p-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-700 text-sm font-bold text-white">4</span>
                        <div class="min-w-0 text-sm">
                            <p class="font-semibold text-slate-900">ตรวจสอบผล</p>
                            <p class="mt-0.5 text-slate-600">ดูผู้ลงทะเบียนในกลุ่ม — เอาออกจากกลุ่มได้ที่ขั้นตอน 4</p>
                        </div>
                    </li>
                </ol>
            </div>

            {{-- Step 1: Time slot --}}
            <section class="mt-6 overflow-hidden rounded-2xl border-2 border-blue-200 bg-white shadow-sm" aria-labelledby="grp-step-1">
                <div class="flex flex-wrap items-center gap-3 border-b border-blue-100 bg-blue-50/80 px-5 py-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">1</span>
                    <h2 class="text-base font-semibold text-slate-900" id="grp-step-1">เลือกวันและช่วงเวลา</h2>
                </div>
                <div class="p-5">
                    <form method="GET" action="{{ route("hrd.admin.projects.groups.index", $project->id) }}">
                        <label class="mb-2 block text-sm font-medium text-slate-700" for="time_slot_filter">ช่วงที่ต้องการจัดกลุ่ม</label>
                        <select class="w-full max-w-2xl rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" id="time_slot_filter" name="time_id" onchange="this.form.submit()">
                            @foreach ($timeSlots as $slot)
                                <option value="{{ $slot["time_id"] }}" {{ (int) $selectedTimeId === (int) $slot["time_id"] ? "selected" : "" }}>
                                    {{ $slot["label"] }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                    <div class="mt-4 flex flex-wrap gap-2 text-sm">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-3 py-1 font-medium text-blue-900">
                            <i class="fas fa-user-check text-xs"></i> ลงทะเบียน {{ $stats["registered_users"] }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 font-medium text-emerald-900">
                            <i class="fas fa-users text-xs"></i> จัดกลุ่มแล้ว {{ $stats["assigned_users"] }}
                        </span>
                        @if ($isAuto)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 font-medium text-amber-900">
                                <i class="fas fa-user-clock text-xs"></i> ยังไม่มีกลุ่ม {{ $stats["unassigned_users"] }}
                            </span>
                        @endif
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-violet-100 px-3 py-1 font-medium text-violet-900">
                            <i class="fas fa-layer-group text-xs"></i> กลุ่ม {{ $stats["group_count"] }}
                        </span>
                    </div>
                </div>
            </section>

            <div class="mt-6 grid gap-6 lg:grid-cols-2">
                {{-- Step 2: Create groups --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="grp-step-2">
                    <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50 px-5 py-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-600 text-sm font-bold text-white">2</span>
                        <h2 class="text-base font-semibold text-slate-900" id="grp-step-2">สร้างกลุ่มในช่วงนี้</h2>
                    </div>
                    <div class="p-5">
                        <p class="text-sm text-slate-600">
                            ตั้งชื่อกลุ่มก่อนมอบหมายสมาชิก — จำนวนที่นั่งไม่บังคับ (เว้นว่าง = ไม่จำกัด)
                        </p>

                        <form class="mt-4 space-y-4" action="{{ route("hrd.admin.projects.groups.definitions.store", $project->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="time_id" value="{{ $selectedTimeId }}">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700" for="group_name">ชื่อกลุ่ม</label>
                                <input class="@error("name") border-red-400 @else border-slate-200 @enderror w-full rounded-xl border px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" id="group_name" type="text" name="name" placeholder="เช่น กลุ่ม A" value="{{ old("name") }}" required>
                                @error("name")
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700" for="max_members">
                                    จำนวนที่นั่ง (Slot)
                                    <span class="font-normal text-slate-500">(ไม่บังคับ)</span>
                                </label>
                                <input class="@error("max_members") border-red-400 @else border-slate-200 @enderror w-full rounded-xl border px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" id="max_members" type="number" name="max_members" min="1" step="1" placeholder="เว้นว่าง = ไม่จำกัด" value="{{ old("max_members") }}">
                                @error("max_members")
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <button class="w-full rounded-xl bg-emerald-600 px-4 py-2.5 font-medium text-white transition hover:bg-emerald-700" type="submit">
                                <i class="fas fa-plus mr-2"></i>สร้างกลุ่ม
                            </button>
                        </form>

                        @if ($otherSlotCount > 0 && $groupDefinitions->isNotEmpty())
                            <form
                                class="js-swal-confirm mt-4 border-t border-slate-100 pt-4"
                                action="{{ route("hrd.admin.projects.groups.copy_to_all_slots", $project->id) }}"
                                method="POST"
                                data-confirm-title="คัดลอกกลุ่มไปทุกช่วงเวลา?"
                                data-confirm-message="คัดลอก {{ $groupDefinitions->count() }} กลุ่มจากช่วงที่เลือกไปอีก {{ $otherSlotCount }} ช่วงเวลา (ชื่อกลุ่มและจำนวนที่นั่ง) — ไม่คัดลอกสมาชิก กลุ่มที่มีชื่อเดิมในช่วงปลายทางจะถูกอัปเดตจำนวนที่นั่ง"
                                data-confirm-icon="question"
                                data-confirm-button="ใช่, คัดลอก"
                                data-confirm-color="#0d9488"
                            >
                                @csrf
                                <input type="hidden" name="time_id" value="{{ $selectedTimeId }}">
                                <button class="w-full rounded-xl border border-teal-200 bg-teal-50 px-4 py-2.5 text-sm font-medium text-teal-900 transition hover:bg-teal-100" type="submit">
                                    <i class="fas fa-copy mr-2"></i>ทางลัด: คัดลอกกลุ่มไปอีก {{ $otherSlotCount }} ช่วง
                                </button>
                            </form>
                        @endif

                        @include("hrd.admin.projects.participants.partials.group-definition-quick-list", [
                            "project" => $project,
                            "groupDefinitions" => $groupDefinitions,
                            "isAuto" => $isAuto,
                        ])
                    </div>
                </section>

                {{-- Step 3: Mode-specific actions --}}
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="grp-step-3">
                    <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50 px-5 py-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full {{ $isAuto ? "bg-violet-600" : "bg-blue-600" }} text-sm font-bold text-white">3</span>
                        <h2 class="text-base font-semibold text-slate-900" id="grp-step-3">
                            @if ($isAuto)
                                จัดกลุ่มอัตโนมัติ
                            @else
                                มอบหมายสมาชิก
                            @endif
                        </h2>
                    </div>
                    <div class="p-5">
                        @if ($isAuto)
                            <div class="rounded-xl border border-violet-200 bg-violet-50/80 p-4 text-sm text-violet-950">
                                <p class="font-medium">เมื่อผู้ใช้ลงทะเบียนช่วงนี้</p>
                                <p class="mt-1 text-violet-900">ระบบจะจัดเข้ากลุ่มที่ยังไม่มีสมาชิกแผนกเดียวกัน (แยกแผนกละกลุ่มเมื่อทำได้) หากทุกกลุ่มที่ว่างมีแผนกนี้แล้วหรือที่นั่งไม่พอ ผู้ลงทะเบียนจะยังไม่มีกลุ่มจนกว่าจะเพิ่มกลุ่ม/ที่นั่งหรือสุ่มใหม่</p>
                            </div>
                            <form class="js-swal-confirm mt-4" action="{{ route("hrd.admin.projects.groups.rerandom", $project->id) }}" method="POST" data-confirm-title="สุ่มจัดกลุ่มใหม่?" data-confirm-message="จัดกลุ่มใหม่สำหรับผู้ลงทะเบียนในช่วงนี้ {{ $stats["registered_users"] }} คน การกระทำนี้ไม่สามารถย้อนกลับได้" data-confirm-icon="question" data-confirm-button="ใช่, สุ่มใหม่" data-confirm-color="#7c3aed">
                                @csrf
                                <input type="hidden" name="time_id" value="{{ $selectedTimeId }}">
                                <button class="w-full rounded-xl bg-violet-600 px-4 py-2.5 font-medium text-white transition hover:bg-violet-700 disabled:cursor-not-allowed disabled:opacity-50" type="submit" {{ $stats["registered_users"] < 1 ? "disabled" : "" }}>
                                    <i class="fas fa-dice mr-2"></i>สุ่มจัดกลุ่มใหม่ทั้งช่วง
                                </button>
                            </form>
                            @if ($stats["registered_users"] < 1)
                                <p class="mt-2 text-xs text-slate-500">ใช้ได้เมื่อมีผู้ลงทะเบียนในช่วงนี้อย่างน้อย 1 คน</p>
                            @elseif ($groupDefinitions->isEmpty())
                                <p class="mt-2 text-xs text-amber-700">สร้างกลุ่มในขั้นตอน 2 ก่อน — ไม่มีกลุ่มให้จัดสรร</p>
                            @endif
                        @else
                            @if ($groupDefinitions->isEmpty())
                                <p class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                                    <i class="fas fa-arrow-up mr-1"></i>สร้างกลุ่มในขั้นตอน 2 ก่อน จึงจะเพิ่มสมาชิกหรือนำเข้า Excel ได้
                                </p>
                            @else
                                <form class="space-y-4" action="{{ route("hrd.admin.projects.groups.store", $project->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="time_id" value="{{ $selectedTimeId }}">
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-slate-700" for="user_id">รหัสพนักงาน</label>
                                        <input class="@error("user_id") border-red-400 @else border-slate-200 @enderror w-full rounded-xl border px-4 py-2.5" id="user_id" type="text" name="user_id" value="{{ old("user_id") }}" required>
                                        <p class="mt-1 text-xs text-slate-500">จัดกลุ่มได้ก่อนลงทะเบียนเข้าร่วม</p>
                                        @error("user_id")
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-slate-700" for="group_definition_id">กลุ่ม</label>
                                        <select class="@error("group_definition_id") border-red-400 @else border-slate-200 @enderror w-full rounded-xl border px-4 py-2.5" id="group_definition_id" name="group_definition_id" required>
                                            <option value="" disabled {{ old("group_definition_id") ? "" : "selected" }}>เลือกกลุ่ม</option>
                                            @foreach ($groupDefinitions as $groupData)
                                                @php $def = $groupData["definition"]; @endphp
                                                <option value="{{ $def->id }}" {{ (string) old("group_definition_id") === (string) $def->id ? "selected" : "" }} {{ $def->hasCapacity() ? "" : "disabled" }}>
                                                    {{ $def->name }}
                                                    @if ($def->max_members)
                                                        ({{ $groupData["count"] }}/{{ $def->max_members }})
                                                    @else
                                                        ({{ $groupData["count"] }} คน)
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error("group_definition_id")
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button class="w-full rounded-xl bg-blue-600 px-4 py-2.5 font-medium text-white hover:bg-blue-700" type="submit">
                                        <i class="fas fa-user-plus mr-2"></i>เพิ่มเข้ากลุ่ม
                                    </button>
                                </form>

                                <div class="mt-6 border-t border-slate-100 pt-5">
                                    <p class="text-sm font-medium text-slate-800"><i class="fas fa-file-excel mr-2 text-emerald-600"></i>นำเข้าหลายคนด้วย Excel</p>
                                    <p class="mt-1 text-xs text-slate-500">คอลัมน์: รหัสพนักงาน + ชื่อกลุ่ม (ต้องมีกลุ่มในขั้นตอน 2 แล้ว)</p>
                                    <a class="mt-3 inline-flex w-full items-center justify-center rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-medium text-slate-800 hover:bg-slate-200" href="{{ route("hrd.admin.projects.groups.template", [$project->id, "time_id" => $selectedTimeId]) }}">
                                        <i class="fas fa-download mr-2"></i>ดาวน์โหลดเทมเพลต
                                    </a>
                                    <form class="mt-3 space-y-3" action="{{ route("hrd.admin.projects.groups.import", $project->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="time_id" value="{{ $selectedTimeId }}">
                                        <input class="w-full text-sm" type="file" name="import_file" accept=".xlsx,.xls,.csv" required>
                                        <button class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700" type="submit">
                                            <i class="fas fa-upload mr-2"></i>นำเข้า Excel
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endif
                    </div>
                </section>
            </div>

            {{-- Step 4: Results --}}
            <section class="mt-6 space-y-6" aria-labelledby="grp-step-4">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-700 text-sm font-bold text-white">4</span>
                    <h2 class="text-lg font-semibold text-slate-900" id="grp-step-4">ตรวจสอบผลลัพธ์</h2>
                </div>

                @if ($isAuto)
                    <details class="group overflow-hidden rounded-2xl border border-amber-200 bg-white shadow-sm">
                        <summary class="cursor-pointer list-none border-b border-amber-100 bg-amber-50/60 px-5 py-4 hover:bg-amber-50 sm:flex sm:items-center sm:justify-between sm:gap-4">
                            <div class="flex min-w-0 flex-1 items-center justify-between gap-3 sm:justify-start">
                                <div>
                                    <h3 class="font-semibold text-amber-950">
                                        <i class="fas fa-user-clock mr-2 text-amber-600"></i>
                                        ลงทะเบียนแล้วแต่ยังไม่มีกลุ่ม
                                    </h3>
                                    <p class="mt-1 text-sm text-amber-900/90">รวมทั้งหมด <span class="font-bold tabular-nums">{{ $unassignedRegisteredTotal }}</span> คนในช่วงนี้</p>
                                </div>
                                <div class="flex shrink-0 items-center gap-2">
                                    @if ($unassignedRegisteredTotal > 0)
                                        <span class="inline-flex rounded-full bg-amber-200 px-3 py-1 text-sm font-bold text-amber-950">{{ $unassignedRegisteredTotal }}</span>
                                    @endif
                                    <i class="fas fa-chevron-down text-amber-700/70 transition group-open:rotate-180"></i>
                                </div>
                            </div>
                        </summary>
                        @if ($unassignedRegisteredTotal === 0)
                            <p class="px-5 py-8 text-center text-sm text-slate-500">ผู้ลงทะเบียนทุกคนมีกลุ่มแล้ว</p>
                        @else
                            <p class="border-b border-amber-50 bg-white px-5 py-2 text-xs text-slate-500">แสดง 10 รายการแรกจาก {{ $unassignedRegisteredTotal }} คน</p>
                            <ul class="divide-y divide-slate-100">
                                @foreach ($unassignedRegisteredPreview as $user)
                                    <li class="flex flex-col gap-1 px-5 py-3 sm:flex-row sm:items-center sm:justify-between">
                                        <div>
                                            <p class="font-medium text-slate-900">{{ $user->name }}</p>
                                            <p class="text-sm text-slate-500">{{ $user->userid }}</p>
                                        </div>
                                        <p class="text-sm text-slate-600">{{ $user->department ?? "ไม่ระบุแผนก" }}</p>
                                    </li>
                                @endforeach
                            </ul>
                            @if ($unassignedRegisteredTotal > 10)
                                <p class="border-t border-slate-100 bg-slate-50 px-5 py-3 text-center text-xs text-slate-600">
                                    และอีก {{ $unassignedRegisteredTotal - 10 }} คน — กดสุ่มจัดกลุ่มใหม่ทั้งช่วง (ขั้นตอน 3) หรือเพิ่มที่นั่งในกลุ่ม
                                </p>
                            @endif
                        @endif
                    </details>
                @endif

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-5 py-4">
                        <h3 class="font-semibold text-slate-900">
                            <i class="fas fa-clipboard-list mr-2 text-slate-500"></i>
                            ผู้ลงทะเบียนในกลุ่ม
                        </h3>
                        <p class="mt-1 text-sm text-slate-500">แสดงเฉพาะผู้ที่ถูกจัดกลุ่มแล้ว — แก้ไข/ลบกลุ่มทำที่ขั้นตอน 2</p>
                    </div>

                        @if ($groupDefinitions->isEmpty())
                            <div class="px-6 py-16 text-center">
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                    <i class="fas fa-users text-2xl"></i>
                                </div>
                                <p class="mt-4 font-medium text-slate-700">ยังไม่มีกลุ่มในช่วงนี้</p>
                                <p class="mt-1 text-sm text-slate-500">เริ่มที่ขั้นตอน 2 — สร้างกลุ่ม</p>
                            </div>
                        @else
                            <div class="divide-y divide-slate-100">
                                @foreach ($groupDefinitions as $groupData)
                                    @php
                                        $def = $groupData["definition"];
                                        $members = $groupData["members"];
                                    @endphp
                                    <details class="group">
                                        <summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-5 py-4 hover:bg-slate-50">
                                            <div class="flex min-w-0 items-center gap-3">
                                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-sm font-bold text-blue-700">
                                                    {{ mb_substr($def->name, 0, 1) }}
                                                </span>
                                                <div class="min-w-0">
                                                    <p class="font-semibold text-slate-900">{{ $def->name }}</p>
                                                    <p class="text-xs text-slate-500">
                                                        {{ $groupData["count"] }} คน
                                                        @if ($def->max_members)
                                                            · ที่นั่ง {{ $groupData["count"] }}/{{ $def->max_members }}
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <i class="fas fa-chevron-down shrink-0 text-slate-400 transition group-open:rotate-180"></i>
                                        </summary>
                                        <div class="border-t border-slate-100 bg-slate-50/40 px-5 py-4">
                                            @if ($members->isEmpty())
                                                <p class="text-sm text-slate-500">ยังไม่มีผู้ลงทะเบียนในกลุ่มนี้</p>
                                            @else
                                                <ul class="space-y-2">
                                                    @foreach ($members as $member)
                                                        <li class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3">
                                                            <div class="min-w-0">
                                                                <p class="font-medium text-slate-900">{{ $member->user->name }}</p>
                                                                <p class="text-sm text-slate-500">{{ $member->user->userid }}</p>
                                                                @if ($member->user->department)
                                                                    <p class="text-xs text-slate-500">{{ $member->user->department }}</p>
                                                                @endif
                                                            </div>
                                                            <form class="js-swal-confirm shrink-0" action="{{ route("hrd.admin.projects.groups.delete", [$project->id, $member->id]) }}" method="POST" data-confirm-title="เอาออกจากกลุ่ม?" data-confirm-message="เอา {{ $member->user->name }} ({{ $member->user->userid }}) ออกจากกลุ่ม {{ $def->name }}? (ยังลงทะเบียนช่วงนี้อยู่)" data-confirm-icon="warning" data-confirm-button="ใช่, เอาออก" onclick="event.stopPropagation()">
                                                                @csrf
                                                                @method("DELETE")
                                                                <button class="inline-flex items-center gap-1 rounded-lg border border-red-200 bg-white px-2.5 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50" type="submit" title="เอาออกจากกลุ่ม">
                                                                    <i class="fas fa-user-minus"></i>
                                                                    <span class="hidden sm:inline">เอาออกจากกลุ่ม</span>
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    </details>
                                @endforeach
                            </div>
                        @endif
                </div>
            </section>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session("success"))
                Swal.fire({
                    icon: 'success',
                    title: @json(session("success")),
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: 'ตกลง',
                });
            @endif

            @if (session("error"))
                Swal.fire({
                    icon: 'error',
                    title: @json(session("error")),
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: 'ตกลง',
                });
            @endif

            @if (session("warning"))
                Swal.fire({
                    icon: 'warning',
                    title: @json(session("warning")),
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: 'ตกลง',
                });
            @endif

            @if (session("import_errors"))
                @php
                    $importErrorsHtml = '<ul style="text-align:left;margin:0;padding-left:1.25rem;">';
                    foreach (session("import_errors") as $importError) {
                        $importErrorsHtml .= "<li>" . e($importError) . "</li>";
                    }
                    $importErrorsHtml .= "</ul>";
                @endphp
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาดในการนำเข้า',
                    html: @json($importErrorsHtml),
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: 'ตกลง',
                });
            @endif

            document.querySelectorAll('form.js-swal-confirm').forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();

                    const title = form.getAttribute('data-confirm-title') || 'ยืนยัน?';
                    const message = form.getAttribute('data-confirm-message') || '';
                    const icon = form.getAttribute('data-confirm-icon') || 'warning';
                    const confirmButton = form.getAttribute('data-confirm-button') || 'ยืนยัน';
                    const confirmColor = form.getAttribute('data-confirm-color') || '#dc2626';

                    Swal.fire({
                        title: title,
                        text: message,
                        icon: icon,
                        showCancelButton: true,
                        confirmButtonColor: confirmColor,
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: confirmButton,
                        cancelButtonText: 'ยกเลิก',
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
