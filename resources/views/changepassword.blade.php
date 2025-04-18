@extends("layout")
@section("content")
    <div class="m-auto mt-6 w-full rounded bg-[#c1dccd] p-3 shadow-lg md:w-3/4 md:p-6 lg:w-1/2">
        @if ($errors->any())
            <div class="mb-3 text-center text-3xl text-red-600">รหัสผ่านเดิมไม่ถูกต้อง</div>
        @endif
        <form id="changePassword" action="{{ env("APP_URL") }}/changePassword" method="POST">
            @csrf
            <div class="mb-3 text-2xl font-bold text-[#008387]">อัพเดตข้อมูลส่วนตัว</div>
            <div class="flex flex-row">
                <div class="flex-1">ลายเซ็นต์</div>
                <div class="cursor-pointer text-red-600" onclick="clearSign()">clear</div>
                <input id="sign" type="hidden" name="sign">
            </div>
            <div class="p-3">
                <img class="m-auto hidden bg-white" id="old_sign" src="{{ $user->sign }}" alt="">
            </div>
            <div class="p-3">
                <canvas class="m-auto bg-white" id="sign_Canvas" width="300px" height="150px"></canvas>
            </div>
            @if (!$user->password_changed)
                <input id="password_old" name="old_password" type="hidden" value="{{ Auth::user()->userid }}">
                <div>เลขบัตรประจำตัวประชาชน</div>
                <input class="mb-3 w-full rounded bg-gray-50 p-3 outline outline-gray-400" id="refno" name="refno" type="text" placeholder="รหัสประจำตัวประชาชน" autocomplete="off" value="{{ Auth::user()->refNo }}">
            @else
                <div>เลขบัตรประจำตัวประชาชน</div>
                <input class="mb-3 w-full rounded bg-gray-50 p-3 outline outline-gray-400" id="refno" name="refno" type="text" placeholder="รหัสประจำตัวประชาชน" autocomplete="off" value="{{ Auth::user()->refNo }}">
                <div>รหัสผ่านปัจจุบัน</div>
                <input class="mb-3 w-full rounded bg-gray-50 p-3 outline outline-gray-400" id="password_old" autocomplete="current-password" name="old_password" placeholder="รหัสผ่านเดิม" type="password">
            @endif
            <div>รหัสผ่านใหม่</div>
            <input class="mb-3 w-full rounded bg-gray-50 p-3 outline outline-gray-400" id="password" name="password" type="password" placeholder="รหัสผ่านใหม่" autocomplete="off">
            <input class="mb-3 w-full rounded bg-gray-50 p-3 outline outline-gray-400" id="password_check" name="password_check" type="password" placeholder="ยืนยันหรัสผ่านอีกครั้ง" autocomplete="off">
            <div>
                <button class="w-full cursor-pointer rounded bg-[#008387] p-3 font-bold text-white" type="button" onclick="changePassword()">บันทึก</button>
            </div>
        </form>
    </div>
@endsection
@section("scripts")
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    @if (!$user->password_changed)
        <script>
            $(document).ready(function() {
                Swal.fire({
                    title: '',
                    html: '<img src="{{ url("images/how.png") }}">',
                    allowOutsideClick: false,
                    showConfirmButton: true,
                    confirmButtonColor: '#008387'
                })
            });
        </script>
    @endif
    <script>
        const img = document.getElementById('old_sign');
        const canvas = document.getElementById('sign_Canvas');
        const ctx = canvas.getContext('2d');
        const signaturePad = new SignaturePad(canvas, {
            penColor: "rgb(66, 133, 244)"
        });
        ctx.drawImage(img, 0, 0);

        function clearSign() {
            signaturePad.clear();
        }

        function changePassword() {
            old_password = $('#password_old').val();
            ref = $('#refno').val();
            password = $('#password').val();
            password_check = $('#password_check').val();

            cansend = true;
            Textdetail = '';

            if (ref == '') {
                cansend = false;
                Textdetail = Textdetail + '<br>เลขบัตรประจำตัวประชาชน';
            }
            if (ref.length != 13) {
                cansend = false;
                Textdetail = Textdetail + '<br>เลขบัตรประจำตัวประชาชนไม่ถูกต้อง';
            }
            if (signaturePad.isEmpty() && '{{ $user->sign }}' == '') {
                cansend = false;
                Textdetail = Textdetail + '<br>ลายเซ็นต์';
            }
            if (old_password == '') {
                cansend = false;
                Textdetail = Textdetail + '<br>รหัสผ่านเดิม';
            }
            if (password == '' || password !== password_check) {
                cansend = false;
                Textdetail = Textdetail + '<br>รหัสผ่านทั้งสองไม่ตรงกัน';
            }
            Textdetail = Textdetail + '<br>';

            if (!cansend) {
                Swal.fire({
                    title: 'ผิดพลาด',
                    html: Textdetail,
                    icon: 'error',
                    allowOutsideClick: true,
                    showConfirmButton: true,
                    confirmButtonColor: 'red'
                })

                return
            }
            $('#sign').val(signaturePad.toDataURL())

            $('#changePassword').submit();
        }
    </script>
@endsection
