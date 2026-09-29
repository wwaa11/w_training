@php
    $project = $project ?? null;
    $name = old('project_name', $project?->project_name);
    $type = old('project_type', $project?->project_type);
    $detail = old('project_detail', $project?->project_detail);
    $startReg = old(
        'project_start_register',
        $project?->project_start_register
            ? $project->project_start_register->format('Y-m-d\TH:i')
            : now()->format('Y-m-d\TH:i')
    );
    $endReg = old(
        'project_end_register',
        $project?->project_end_register ? $project->project_end_register->format('Y-m-d\TH:i') : ''
    );
@endphp

<div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
    <div>
        <label class="hrd-af-label">ชื่อโปรเจกต์ *</label>
        <input class="hrd-af-input" type="text" name="project_name" value="{{ $name }}" placeholder="ชื่อโปรเจกต์" required>
    </div>
    <div>
        <label class="hrd-af-label">ประเภทโปรเจกต์ *</label>
        <select class="hrd-af-input" name="project_type" required onchange="showProjectTypeHint(this.value)">
            <option value="">เลือกประเภท</option>
            <option value="single" @selected($type === 'single')>ลงทะเบียน 1 ครั้ง</option>
            <option value="multiple" @selected($type === 'multiple')>ลงทะเบียนได้มากกว่า 1 ครั้ง</option>
            <option value="attendance" @selected($type === 'attendance')>ไม่ต้องลงทะเบียน</option>
        </select>
        <div class="mt-1.5 hidden text-xs" id="projectTypeHint"></div>
    </div>
    <div class="sm:col-span-2">
        <label class="hrd-af-label">รายละเอียดโปรเจกต์</label>
        <textarea class="hrd-af-input" name="project_detail" rows="2" placeholder="รายละเอียดโปรเจกต์">{{ $detail }}</textarea>
    </div>
    <div>
        <label class="hrd-af-label">เริ่มการลงทะเบียน *</label>
        <input class="hrd-af-input" type="datetime-local" name="project_start_register" value="{{ $startReg }}" required>
    </div>
    <div>
        <label class="hrd-af-label">สิ้นสุดการลงทะเบียน *</label>
        <input class="hrd-af-input" type="datetime-local" name="project_end_register" value="{{ $endReg }}" required>
    </div>
</div>

@include('hrd.admin.projects.partials.form-group-options', ['project' => $project])
