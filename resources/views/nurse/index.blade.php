@extends("layouts.nurse")
@section("meta")
    <meta http-equiv="Refresh" content="60">
@endsection
@section("content")
    <div class="container mx-auto px-3">
        <!-- Header -->
        <div class="mb-4">
            <div class="flex items-center justify-between">
                <div class="min-w-0 flex-1">
                    <h1 class="text-xl font-bold text-gray-900 sm:text-2xl">รายการลงทะเบียนของฉัน</h1>
                    <p class="mt-1 text-xs text-gray-600 sm:text-sm">จัดการการลงทะเบียนและเช็คอินของคุณ</p>
                </div>
                <button class="ml-3 inline-flex items-center rounded-lg bg-blue-600 px-3 py-2 text-xs font-medium text-white hover:bg-blue-700 sm:px-4 sm:text-sm" onclick="refreshPage()">
                    <i class="fas fa-arrows-rotate mr-1.5 sm:mr-2"></i>
                    อัพเดตข้อมูล
                </button>
            </div>
        </div>

        <!-- Info Notice -->
        {{-- <div class="mb-4 rounded-xl bg-gradient-to-r from-yellow-50 to-yellow-100 p-3 shadow-sm sm:p-4">
            <div class="flex items-start">
                <i class="fas fa-info-circle mr-2 mt-0.5 text-yellow-600"></i>
                <p class="text-xs text-yellow-800 sm:text-sm">ต้องการเปลี่ยนวันที่ลงทะเบียน กรุณายกเลิกวันลงทะเบียนเดิมก่อน</p>
            </div>
        </div> --}}

        <!-- My Transactions -->
        <div class="mb-4 rounded-xl bg-white p-3 shadow-sm sm:p-4">
            <div class="mb-2 flex items-center">
                <i class="fas fa-clipboard-list mr-2 text-green-600"></i>
                <h2 class="text-base font-semibold text-gray-900 sm:text-lg">รายการของฉัน</h2>
            </div>
            @forelse ($myTransaction as $transaction)
                @if (date("Y-m-d") <= date("Y-m-d", strtotime($transaction->date_time)))
                    <x-nurse-transaction-item :transaction="$transaction" />
                @endif
            @empty
                <div class="rounded border border-gray-200 bg-gray-50 p-3 text-xs text-gray-600 sm:text-sm">ยังไม่มีรายการลงทะเบียนของคุณ</div>
            @endforelse
        </div>

        <!-- Open Projects -->
        <div class="rounded-xl bg-white p-3 shadow-sm sm:p-4">
            <div class="mb-2 flex items-center">
                <i class="fas fa-calendar-check mr-2 text-blue-600"></i>
                <h2 class="text-base font-semibold text-gray-900 sm:text-lg">รายการที่เปิดลงทะเบียน</h2>
            </div>
            <div class="space-y-2">
                @foreach ($projects as $project)
                    <a class="block" href="{{ route("nurse.project.show", $project->id) }}">
                        <div class="cursor-pointer rounded-xl border border-gray-200 bg-gradient-to-br from-gray-50 to-white p-4 shadow-sm transition-all duration-200 hover:scale-[1.01] hover:border-gray-300 hover:shadow-md">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="text-sm font-medium text-gray-500 sm:text-base">{{ $project->detail }}</div>
                                    <div class="mt-1 text-lg font-bold text-gray-900 sm:text-xl">{{ $project->title }}</div>
                                </div>
                                <div class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800 sm:text-sm">
                                    <i class="fas fa-chevron-right mr-1"></i>
                                    ดูรายละเอียด
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@section("scripts")
    <script>
        function refreshPage() {
            window.location.reload();
        }

        async function sign(id, project_name) {
            const alert = await Swal.fire({
                title: "ลงชื่อ : " + project_name,
                icon: "warning",
                allowOutsideClick: false,
                showConfirmButton: true,
                confirmButtonColor: "green",
                confirmButtonText: "ลงชื่อ",
                showCancelButton: true,
                cancelButtonColor: "gray",
                cancelButtonText: "ยกเลิก",
            });

            if (alert.isConfirmed) {
                axios.post('{{ route("nurse.project.sign") }}', {
                        transaction_id: id,
                    })
                    .then((res) => {
                        Swal.fire({
                            title: res["data"]["message"],
                            icon: "success",
                            confirmButtonText: "ตกลง",
                            confirmButtonColor: "green",
                        }).then(function(isConfirmed) {
                            if (isConfirmed) {
                                window.location.reload();
                            }
                        });
                    });
            }
        }

        async function deleteTransaction(projectId, name) {
            const alert = await Swal.fire({
                title: `ยืนยันการยกเลิกการทะเบียน ${name}`,
                icon: "warning",
                allowOutsideClick: false,
                showConfirmButton: true,
                confirmButtonColor: "red",
                confirmButtonText: "ยืนยัน",
                showCancelButton: true,
                cancelButtonColor: "gray",
                cancelButtonText: "ยกเลิก",
            });

            if (alert.isConfirmed) {
                axios.post('{{ route("nurse.project.delete") }}', {
                        project_id: projectId,
                    })
                    .then((res) => {
                        Swal.fire({
                            title: res.data.message,
                            icon: "success",
                            confirmButtonText: "ตกลง",
                            confirmButtonColor: "green",
                        }).then(() => window.location.reload());
                    });
            }
        }
    </script>
@endsection
