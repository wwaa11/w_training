@php
    $checkInsByTimeId = $availableCheckIns->keyBy(fn ($c) => $c['time']->id);
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
                            <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                                @if ($t['hasAttended'])
                                    <span class="inline-flex items-center rounded-lg bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800"><i class="fas fa-check-circle mr-1"></i>เช็คอินแล้ว</span>
                                @elseif ($liveCheckIn && $checkIn['canCheckIn'])
                                    <span class="inline-flex items-center rounded-lg bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-800"><i class="fas fa-circle text-[6px] mr-1 align-middle"></i>เปิดเช็คอิน</span>
                                @else
                                    <span class="text-xs font-medium text-slate-500">รอช่วงเช็คอิน</span>
                                @endif
                            </div>

                            @include('hrd.partials.checkin-session-schedule', [
                                'time' => $checkIn['time'] ?? null,
                                'timeRange' => $t['timeRange'],
                                'checkinFromText' => $t['checkinFromText'],
                                'class' => 'mb-3',
                            ])

                            @if ($project->project_seat_assign || $project->project_group_assign)
                                <div class="mt-3">
                                    @include('hrd.partials.assignment-inline', [
                                        'project' => $project,
                                        'layout' => 'full',
                                        'hasAttended' => (bool) $t['hasAttended'],
                                        'userSeat' => $t['userSeat'] ?? null,
                                        'userGroup' => $t['userGroup'] ?? null,
                                    ])
                                </div>
                            @endif

                            @if ($liveCheckIn && $checkIn['hasAttended'])
                                <p class="mt-2 text-sm text-emerald-800">
                                    เมื่อ {{ \Carbon\Carbon::parse($checkIn['attendanceRecord']->attend_datetime)->format('H:i · d M Y') }}
                                </p>
                                @if ($checkIn && ! empty($checkIn['showSessionLinks']))
                                    @include('hrd.partials.project-session-links', [
                                        'project' => $project,
                                    ])
                                @endif
                            @elseif ($liveCheckIn && $checkIn['canCheckIn'])
                                <form
                                    class="stamp-form-top js-hrd-checkin-confirm mt-3"
                                    id="stamp-form-{{ $project->id }}-{{ $checkIn['userRegistration']->id }}"
                                    action="{{ route('hrd.projects.stamp.store', [$project->id, $checkIn['userRegistration']->id]) }}"
                                    method="POST"
                                    data-checkin-project="{{ $project->project_name }}"
                                    data-checkin-date="{{ $d['title'] }}"
                                    data-checkin-session="{{ $t['timeRange'] }}"
                                    data-checkin-from="{{ $t['checkinFromText'] }}"
                                    data-checkin-location="{{ $d['location'] ?? '' }}"
                                    data-checkin-session-title="{{ $t['timeDetail'] ?? '' }}"
                                    data-checkin-hint="การเช็คอินจะบันทึกเวลาที่คุณเข้าร่วมสำหรับเซสชันที่ลงทะเบียนแล้ว"
                                >
                                    @csrf
                                    <button class="hrd-btn-primary min-h-[48px] w-full py-3" id="stamp-btn-{{ $project->id }}-{{ $checkIn['userRegistration']->id }}" type="submit">
                                        <i class="fas fa-user-check"></i>
                                        เช็คอินตอนนี้
                                    </button>
                                </form>
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
