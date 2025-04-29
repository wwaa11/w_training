@extends("layouts.hr")
@section("meta")
    <meta http-equiv="Refresh" content="60">
@endsection
@section("content")
    <div class="m-auto w-full p-3 md:w-3/4">
        <div class="mb-6 rounded-lg border border-[#eaf7ab] bg-[#c1dccd] p-3 shadow">
            <div class="flex text-3xl text-[#1a3f34]">
                <div class="flex-1">รายการลงทะเบียนของฉัน</div>
                <div class="cursor-pointer text-lg" onclick="refreshPage()"><i class="fa-solid fa-arrows-rotate"></i> อัพเดตข้อมูล</div>
            </div>
            <hr class="border-[#eaf7ab] shadow">
            <div class="text-sm text-red-600">ต้องการเปลี่ยนวันที่ลงทะเบียน กรุณายกเลิกวันลงทะเบียนเดิมก่อน</div>
            @foreach ($myItem as $transaction)
                @if ($transaction->item->slot->slot_date >= date("Y-m-d"))
                    <x-hr-transaction-item :transaction="$transaction" />
                @endif
            @endforeach
        </div>
        <div class="rounded-lg border border-[#eaf7ab] bg-[#c1dccd] p-3 shadow">
            <div class="text-3xl text-[#1a3f34]">รายการที่เปิดลงทะเบียน</div>
            <hr class="border-[#eaf7ab] shadow">
            @foreach ($projects as $index => $project)
                <a href="{{ env("APP_URL") }}/hr/project/{{ $project->id }}">
                    <div class="m-3 cursor-pointer rounded border border-[#eaf7ab] bg-[#eeeeee] p-6">
                        <div class="text-2xl">{{ $project->project_name }}</div>
                        <div class="text-gray-500"><i class="fa-regular fa-calendar text-[#008387]"></i> {{ $project->project_detail }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection
@section("scripts")
    <script>
        async function sign(id, project_name) {
            alert = await Swal.fire({
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
                axios.post('{{ env("APP_URL") }}/hr/project/sign', {
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
                axios.post('{{ env("APP_URL") }}/hr/project/delete', {
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
