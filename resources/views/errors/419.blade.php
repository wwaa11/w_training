@extends("layout")
@section("content")
    <div class="flex h-screen flex-col text-gray-600">
        <div class="m-auto flex w-full flex-col rounded-lg bg-[#fff] py-6 text-center text-lg shadow md:w-1/2 lg:w-2/3">
            <img class="m-auto h-36 p-3" src="{{ url("images/Side Logo.png") }}" alt="logo">
            <div class="text-center text-xl font-bold" style="white-space: nowrap;">Human Resource Developement</div>
            <div class="mb-3 text-lg font-bold">Reservation</div>
            <div class="mb-3 text-lg font-bold text-red-600">การ Login หมดอายุ กรูณาเข้าสู่ระบบอีกครั้ง</div>
            <div class="px-12">
                <div class="mb-1 text-xl">รหัสพนักงาน</div>
                <input class="mb-3 w-full rounded-lg p-3 text-center outline-2 outline-gray-400 focus:outline-2 focus:outline-green-600" id="userid" name="userid" type="text">
                <div class="mb-1 text-xl">รหัสผ่าน</div>
                <input class="mb-3 w-full rounded-lg p-3 text-center outline-2 outline-gray-400 focus:outline-2 focus:outline-green-600" id="password" name="password" type="password">
                <button class="mt-6 w-full cursor-pointer rounded-lg bg-[#008387] p-3 text-white" type="button" onclick="login()">เข้าสู่ระบบ</button>
            </div>
        </div>
    </div>
@endsection
@section("scripts")
    <script>
        $('#password').keyup(function(e) {
            if (e.keyCode === 13) {
                login()
            }
        });

        function login() {
            userid = $('#userid').val()
            password = $('#password').val()

            axios.post('{{ env("APP_URL") }}/login', {
                'userid': userid,
                'password': password,
            }).then((res) => {
                if (res.data.status == 'success') {
                    window.location.href = '{{ env("APP_URL") }}/'
                } else {
                    Swal.fire({
                        title: res.data.message,
                        icon: 'error',
                        allowOutsideClick: true,
                        showConfirmButton: true,
                        confirmButtonColor: 'red'
                    })
                }
            })

        }
    </script>
@endsection
