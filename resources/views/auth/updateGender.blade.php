@extends("layouts.layout")
@section("content")
    <div class="flex h-screen flex-col text-gray-600">
        <div class="m-auto flex w-full flex-col rounded-lg bg-[#f0f0f0] p-6 text-center text-lg md:w-1/2 lg:w-1/3">
            <form action="{{ env("APP_URL") }}/profile/updateGender" method="POST">
                @csrf
                <div class="p-3 text-center">
                    <div class="mb-3 text-2xl">กรุณาระบุเพศ</div>
                    <select class="mb-3 w-full rounded bg-white p-3" name="gender">
                        <option value="" selected disabled>โปรดระบุ</option>
                        <option value="ชาย">ชาย</option>
                        <option value="หญิง">หญิง</option>
                    </select>
                    <button class="my-3 w-full cursor-pointer rounded bg-[#008387] p-3 font-bold text-white" type="submit">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
@endsection
