@extends("layouts.layout")
@section("content")
    <div class="flex h-screen flex-col text-gray-600">
        <div class="m-auto flex w-full flex-col rounded-lg bg-[#fff] py-6 text-center text-lg md:w-1/2 lg:w-1/3">
            <div class="flex flex-col">
                <a href="{{ env("APP_URL") }}/hr">
                    <div class="mt-3 rounded border-2 border-gray-400 py-12">
                        HRD Division
                    </div>
                </a>
                <a href="{{ env("APP_URL") }}/nurse">
                    <div class="mt-3 rounded border-2 border-gray-400 py-12">
                        Nursing Division
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
