@extends("layouts.layout")
@section("content")
    <div class="flex h-screen flex-col text-gray-600">
        <div class="m-auto flex w-full flex-col rounded-lg bg-[#f0f0f0] p-6 text-center text-lg md:w-1/2 lg:w-2/3">
            <form id="saveSign" action="{{ route("profile.updateSign") }}" method="POST">
                @csrf
                <input id="sign" type="hidden" name="sign">
                <div class="p-3 text-center">
                    <div class="mb-3 text-red-600">เนื่องจากระบบมีความผิดพลาดในการบันทึก กรุณาเซ็นต์ ลายเซ็นต์อีกครั้ง</div>
                    <div class="mb-3 text-2xl">โปรดเซ็นต์ ลายเซ็นต์อีกครั้ง</div>
                    <div class="mb-3 cursor-pointer text-red-600" onclick="clearSign()">เซ็นต์อีกครั้ง</div>
                    <canvas class="m-auto mb-3 bg-white" id="sign_Canvas" width="300px" height="150px"></canvas>
                    <button class="my-3 w-full cursor-pointer rounded bg-[#008387] p-3 font-bold text-white" type="button" onclick="saveSign()">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
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
