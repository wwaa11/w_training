@php
    $checkInsByTimeId = $availableCheckIns->keyBy(fn ($c) => $c['time']->id);
    $showPerSessionSeats = $project->project_seat_assign
        && $userSeatAssignments->isNotEmpty()
        && $userSeatAssignments->pluck('seat_number')->unique()->count() > 1;
@endphp

<section class="hrd-panel" aria-label="เซสชันและเช็คอิน">
    <div class="border-b border-slate-200 bg-slate-50/80 px-4 py-3 sm:px-5">
        <h2 class="text-lg font-bold text-slate-900">เซสชันและเช็คอิน</h2>
        <p class="mt-0.5 text-sm text-slate-600">{{ $registrationCount }} เซสชันที่ลงทะเบียน</p>
    </div>

    <div class="space-y-4 p-4 sm:p-5">
        @foreach ($scheduleView as $d)
            @php
                $myTimes = collect($d['times'])->filter(fn ($t) => $t['userRegistered'] || $t['hasAttended']);
            @endphp
            @if ($myTimes->isEmpty())
                @continue
            @endif
            <div>
                <p class="text-sm font-semibold text-slate-900">{{ $d['title'] }}</p>
                @if (! empty($d['location']))
                    <p class="text-xs text-slate-500"><i class="fas fa-map-marker-alt mr-1 text-blue-600"></i>{{ $d['location'] }}</p>
                @endif
                <div class="mt-2 space-y-3">
                    @foreach ($myTimes as $t)
                        @php
                            $checkIn = $checkInsByTimeId->get($t['timeId']);
                            $liveCheckIn = $checkIn && ($checkIn['canCheckIn'] || $checkIn['hasAttended']);
                        @endphp
                        <article class="js-unregister-slot rounded-2xl border border-slate-200 bg-slate-50/50 p-3 sm:p-4" id="session-hub-{{ $project->id }}-{{ $t['timeId'] }}">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <p class="text-sm font-semibold text-slate-900">{{ $t['timeRange'] }}</p>
                                @if ($t['hasAttended'])
                                    <span class="text-xs font-medium text-emerald-700"><i class="fas fa-check-circle mr-1"></i>เช็คอินแล้ว</span>
                                @elseif ($liveCheckIn && $checkIn['canCheckIn'])
                                    <span class="text-xs font-semibold text-blue-700"><i class="fas fa-circle text-[6px] mr-1 align-middle"></i>เปิดเช็คอิน</span>
                                @endif
                            </div>

                            @if ($showPerSessionSeats && $t['userSeat'])
                                <p class="mt-1 text-xs text-emerald-800"><i class="fas fa-chair mr-1"></i>ที่นั่ง {{ $t['userSeat']->seat_number }}</p>
                            @endif

                            @if ($liveCheckIn && $checkIn['hasAttended'])
                                <p class="mt-2 text-sm text-emerald-800">
                                    เมื่อ {{ \Carbon\Carbon::parse($checkIn['attendanceRecord']->attend_datetime)->format('H:i · d M Y') }}
                                </p>
                                @if ($project->links->count() > 0 && $checkIn['hasApprove'])
                                    <div class="mt-3 space-y-2">
                                        @foreach ($project->links as $link)
                                            @php
                                                $linkAvailable = true;
                                                if ($link->link_limit) {
                                                    $now = now();
                                                    $linkAvailable = (! $link->link_time_start || $now >= $link->link_time_start) && (! $link->link_time_end || $now <= $link->link_time_end);
                                                }
                                            @endphp
                                            @if ($linkAvailable)
                                                <a class="flex items-center justify-between rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-blue-800 hover:bg-blue-50" href="{{ $link->link_url }}" target="_blank" rel="noopener">
                                                    {{ $link->link_name }}
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            @elseif ($liveCheckIn && $checkIn['canCheckIn'])
                                <form class="stamp-form-top mt-3" id="stamp-form-{{ $project->id }}-{{ $checkIn['userRegistration']->id }}" action="{{ route('hrd.projects.stamp.store', [$project->id, $checkIn['userRegistration']->id]) }}" method="POST">
                                    @csrf
                                    <button class="hrd-btn-primary min-h-[48px] w-full py-3" id="stamp-btn-{{ $project->id }}-{{ $checkIn['userRegistration']->id }}" type="submit">
                                        <i class="fas fa-user-check"></i>
                                        เช็คอินตอนนี้
                                    </button>
                                </form>
                            @elseif (! $t['hasAttended'])
                                <p class="mt-2 text-xs text-slate-500">
                                    <i class="fas fa-sign-in-alt mr-1 text-blue-600"></i>เช็คอินได้ตั้งแต่ {{ $t['checkinFromText'] }}
                                </p>
                            @endif

                            @if ($t['userRegistrationId'] && ! $t['hasAttended'])
                                @php $canUnregister = $project->project_register_today || ! $d['dateIsToday']; @endphp
                                @if ($canUnregister)
                                    <button class="unregister-trigger mt-3 min-h-[44px] w-full rounded-xl border border-red-200 bg-white px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-50" id="unregister-btn-{{ $project->id }}-{{ $t['userRegistrationId'] }}" data-registration-id="{{ $t['userRegistrationId'] }}" type="button">
                                        <i class="fas fa-user-times mr-2"></i>ยกเลิกการลงทะเบียน
                                    </button>
                                @endif
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>
