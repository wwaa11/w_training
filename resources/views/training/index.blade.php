@extends("layouts.training")
@section("content")
    <div class="container mx-auto max-w-2xl px-2 py-10 sm:px-0">
        @if ($user == null)
            <div class="animate-fade-in rounded-lg border border-[var(--border-color)] bg-[var(--background-primary)] p-8 text-center text-lg text-[var(--text-primary)] shadow-lg">
                <span class="block font-semibold text-[var(--danger-color)]" role="alert">ไม่พบข้อมูลในการลงทะเบียน</span>
                <div class="mt-4 text-base text-[var(--text-secondary)]">โปรดติดต่อแผนก HR</div>
            </div>
        @elseif($user->time_id !== null)
            <div class="animate-fade-in rounded-lg border border-[var(--border-color)] bg-[var(--background-primary)] p-8 shadow-lg">
                <h4 class="mb-6 flex items-center gap-3 text-2xl font-bold text-[var(--primary-color)]">
                    <i class="fa fa-calendar-alt text-[var(--primary-color)]"></i> My Schedule
                </h4>

                @if (now() < \Carbon\Carbon::parse($user->time->dates[0]->name))
                    <div class="mb-6">
                        <button class="change-registration-btn hover:bg-[var(--warning-color)]/90 focus:ring-[var(--warning-color)]/50 flex items-center gap-2 rounded-lg bg-[var(--warning-color)] px-4 py-2 font-semibold text-white shadow transition hover:scale-105 focus:outline-none focus:ring-2" onclick="changeRegistration()" type="button">
                            <i class="fa fa-exchange-alt"></i> เปลี่ยนรอบลงทะเบียน
                        </button>
                    </div>
                @endif

                <div class="grid gap-6">
                    @foreach ($dates as $date)
                        <div class="animate-fade-in flex flex-col gap-4 rounded-xl border border-[var(--border-color)] bg-[var(--background-secondary)] p-6 shadow-sm transition-transform hover:scale-[1.015] hover:shadow-md sm:flex-row sm:items-center">
                            <div class="flex-1">
                                <div class="mb-1 flex items-center gap-2 text-lg font-semibold text-[var(--primary-color)]">
                                    <i class="fa fa-calendar text-[var(--primary-color)]"></i> {{ $date["title"] }}
                                </div>
                                <div class="mb-1 flex items-center gap-2 text-[var(--text-primary)]">
                                    <i class="fa fa-clock text-[var(--secondary-color)]"></i> <span class="font-medium">เวลา:</span> {{ $date["time"] }}
                                </div>
                                <div class="flex items-center gap-2 text-[var(--text-primary)]">
                                    <i class="fa fa-map-marker-alt text-[var(--secondary-color)]"></i> <span class="font-medium">สถานที่:</span> {{ $date["location"] }}
                                </div>
                                @if ($date["checkable"])
                                    <button class="checkin-btn mt-4 flex items-center gap-2 rounded-lg bg-[var(--primary-color)] px-5 py-2 font-semibold text-white shadow transition hover:scale-105 hover:bg-[var(--primary-dark)] disabled:cursor-not-allowed disabled:opacity-50" onclick="checkIn('{{ $date["id"] }}', this)" type="button">
                                        <span class="btn-text"><i class="fa fa-sign-in-alt"></i> Check IN</span>
                                        <span class="btn-spinner hidden"><i class="fa fa-spinner fa-spin"></i></span>
                                    </button>
                                @endif
                                @if ($date["checked"])
                                    <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                                        <span class="inline-flex items-center gap-2 rounded-lg bg-[var(--success-color)] px-4 py-2 font-semibold text-white shadow">
                                            <i class="fa fa-check"></i> CHECKIN : {{ $date["user_date"] }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="rounded-lg bg-[var(--background-primary)] p-6 shadow">
                <h4 class="mb-6 flex items-center gap-2 text-2xl font-bold text-[var(--primary-color)]"><i class="fa fa-chalkboard-teacher"></i> ลงทะเบียน English Lesson</h4>
                <div class="mb-8">
                    @if ($team !== null)
                        <div class="mb-4 flex items-center gap-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[var(--primary-color)] font-bold text-white">1</div>
                            <span class="text-lg font-semibold text-[var(--text-primary)]">เลือกอาจารย์</span>
                        </div>
                        <div class="mb-4 flex flex-wrap gap-2" id="teacher-list" role="list">
                            @forelse ($team->teachers as $teacher)
                                <button class="teacher-btn rounded bg-[var(--primary-light)] px-4 py-2 text-[var(--text-primary)] transition hover:bg-[var(--primary-color)] hover:text-white focus:bg-[var(--primary-color)] focus:text-white focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)]" onclick="getSessionList('{{ $teacher->id }}')" aria-label="เลือกอาจารย์ {{ $teacher->name }}">{{ $teacher->name }}</button>
                            @empty
                                <button class="teacher-btn rounded bg-[var(--secondary-light)] px-4 py-2 text-[var(--text-primary)] transition hover:bg-[var(--secondary-color)] hover:text-white focus:bg-[var(--secondary-color)] focus:text-white focus:outline-none focus:ring-2 focus:ring-[var(--secondary-color)]" aria-label="เลือกอาจารย์ ">ไม่พบข้อมูล</button>
                            @endforelse
                        </div>
                    @else
                        <div class="mb-4 flex items-center gap-2">
                            <span class="m-auto text-lg font-semibold text-[var(--text-secondary)]">ไม่พบข้อมูล</span>
                        </div>
                    @endif
                </div>
                <div class="mb-8" id="session-section" style="display:none;">
                    <div class="mb-2 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[var(--secondary-color)] font-bold text-white">2</div>
                        <span class="text-lg font-semibold text-[var(--text-primary)]">เลือกกลุ่ม/รอบ</span>
                    </div>
                    <div class="flex flex-wrap gap-2" id="session-list" role="list"></div>
                </div>
                <div class="mb-8" id="time-section" style="display:none;">
                    <div class="mb-2 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[var(--success-color)] font-bold text-white">3</div>
                        <span class="text-lg font-semibold text-[var(--text-primary)]">เลือกเวลา</span>
                    </div>
                    <div class="flex flex-col gap-2" id="time-list" role="list"></div>
                </div>
                <div id="register"></div>
                <div class="mt-4 font-bold" id="register-message" aria-live="polite"></div>
            </div>
        @endif
    </div>
@endsection
@section("scripts")
    <script>
        let selectedTeacher = null;
        let selectedSession = null;
        let selectedTime = null;
        let lastTeacherBtn = null;
        let lastSessionBtn = null;
        let lastTimeBtn = null;
        let registerBtn = null;

        // Get CSS variables for colors
        const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--primary-color').trim();
        const secondaryColor = getComputedStyle(document.documentElement).getPropertyValue('--secondary-color').trim();
        const successColor = getComputedStyle(document.documentElement).getPropertyValue('--success-color').trim();
        const warningColor = getComputedStyle(document.documentElement).getPropertyValue('--warning-color').trim();
        const dangerColor = getComputedStyle(document.documentElement).getPropertyValue('--danger-color').trim();

        function clearSection(sectionId) {
            document.getElementById(sectionId).style.display = 'none';
            document.getElementById(sectionId.replace('-section', '-list')).innerHTML = '';
        }

        function showLoading(sectionId) {
            document.getElementById(sectionId).innerHTML = '<div class="flex items-center gap-2 text-[var(--text-secondary)]"><i class="fa fa-sync-alt fa-spin"></i> กำลังโหลด...</div>';
        }

        function showError(sectionId, message) {
            document.getElementById(sectionId).innerHTML = `<div class='bg-[var(--danger-color)]/10 text-[var(--danger-color)] px-4 py-2 rounded flex items-center gap-2' role='alert'><i class="fa fa-exclamation-circle"></i> ${message}</div>`;
        }

        function clearRegister() {
            document.getElementById('register').innerHTML = '';
            document.getElementById('register-message').innerHTML = '';
        }

        function getSessionList(teacher_id) {
            selectedTeacher = teacher_id;
            selectedSession = null;
            selectedTime = null;
            clearRegister();
            showLoading('session-list');
            document.getElementById('session-section').style.display = 'block';
            clearSection('time-section');
            // Highlight selected teacher
            if (lastTeacherBtn) lastTeacherBtn.classList.remove('bg-[var(--primary-color)]', 'text-white');
            const btns = document.querySelectorAll('.teacher-btn');
            btns.forEach(btn => {
                if (btn.getAttribute('onclick').includes(teacher_id)) {
                    btn.classList.add('bg-[var(--primary-color)]', 'text-white');
                    btn.setAttribute('aria-pressed', 'true');
                    lastTeacherBtn = btn;
                } else {
                    btn.classList.remove('bg-[var(--primary-color)]', 'text-white');
                    btn.setAttribute('aria-pressed', 'false');
                }
            });
            axios.post('{{ route("training.get.sessions") }}', {
                'teacher_id': teacher_id,
            }).then((res) => {
                const sessions = res.data.sessions || [];
                if (sessions.length === 0) {
                    showError('session-list', 'ไม่พบกลุ่ม/รอบสำหรับอาจารย์นี้');
                    return;
                }
                let html = '';
                sessions.forEach(session => {
                    html += `<button class='session-btn rounded bg-[var(--secondary-light)] px-4 py-2 hover:bg-[var(--secondary-color)] hover:text-white focus:ring-2 focus:ring-[var(--secondary-color)] focus:outline-none focus:bg-[var(--secondary-color)] focus:text-white transition text-[var(--text-primary)]' onclick='getTimeList(${session.id}, this)' aria-label='เลือกกลุ่ม ${session.name}'>${session.name}</button>`;
                });
                document.getElementById('session-list').innerHTML = html;
            }).catch(() => {
                showError('session-list', 'เกิดข้อผิดพลาดในการโหลดกลุ่ม/รอบ');
            });
        }

        function getTimeList(session_id, btn) {
            selectedSession = session_id;
            selectedTime = null;
            clearRegister();
            showLoading('time-list');
            document.getElementById('time-section').style.display = 'block';
            // Highlight selected session
            if (lastSessionBtn) lastSessionBtn.classList.remove('bg-[var(--secondary-color)]', 'text-white');
            btn.classList.add('bg-[var(--secondary-color)]', 'text-white');
            btn.setAttribute('aria-pressed', 'true');
            lastSessionBtn = btn;
            axios.post('{{ route("training.get.times") }}', {
                'session_id': session_id,
            }).then((res) => {
                const times = res.data.times || [];
                if (times.length === 0) {
                    showError('time-list', 'ไม่พบเวลาในกลุ่ม/รอบนี้');
                    return;
                }
                let html = '';
                times.forEach(time => {
                    if (time.available_seat > 0) {
                        html += `<button class='time-btn rounded bg-[var(--success-color)]/20 px-4 py-2 hover:bg-[var(--success-color)] hover:text-white focus:ring-2 focus:ring-[var(--success-color)] focus:outline-none focus:bg-[var(--success-color)] focus:text-white transition text-[var(--text-primary)]' onclick='selectTime(${time.id}, \"${time.name}\", this)' aria-label='เลือกเวลา ${time.name}'>${time.name}</button>`;
                    } else {
                        html += `<button class='time-btn rounded bg-[var(--text-muted)]/20 px-4 py-2 text-[var(--text-muted)] cursor-not-allowed flex items-center gap-2' disabled aria-disabled='true' title='เต็ม'><i class='fa fa-ban'></i> ${time.name} (เต็ม)</button>`;
                    }
                });
                document.getElementById('time-list').innerHTML = html;
            }).catch(() => {
                showError('time-list', 'เกิดข้อผิดพลาดในการโหลดเวลา');
            });
        }

        function selectTime(time_id, time_name, btn) {
            selectedTime = time_id;
            clearRegister();
            // Highlight selected time
            if (lastTimeBtn) lastTimeBtn.classList.remove('bg-[var(--success-color)]', 'text-white');
            btn.classList.add('bg-[var(--success-color)]', 'text-white');
            btn.setAttribute('aria-pressed', 'true');
            lastTimeBtn = btn;
            // Show register button
            document.getElementById('register').innerHTML = `<div class='mt-2'><button id='register-btn' class='register-btn bg-[var(--primary-color)] text-white px-4 py-2 rounded hover:bg-[var(--primary-dark)] focus:ring-2 focus:ring-[var(--primary-color)] focus:outline-none transition flex items-center gap-2' onclick='registerTime(this)' aria-label='ลงทะเบียนรอบ: ${time_name}'><i class='fa fa-user-check align-middle' style='font-size:20px;'></i> ลงทะเบียนรอบ: ${time_name}</button></div>`;
        }

        function registerTime(btn) {
            if (!selectedTime) return;
            document.getElementById('register-message').innerHTML = '';
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa fa-sync-alt fa-spin mr-2"></i>กำลังลงทะเบียน...';
            }
            axios.post('{{ route("training.register") }}', {
                'time_id': selectedTime,
            }).then((res) => {
                if (res.data.status === 'success') {
                    document.getElementById('register-message').innerHTML = '<div class="bg-[var(--success-color)]/10 text-[var(--success-color)] px-4 py-2 rounded flex items-center gap-2"><i class="fa fa-check-circle"></i> ลงทะเบียนสำเร็จ!</div>';
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    document.getElementById('register-message').innerHTML = '<div class="bg-[var(--danger-color)]/10 text-[var(--danger-color)] px-4 py-2 rounded flex items-center gap-2" role="alert"><i class="fa fa-exclamation-circle"></i> ' + (res.data.message || 'เกิดข้อผิดพลาด') + '</div>';
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa fa-sync-alt fa-spin mr-2"></i>ลงทะเบียนรอบ';
                    }
                }
            }).catch(() => {
                document.getElementById('register-message').innerHTML = '<div class="bg-[var(--danger-color)]/10 text-[var(--danger-color)] px-4 py-2 rounded flex items-center gap-2" role="alert"><i class="fa fa-exclamation-circle"></i> เกิดข้อผิดพลาดในการลงทะเบียน</div>';
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa fa-sync-alt fa-spin mr-2"></i>ลงทะเบียนรอบ';
                }
            });
        }

        function checkIn(date_id, btn) {
            if (!btn) return;
            btn.disabled = true;
            btn.querySelector('.btn-text').classList.add('hidden');
            btn.querySelector('.btn-spinner').classList.remove('hidden');
            document.getElementById('register-message')?.remove();

            axios.post('{{ route("training.checkin") }}', {
                'date_id': date_id,
            }).then((res) => {
                let msg = document.createElement('div');
                msg.id = 'register-message';
                if (res.data.status === 'success') {
                    msg.innerHTML = '<div class="bg-[var(--success-color)]/10 text-[var(--success-color)] px-4 py-2 rounded flex items-center gap-2"><i class="fa fa-check-circle"></i> ลงชื่อสำเร็จ!</div>';
                    btn.parentNode.insertBefore(msg, btn.nextSibling);
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    msg.innerHTML = '<div class="bg-[var(--danger-color)]/10 text-[var(--danger-color)] px-4 py-2 rounded flex items-center gap-2" role="alert"><i class="fa fa-exclamation-circle"></i> ' + (res.data.message || 'เกิดข้อผิดพลาด') + '</div>';
                    btn.parentNode.insertBefore(msg, btn.nextSibling);
                    btn.disabled = false;
                    btn.querySelector('.btn-text').classList.remove('hidden');
                    btn.querySelector('.btn-spinner').classList.add('hidden');
                }
            }).catch((err) => {
                let msg = document.createElement('div');
                msg.id = 'register-message';
                msg.innerHTML = '<div class="bg-[var(--danger-color)]/10 text-[var(--danger-color)] px-4 py-2 rounded flex items-center gap-2" role="alert"><i class="fa fa-exclamation-circle"></i> ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้</div>';
                btn.parentNode.insertBefore(msg, btn.nextSibling);
                btn.disabled = false;
                btn.querySelector('.btn-text').classList.remove('hidden');
                btn.querySelector('.btn-spinner').classList.add('hidden');
            });
        }

        function changeRegistration() {
            Swal.fire({
                title: 'ยืนยันการเปลี่ยนรอบลงทะเบียน',
                text: 'คุณต้องการเปลี่ยนรอบลงทะเบียนหรือไม่? การดำเนินการนี้จะยกเลิกการลงทะเบียนปัจจุบันของคุณ',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: warningColor,
                cancelButtonColor: secondaryColor,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const btn = document.querySelector('.change-registration-btn');
                    if (btn) {
                        btn.disabled = true;
                        btn.innerHTML = '<i class="fa fa-sync-alt fa-spin"></i> กำลังดำเนินการ...';
                    }

                    document.getElementById('register-message')?.remove();

                    axios.post('{{ route("training.change.registration") }}', {})
                        .then((res) => {
                            if (res.data.status === 'success') {
                                Swal.fire({
                                    title: 'สำเร็จ!',
                                    text: 'ยกเลิกการลงทะเบียนสำเร็จ! กำลังโหลดหน้าใหม่...',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'เกิดข้อผิดพลาด',
                                    text: res.data.message || 'เกิดข้อผิดพลาด',
                                    icon: 'error',
                                    confirmButtonColor: dangerColor
                                });
                                if (btn) {
                                    btn.disabled = false;
                                    btn.innerHTML = '<i class="fa fa-exchange-alt"></i> เปลี่ยนรอบลงทะเบียน';
                                }
                            }
                        }).catch((err) => {
                            Swal.fire({
                                title: 'เกิดข้อผิดพลาด',
                                text: 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้',
                                icon: 'error',
                                confirmButtonColor: dangerColor
                            });
                            if (btn) {
                                btn.disabled = false;
                                btn.innerHTML = '<i class="fa fa-exchange-alt"></i> เปลี่ยนรอบลงทะเบียน';
                            }
                        });
                }
            });
        }
    </script>
@endsection
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(16px);
        }

        to {
            opacity: 1;
            transform: none;
        }
    }

    .animate-fade-in {
        animation: fade-in 0.5s cubic-bezier(.4, 0, .2, 1);
    }
</style>
