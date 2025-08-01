@extends("layouts.layout")
@section("content")
    <div class="flex flex-col text-gray-600">
        <div class="m-auto flex w-full flex-col rounded-lg py-6 text-center text-lg md:w-1/2 lg:w-1/3">
            <div class="flex flex-col">
                <a href="{{ route("hr.index") }}">
                    <div class="mx-3 mt-3 rounded-2xl border-2 border-[#eaf7ab] bg-[#c1dccd] py-12 text-3xl font-bold text-[#143429]">
                        HRD Division
                    </div>
                </a>
                <a href="{{ route("nurse.index") }}">
                    <div class="mx-3 mt-3 rounded-2xl border-2 border-[#eaf7ab] bg-[#c1dccd] py-12 text-3xl font-bold text-[#143429]">
                        Nursing Division
                    </div>
                </a>
                <a href="{{ route("training.index") }}">
                    <div class="mx-3 mt-3 rounded-2xl border-2 border-[#eaf7ab] bg-[#c1dccd] py-12 text-3xl font-bold text-[#143429]">
                        English Training Program
                    </div>
                </a>
                <a href="{{ route("hrd.index") }}">
                    <div class="mx-3 mt-3 rounded-2xl border-2 border-gray-200 bg-gray-100 py-12 text-3xl font-bold text-gray-800">
                        ทดสอบระบบ HRD Version 2.0
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
