@extends("layouts.training")
@section("content")
    <div class="container mx-auto max-w-2xl px-2 py-10 sm:px-0">
        @if ($user == null)
            <div class="animate-fade-in rounded-lg border border-[var(--border-color)] bg-[var(--background-primary)] p-8 text-center text-lg text-[var(--text-primary)] shadow-lg">
                <span class="block font-semibold text-[var(--danger-color)]" role="alert">ไม่พบข้อมูลในการลงทะเบียน</span>
                <div class="mt-4 text-base text-[var(--text-secondary)]">โปรดติดต่อแผนก ลงทะเบียนเรียนก่อน</div>
            </div>
        @else
            <div class="animate-fade-in rounded-lg border border-[var(--border-color)] bg-[var(--background-primary)] p-8 shadow-lg">
                <h4 class="mb-6 flex items-center gap-3 text-2xl font-bold text-[var(--primary-color)]">
                    <i class="fa fa-calendar-alt text-[var(--primary-color)]"></i> My Attendance History
                </h4>
                <div class="grid gap-6">
                    @forelse ($attends as $attend)
                        <div class="animate-fade-in flex flex-col gap-4 rounded-xl border border-[var(--border-color)] bg-[var(--background-secondary)] p-6 shadow-sm transition-transform hover:scale-[1.015] hover:shadow-md sm:flex-row sm:items-center">
                            <div class="flex-1">
                                <div class="mb-1 flex items-center gap-2 text-lg font-semibold text-[var(--primary-color)]">
                                    <i class="fa fa-calendar text-[var(--primary-color)]"></i> {{ $attend->full_date }}
                                </div>
                                <div class="mb-1 flex items-center gap-2 text-[var(--text-primary)]">
                                    <i class="fa fa-clock text-[var(--secondary-color)]"></i> <span class="font-medium">เวลา:</span> {{ $attend->date->time->name }}
                                </div>
                                <div class="flex items-center gap-2 text-[var(--text-primary)]">
                                    <i class="fa fa-map-marker-alt text-[var(--secondary-color)]"></i> <span class="font-medium">สถานที่:</span> {{ $attend->date->location }}
                                </div>
                                <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                                    <span class="inline-flex items-center gap-2 rounded-lg bg-[var(--success-color)] px-4 py-2 font-semibold text-white shadow">
                                        <i class="fa fa-check"></i> CHECKIN : {{ $attend->user_date }}
                                    </span>
                                    <span class="inline-flex items-center gap-2 rounded-lg bg-[var(--secondary-color)] px-4 py-2 font-semibold text-white shadow">
                                        <i class="fa fa-user-check"></i> APPROVE : {{ $attend->admin_date }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="animate-fade-in rounded-lg border border-[var(--border-color)] bg-[var(--background-secondary)] p-6 text-center text-[var(--text-secondary)]">
                            <i class="fa fa-calendar-times mb-2 text-3xl text-[var(--secondary-color)]"></i>
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
