@extends("layouts.layout")
@section("content")
    <div class="flex flex-col text-gray-600">
        <div class="m-auto flex w-full flex-col rounded-lg py-6 text-center text-lg md:w-1/2 lg:w-1/3">
            <div class="flex flex-col">
                <a href="{{ env("APP_URL") }}/hr">
                    <div class="mt-3 rounded border-2 border-[#eaf7ab] bg-[#c1dccd] py-12 text-3xl font-bold text-[#143429]">
                        HRD Division
                    </div>
                </a>
                <a href="{{ env("APP_URL") }}/nurse">
                    <div class="mt-3 rounded border-2 border-[#eaf7ab] bg-[#c1dccd] py-12 text-3xl font-bold text-[#143429]">
                        Nursing Division
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
