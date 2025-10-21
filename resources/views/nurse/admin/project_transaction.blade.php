@extends("layouts.nurse")
@section("content")
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center">
                <a class="mr-4 text-blue-600 hover:text-blue-800" href="{{ route("nurse.admin.project.management", $project->id) }}">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">จัดการการลงทะเบียน</h1>
                    <p class="text-gray-600">{{ $project->title }}</p>
                </div>
            </div>
        </div>

        @if (session("success"))
            <div class="mb-6 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session("success") }}
                </div>
            </div>
        @endif

        @if (session("error"))
            <div class="mb-6 rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session("error") }}
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700">
                <h4 class="font-bold">กรุณาแก้ไขข้อผิดพลาดต่อไปนี้:</h4>
                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Search Section -->
        <div class="mb-6 rounded-lg bg-white p-6 shadow-lg">
            <h2 class="mb-4 text-xl font-semibold text-gray-800">
                <i class="fas fa-search mr-2 text-blue-600"></i>ค้นหา
            </h2>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">ค้นหาด้วยคำค้น</label>
                    <input class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-blue-500" id="searchInput" onkeyup="search()" placeholder="ค้นหาจากชื่อ, รหัสพนักงาน, ตำแหน่ง, แผนก, วันที่, รอบ" type="text">
                </div>
                <div class="flex items-end">
                    <button class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50" type="button" onclick="document.getElementById('searchInput').value=''; search();">
                        <i class="fas fa-times mr-1"></i>ล้าง
                    </button>
                </div>
            </div>
        </div>

        <!-- Registrations Table -->
        <div class="rounded-lg bg-white p-6 shadow-lg">
            <h2 class="mb-4 text-xl font-semibold text-gray-800">
                <i class="fas fa-list mr-2 text-blue-600"></i>รายการลงทะเบียน
            </h2>

            <div class="overflow-x-auto">
                <table class="min-w-full" id="user-table">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">ผู้ลงทะเบียน</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">แผนก</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">วันที่/ช่วงเวลา</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">สถานะเข้าร่วม</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">สถานะอนุมัติ</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody id="userTable">
                        @foreach ($project->dateData as $date)
                            @foreach ($date->timeData as $time)
                                @foreach ($time->transactionData as $index => $transaction)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            <div class="font-medium">{{ $transaction->userData->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $transaction->user_id }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            <div>{{ $transaction->userData->position }}</div>
                                            <div class="text-xs text-gray-500">{{ $transaction->userData->department }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            <div>{{ $date->title }}</div>
                                            <div>{{ $time->title }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @if ($transaction->user_sign !== null)
                                                <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>เข้าร่วมแล้ว (Check-in)
                                                </span>
                                                <div class="mt-1 text-xs text-gray-500">
                                                    {{ date("d/m/Y H:i", strtotime($transaction->user_sign)) }}
                                                </div>
                                            @else
                                                <span class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i>รอเข้าร่วม
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @if ($transaction->admin_sign !== null)
                                                <span class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-800">
                                                    <i class="fas fa-thumbs-up mr-1"></i>อนุมัติแล้ว
                                                </span>
                                                <div class="mt-1 text-xs text-gray-500">
                                                    {{ date("d/m/Y H:i", strtotime($transaction->admin_sign)) }}
                                                </div>
                                            @else
                                                <span class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-800">
                                                    <i class="fas fa-minus mr-1"></i>ยังไม่อนุมัติ
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-end text-sm">
                                            <div class="flex space-x-2">
                                                <button class="rounded bg-blue-600 px-3 py-2 text-xs text-white hover:bg-blue-700" onclick="openEditModal('{{ $transaction->id }}','{{ $transaction->user_sign }}','{{ $transaction->admin_sign }}','{{ $transaction->user_id }} {{ $transaction->userData->name }}','{{ $time->time_start }}')">
                                                    <i class="fas fa-edit mr-1"></i>แก้ไข
                                                </button>
                                                <button class="rounded bg-red-600 px-3 py-2 text-xs text-white hover:bg-red-700" onclick="deleteRegister('{{ $transaction->id }}','{{ $transaction->user_id }} {{ $transaction->userData->name }}')">
                                                    <i class="fas fa-trash mr-1"></i>ลบ
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal: Edit/Delete Transaction -->
    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900 bg-opacity-30 backdrop-blur-sm" id="editModal">
        <div class="mt-50 m-auto w-full max-w-md rounded-xl border border-gray-200 bg-white p-6 shadow-2xl">
            <h2 class="mb-4 text-xl font-semibold text-gray-800">แก้ไขข้อมูลการลงทะเบียน</h2>
            <div class="mb-3">
                <label class="mb-1 block text-sm font-medium text-gray-700">Check-In</label>
                <input class="w-full rounded border border-gray-300 px-3 py-2 text-sm" id="modalCheckin" type="datetime-local">
            </div>
            <div class="mb-6">
                <label class="mb-1 block text-sm font-medium text-gray-700">Approve</label>
                <input class="w-full rounded border border-gray-300 px-3 py-2 text-sm" id="modalApprove" type="datetime-local">
            </div>
            <div class="flex justify-end gap-2">
                <button class="rounded bg-gray-600 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700" type="button" onclick="closeEditModal()">ปิด</button>
                <button class="rounded bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700" type="button" onclick="updateTransaction()">บันทึก</button>
            </div>
        </div>
    </div>
@endsection
@section("scripts")
    <script>
        let activeTransactionId = null;
        let activeTransactionName = '';

        function search() {
            var value = $('#searchInput').val().toLowerCase();
            $("#userTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        }

        function formatDateTimeLocal(value) {
            if (!value) return '';
            const date = new Date(value);
            if (isNaN(date.getTime())) return '';
            const pad = (n) => String(n).padStart(2, '0');
            const y = date.getFullYear();
            const m = pad(date.getMonth() + 1);
            const d = pad(date.getDate());
            const hh = pad(date.getHours());
            const mm = pad(date.getMinutes());
            return `${y}-${m}-${d}T${hh}:${mm}`;
        }

        function openEditModal(id, userSign, adminSign, name, timeStart) {
            activeTransactionId = id;
            activeTransactionName = name;
            if (userSign || adminSign) {
                document.getElementById('modalCheckin').value = formatDateTimeLocal(userSign);
                document.getElementById('modalApprove').value = formatDateTimeLocal(adminSign);
            } else {
                document.getElementById('modalCheckin').value = formatDateTimeLocal(timeStart);
                document.getElementById('modalApprove').value = '{{ date("Y-m-d H:i") }}';
            }
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        async function updateTransaction() {
            const userSign = document.getElementById('modalCheckin').value;
            const adminSign = document.getElementById('modalApprove').value;

            try {
                const res = await axios.post('{{ route("nurse.admin.transactions.update") }}', {
                    'transaction_id': activeTransactionId,
                    'user_sign': userSign || null,
                    'admin_sign': adminSign || null,
                });
                await Swal.fire({
                    title: res['data']['message'],
                    icon: 'success',
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: 'green'
                });
                window.location.reload();
            } catch (e) {
                await Swal.fire({
                    title: 'บันทึกไม่สำเร็จ',
                    text: 'กรุณาลองใหม่อีกครั้ง',
                    icon: 'error',
                    confirmButtonText: 'ตกลง',
                });
            }
        }

        async function deleteRegister(id, name) {
            alert = await Swal.fire({
                title: "ยืนยันลบข้อมูลการลงทะเบียน " + name,
                icon: 'warning',
                showConfirmButton: true,
                confirmButtonColor: 'red',
                confirmButtonText: 'ยืนยัน',
                showCancelButton: true,
                cancelButtonColor: 'gray',
                cancelButtonText: 'ยกเลิก',
            })

            if (alert.isConfirmed) {
                axios.post('{{ route("nurse.admin.transactions.delete") }}', {
                    'transaction_id': id
                }).then((res) => {
                    Swal.fire({
                        title: res['data']['message'],
                        icon: 'success',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: 'green'
                    }).then(function(isConfirmed) {
                        if (isConfirmed) {
                            window.location.reload()
                        }
                    })
                });
            }
        }
    </script>
@endsection
