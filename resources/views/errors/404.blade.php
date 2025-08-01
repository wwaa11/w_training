@extends("layouts.layout")
@section("content")
    <div class="flex h-screen flex-col text-gray-600">
        <div class="m-auto flex w-full flex-col rounded-lg bg-[#fff] py-6 text-center text-lg shadow md:w-1/2 lg:w-2/3">
            <div class="mb-4 text-4xl font-bold text-red-600">404</div>
            <div class="mb-2 text-2xl font-semibold">Page Not Found</div>
            <div class="mb-4 text-gray-600">ขออภัย หน้าที่คุณกำลังค้นหาไม่พบ</div>

            <div class="mx-4 mb-4 rounded-lg bg-gray-100 p-4">
                <div class="mb-2 text-sm text-gray-700">
                    <strong>Error Details:</strong>
                </div>
                <div class="mb-2 text-xs text-gray-600">
                    <strong>URL:</strong> {{ request()->url() }}
                </div>
                <div class="mb-2 text-xs text-gray-600">
                    <strong>Method:</strong> {{ request()->method() }}
                </div>
                <div class="text-xs text-gray-600">
                    <strong>Timestamp:</strong> {{ now()->format("Y-m-d H:i:s") }}
                </div>
            </div>

            <div class="mx-4 mb-4 rounded-lg bg-blue-50 p-4">
                <div class="mb-2 text-sm text-blue-800">
                    <strong>Need Help?</strong>
                </div>
                <div class="text-xs text-blue-700">
                    หากคุณพบปัญหานี้ กรุณาติดต่อโปรแกรมเมอร์<br>
                    <strong>โทร: 21471</strong>
                </div>
            </div>

            <div class="flex justify-center space-x-4">
                <button class="rounded bg-gray-500 px-4 py-2 text-white hover:bg-gray-600" onclick="window.history.back()">
                    กลับไปหน้าก่อน
                </button>
                <button class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600" onclick="window.location.href='{{ route("index") }}'">
                    หน้าแรก
                </button>
            </div>

            <div class="mt-4 text-sm text-gray-500" id="countdown">Redirecting to homepage in 10 seconds...</div>
        </div>
    </div>
@endsection
@section("scripts")
    <script>
        $(document).ready(function() {
            let countdown = 10;
            const countdownElement = $('#countdown');

            const interval = setInterval(function() {
                countdown--;
                countdownElement.text(`Redirecting to homepage in ${countdown} seconds...`);
                if (countdown <= 0) {
                    clearInterval(interval);
                    window.location.href = '{{ route("index") }}';
                }
            }, 1000);
        });
    </script>
@endsection
