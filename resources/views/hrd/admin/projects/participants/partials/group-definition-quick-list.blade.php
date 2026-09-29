@php
    /** @var \Illuminate\Support\Collection $groupDefinitions */
    /** @var bool $isAuto */
@endphp

@if ($groupDefinitions->isNotEmpty())
    <div class="mt-5 border-t border-slate-100 pt-5">
        <div class="mb-3 flex items-center justify-between gap-2">
            <p class="text-sm font-semibold text-slate-900">กลุ่มในช่วงนี้</p>
            <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700">{{ $groupDefinitions->count() }}</span>
        </div>
        <ul class="space-y-2">
            @foreach ($groupDefinitions as $groupData)
                @php
                    $def = $groupData["definition"];
                    $deleteGroupConfirm = $groupData["count"] > 0
                        ? "ลบกลุ่ม {$def->name} และสมาชิก {$groupData["count"]} คน? การกระทำนี้ไม่สามารถย้อนกลับได้"
                        : "ลบกลุ่ม {$def->name}?";
                @endphp
                <li class="rounded-xl border border-slate-200 bg-slate-50/50 p-3">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="truncate font-semibold text-slate-900">{{ $def->name }}</p>
                            <p class="text-xs text-slate-500">
                                @if ($def->max_members)
                                    {{ $groupData["count"] }}/{{ $def->max_members }} ที่นั่ง
                                @else
                                    {{ $groupData["count"] }} สมาชิก · ไม่จำกัด
                                @endif
                            </p>
                        </div>
                        <form class="js-swal-confirm shrink-0" action="{{ route("hrd.admin.projects.groups.definitions.delete", [$project->id, $def->id]) }}" method="POST" data-confirm-title="ลบกลุ่ม?" data-confirm-message="{{ $deleteGroupConfirm }}" data-confirm-icon="warning" data-confirm-button="ใช่, ลบกลุ่ม">
                            @csrf
                            @method("DELETE")
                            <button class="rounded-lg p-2 text-slate-400 hover:bg-red-50 hover:text-red-600" type="submit" title="ลบกลุ่ม">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                    <form class="mt-2 flex flex-wrap items-end gap-2" action="{{ route("hrd.admin.projects.groups.definitions.update", [$project->id, $def->id]) }}" method="POST">
                        @csrf
                        @method("PUT")
                        <div class="min-w-[7rem] flex-1">
                            <label class="mb-0.5 block text-xs font-medium text-slate-600">ที่นั่ง (Slot) <span class="font-normal text-slate-500">(ไม่บังคับ)</span></label>
                            <input class="w-full rounded-lg border border-slate-200 px-2.5 py-1.5 text-sm" type="number" name="max_members" min="{{ max(1, $groupData["count"]) }}" step="1" placeholder="ไม่จำกัด" value="{{ $def->max_members }}">
                        </div>
                        <button class="rounded-lg bg-slate-800 px-3 py-1.5 text-xs font-medium text-white hover:bg-slate-900" type="submit">บันทึก</button>
                    </form>
                </li>
            @endforeach
        </ul>
        <p class="mt-2 text-xs text-slate-500">ดูรายชื่อผู้ลงทะเบียนในกลุ่มที่ขั้นตอน 4</p>
    </div>
@endif
