@extends("layouts.nurse")
@section("meta")
    <meta http-equiv="Refresh" content="60">
@endsection
@section("content")
    @include("hrd.partials.app-page", [
        "appTitle" => "รายการลงทะเบียนของฉัน",
        "appSubtitle" => "จัดการการลงทะเบียนและเช็คอินของคุณ",
        "appHeaderSticky" => true,
        "appHeaderEnd" => '<button type="button" class="hrd-btn-secondary text-xs sm:text-sm" onclick="refreshPage()"><i class="fas fa-arrows-rotate mr-1.5 sm:mr-2"></i>อัพเดตข้อมูล</button>',
    ])

        <section class="hrd-card p-3 sm:p-4">
            <div class="mb-2 flex items-center">
                <i class="fas fa-clipboard-list mr-2 text-emerald-600"></i>
                <h2 class="text-base font-semibold text-slate-900 sm:text-lg">รายการของฉัน</h2>
            </div>
            @forelse ($myTransaction as $transaction)
                @if (date("Y-m-d") <= date("Y-m-d", strtotime($transaction->date_time)))
                    <x-nurse-transaction-item :transaction="$transaction" />
                @endif
            @empty
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-3 text-xs text-slate-600 sm:text-sm">ยังไม่มีรายการลงทะเบียนของคุณ</div>
            @endforelse
        </section>

        <section class="hrd-card p-3 sm:p-4">
            <div class="mb-2 flex items-center">
                <i class="fas fa-calendar-check mr-2 text-blue-600"></i>
                <h2 class="text-base font-semibold text-slate-900 sm:text-lg">รายการที่เปิดลงทะเบียน</h2>
            </div>
            <div class="space-y-2">
                @foreach ($projects as $project)
                    <a class="block" href="{{ route("nurse.project.show", $project->id) }}">
                        <div class="cursor-pointer rounded-xl border border-slate-200 bg-gradient-to-br from-slate-50 to-white p-4 shadow-sm transition-all duration-200 hover:scale-[1.01] hover:border-blue-200 hover:shadow-md">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="text-sm font-medium text-slate-500 sm:text-base">{{ $project->detail }}</div>
                                    <div class="mt-1 text-lg font-bold text-slate-900 sm:text-xl">{{ $project->title }}</div>
                                </div>
                                <div class="inline-flex shrink-0 items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800 sm:text-sm">
                                    <i class="fas fa-chevron-right mr-1"></i>
                                    ดูรายละเอียด
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

    @include("hrd.partials.app-page", ["appPageClose" => true])
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
                confirmButtonColor: "#2563eb",
                confirmButtonText: "ลงชื่อ",
                showCancelButton: true,
                cancelButtonColor: "#64748b",
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
                            confirmButtonColor: "#2563eb",
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
                confirmButtonColor: "#dc2626",
                confirmButtonText: "ยืนยัน",
                showCancelButton: true,
                cancelButtonColor: "#64748b",
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
                            confirmButtonColor: "#2563eb",
                        }).then(() => window.location.reload());
                    });
            }
        }
    </script>
@endsection
