@extends("layouts.hrd")

@section("content")
    @php
        $isAuto = $project->project_group_mode === "auto";
    @endphp

    <div class="hrd-hospital hrd-page min-h-screen">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            @include("hrd.partials.admin-page-header", [
                "backUrl" => route("hrd.admin.projects.show", $project->id),
                "title" => "จัดการกลุ่ม",
                "subtitle" => $project->project_name,
            ])

            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 {{ $isAuto ? "lg:grid-cols-4" : "lg:grid-cols-3" }}">
                <div class="rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 p-5 text-white shadow-sm">
                    <p class="text-sm font-medium text-blue-100">ลงทะเบียนแล้ว</p>
                    <p class="mt-1 text-3xl font-bold">{{ $stats["registered_users"] }}</p>
                </div>
                <div class="rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 p-5 text-white shadow-sm">
                    <p class="text-sm font-medium text-emerald-100">จัดกลุ่มแล้ว</p>
                    <p class="mt-1 text-3xl font-bold">{{ $stats["assigned_users"] }}</p>
                </div>
                @if ($isAuto)
                    <div class="rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 p-5 text-white shadow-sm">
                        <p class="text-sm font-medium text-amber-100">ยังไม่มีกลุ่ม</p>
                        <p class="mt-1 text-3xl font-bold">{{ $stats["unassigned_users"] }}</p>
                    </div>
                @endif
                <div class="rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 p-5 text-white shadow-sm">
                    <p class="text-sm font-medium text-violet-100">จำนวนกลุ่ม</p>
                    <p class="mt-1 text-3xl font-bold">{{ $stats["group_count"] }}</p>
                </div>
            </div>

            {{-- Mode selection --}}
            <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">
                    <i class="fas fa-sliders-h mr-2 text-blue-600"></i>
                    โหมดการจัดกลุ่ม
                </h2>
                <p class="mt-1 text-sm text-slate-600">เลือกวิธีจัดกลุ่มสำหรับโปรเจกต์นี้</p>

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
                                <p class="mt-1 text-sm text-slate-600">สร้างกลุ่มพร้อมจำนวนที่นั่ง (Slot) แล้วผู้ดูแลมอบหมายสมาชิก / นำเข้า Excel</p>
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
                                <p class="mt-1 text-sm text-slate-600">สร้างชื่อกลุ่มล่วงหน้า (ไม่บังคับ Slot) ระบบจัดกลุ่มอัตโนมัติเมื่อผู้ใช้ลงทะเบียน หากที่นั่งไม่พอจะยังไม่มีกลุ่มจนกว่าจะสุ่มใหม่</p>
                            </div>
                        </div>
                    </label>
                </form>
            </div>

            <div class="mt-8 grid gap-8 lg:grid-cols-5">
                <div class="space-y-6 lg:col-span-2">
                    {{-- Create group --}}
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="flex items-center text-lg font-semibold text-slate-900">
                            <i class="fas fa-layer-group mr-2 text-emerald-600"></i>
                            สร้างกลุ่ม
                        </h2>
                        <p class="mt-1 text-sm text-slate-600">
                            @if ($isAuto)
                                กำหนดชื่อกลุ่มก่อน — จำนวนที่นั่ง (Slot) เป็นตัวเลือก สามารถแก้ไขภายหลังได้
                            @else
                                ระบุชื่อกลุ่มและจำนวนที่นั่ง (Slot) ที่รับได้
                            @endif
                        </p>

                        <form class="mt-5 space-y-4" action="{{ route("hrd.admin.projects.groups.definitions.store", $project->id) }}" method="POST">
                            @csrf
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
                                    @if ($isAuto)
                                        <span class="font-normal text-slate-500">(ไม่บังคับ)</span>
                                    @endif
                                </label>
                                <input class="@error("max_members") border-red-400 @else border-slate-200 @enderror w-full rounded-xl border px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" id="max_members" type="number" name="max_members" min="1" placeholder="{{ $isAuto ? "เว้นว่าง = ไม่จำกัด" : "เช่น 10" }}" value="{{ old("max_members") }}" {{ $isAuto ? "" : "required" }}>
                                @error("max_members")
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button class="w-full rounded-xl bg-emerald-600 px-4 py-2.5 font-medium text-white transition hover:bg-emerald-700" type="submit">
                                <i class="fas fa-plus mr-2"></i>สร้างกลุ่ม
                            </button>
                        </form>
                    </div>

                    @if (! $isAuto)
                        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h2 class="flex items-center text-lg font-semibold text-slate-900">
                                <i class="fas fa-user-plus mr-2 text-blue-600"></i>
                                เพิ่มสมาชิก
                            </h2>
                            <form class="mt-5 space-y-4" action="{{ route("hrd.admin.projects.groups.store", $project->id) }}" method="POST">
                                @csrf
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
                                <button class="w-full rounded-xl bg-blue-600 px-4 py-2.5 font-medium text-white hover:bg-blue-700" type="submit">เพิ่มเข้ากลุ่ม</button>
                            </form>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h2 class="flex items-center text-lg font-semibold text-slate-900">
                                <i class="fas fa-file-excel mr-2 text-blue-600"></i>
                                นำเข้า Excel
                            </h2>
                            <p class="mt-1 text-sm text-slate-600">สร้างกลุ่มก่อน แล้วนำเข้ารหัสพนักงานกับชื่อกลุ่ม</p>
                            <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                                <a class="inline-flex flex-1 items-center justify-center rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-medium text-slate-800 hover:bg-slate-200" href="{{ route("hrd.admin.projects.groups.template", $project->id) }}">
                                    <i class="fas fa-download mr-2"></i>เทมเพลต
                                </a>
                            </div>
                            <form class="mt-4 space-y-3" action="{{ route("hrd.admin.projects.groups.import", $project->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input class="w-full text-sm" type="file" name="import_file" accept=".xlsx,.xls,.csv" required>
                                <button class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700" type="submit">
                                    <i class="fas fa-upload mr-2"></i>นำเข้า
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="rounded-2xl border border-violet-200 bg-violet-50 p-5 text-sm text-violet-900">
                            <p class="font-medium"><i class="fas fa-info-circle mr-2"></i>โหมดอัตโนมัติ</p>
                            <p class="mt-2 text-violet-800">เมื่อลงทะเบียน ระบบจัดกลุ่มให้อัตโนมัติ (แยกแผนกเดียวกันเมื่อเป็นไปได้) ใช้สุ่มจัดกลุ่มใหม่เพื่อจัดสรรผู้ที่ยังไม่มีกลุ่มหรือจัดใหม่ทั้งหมด</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h2 class="flex items-center text-lg font-semibold text-slate-900">
                                <i class="fas fa-random mr-2 text-violet-600"></i>
                                สุ่มจัดกลุ่มใหม่
                            </h2>
                            <p class="mt-1 text-sm text-slate-600">สุ่มจัดกลุ่มให้ผู้ลงทะเบียนทั้งหมด — หากที่นั่งรวมน้อยกว่าจำนวนผู้ลงทะเบียน ผู้ที่เหลือจะอยู่ในสถานะยังไม่มีกลุ่ม</p>
                            <form class="js-swal-confirm mt-4" action="{{ route("hrd.admin.projects.groups.rerandom", $project->id) }}" method="POST" data-confirm-title="สุ่มจัดกลุ่มใหม่?" data-confirm-message="จัดกลุ่มใหม่สำหรับผู้ลงทะเบียนทั้งหมด {{ $stats["registered_users"] }} คน การกระทำนี้ไม่สามารถย้อนกลับได้" data-confirm-icon="question" data-confirm-button="ใช่, สุ่มใหม่" data-confirm-color="#7c3aed">
                                @csrf
                                <button class="w-full rounded-xl bg-violet-600 px-4 py-2.5 font-medium text-white transition hover:bg-violet-700 disabled:cursor-not-allowed disabled:opacity-50" type="submit" {{ $stats["registered_users"] < 1 ? "disabled" : "" }}>
                                    <i class="fas fa-dice mr-2"></i>สุ่มจัดกลุ่มใหม่ทั้งหมด
                                </button>
                            </form>
                            @if ($stats["registered_users"] < 1)
                                <p class="mt-2 text-xs text-slate-500">ต้องมีผู้ลงทะเบียนอย่างน้อย 1 คน</p>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="space-y-6 lg:col-span-3">
                    @if ($isAuto)
                        <div class="rounded-2xl border border-amber-200 bg-white shadow-sm">
                            <div class="border-b border-amber-100 bg-amber-50/60 px-6 py-4">
                                <h2 class="text-lg font-semibold text-amber-950">
                                    <i class="fas fa-user-clock mr-2 text-amber-600"></i>
                                    ลงทะเบียนแล้วแต่ยังไม่มีกลุ่ม
                                    <span class="ml-2 rounded-full bg-amber-200 px-2.5 py-0.5 text-sm font-medium text-amber-900">{{ $unassignedRegisteredUsers->count() }}</span>
                                </h2>
                            </div>
                            @if ($unassignedRegisteredUsers->isEmpty())
                                <p class="px-6 py-8 text-center text-sm text-slate-500">ผู้ลงทะเบียนทุกคนมีกลุ่มแล้ว</p>
                            @else
                                <ul class="divide-y divide-slate-100">
                                    @foreach ($unassignedRegisteredUsers as $user)
                                        <li class="flex flex-col gap-1 px-6 py-3 sm:flex-row sm:items-center sm:justify-between">
                                            <div>
                                                <p class="font-medium text-slate-900">{{ $user->name }}</p>
                                                <p class="text-sm text-slate-500">{{ $user->userid }}</p>
                                            </div>
                                            <p class="text-sm text-slate-600">{{ $user->department ?? "ไม่ระบุแผนก" }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif

                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-100 px-6 py-4">
                            <h2 class="text-lg font-semibold text-slate-900">
                                <i class="fas fa-users mr-2 text-slate-500"></i>
                                รายการกลุ่ม
                            </h2>
                        </div>

                        @if ($groupDefinitions->isEmpty())
                            <div class="px-6 py-16 text-center">
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                    <i class="fas fa-users text-2xl"></i>
                                </div>
                                <p class="mt-4 font-medium text-slate-700">ยังไม่มีกลุ่ม</p>
                                <p class="mt-1 text-sm text-slate-500">สร้างกลุ่มแรกจากแผงด้านซ้าย</p>
                            </div>
                        @else
                            <div class="divide-y divide-slate-100">
                                @foreach ($groupDefinitions as $groupData)
                                    @php
                                        $def = $groupData["definition"];
                                        $members = $groupData["members"];
                                        $full = $def->max_members && $groupData["count"] >= $def->max_members;
                                        $deleteGroupConfirm = $members->isNotEmpty()
                                            ? "ลบกลุ่ม {$def->name} และสมาชิก {$groupData["count"]} คน? การกระทำนี้ไม่สามารถย้อนกลับได้"
                                            : "ลบกลุ่ม {$def->name}?";
                                    @endphp
                                    <details class="group">
                                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-6 py-4 hover:bg-slate-50">
                                            <div class="flex min-w-0 items-center gap-3">
                                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 font-bold text-blue-700">
                                                    {{ mb_substr($def->name, 0, 1) }}
                                                </span>
                                                <div class="min-w-0">
                                                    <p class="truncate font-semibold text-slate-900">{{ $def->name }}</p>
                                                    <p class="text-sm text-slate-500">
                                                        @if ($def->max_members)
                                                            {{ $groupData["count"] }} / {{ $def->max_members }} ที่นั่ง
                                                        @else
                                                            {{ $groupData["count"] }} สมาชิก · ไม่จำกัดที่นั่ง
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex shrink-0 items-center gap-2">
                                                @if ($full)
                                                    <span class="rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800">เต็ม</span>
                                                @endif
                                                <form class="js-swal-confirm" action="{{ route("hrd.admin.projects.groups.definitions.delete", [$project->id, $def->id]) }}" method="POST" data-confirm-title="ลบกลุ่ม?" data-confirm-message="{{ $deleteGroupConfirm }}" data-confirm-icon="warning" data-confirm-button="ใช่, ลบกลุ่ม" onclick="event.stopPropagation()">
                                                    @csrf
                                                    @method("DELETE")
                                                    <button class="rounded-lg p-2 text-slate-400 hover:bg-red-50 hover:text-red-600" type="submit" title="ลบกลุ่ม">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                <i class="fas fa-chevron-down text-slate-400 transition group-open:rotate-180"></i>
                                            </div>
                                        </summary>
                                        <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4">
                                            <form class="mb-4 flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 ring-1 ring-slate-200/80 sm:flex-row sm:items-end" action="{{ route("hrd.admin.projects.groups.definitions.update", [$project->id, $def->id]) }}" method="POST">
                                                @csrf
                                                @method("PUT")
                                                <div class="flex-1">
                                                    <label class="mb-1 block text-xs font-medium text-slate-600">จำนวนที่นั่ง (Slot)</label>
                                                    <input class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" type="number" name="max_members" min="{{ max(1, $groupData["count"]) }}" placeholder="{{ $isAuto ? "ไม่จำกัด" : "จำนวนที่นั่ง" }}" value="{{ $def->max_members }}">
                                                    <p class="mt-1 text-xs text-slate-500">
                                                        @if ($isAuto)
                                                            เว้นว่างเพื่อไม่จำกัดที่นั่ง
                                                        @else
                                                            ต้องไม่น้อยกว่าจำนวนสมาชิกปัจจุบัน ({{ $groupData["count"] }})
                                                        @endif
                                                    </p>
                                                </div>
                                                <button class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white hover:bg-slate-900" type="submit">บันทึก Slot</button>
                                            </form>

                                            @if ($members->isEmpty())
                                                <p class="text-sm text-slate-500">ยังไม่มีสมาชิกในกลุ่มนี้</p>
                                            @else
                                                <ul class="space-y-2">
                                                    @foreach ($members as $member)
                                                        <li class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-3 ring-1 ring-slate-200/80">
                                                            <div>
                                                                <p class="font-medium text-slate-900">{{ $member->user->name }}</p>
                                                                <p class="text-sm text-slate-500">{{ $member->user->userid }}</p>
                                                            </div>
                                                            <form class="js-swal-confirm" action="{{ route("hrd.admin.projects.groups.delete", [$project->id, $member->id]) }}" method="POST" data-confirm-title="ลบสมาชิกจากกลุ่ม?" data-confirm-message="ลบ {{ $member->user->name }} ({{ $member->user->userid }}) ออกจากกลุ่ม {{ $def->name }}?" data-confirm-icon="warning" data-confirm-button="ใช่, ลบ">
                                                                @csrf
                                                                @method("DELETE")
                                                                <button class="rounded-lg p-2 text-red-500 hover:bg-red-50" type="submit" title="ลบสมาชิก">
                                                                    <i class="fas fa-times"></i>
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
                </div>
            </div>
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
