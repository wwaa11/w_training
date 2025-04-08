@extends("layout")
@section("content")
    <div class="m-auto mt-6 w-full rounded bg-[#c1dccd] p-3 text-center shadow-lg md:w-3/4 md:p-6 lg:w-1/2">
        @if ($errors->any())
            <div class="mb-3 text-center text-3xl text-red-600">รหัสผ่านเดิมไม่ถูกต้อง</div>
        @endif
        <form id="changePassword" action="{{ env("APP_URL") }}/changePassword" method="POST">
            @csrf
            <div class="mb-3 text-2xl font-bold text-[#008387]">เปลี่ยนรหัสผ่าน</div>
            <input class="mb-3 w-full rounded bg-gray-50 p-3 outline outline-gray-400" id="password_old" name="old_password" placeholder="รหัสผ่านเดิม" @if (!$user->password_changed) type="hidden" @else type="password" @endif @if (!$user->password_changed) value="{{ Auth::user()->userid }}" @endif>
            <input class="mb-3 w-full rounded bg-gray-50 p-3 outline outline-gray-400" id="password" name="password" type="password" placeholder="รหัสผ่านใหม่">
            <input class="mb-3 w-full rounded bg-gray-50 p-3 outline outline-gray-400" id="password_check" name="password_check" type="password" placeholder="ยืนยันหรัสผ่านอีกครั้ง">
            <div>
                <button class="w-full cursor-pointer rounded bg-[#008387] p-3 font-bold" type="button" onclick="changePassword()">เปลี่ยนรหัสผ่าน</button>
            </div>
        </form>
    </div>
@endsection
@section("scripts")
    <script>
        function changePassword() {
            old_password = $('#password_old').val();
            password = $('#password').val();
            password_check = $('#password_check').val();

            if (old_password == '' || password == '' || password !== password_check) {
                Swal.fire({
                    title: 'รหัสผ่านไม่ตรงกัน',
                    icon: 'error',
                    allowOutsideClick: true,
                    showConfirmButton: true,
                    confirmButtonColor: 'red'
                })
            } else {

                $('#changePassword').submit();
            }
        }
    </script>
@endsection
