@extends("layouts.training")
@section("content")
    <div class="container mx-auto max-w-2xl px-2 py-10 sm:px-0">
        @if ($user == null)
            <div class="animate-fade-in rounded-lg border border-[#c1dccd] bg-white p-8 text-center text-lg text-gray-700 shadow-lg">
                <span class="block font-semibold text-red-600" role="alert">ไม่พบข้อมูลในการลงทะเบียน</span>
                <div class="mt-4 text-base text-gray-500">โปรดติดต่อแผนก ลงทะเบียนเรียนก่อน</div>
            </div>
        @else
            <div class="animate-fade-in rounded-lg border border-[#c1dccd] bg-white p-8 shadow-lg">
                <h4 class="mb-6 flex items-center gap-3 text-2xl font-bold text-[#256353]">
                    <i class="fa fa-calendar-alt text-[#c1dccd]"></i> My Attendance History
                </h4>
                <div class="grid gap-6">
                    @forelse ($dates as $date)
                        <div class="animate-fade-in flex flex-col gap-4 rounded-xl border border-[#c1dccd] bg-[#f6fbf8] p-6 shadow-sm transition-transform hover:scale-[1.015] hover:shadow-md sm:flex-row sm:items-center">
                            <div class="flex-1">
                                <div class="mb-1 flex items-center gap-2 text-lg font-semibold text-[#256353]">
                                    <i class="fa fa-calendar text-[#c1dccd]"></i> {{ $date["title"] }}
                                </div>
                                <div class="mb-1 flex items-center gap-2 text-gray-700">
                                    <i class="fa fa-clock text-[#c1dccd]"></i> <span class="font-medium">เวลา:</span> {{ $date["time"] }}
                                </div>
                                <div class="flex items-center gap-2 text-gray-700">
                                    <i class="fa fa-map-marker-alt text-[#c1dccd]"></i> <span class="font-medium">สถานที่:</span> {{ $date["location"] }}
                                </div>
                                @if ($date["checked"])
                                    <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                                        <span class="inline-flex items-center gap-2 rounded-lg bg-[#c1dccd] px-4 py-2 font-semibold text-[#256353] shadow">
                                            <i class="fa fa-check"></i> CHECKIN : {{ $date["user_date"] }}
                                        </span>
                                        <span class="inline-flex items-center gap-2 rounded-lg bg-[#e6e6e6] px-4 py-2 font-semibold text-[#4b5563] shadow">
                                            <i class="fa fa-user-check"></i> APPROVE : {{ $date["admin_date"] }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="animate-fade-in rounded-lg border border-[#c1dccd] bg-[#f6fbf8] p-6 text-center text-gray-500">
                            <i class="fa fa-calendar-times mb-2 text-3xl text-[#c1dccd]"></i>
                            <div class="font-semibold">ยังไม่มีประวัติการลงทะเบียน</div>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif
    </div>
    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .animate-fade-in {
            animation: fade-in 0.5s cubic-bezier(.4, 0, .2, 1);
        }
    </style>
@endsection
@section("scripts")
@endsection
