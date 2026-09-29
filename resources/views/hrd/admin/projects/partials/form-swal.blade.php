<script>
    window.HRD_ADMIN_SWAL = {
        confirmColor: '#2563eb',
        cancelColor: '#64748b',
        warningColor: '#d97706',
        dangerColor: '#dc2626',
    };

    window.hrdAdminConfirm = function(options) {
        return Swal.fire({
            icon: options.icon || 'question',
            title: options.title,
            text: options.text || undefined,
            html: options.html || undefined,
            showCancelButton: options.showCancelButton !== false,
            confirmButtonColor: options.confirmButtonColor || window.HRD_ADMIN_SWAL.confirmColor,
            cancelButtonColor: options.cancelButtonColor || window.HRD_ADMIN_SWAL.cancelColor,
            confirmButtonText: options.confirmText || 'ยืนยัน',
            cancelButtonText: options.cancelText || 'ยกเลิก',
        });
    };

    window.hrdAdminConfirmDelete = function(message, onConfirm) {
        window.hrdAdminConfirm({
            title: 'ยืนยันการลบ?',
            text: message || 'รายการนี้จะถูกลบจากฟอร์ม',
            icon: 'warning',
            confirmButtonColor: window.HRD_ADMIN_SWAL.dangerColor,
            confirmText: 'ใช่, ลบ',
        }).then((result) => {
            if (result.isConfirmed && typeof onConfirm === 'function') {
                onConfirm();
            }
        });
    };

    document.addEventListener('DOMContentLoaded', function() {
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: @json(session('success')),
                confirmButtonText: 'ตกลง',
                confirmButtonColor: window.HRD_ADMIN_SWAL.confirmColor,
            });
        @endif
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: @json(session('error')),
                confirmButtonText: 'ตกลง',
                confirmButtonColor: window.HRD_ADMIN_SWAL.dangerColor,
            });
        @endif
    });
</script>
