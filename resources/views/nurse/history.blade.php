@extends("layouts.nurse")
@section("meta")
    <meta http-equiv="Refresh" content="60">
@endsection
@section("content")
    @include("hrd.partials.app-page", [
        "appTitle" => "ประวัติการลงทะเบียน",
        "appSubtitle" => "ดูคะแนนและประวัติการลงทะเบียนของคุณ",
        "appHeaderSticky" => true,
        "appHeaderEnd" => '<button type="button" class="hrd-btn-secondary text-xs sm:text-sm" onclick="refreshPage()"><i class="fas fa-arrows-rotate mr-1.5 sm:mr-2"></i>อัพเดตข้อมูล</button>',
    ])

        <div class="hrd-stat-card hrd-stat-card--amber p-3 sm:p-4">
            <div class="flex items-center">
                <i class="fas fa-star mr-2 text-amber-600"></i>
                <span class="text-sm font-medium text-slate-700 sm:text-base">คะแนนของฉัน :</span>
                <span class="ml-2 text-base font-bold text-slate-900 sm:text-lg">{{ $myscore }}</span>
            </div>
        </div>

        @if (count($lectures) > 0)
            <section>
                <div class="mb-2 flex items-center">
                    <i class="fas fa-chalkboard-teacher mr-2 text-emerald-600"></i>
                    <h2 class="text-base font-semibold text-slate-900 sm:text-lg">วิทยากรที่เข้าร่วม</h2>
                </div>
                <div class="space-y-2">
                    @foreach ($lectures as $lecture)
                        <div class="flex rounded-xl border border-slate-200 bg-white p-3 shadow-sm sm:p-4">
                            <div class="w-24 flex-shrink-0 text-center sm:w-28">
                                <div class="text-[10px] text-slate-500 sm:text-xs">{{ $lecture->dateData->dateThai }}</div>
                                <div class="text-2xl font-bold text-blue-600 sm:text-3xl">{{ date("d", strtotime($lecture->dateData->date)) }}</div>
                                <div class="text-xs text-slate-700 sm:text-sm">{{ $lecture->dateData->monthThai }}</div>
                            </div>
                            <div class="relative ml-3 flex-1 border-l border-slate-200 pl-3">
                                <div class="text-xs font-semibold text-blue-700 sm:text-sm">วิทยากร</div>
                                <div class="text-sm font-bold text-slate-900 sm:text-base">{{ $lecture->dateData->projectData->title }}</div>
                                <div class="absolute right-3 top-3 inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">คะแนน {{ $lecture->score }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if (count($transactions) > 0)
            <section>
                <div class="mb-2 flex items-center">
                    <i class="fas fa-list-check mr-2 text-blue-600"></i>
                    <h2 class="text-base font-semibold text-slate-900 sm:text-lg">ประวัติการลงทะเบียน</h2>
                </div>
                <div class="space-y-2">
                    @foreach ($transactions as $transaction)
                        <div class="flex rounded-xl border border-slate-200 bg-white p-3 shadow-sm sm:p-4">
                            <div class="w-24 flex-shrink-0 text-center sm:w-28">
                                <div class="text-[10px] text-slate-500 sm:text-xs">{{ $transaction->timeData->dateData->dateThai }}</div>
                                <div class="text-2xl font-bold text-blue-600 sm:text-3xl">{{ date("d", strtotime($transaction->timeData->dateData->date)) }}</div>
                                <div class="text-xs text-slate-700 sm:text-sm">{{ $transaction->timeData->dateData->monthThai }}</div>
                            </div>
                            <div class="relative ml-3 flex-1 border-l border-slate-200 pl-3">
                                <div class="text-sm font-bold text-slate-900 sm:text-base">{{ $transaction->projectData->title }}</div>
                                <div class="mt-1 text-xs text-slate-700 sm:text-sm">
                                    <i class="fa-regular fa-clock w-4 text-blue-600"></i>
                                    {{ $transaction->timedata->title }}
                                </div>
                                <div class="mt-1 text-xs text-slate-700 sm:text-sm">
                                    <i class="fa-solid fa-map-pin w-4 text-blue-600"></i>
                                    {{ $transaction->projectData->location }}
                                </div>
                                @if ($transaction->user_sign !== null)
                                    <div class="mt-2 inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                        <i class="fa-solid fa-location-dot mr-1"></i> CHECK IN {{ date("H:i", strtotime($transaction->user_sign)) }}
                                    </div>
                                @endif
                                @if ($transaction->admin_sign !== null)
                                    <div class="mt-2 inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                        <i class="fa-solid fa-user-nurse mr-1"></i> อนุมัติ {{ date("H:i", strtotime($transaction->admin_sign)) }}
                                    </div>
                                    <div class="absolute right-3 top-3 inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">+1 คะแนน</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @else
            <div class="hrd-card rounded-xl border border-slate-200 bg-slate-50 p-3 text-xs text-slate-600 sm:p-4 sm:text-sm">ไม่มีประวัติการลงทะเบียน</div>
        @endif

    @include("hrd.partials.app-page", ["appPageClose" => true])
@endsection
@section("scripts")
    <script>
        function refreshPage() {
            window.location.reload();
        }
    </script>
@endsection
