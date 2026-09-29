<script>
    (function() {
        function escapeHtml(value) {
            if (value === null || value === undefined) {
                return '';
            }
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        function readCheckinDetailFromForm(form) {
            return {
                project: form.dataset.checkinProject || '',
                date: form.dataset.checkinDate || '',
                location: form.dataset.checkinLocation || '',
                sessionTime: form.dataset.checkinSession || '',
                checkinFrom: form.dataset.checkinFrom || '',
                sessionTitle: form.dataset.checkinSessionTitle || '',
                hint: form.dataset.checkinHint || 'การเช็คอินจะบันทึกเวลาที่คุณเข้าร่วมโปรแกรม',
            };
        }

        window.buildHrdCheckinSwalHtml = function(detail) {
            const d = detail || {};
            const lines = [];

            if (d.project) {
                lines.push(`<p><span class="font-semibold text-slate-800">โปรแกรม:</span> ${escapeHtml(d.project)}</p>`);
            }
            if (d.date) {
                lines.push(`<p><span class="font-semibold text-slate-800">วันที่:</span> ${escapeHtml(d.date)}</p>`);
            }
            if (d.sessionTitle) {
                lines.push(`<p><span class="font-semibold text-slate-800">รอบ:</span> ${escapeHtml(d.sessionTitle)}</p>`);
            }
            if (d.location) {
                lines.push(`<p><span class="font-semibold text-slate-800">สถานที่:</span> ${escapeHtml(d.location)}</p>`);
            }

            const timeCards = (d.sessionTime || d.checkinFrom) ? `
                <div class="mt-3 grid grid-cols-1 gap-2 sm:grid-cols-2">
                    ${d.sessionTime ? `
                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-left">
                            <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">เวลาจัด</p>
                            <p class="text-lg font-extrabold leading-tight text-slate-900">${escapeHtml(d.sessionTime)}</p>
                        </div>
                    ` : ''}
                    ${d.checkinFrom ? `
                        <div class="rounded-xl border border-blue-200 bg-blue-50 px-3 py-2.5 text-left">
                            <p class="text-[10px] font-semibold uppercase tracking-wide text-blue-800">เปิดเช็คอิน</p>
                            <p class="text-lg font-extrabold leading-tight text-blue-950">${escapeHtml(d.checkinFrom)}</p>
                            <p class="mt-0.5 text-[11px] text-blue-800/90">30 นาทีก่อนเวลาจัด</p>
                        </div>
                    ` : ''}
                </div>
            ` : '';

            return `
                <div class="text-left text-sm text-slate-700 space-y-1.5">
                    ${lines.join('')}
                    ${timeCards}
                </div>
                <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2.5 text-left text-sm text-emerald-800">
                    <i class="fas fa-info-circle mr-1.5" aria-hidden="true"></i>${escapeHtml(d.hint)}
                </div>
                <p class="mt-3 text-sm text-slate-600">ยืนยันเพื่อบันทึกการเช็คอิน</p>
            `;
        };

        window.confirmHrdCheckin = function(form, submitButton) {
            const detail = readCheckinDetailFromForm(form);

            return Swal.fire({
                title: 'ยืนยันการเช็คอิน',
                html: window.buildHrdCheckinSwalHtml(detail),
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#71717a',
                confirmButtonText: 'ใช่, เช็คอิน',
                cancelButtonText: 'ยกเลิก',
                focusConfirm: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> กำลังเช็คอิน...';
                    }
                    form.submit();
                }
            });
        };

        window.bindHrdCheckinConfirmForms = function(selector) {
            document.querySelectorAll(selector || '.js-hrd-checkin-confirm').forEach((form) => {
                if (form.dataset.checkinBound === '1') {
                    return;
                }
                form.dataset.checkinBound = '1';
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    const btn = form.querySelector('button[type="submit"]');
                    window.confirmHrdCheckin(form, btn);
                });
            });
        };
    })();
</script>
