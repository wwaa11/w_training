@extends("layouts.layout")
@section("content")
    <div class="flex h-screen flex-col text-gray-600">
        <div class="m-auto flex w-full flex-col rounded-lg bg-[#fff] py-6 text-center text-lg shadow md:w-1/2 lg:w-2/3">
            <div>ERROR : 404 Not Found</div>
            <div class="mt-4 text-sm text-gray-500" id="countdown">Redirecting in 10 seconds...</div>
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
                countdownElement.text(`Redirecting in ${countdown} seconds...`);
                if (countdown <= 0) {
                    clearInterval(interval);
                    window.location.href = '{{ env("APP_URL") }}/';
                }
            }, 1000);
        });
    </script>
@endsection
