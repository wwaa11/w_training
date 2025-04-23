@extends("layout")
@section("content")
    @auth
        @if (Auth::user()->sign == null)
            <div class="m-auto mt-6 w-full rounded bg-[#c1dccd] p-3 shadow-lg md:w-3/4 md:p-6 lg:w-1/2">
                <form id="saveSign" action="{{ env("APP_URL") }}/updateSign" method="POST">
                    @csrf
                    <input id="sign" type="hidden" name="sign">
                    <div class="p-3 text-center">
                        <div class="text-red-600">เนื่องจากระบบมีความผิดพลาดในการบันทึก กรุณาเซ็นต์ ลายเซ็นต์อีกครั้ง</div>
                        <div class="text-2xl">โปรดเซ็นต์ ลายเซ็นต์อีกครั้ง</div>
                        <div class="cursor-pointer text-red-600" onclick="clearSign()">เซ็นต์อีกครั้ง</div>
                        <canvas class="m-auto mb-3 bg-white" id="sign_Canvas" width="300px" height="150px"></canvas>
                        <button class="my-3 w-full cursor-pointer rounded bg-[#008387] p-3 font-bold text-white" type="button" onclick="saveSign()">บันทึก</button>
                    </div>
                </form>
            </div>
        @endif
    @endauth
@endsection
@section("scripts")
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        const canvas = document.getElementById('sign_Canvas');
        const ctx = canvas.getContext('2d');
        const signaturePad = new SignaturePad(canvas, {
            penColor: "rgb(66, 133, 244)"
        });

        function clearSign() {
            signaturePad.clear();
        }

        function saveSign() {
            if (signaturePad.isEmpty()) {
                alert = Swal.fire({
                    title: "กรุณาเซ็นต์ ลายเซ็นต์ ",
                    icon: 'error',
                    showConfirmButton: true,
                    confirmButtonColor: 'red',
                    confirmButtonText: 'ยืนยัน',
                })
            } else {
                $('#sign').val(signaturePad.toDataURL())

                $('#saveSign').submit();
            }
        }
    </script>
@endsection
