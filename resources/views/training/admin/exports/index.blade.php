@extends("layouts.training")
@section("content")
    <div class="mx-auto mt-10 max-w-xl rounded-lg bg-white p-6 shadow-md">
        <a class="mb-3 inline-flex items-center text-gray-600 transition-colors hover:text-gray-800" href="{{ route("training.admin.index") }}">
            <i class="fa-solid fa-arrow-left mr-2"></i>Back to Management
        </a>
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-2xl font-bold text-blue-800">Export</h2>
        </div>
        <div>
            <label class="mb-1 block font-medium text-gray-700" for="date">เลือกวันที่ (Select Date)</label>
            <select class="w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" id="date" name="date" required>
                <option value="">-- กรุณาเลือกวันที่ --</option>
                @foreach ($arrayDates as $date)
                    <option value="{{ $date }}">{{ date("d/m/Y", strtotime($date)) }}</option>
                @endforeach
            </select>
            <div class="mt-1 text-sm text-gray-500">กรุณาเลือกวันที่ที่ต้องการส่งออกข้อมูล</div>
        </div>
        <button class="flex items-center justify-center rounded bg-green-600 px-4 py-2 text-white transition hover:bg-green-700 disabled:opacity-60" id="exportBtn" onclick="exportAttend(event)" type="submit" style="min-width:180px">
            <span id="btnText"><i class="fa-solid fa-download mr-2"></i> ดาวน์โหลดไฟล์ บันทึกการเข้าเรียน</span>
            <span class="ml-2 hidden" id="btnSpinner">
                <i class="fa-solid fa-spinner fa-spin h-5 w-5"></i>
            </span>
        </button>
    </div>
@endsection
@section("scripts")
    <script>
        function showToast(message, type = 'success') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type,
                title: message,
                showConfirmButton: false,
                timer: 2500
            });
        }

        function exportAttend(e) {
            e.preventDefault();
            const date = $('#date').val();
            $('#exportBtn').attr('disabled', true);
            $('#btnText').addClass('hidden');
            $('#btnSpinner').removeClass('hidden');

            axios.get('{{ route("training.admin.exports.attends") }}', {
                    params: {
                        date
                    },
                    responseType: 'blob',
                })
                .then(res => {
                    const url = window.URL.createObjectURL(new Blob([res.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    const disposition = res.headers['content-disposition'];
                    let filename = 'attend.xlsx';
                    if (disposition) {
                        const utf8Match = disposition.match(/filename\*=UTF-8''([^;\n]+)/i);
                        if (utf8Match) {
                            filename = decodeURIComponent(utf8Match[1]);
                        } else {
                            const filenameMatch = disposition.match(/filename="?([^";\n]+)"?/i);
                            if (filenameMatch) {
                                filename = filenameMatch[1];
                            }
                        }
                    }
                    link.setAttribute('download', filename);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    window.URL.revokeObjectURL(url);
                    showToast(`ดาวน์โหลดไฟล์สำหรับวันที่ ${date} สำเร็จ!`, 'success');
                })
                .catch(err => {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่พบข้อมูล ในวันที่เลือก'
                    });
                })
                .finally(() => {
                    $('#exportBtn').attr('disabled', false);
                    $('#btnText').removeClass('hidden');
                    $('#btnSpinner').addClass('hidden');
                });
        }
    </script>
@endsection
