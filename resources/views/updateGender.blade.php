@extends("layout")
@section("content")
    @auth
        <div class="m-auto mt-6 w-full rounded bg-[#c1dccd] p-3 shadow-lg md:w-3/4 md:p-6 lg:w-1/2">
            <form id="saveSign" action="{{ env("APP_URL") }}/updateGender" method="POST">
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
    @endauth
@endsection
@section("scripts")
@endsection
