<?php
namespace App\Http\Controllers;

use App\Exports\Hr\AllDateExport;
use App\Exports\Hr\DateExport;
use App\Exports\Hr\DBDExport;
use App\Exports\Hr\HrGroupsTemplateExport;
use App\Exports\Hr\OnebookExport;
use App\Exports\Hr\ResultsTemplateExport;
use App\Imports\HrGroupsImport;
use App\Imports\HrResultsImport;
use App\Jobs\HrAssignSeatForAttendance;
use App\Jobs\HrProjectSeatAssignment;
use App\Models\HrAttend;
use App\Models\HrDate;
use App\Models\HrGroup;
use App\Models\HrProject;
use App\Models\HrResult;
use App\Models\HrSeat;
use App\Models\HrTime;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\HrLoggingTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class HRController extends Controller
{
    use HrLoggingTrait;

    // ========================================================================
    // PUBLIC USER INTERFACE METHODS
    // ========================================================================

    /**
     * Display the main HRD index page with available projects
     */
    public function Index()
    {
        $payload = $this->prepareDashboardPayload();
        return view('hrd.dashboard', $payload);
    }

    // ========================================================================
    // PRIVATE HELPER METHODS - USER INTERFACE
    // ========================================================================

    /**
     * Aggregate all data for the dashboard page into one payload
     */
    private function prepareDashboardPayload(): array
    {
        $projects        = $this->fetchPaginatedProjectsWithRelations();
        $ongoingProjects = $this->getOngoingProjects($projects);

        $projectsWithStates = $projects->map(function ($project) {
            return [
                'project'           => $project,
                'registrationState' => $this->calculateProjectRegistrationState($project),
            ];
        });

        return compact('projects', 'projectsWithStates', 'ongoingProjects');
    }

    /**
     * Common query to fetch public projects with required relations
     */
    private function fetchPaginatedProjectsWithRelations()
    {
        return HrProject::availableForRegistration()
            ->with([
                'dates'       => function ($query) {
                    $query->where('date_delete', false)
                        ->orderBy('date_datetime', 'asc');
                },
                'dates.times' => function ($query) {
                    $query->where('time_delete', false)
                        ->orderBy('time_start', 'asc');
                },
                'attends'     => function ($query) {
                    $query->where('attend_delete', false);
                },
            ])
            ->orderBy('project_end_register', 'asc')
            ->paginate(12);
    }

    /**
     * Get ongoing projects that user can check in for
     */
    private function getOngoingProjects($projects)
    {
        $now             = now();
        $userId          = auth()->id();
        $ongoingProjects = collect();

        foreach ($projects as $project) {
            // Skip attendance projects from ongoing section
            if ($project->project_type === 'attendance') {
                continue;
            }

            $checkInSessions = $this->getCheckInSessions($project, $now, $userId);

            if ($checkInSessions->isNotEmpty()) {
                $ongoingProjects->push([
                    'project'  => $project,
                    'sessions' => $checkInSessions,
                ]);
            }
        }

        return $ongoingProjects;
    }

    /**
     * Get check-in sessions for a project
     */
    private function getCheckInSessions($project, $now, $userId)
    {
        $sessions = collect();
        $today    = $now->format('Y-m-d');

        foreach ($project->dates as $date) {
            $dateString = $date->date_datetime->format('Y-m-d');

            if ($dateString === $today) {
                foreach ($date->times as $time) {
                    if ($this->isWithinCheckInWindow($time, $now)) {
                        $canCheckIn    = false;
                        $checkInRoute  = '';
                        $checkInMethod = 'POST';
                        $checkInData   = [];

                        if ($project->project_type === 'attendance') {
                            $attendanceRecord = $project->attends
                                ->where('user_id', $userId)
                                ->where('time_id', $time->id)
                                ->where('attend_delete', false)
                                ->first();

                            if (! $attendanceRecord || ! $attendanceRecord->attend_datetime) {
                                $canCheckIn   = true;
                                $checkInRoute = route('hrd.projects.attend.store', $project->id);
                                $checkInData  = ['time_id' => $time->id];
                            }
                        } else {
                            $userRegistration = $project->attends
                                ->where('user_id', $userId)
                                ->where('time_id', $time->id)
                                ->where('attend_delete', false)
                                ->first();

                            if ($userRegistration && ! $userRegistration->attend_datetime) {
                                $canCheckIn   = true;
                                $checkInRoute = route('hrd.projects.stamp.store', [$project->id, $userRegistration->id]);
                            }
                        }

                        if ($canCheckIn) {
                            $sessions->push([
                                'date'          => $date,
                                'time'          => $time,
                                'checkInRoute'  => $checkInRoute,
                                'checkInMethod' => $checkInMethod,
                                'checkInData'   => $checkInData,
                                'userSeat'      => $this->getUserSeat($time, $userId),
                            ]);
                        }
                    }
                }
            }
        }

        return $sessions;
    }

    /**
     * Determine if the current time is within the check-in window (30m before start until end)
     */
    private function isWithinCheckInWindow($time, Carbon $now): bool
    {
        $currentTime  = $now->format('H:i:s');
        $timeEnd      = Carbon::parse($time->time_end)->format('H:i:s');
        $earlyCheckIn = Carbon::parse($time->time_start)->subMinutes(30)->format('H:i:s');
        return $currentTime >= $earlyCheckIn && $currentTime <= $timeEnd;
    }

    /**
     * Get user's assigned seat for a time slot
     */
    private function getUserSeat($time, $userId)
    {
        return $time->seats()
            ->where('user_id', $userId)
            ->where('seat_delete', false)
            ->first();
    }

    private function getUserGroup($projectId, $userId)
    {
        return HrGroup::where('project_id', $projectId)
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * Display project details page
     */
    public function projectShow($id)
    {
        $payload = $this->prepareProjectDetailsPayload($id);
        if ($payload instanceof RedirectResponse) {
            return $payload;
        }
        return view('hrd.project-details', $payload);
    }

    /**
     * Aggregate and validate all data for project details page
     */
    private function prepareProjectDetailsPayload($id)
    {
        $project = HrProject::with([
            'dates' => function ($query) {
                $query->where('date_delete', false)
                    ->orderBy('date_datetime', 'asc');
            },
            'dates.times.activeAttends',
            'links',
            'attends.user',
            'activeAttends',
        ])->findOrFail($id);

        if (! $project->project_active || $project->project_delete) {
            return redirect()->route('hrd.index')
                ->with('error', 'This project is not available.');
        }

        if (now() > $project->project_end_register) {
            return redirect()->route('hrd.index')
                ->with('error', 'This project registration period has ended.');
        }

        $registrationData      = $this->calculateRegistrationState($project);
        $availableCheckIns     = $this->getAvailableCheckInSessions($project);
        $registrationTimeSlots = $this->getRegistrationTimeSlots($project);
        $scheduleView          = $this->buildScheduleViewModel($project, $registrationData);

        return compact('project', 'registrationData', 'availableCheckIns', 'registrationTimeSlots', 'scheduleView');
    }

    /**
     * Display user guide page
     */
    public function userGuide()
    {
        return view('hrd.user-manual');
    }

    /**
     * Get available check-in sessions for all project types
     */
    private function getAvailableCheckInSessions($project)
    {
        $now               = now();
        $today             = $now->format('Y-m-d');
        $currentTime       = $now->format('H:i:s');
        $userId            = auth()->id();
        $availableCheckIns = collect();

        foreach ($project->dates as $date) {
            $dateString = $date->date_datetime->format('Y-m-d');

            // Skip past dates
            if ($dateString < $today) {
                continue;
            }

            if ($dateString === $today) {
                foreach ($date->times as $time) {
                    $timeStart = Carbon::parse($time->time_start)->format('H:i:s');
                    $timeEnd   = Carbon::parse($time->time_end)->format('H:i:s');

                    // Calculate early check-in time (30 minutes before start)
                    $earlyCheckInTime = Carbon::parse($time->time_start)->subMinutes(30)->format('H:i:s');

                    // Allow check-in from 30 minutes before start until end time
                    if ($currentTime >= $earlyCheckInTime && $currentTime <= $timeEnd) {
                        if ($project->project_type === 'attendance') {
                            // For attendance projects, check if user hasn't attended yet
                            $attendanceRecord = $project->attends
                                ->where('user_id', $userId)
                                ->where('time_id', $time->id)
                                ->where('attend_delete', false)
                                ->first();

                            if (! $attendanceRecord || ! $attendanceRecord->attend_datetime) {
                                $availableCheckIns->push([
                                    'date'             => $date,
                                    'time'             => $time,
                                    'attendanceRecord' => $attendanceRecord,
                                    'userSeat'         => $this->getUserSeat($time, $userId),
                                    'userGroup'        => $this->getUserGroup($project->id, $userId),
                                    'projectType'      => 'attendance',
                                ]);
                            }
                        } else {
                            // For single/multiple projects, check if user is registered and hasn't attended
                            $userRegistration = $project->attends
                                ->where('user_id', $userId)
                                ->where('time_id', $time->id)
                                ->where('attend_delete', false)
                                ->first();

                            if ($userRegistration && ! $userRegistration->attend_datetime) {
                                $availableCheckIns->push([
                                    'date'             => $date,
                                    'time'             => $time,
                                    'userRegistration' => $userRegistration,
                                    'userSeat'         => $this->getUserSeat($time, $userId),
                                    'userGroup'        => $this->getUserGroup($project->id, $userId),
                                    'projectType'      => $project->project_type,
                                ]);
                            }
                        }
                    }
                }
            }
        }

        return $availableCheckIns;
    }

    /**
     * Build a simplified schedule view model for the project details page
     */
    private function buildScheduleViewModel($project, array $registrationData)
    {
        $userId            = auth()->id();
        $timeSlotStates    = $registrationData['timeSlotStates'] ?? [];
        $userRegistrations = $registrationData['userRegistrations'] ?? collect();

        $now      = now();
        $today    = $now->format('Y-m-d');
        $schedule = [];

        foreach ($project->dates->where('date_delete', false) as $date) {
            $dateString  = $date->date_datetime->format('Y-m-d');
            $dateIsToday = $dateString === $today;

            $dateVm = [
                'title'       => $date->date_title,
                'formatted'   => $date->date_datetime->format('l, d M Y'),
                'detail'      => $date->date_detail,
                'location'    => $date->date_location,
                'dateIsToday' => $dateIsToday,
                'times'       => [],
            ];

            foreach ($date->times->where('time_delete', false) as $time) {
                $state = $timeSlotStates[$time->id] ?? [];

                $userRegistration = $userRegistrations->where('time_id', $time->id)->first();
                $hasAttendedState = (bool) ($state['hasAttended'] ?? false);
                $hasAttended      = $hasAttendedState || ($userRegistration && $userRegistration->attend_datetime);
                $userRegistered   = (bool) ($state['userRegistered'] ?? false);

                $earlyCheckIn = Carbon::parse($time->time_start)->subMinutes(30);
                $timeStart    = Carbon::parse($time->time_start);
                $timeEnd      = Carbon::parse($time->time_end);

                $registeredCount = $time->activeAttends->count();
                $availableCount  = $time->time_limit ? max(0, $time->time_max - $registeredCount) : null;

                // Resolve link visibility (only when session active AND attended)
                $showLinksResolved = (bool) ($state['showLinks'] ?? false) && $hasAttended;

                // Unregister rule: allowed if not attended AND (register_today or not same-day)
                $canUnregister = ! $hasAttended && ($project->project_register_today || ! $dateIsToday);

                $dateVm['times'][] = [
                    'timeId'             => $time->id,
                    'timeRange'          => $timeStart->format('H:i') . ' - ' . $timeEnd->format('H:i'),
                    'timeDetail'         => $time->time_detail,
                    'timeLimit'          => (bool) $time->time_limit,
                    'availableCount'     => $availableCount,
                    'hasAttended'        => $hasAttended,
                    'userRegistered'     => $userRegistered,
                    'showLinks'          => $showLinksResolved,
                    'canStamp'           => (bool) ($state['canStamp'] ?? false),
                    'canCheckIn'         => (bool) ($state['canCheckIn'] ?? false),
                    'timeSlotMessage'    => $state['timeSlotMessage'] ?? null,
                    'attendanceRecord'   => $state['attendanceRecord'] ?? null,
                    'checkinFromText'    => $earlyCheckIn->format('H:i'),
                    'userSeat'           => $this->getUserSeat($time, $userId),
                    'userGroup'          => $this->getUserGroup($project->id, $userId),
                    'userRegistrationId' => $userRegistration->id ?? null,
                    'registeredAtText'   => $userRegistration && ! $userRegistration->attend_datetime ? $userRegistration->created_at->format('d M Y, H:i') : null,
                    'attendedAtText'     => $userRegistration && $userRegistration->attend_datetime ? $userRegistration->attend_datetime->format('d M Y, H:i') : null,
                ];
            }

            $schedule[] = $dateVm;
        }

        return $schedule;
    }

    /**
     * Calculate registration state and availability for a project
     */
    private function calculateRegistrationState($project)
    {
        $now    = now();
        $today  = $now->format('Y-m-d');
        $userId = auth()->id();

        // Get user's current registrations using direct query to ensure accuracy
        $userRegistrations = HrAttend::where('project_id', $project->id)
            ->where('user_id', $userId)
            ->where('attend_delete', false)
            ->get();

        // Analyze project dates (only future dates)
        $hasFutureDates    = false;
        $hasOnlyTodayDates = true;
        $todayDates        = collect();
        $futureDates       = collect();

        foreach ($project->dates as $date) {
            $dateString = $date->date_datetime->format('Y-m-d');

            if ($dateString > $today) {
                $hasFutureDates    = true;
                $hasOnlyTodayDates = false;
                $futureDates->push($date);
            } elseif ($dateString === $today) {
                $hasOnlyTodayDates = $hasOnlyTodayDates && true;
                $todayDates->push($date);
            }
            // Skip past dates
        }

        // Calculate base registration availability
        $isWithinRegistrationPeriod = $now >= $project->project_start_register && $now <= $project->project_end_register;
        $isUpcoming                 = $now < $project->project_start_register;
        $isExpired                  = $now > $project->project_end_register;

        // Check same-day registration rules
        $canRegisterToday = $project->project_register_today || $hasFutureDates;

        $baseCanRegister = $project->project_active &&
        ! $project->project_delete &&
        $isWithinRegistrationPeriod &&
        $project->project_type !== 'attendance' &&
            $canRegisterToday;

        // Calculate registration state based on project type
        $canRegister      = false;
        $showRegisterForm = false;
        $canReselect      = false;
        $statusBadge      = null;

        if ($project->project_type === 'attendance') {
            $statusBadge = [
                'type' => 'purple',
                'icon' => 'fas fa-info-circle',
                'text' => 'ไม่ต้องลงทะเบียน',
            ];
        } elseif ($project->project_type === 'multiple') {
            // Multiple type always shows registration form if within registration period
            $showRegisterForm = $baseCanRegister;
            $canRegister      = $baseCanRegister;

            if ($userRegistrations->count() > 0) {
                // User has already registered — keep form visible for additional selections on multiple
                $showRegisterForm = $baseCanRegister;
                $canRegister      = $baseCanRegister;

                // Determine if registration is complete for all dates or partial
                $projectDates         = $project->dates->where('date_delete', false);
                $totalDates           = $projectDates->count();
                $registeredDatesCount = $userRegistrations
                    ->where('attend_delete', false)
                    ->pluck('date_id')
                    ->unique()
                    ->count();

                $registrationText = 'ลงทะเบียนแล้ว';
                if ($totalDates > 0 && $registeredDatesCount < $totalDates) {
                    $registrationText = 'ลงทะเบียนแล้วบางส่วน';
                }

                $statusBadge = [
                    'type' => 'blue',
                    'icon' => 'fas fa-user-check',
                    'text' => $registrationText,
                ];
            } else {
                if ($showRegisterForm) {
                    $statusBadge = [
                        'type' => 'green',
                        'icon' => 'fas fa-check-circle',
                        'text' => 'เปิดรับลงทะเบียน',
                    ];
                } elseif ($isUpcoming) {
                    $statusBadge = [
                        'type' => 'yellow',
                        'icon' => 'fas fa-clock',
                        'text' => 'เร็วๆ นี้',
                    ];
                } elseif ($isExpired) {
                    $statusBadge = [
                        'type' => 'gray',
                        'icon' => 'fas fa-lock',
                        'text' => 'ปิดรับลงทะเบียน',
                    ];
                }
            }
        } else {
            // Single type
            if ($userRegistrations->count() > 0) {
                // User has already registered
                $showRegisterForm = false;
                $canRegister      = false;

                $statusBadge = [
                    'type' => 'blue',
                    'icon' => 'fas fa-user-check',
                    'text' => 'ลงทะเบียนแล้ว',
                ];
            } else {
                // User hasn't registered yet
                $showRegisterForm = $baseCanRegister;
                $canRegister      = $baseCanRegister;

                if ($showRegisterForm) {
                    $statusBadge = [
                        'type' => 'green',
                        'icon' => 'fas fa-check-circle',
                        'text' => 'เปิดรับลงทะเบียน',
                    ];
                } elseif ($isUpcoming) {
                    $statusBadge = [
                        'type' => 'yellow',
                        'icon' => 'fas fa-clock',
                        'text' => 'เร็วๆ นี้',
                    ];
                } elseif ($isExpired) {
                    $statusBadge = [
                        'type' => 'gray',
                        'icon' => 'fas fa-lock',
                        'text' => 'ปิดรับลงทะเบียน',
                    ];
                }
            }
        }

        // Check if we should show same-day notice
        $showSameDayNotice = $project->project_type !== 'attendance' &&
        $hasOnlyTodayDates &&
        ! $project->project_register_today &&
            $baseCanRegister;

        // Calculate time slot states
        $timeSlotStates = $this->calculateTimeSlotStates($project, $userRegistrations, $today);

        // Calculate attendance status
        $attendanceStatus = $this->calculateAttendanceStatus($project, $userRegistrations);

        return [
            'userRegistrations'          => $userRegistrations,
            'canRegister'                => $canRegister,
            'showRegisterForm'           => $showRegisterForm,
            'canReselect'                => $canReselect,
            'statusBadge'                => $statusBadge,
            'showSameDayNotice'          => $showSameDayNotice,
            'timeSlotStates'             => $timeSlotStates,
            'hasFutureDates'             => $hasFutureDates,
            'hasOnlyTodayDates'          => $hasOnlyTodayDates,
            'isWithinRegistrationPeriod' => $isWithinRegistrationPeriod,
            'isUpcoming'                 => $isUpcoming,
            'isExpired'                  => $isExpired,
            'attendanceStatus'           => $attendanceStatus,
        ];
    }

    /**
     * Get registration time slots with availability data (future dates only)
     */
    private function getRegistrationTimeSlots($project)
    {
        $now               = now();
        $today             = $now->format('Y-m-d');
        $userId            = auth()->id();
        $registrationSlots = collect();

        // Get user's current registrations for this project
        $userRegistrations = HrAttend::where('project_id', $project->id)
            ->where('user_id', $userId)
            ->where('attend_delete', false)
            ->get();

        foreach ($project->dates as $date) {
            $dateString = $date->date_datetime->format('Y-m-d');

            // Skip past dates
            if ($dateString < $today) {
                continue;
            }

            $dateIsToday            = $dateString === $today;
            $showDateInRegistration = $project->project_register_today || ! $dateIsToday;

            if ($showDateInRegistration) {
                $dateSlots = collect();

                foreach ($date->times->where('time_delete', false) as $time) {
                    $isLimited            = $time->time_limit;
                    $currentRegistrations = $time->activeAttends->count();
                    $available            = $isLimited ? $time->time_max - $currentRegistrations : null;
                    $isFull               = $isLimited && $available <= 0;

                    // Check if user is already registered for this time slot
                    $userRegistered = $userRegistrations->where('time_id', $time->id)->first();

                    // Get user seat if seat assignment is enabled
                    $userSeat = null;
                    if ($project->project_seat_assign) {
                        $userSeat = $this->getUserSeat($time, $userId);
                    }

                    $dateSlots->push([
                        'time'                 => $time,
                        'isLimited'            => $isLimited,
                        'currentRegistrations' => $currentRegistrations,
                        'available'            => $available,
                        'isFull'               => $isFull,
                        'userSeat'             => $userSeat,
                        'userRegistered'       => $userRegistered,
                    ]);
                }

                if ($dateSlots->isNotEmpty()) {
                    $registrationSlots->push([
                        'date'                   => $date,
                        'dateIsToday'            => $dateIsToday,
                        'showDateInRegistration' => $showDateInRegistration,
                        'slots'                  => $dateSlots,
                    ]);
                }
            }
        }

        return $registrationSlots;
    }

    /**
     * Calculate time slot states for each session
     */
    private function calculateTimeSlotStates($project, $userRegistrations, $today)
    {
        $timeSlotStates = [];
        $now            = now();
        $currentTime    = $now->format('H:i:s');

        foreach ($project->dates as $date) {
            $dateString = $date->date_datetime->format('Y-m-d');

            // Skip past dates
            if ($dateString < $today) {
                continue;
            }

            foreach ($date->times as $time) {
                $timeStart = Carbon::parse($time->time_start)->format('H:i:s');
                $timeEnd   = Carbon::parse($time->time_end)->format('H:i:s');

                // Calculate early check-in time (30 minutes before start)
                $earlyCheckInTime = Carbon::parse($time->time_start)->subMinutes(30)->format('H:i:s');

                $userRegisteredForTime = $userRegistrations->where('time_id', $time->id)->first();
                $hasAttendedRegistered = $userRegisteredForTime && $userRegisteredForTime->attend_datetime;

                $state = [
                    'userRegistered'   => $userRegisteredForTime ? true : false,
                    'hasAttended'      => $hasAttendedRegistered,
                    'canStamp'         => false,
                    'canCheckIn'       => false,
                    'attendanceRecord' => null,
                    'timeSlotMessage'  => null,
                    'showLinks'        => false,
                ];

                // For attendance projects
                if ($project->project_type === 'attendance') {
                    $attendanceRecord = $project->attends
                        ->where('user_id', auth()->id())
                        ->where('time_id', $time->id)
                        ->where('attend_delete', false)
                        ->first();

                    $state['attendanceRecord'] = $attendanceRecord;
                    $state['hasAttended']      = $attendanceRecord && $attendanceRecord->attend_datetime;

                    // Check if can check in (from 30 minutes before start until end)
                    $state['canCheckIn'] = $today === $dateString &&
                    $currentTime >= $earlyCheckInTime &&
                    $currentTime <= $timeEnd &&
                    ! $state['hasAttended'];

                    if (! $state['hasAttended'] && ! $state['canCheckIn']) {
                        if ($today !== $dateString) {
                            $state['timeSlotMessage'] = 'Check-in available on ' . $date->date_datetime->format('d M Y');
                        } elseif ($currentTime < $earlyCheckInTime) {
                            $state['timeSlotMessage'] = 'Check-in available from ' . Carbon::parse($earlyCheckInTime)->format('H:i');
                        } elseif ($currentTime > $timeEnd) {
                            $state['timeSlotMessage'] = 'Check-in period ended';
                        }
                    }
                }

                // For registered users (single/multiple projects)
                if ($userRegisteredForTime && ! $hasAttendedRegistered) {
                    $state['canStamp'] = $today === $dateString &&
                        $currentTime >= $earlyCheckInTime &&
                        $currentTime <= $timeEnd;

                    if (! $state['canStamp']) {
                        if ($today !== $dateString) {
                            $state['timeSlotMessage'] = 'Check-in on ' . $date->date_datetime->format('d M Y');
                        } elseif ($currentTime < $earlyCheckInTime) {
                            $state['timeSlotMessage'] = 'Check-in from ' . Carbon::parse($earlyCheckInTime)->format('H:i');
                        } elseif ($currentTime > $timeEnd) {
                            $state['timeSlotMessage'] = 'Check-in period ended';
                        }
                    }
                }

                // Check if links should be shown (during active session time)
                $state['showLinks'] = $today === $dateString &&
                    $currentTime >= $timeStart &&
                    $currentTime <= $timeEnd;

                $timeSlotStates[$time->id] = $state;
            }
        }

        return $timeSlotStates;
    }

    // ========================================================================
    // USER REGISTRATION & ATTENDANCE ACTIONS
    // ========================================================================

    public function projectRegisterStore(Request $request, $id)
    {
        $project = HrProject::with(['dates.times'])->findOrFail($id);

        // Validation based on project type
        $request->validate([
            'project_type' => 'required|in:single,multiple',
            'time_ids'     => 'required|array|min:1',
            'time_ids.*'   => 'required|exists:hr_times,id',
        ]);

        // Check if registration is still available
        $now         = now();
        $canRegister = $project->project_active &&
        ! $project->project_delete &&
        $now >= $project->project_start_register &&
        $now <= $project->project_end_register &&
        $project->project_type !== 'attendance';

        if (! $canRegister) {
            return back()->withErrors(['error' => 'ไม่สามารถลงทะเบียนได้ โปรเจกต์นี้ปิดรับลงทะเบียนแล้ว']);
        }

        // Check project type constraints
        if ($project->project_type === 'single' && count($request->time_ids) > 1) {
            return back()->withErrors(['error' => 'คุณสามารถเลือกช่วงเวลาได้เพียงช่วงเดียวสำหรับโปรเจกต์นี้']);
        }

        // Check if user already registered (for single type projects)
        if ($project->project_type === 'single') {
            $existingRegistration = $project->attends()
                ->where('user_id', auth()->id())
                ->where('attend_delete', false)
                ->first();

            if ($existingRegistration) {
                return back()->withErrors(['error' => 'คุณได้ลงทะเบียนสำหรับโปรเจกต์นี้แล้ว']);
            }
        }

        // Verify all selected times belong to this project and check capacity
        $selectedTimes = [];
        $dateTimeMap   = [];

        foreach ($request->time_ids as $timeId) {
            $selectedTime = null;
            $selectedDate = null;

            // Find the time and its corresponding date
            foreach ($project->dates as $date) {
                $time = $date->times->where('id', $timeId)->first();
                if ($time) {
                    $selectedTime = $time;
                    $selectedDate = $date;
                    break;
                }
            }

            if (! $selectedTime || ! $selectedDate) {
                return back()->withErrors(['error' => 'การเลือกช่วงเวลาไม่ถูกต้อง']);
            }

            // Check if user already registered for this specific time slot (for multiple type)
            if ($project->project_type === 'multiple') {
                $existingRegistration = $project->attends()
                    ->where('user_id', auth()->id())
                    ->where('time_id', $timeId)
                    ->where('attend_delete', false)
                    ->first();

                if ($existingRegistration) {
                    return back()->withErrors(['error' => "คุณได้ลงทะเบียนในช่วงเวลา: {$selectedTime->time_title} แล้ว"]);
                }
            }

            // Check if time slot has capacity (if limited)
            if ($selectedTime->time_limit) {
                $currentRegistrations = $selectedTime->attends()->where('attend_delete', false)->count();
                if ($currentRegistrations >= $selectedTime->time_max) {
                    return back()->withErrors(['error' => "ช่วงเวลา '{$selectedTime->time_title}' เต็มแล้ว"]);
                }
            }

            $selectedTimes[]      = $selectedTime;
            $dateTimeMap[$timeId] = $selectedDate->id;
        }

        try {
            DB::beginTransaction();

            // Create registrations for all selected time slots
            foreach ($request->time_ids as $timeId) {
                $attendance = $project->attends()->create([
                    'date_id'         => $dateTimeMap[$timeId],
                    'time_id'         => $timeId,
                    'user_id'         => auth()->id(),
                    'attend_datetime' => ($project->project_type === 'attendance') ? now() : null,
                    'attend_delete'   => false,
                ]);

                // Dispatch seat assignment job if project has seat assignment enabled
                if ($project->project_seat_assign) {
                    HrAssignSeatForAttendance::dispatch($attendance->id);
                }
            }

            DB::commit();

            // Log user registration
            $this->logUserRegistration($project, auth()->user(), $request->time_ids, [
                'session_count'       => count($request->time_ids),
                'registration_method' => 'user_self_registration',
            ]);

            $sessionCount = count($request->time_ids);
            $message      = $sessionCount === 1
            ? 'ลงทะเบียนเซสชันสำเร็จ!'
            : "ลงทะเบียน {$sessionCount} เซสชันสำเร็จ!";

            return redirect()->route('hrd.projects.show', $id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();

            // Log error
            $this->logHrError($e, 'project_registration', [
                'project_id' => $id,
                'user_id'    => auth()->id(),
                'time_ids'   => $request->time_ids,
            ]);

            return back()->withErrors(['error' => 'การลงทะเบียนล้มเหลว: ' . $e->getMessage()]);
        }
    }

    public function projectAttendStore(Request $request, $id)
    {
        try {
            $project = HrProject::findOrFail($id);

            // Verify it's an attendance project
            if ($project->project_type !== 'attendance') {
                return redirect()->back()->with('error', 'นี่ไม่ใช่โปรเจกต์เข้าร่วม');
            }

            // Verify project is active
            if (! $project->project_active || $project->project_delete) {
                return redirect()->back()->with('error', 'โปรเจกต์นี้ไม่เปิดใช้งาน');
            }

            $request->validate([
                'time_id' => 'required|exists:hr_times,id',
            ]);

            $timeId = $request->time_id;

            // Verify the time belongs to this project
            $time = HrTime::with(['date'])->findOrFail($timeId);
            if ($time->date->project_id != $project->id) {
                return redirect()->back()->with('error', 'ช่วงเวลาไม่ถูกต้องสำหรับโปรเจกต์นี้');
            }

            // Check if today matches the date and current time is within range
            $today       = now()->format('Y-m-d');
            $currentTime = now()->format('H:i:s');
            $dateTime    = $time->date->date_datetime->format('Y-m-d');

            if ($today !== $dateTime) {
                return redirect()->back()->with('error', 'การเช็คอินมีเฉพาะในวันที่กำหนดเท่านั้น');
            }

            // Check if current time is within the time slot range (30 minutes before start until end)
            $timeStart = Carbon::parse($time->time_start)->format('H:i:s');
            $timeEnd   = Carbon::parse($time->time_end)->format('H:i:s');

            // Calculate early check-in time (30 minutes before start)
            $earlyCheckInTime = Carbon::parse($time->time_start)->subMinutes(30)->format('H:i:s');

            if ($currentTime < $earlyCheckInTime || $currentTime > $timeEnd) {
                return redirect()->back()->with('error', 'การเช็คอินมีเฉพาะตั้งแต่ 30 นาทีก่อนเวลาเริ่มต้นจนถึงเวลาสิ้นสุดเท่านั้น');
            }

            // Check if user has already checked in for this time slot
            $existingAttend = HrAttend::where('project_id', $project->id)
                ->where('user_id', auth()->id())
                ->where('time_id', $timeId)
                ->where('attend_delete', false)
                ->first();

            if ($existingAttend) {
                return redirect()->back()->with('error', 'คุณได้เช็คอินสำหรับเซสชันนี้แล้ว');
            }

            // Create attendance record
            $attendance = HrAttend::create([
                'project_id'      => $project->id,
                'date_id'         => $time->date_id,
                'time_id'         => $timeId,
                'user_id'         => auth()->id(),
                'attend_datetime' => now(),
                'attend_delete'   => false,
            ]);

            // Dispatch seat assignment job if project has seat assignment enabled
            if ($project->project_seat_assign) {
                HrAssignSeatForAttendance::dispatch($attendance->id);
            }

            // Log attendance recording
            $this->logUserAttendance($project, auth()->user(), $time, 'attendance_checkin', [
                'attendance_id'     => $attendance->id,
                'attendance_method' => 'user_self_checkin',
            ]);

            return redirect()->route('hrd.projects.show', $id)->with('success', 'บันทึกการเข้าร่วมสำเร็จ!');

        } catch (\Exception $e) {
            // Log error
            $this->logHrError($e, 'attendance_recording', [
                'project_id' => $id,
                'user_id'    => auth()->id(),
                'time_id'    => $request->time_id,
            ]);

            return redirect()->back()->with('error', 'การบันทึกการเข้าร่วมล้มเหลว: ' . $e->getMessage());
        }
    }

    public function projectStampAttendance(Request $request, $id, $attendId)
    {
        try {
            $project = HrProject::findOrFail($id);

            // Find the attendance record
            $attendance = HrAttend::with(['time.date'])
                ->where('id', $attendId)
                ->where('project_id', $project->id)
                ->where('user_id', auth()->id())
                ->where('attend_delete', false)
                ->firstOrFail();

            // Check if already stamped (has attend_datetime)
            if ($attendance->attend_datetime) {
                return redirect()->back()->with('error', 'คุณได้เช็คอินสำหรับเซสชันนี้แล้ว');
            }

            // Verify project is active
            if (! $project->project_active || $project->project_delete) {
                return redirect()->back()->with('error', 'โปรเจกต์นี้ไม่เปิดใช้งาน');
            }

            // Get the time slot details
            $time = $attendance->time;
            $date = $time->date;

            // Check if today matches the date and current time is within range
            $today       = now()->format('Y-m-d');
            $currentTime = now()->format('H:i:s');
            $dateTime    = $date->date_datetime->format('Y-m-d');

            if ($today !== $dateTime) {
                return redirect()->back()->with('error', 'การเช็คอินมีเฉพาะในวันที่กำหนดเท่านั้น');
            }

            // Check if current time is within the time slot range (30 minutes before start until end)
            $timeStart = Carbon::parse($time->time_start)->format('H:i:s');
            $timeEnd   = Carbon::parse($time->time_end)->format('H:i:s');

            // Calculate early check-in time (30 minutes before start)
            $earlyCheckInTime = Carbon::parse($time->time_start)->subMinutes(30)->format('H:i:s');

            if ($currentTime < $earlyCheckInTime || $currentTime > $timeEnd) {
                return redirect()->back()->with('error', 'การเช็คอินมีเฉพาะตั้งแต่ 30 นาทีก่อนเวลาเริ่มต้นจนถึงเวลาสิ้นสุดเท่านั้น');
            }

            // Update the attendance record with current timestamp
            $attendance->update([
                'attend_datetime' => now(),
            ]);

            // Log stamp attendance
            $this->logUserAttendance($attendance->project, $attendance->user, $attendance->time, 'stamp_checkin', [
                'attendance_id'     => $attendance->id,
                'attendance_method' => 'user_self_stamp',
            ]);

            return redirect()->route('hrd.projects.show', $id)->with('success', 'เช็คอินสำเร็จ! บันทึกการเข้าร่วมแล้ว');

        } catch (\Exception $e) {
            // Log error
            $this->logHrError($e, 'stamp_attendance', [
                'project_id' => $id,
                'attend_id'  => $attendId,
                'user_id'    => auth()->id(),
            ]);

            return redirect()->back()->with('error', 'การบันทึกการเช็คอินล้มเหลว: ' . $e->getMessage());
        }
    }

    public function projectReselectRegistration(Request $request, $id)
    {
        try {
            $project = HrProject::findOrFail($id);
            $userId  = auth()->id();

            // Check if user has registrations for this project
            $userRegistrations = $project->attends()
                ->where('user_id', $userId)
                ->where('attend_delete', false)
                ->get();

            if ($userRegistrations->isEmpty()) {
                return redirect()->route('hrd.projects.show', $id)
                    ->with('error', 'คุณยังไม่ได้ลงทะเบียนสำหรับโปรเจกต์นี้');
            }

            // Check if any registrations have been attended
            $attendedRegistrations = $userRegistrations->whereNotNull('attend_datetime');
            if ($attendedRegistrations->isNotEmpty()) {
                return redirect()->route('hrd.projects.show', $id)
                    ->with('error', 'ไม่สามารถล้างการลงทะเบียนได้เนื่องจากคุณได้เข้าร่วมบางเซสชันแล้ว');
            }

            // Check if any registrations are for today and project doesn't allow same-day registration
            if (! $project->project_register_today) {
                $today              = now()->format('Y-m-d');
                $todayRegistrations = $userRegistrations->filter(function ($registration) use ($today) {
                    return $registration->date && $registration->date->date_datetime->format('Y-m-d') === $today;
                });

                if ($todayRegistrations->isNotEmpty()) {
                    return redirect()->route('hrd.projects.show', $id)
                        ->with('error', 'ไม่สามารถล้างการลงทะเบียนในวันเดียวกันได้');
                }
            }

            // Log reselection operation
            $this->logBulkOperation('USER_RESELECTION', $project, $userRegistrations->count(), [
                'user_id'   => $userId,
                'user_name' => auth()->user()->name,
                'reason'    => 'user_requested_reselection',
            ]);

            // Soft delete all user registrations for this project and remove seat assignments
            $userRegistrations->each(function ($registration) {
                $registration->update(['attend_delete' => true]);
                $registration->removeSeatAssignment();
            });

            return redirect()->route('hrd.projects.show', $id)
                ->with('success', 'ล้างการลงทะเบียนเรียบร้อยแล้ว คุณสามารถลงทะเบียนใหม่ได้');

        } catch (\Exception $e) {
            return redirect()->route('hrd.projects.show', $id)
                ->with('error', 'เกิดข้อผิดพลาดในการล้างการลงทะเบียน: ' . $e->getMessage());
        }
    }

    public function projectUnregister(Request $request, $id, $registrationId)
    {
        try {
            $project = HrProject::findOrFail($id);
            $userId  = auth()->id();

            // Find the specific registration
            $registration = $project->attends()
                ->where('id', $registrationId)
                ->where('user_id', $userId)
                ->where('attend_delete', false)
                ->first();

            if (! $registration) {
                return redirect()->route('hrd.projects.show', $id)
                    ->with('error', 'ไม่พบการลงทะเบียนที่ต้องการยกเลิก');
            }

            // Disallow unregistration if user has already checked in (stamped attendance)
            if ($registration->attend_datetime) {
                return redirect()->route('hrd.projects.show', $id)
                    ->with('error', 'ไม่สามารถยกเลิกการลงทะเบียนหลังจากเช็คอินแล้ว');
            }

            // Check if this is a supported project type for unregistration
            if ($project->project_type !== 'single' && $project->project_type !== 'multiple' && $project->project_type !== 'attendance') {
                return redirect()->route('hrd.projects.show', $id)
                    ->with('error', 'ไม่สามารถยกเลิกการลงทะเบียนสำหรับโปรเจกต์ประเภทนี้ได้');
            }

            // Allow unregistration for all supported project types (except after checked-in)

            // Check if this is for today and project doesn't allow same-day registration
            if (! $project->project_register_today) {
                $today            = now()->format('Y-m-d');
                $registrationDate = $registration->date->date_datetime->format('Y-m-d');

                if ($registrationDate === $today) {
                    return redirect()->route('hrd.projects.show', $id)
                        ->with('error', 'ไม่สามารถยกเลิกการลงทะเบียนในวันเดียวกันได้');
                }
            }

            // Log user unregistration
            $this->logUserUnregistration($project, auth()->user(), $registration, [
                'unregistration_reason' => 'user_requested',
                'registration_date'     => $registration->created_at,
            ]);

            // Soft delete the registration
            $registration->update(['attend_delete' => true]);
            $registration->removeSeatAssignment();

            return redirect()->route('hrd.projects.show', $id)
                ->with('success', 'ยกเลิกการลงทะเบียนเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            return redirect()->route('hrd.projects.show', $id)
                ->with('error', 'เกิดข้อผิดพลาดในการยกเลิกการลงทะเบียน: ' . $e->getMessage());
        }
    }

    /**
     * Display user's attendance history
     */
    public function userHistory()
    {
        $userId            = auth()->id();
        $attendanceHistory = HrAttend::with([
            'project', 'date', 'time', 'user',
        ])
            ->where('user_id', $userId)
            ->where('attend_delete', false)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get legacy HR data
        $legacyTransactions = Transaction::with([
            'item.slot.project',
            'scoreHeader',
            'scoreData',
        ])
            ->where('user', auth()->user()->userid)
            ->where('transaction_active', true)
            ->orderBy('date', 'desc')
            ->where('project_id', '!=', 15)
            ->where('project_id', '!=', 16)
            ->get();

        // Calculate statistics including legacy data
        $statistics = $this->calculateCombinedHistoryStatistics($attendanceHistory, $legacyTransactions);

        return view('hrd.user-history', compact('attendanceHistory', 'statistics', 'legacyTransactions'));
    }

    /**
     * Calculate statistics for user history
     */
    private function calculateHistoryStatistics($attendanceHistory)
    {
        $totalRegistrations   = $attendanceHistory->total();
        $attendedCount        = $attendanceHistory->where('attend_datetime', '!=', null)->count();
        $pendingCount         = $totalRegistrations - $attendedCount;
        $approvedCount        = $attendanceHistory->where('approve_datetime', '!=', null)->count();
        $pendingApprovalCount = $totalRegistrations - $approvedCount;

        return [
            'total'           => $totalRegistrations,
            'attended'        => $attendedCount,
            'pending'         => $pendingCount,
            'approved'        => $approvedCount,
            'pendingApproval' => $pendingApprovalCount,
        ];
    }

    /**
     * Calculate statistics including legacy data
     */
    private function calculateCombinedHistoryStatistics($attendanceHistory, $legacyTransactions)
    {
        // New system statistics
        $newTotalRegistrations   = $attendanceHistory->total();
        $newAttendedCount        = $attendanceHistory->where('attend_datetime', '!=', null)->count();
        $newPendingCount         = $newTotalRegistrations - $newAttendedCount;
        $newApprovedCount        = $attendanceHistory->where('approve_datetime', '!=', null)->count();
        $newPendingApprovalCount = $newTotalRegistrations - $newApprovedCount;

        // Legacy system statistics
        $legacyTotalRegistrations = $legacyTransactions->count();
        $legacyAttendedCount      = $legacyTransactions->where('checkin_datetime', '!=', null)->count();
        $legacyPendingCount       = $legacyTotalRegistrations - $legacyAttendedCount;

        // Combined statistics
        $combinedTotal    = $newTotalRegistrations + $legacyTotalRegistrations;
        $combinedAttended = $newAttendedCount + $legacyAttendedCount;
        $combinedPending  = $newPendingCount + $legacyPendingCount;

        return [
            'total'           => $combinedTotal,
            'attended'        => $combinedAttended,
            'pending'         => $combinedPending,
            'approved'        => $newApprovedCount,
            'pendingApproval' => $newPendingApprovalCount,
            'legacy'          => [
                'total'    => $legacyTotalRegistrations,
                'attended' => $legacyAttendedCount,
                'pending'  => $legacyPendingCount,
            ],
            'new'             => [
                'total'    => $newTotalRegistrations,
                'attended' => $newAttendedCount,
                'pending'  => $newPendingCount,
            ],
        ];
    }

    // ========================================================================
    // ADMIN DASHBOARD & CORE PROJECT MANAGEMENT
    // ========================================================================

    /**
     * Display admin index page with all projects
     */
    public function adminIndex()
    {
        $projects = HrProject::with(['dates', 'attends'])
            ->orderBy('created_at', 'desc')
            ->where('project_delete', false)
            ->paginate(10);

        return view('hrd.admin.dashboard', compact('projects'));
    }

    /**
     * Display admin documentation page
     */
    public function adminDocumentation()
    {
        return view('hrd.admin.admin-manual');
    }

    /**
     * Display project creation form
     */
    public function adminProjectCreate()
    {
        return view('hrd.admin.projects.core.create-project');
    }

    /**
     * Store a new project
     */
    public function adminProjectStore(Request $request)
    {
        $request->validate([
            'project_name'                => 'required|string|max:255',
            'project_type'                => 'required|in:single,multiple,attendance',
            'project_detail'              => 'nullable|string',
            'project_seat_assign'         => 'boolean',
            'project_group_assign'        => 'boolean',
            'project_start_register'      => 'required|date',
            'project_end_register'        => 'required|date|after:project_start_register',
            'project_register_today'      => 'boolean',

            // Dates validation
            'dates'                       => 'required|array|min:1',
            'dates.*.date_title'          => 'required|string|max:255',
            'dates.*.date_detail'         => 'nullable|string',
            'dates.*.date_location'       => 'nullable|string|max:255',
            'dates.*.date_datetime'       => 'required|date_format:Y-m-d',

            // Times validation
            'dates.*.times'               => 'required|array|min:1',
            'dates.*.times.*.time_title'  => 'required|string|max:255',
            'dates.*.times.*.time_detail' => 'nullable|string',
            'dates.*.times.*.time_start'  => 'required|date_format:H:i',
            'dates.*.times.*.time_end'    => 'required|date_format:H:i|after:dates.*.times.*.time_start',
            'dates.*.times.*.time_limit'  => 'boolean',
            'dates.*.times.*.time_max'    => 'nullable|integer|min:0',

            // Links validation
            'links'                       => 'nullable|array',
            'links.*.link_name'           => 'required_with:links|string|max:255',
            'links.*.link_url'            => 'required_with:links|url',
            'links.*.link_limit'          => 'boolean',
            'links.*.link_time_start'     => 'nullable|date_format:H:i',
            'links.*.link_time_end'       => 'nullable|date_format:H:i|after:links.*.link_time_start',
        ]);

        DB::beginTransaction();

        try {
            // Create project
            $project = HrProject::create([
                'project_type'           => $request->project_type,
                'project_name'           => $request->project_name,
                'project_detail'         => $request->project_detail,
                'project_seat_assign'    => $request->boolean('project_seat_assign'),
                'project_group_assign'   => $request->boolean('project_group_assign'),
                'project_start_register' => $request->project_start_register,
                'project_end_register'   => $request->project_end_register,
                'project_register_today' => $request->boolean('project_register_today'),
            ]);

            // Create dates and times
            foreach ($request->dates as $dateData) {
                $date = $project->dates()->create([
                    'date_title'    => $dateData['date_title'],
                    'date_detail'   => $dateData['date_detail'] ?? null,
                    'date_location' => $dateData['date_location'] ?? null,
                    'date_datetime' => $dateData['date_datetime'],
                ]);

                // Create times for this date
                foreach ($dateData['times'] as $timeData) {
                    $date->times()->create([
                        'time_title'  => $timeData['time_title'],
                        'time_detail' => $timeData['time_detail'] ?? null,
                        'time_start'  => $timeData['time_start'],
                        'time_end'    => $timeData['time_end'],
                        'time_limit'  => $timeData['time_limit'] ?? false,
                        'time_max'    => $timeData['time_limit'] ? ($timeData['time_max'] ?? 1) : 0,
                    ]);
                }
            }

            // Create links if provided
            if ($request->has('links') && is_array($request->links)) {
                foreach ($request->links as $linkData) {
                    if (! empty($linkData['link_name']) && ! empty($linkData['link_url'])) {
                        $project->links()->create([
                            'link_name'       => $linkData['link_name'],
                            'link_url'        => $linkData['link_url'],
                            'link_limit'      => $linkData['link_limit'] ?? false,
                            'link_time_start' => $linkData['link_limit'] ? $linkData['link_time_start'] : null,
                            'link_time_end'   => $linkData['link_limit'] ? $linkData['link_time_end'] : null,
                        ]);
                    }
                }
            }

            DB::commit();

            // Log project creation
            $this->logProjectCreated($project, [
                'created_by_admin' => true,
                'dates_count'      => count($request->dates),
                'total_times'      => collect($request->dates)->sum(function ($date) {
                    return count($date['times']);
                }),
                'links_count'      => $request->has('links') ? count($request->links) : 0,
            ]);

            return redirect()->route('hrd.admin.projects.show', $project->id)
                ->with('success', 'Project created successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors(['error' => 'Failed to create project: ' . $e->getMessage()]);
        }
    }

    /**
     * Display project details (admin view)
     */
    public function adminProjectShow($id)
    {
        $project = HrProject::with([
            'dates.times',
            'links',
            'resultHeader',
            'attends' => function ($query) {
                $query->where('attend_delete', false)
                    ->orderBy('created_at', 'desc');
            },
            'attends.user',
        ])->findOrFail($id);

        return view('hrd.admin.projects.core.project-overview', compact('project'));
    }

    /**
     * Display project edit form
     */
    public function adminProjectEdit($id)
    {
        $project = HrProject::with([
            'dates'       => function ($query) {
                $query->withoutGlobalScope('active')->orderBy('date_datetime', 'asc');
            },
            'dates.times' => function ($query) {
                $query->withoutGlobalScope('active')->orderBy('time_start', 'asc');
            },
            'links'       => function ($query) {
                $query->withoutGlobalScope('active')->orderBy('created_at', 'asc');
            },
        ])->findOrFail($id);

        // Format data for frontend
        $editData = $this->formatProjectDataForEdit($project);

        return view('hrd.admin.projects.core.edit-project', compact('project', 'editData'));
    }

    // ========================================================================
    // PRIVATE HELPER METHODS - ADMIN FUNCTIONS
    // ========================================================================

    private function formatProjectDataForEdit($project)
    {
        // Format dates with properly formatted datetime and times
        $formattedDates = $project->dates->map(function ($date) {
            return [
                'id'            => $date->id,
                'date_title'    => $date->date_title,
                'date_datetime' => $date->date_datetime->format('Y-m-d'),
                'date_location' => $date->date_location,
                'date_detail'   => $date->date_detail,
                'times'         => $date->times->map(function ($time) {
                    return [
                        'id'          => $time->id,
                        'time_title'  => $time->time_title,
                        'time_start'  => $time->time_start,
                        'time_end'    => $time->time_end,
                        'time_max'    => $time->time_max,
                        'time_detail' => $time->time_detail,
                        'time_limit'  => $time->time_limit,
                    ];
                })->toArray(),
            ];
        })->toArray();

        // Format links with properly formatted datetime
        $formattedLinks = $project->links->map(function ($link) {
            return [
                'id'              => $link->id,
                'link_name'       => $link->link_name,
                'link_url'        => $link->link_url,
                'link_time_start' => $link->link_time_start ? $link->link_time_start->format('H:i') : null,
                'link_time_end'   => $link->link_time_end ? $link->link_time_end->format('H:i') : null,
                'link_limit'      => $link->link_limit,
            ];
        })->toArray();

        // Thai months for frontend
        $thaiMonths = [
            'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
            'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม',
        ];

        return [
            'dates'           => $formattedDates,
            'links'           => $formattedLinks,
            'thaiMonths'      => $thaiMonths,
            'hasExistingData' => $project->dates->count() > 0 || $project->links->count() > 0,
        ];
    }

    public function adminProjectUpdate(Request $request, $id)
    {
        $project = HrProject::findOrFail($id);

        $request->validate([
            'project_name'                => 'required|string|max:255',
            'project_type'                => 'required|in:single,multiple,attendance',
            'project_detail'              => 'nullable|string',
            'project_seat_assign'         => 'boolean',
            'project_group_assign'        => 'boolean',
            'project_start_register'      => 'required|date',
            'project_end_register'        => 'required|date|after:project_start_register',
            'project_register_today'      => 'boolean',
            'project_active'              => 'boolean',

            // Dates validation
            'dates'                       => 'required|array|min:1',
            'dates.*.id'                  => 'nullable|exists:hr_dates,id',
            'dates.*.date_title'          => 'required|string|max:255',
            'dates.*.date_detail'         => 'nullable|string',
            'dates.*.date_location'       => 'nullable|string|max:255',
            'dates.*.date_datetime'       => 'required|date_format:Y-m-d',

            // Times validation
            'dates.*.times'               => 'required|array|min:1',
            'dates.*.times.*.id'          => 'nullable|exists:hr_times,id',
            'dates.*.times.*.time_title'  => 'required|string|max:255',
            'dates.*.times.*.time_detail' => 'nullable|string',
            'dates.*.times.*.time_start'  => 'required|date_format:H:i',
            'dates.*.times.*.time_end'    => 'required|date_format:H:i|after:dates.*.times.*.time_start',
            'dates.*.times.*.time_limit'  => 'boolean',

            // Links validation (optional)
            'links'                       => 'nullable|array',
            'links.*.id'                  => 'nullable|exists:hr_links,id',
            'links.*.link_name'           => 'nullable|string|max:255',
            'links.*.link_url'            => 'nullable|url',
            'links.*.link_limit'          => 'boolean',
            'links.*.link_time_start'     => 'nullable|date_format:H:i',
            'links.*.link_time_end'       => 'nullable|date_format:H:i|after:links.*.link_time_start',
        ]);

        DB::beginTransaction();

        try {
            // Update project basic information
            $project->update([
                'project_type'           => $request->project_type,
                'project_name'           => $request->project_name,
                'project_detail'         => $request->project_detail,
                'project_seat_assign'    => $request->boolean('project_seat_assign'),
                'project_group_assign'   => $request->boolean('project_group_assign'),
                'project_start_register' => $request->project_start_register,
                'project_end_register'   => $request->project_end_register,
                'project_register_today' => $request->boolean('project_register_today'),
                'project_active'         => $request->boolean('project_active'),
            ]);

            // Smart update for dates - update existing, create new, mark deleted as inactive
            $existingDates    = $project->dates()->get()->keyBy('id');
            $submittedDateIds = [];

            foreach ($request->dates as $index => $dateData) {
                // Check if this is an existing date by ID
                $existingDate = null;
                if (isset($dateData['id']) && $existingDates->has($dateData['id'])) {
                    $existingDate = $existingDates->get($dateData['id']);
                }

                if ($existingDate) {
                    // Update existing date
                    $existingDate->update([
                        'date_title'    => $dateData['date_title'],
                        'date_detail'   => $dateData['date_detail'] ?? null,
                        'date_location' => $dateData['date_location'] ?? null,
                        'date_datetime' => $dateData['date_datetime'],
                        'date_active'   => true,
                        'date_delete'   => false,
                    ]);
                    $date               = $existingDate;
                    $submittedDateIds[] = $existingDate->id;
                } else {
                    // Create new date
                    $date = $project->dates()->create([
                        'date_title'    => $dateData['date_title'],
                        'date_detail'   => $dateData['date_detail'] ?? null,
                        'date_location' => $dateData['date_location'] ?? null,
                        'date_datetime' => $dateData['date_datetime'],
                        'date_active'   => true,
                        'date_delete'   => false,
                    ]);
                    $submittedDateIds[] = $date->id;
                }

                // Smart update for times
                $existingTimes    = $date->times()->get()->keyBy('id');
                $submittedTimeIds = [];

                foreach ($dateData['times'] as $timeIndex => $timeData) {
                    // Check if this is an existing time by ID
                    $existingTime = null;
                    if (isset($timeData['id']) && $existingTimes->has($timeData['id'])) {
                        $existingTime = $existingTimes->get($timeData['id']);
                    }

                    if ($existingTime) {
                        // Update existing time
                        $existingTime->update([
                            'time_title'  => $timeData['time_title'],
                            'time_detail' => $timeData['time_detail'] ?? null,
                            'time_start'  => $timeData['time_start'],
                            'time_end'    => $timeData['time_end'],
                            'time_limit'  => $timeData['time_limit'] ?? false,
                            'time_max'    => $timeData['time_limit'] ? ($timeData['time_max'] ?? 1) : 0,
                            'time_active' => true,
                        ]);
                        $submittedTimeIds[] = $existingTime->id;
                    } else {
                        // Create new time
                        $newTime = $date->times()->create([
                            'time_title'  => $timeData['time_title'],
                            'time_detail' => $timeData['time_detail'] ?? null,
                            'time_start'  => $timeData['time_start'],
                            'time_end'    => $timeData['time_end'],
                            'time_limit'  => $timeData['time_limit'] ?? false,
                            'time_max'    => $timeData['time_limit'] ? ($timeData['time_max'] ?? 1) : 0,
                            'time_active' => true,
                        ]);
                        $submittedTimeIds[] = $newTime->id;
                    }
                }

                // Mark times not in submission as deleted (soft delete to preserve registrations)
                foreach ($existingTimes as $existingTime) {
                    if (! in_array($existingTime->id, $submittedTimeIds)) {
                        // Always soft delete times to avoid foreign key constraint issues
                        // Even if no active registrations, there might be deleted ones that still reference the time
                        $existingTime->update([
                            'time_active' => false,
                            'time_delete' => true,
                        ]);
                    }
                }
            }

            // Mark dates not in submission as deleted (soft delete to preserve registrations)
            foreach ($existingDates as $existingDate) {
                if (! in_array($existingDate->id, $submittedDateIds)) {
                    // Always soft delete dates to avoid foreign key constraint issues
                    // Even if no active registrations, there might be deleted ones that still reference the date
                    $existingDate->update([
                        'date_active' => false,
                        'date_delete' => true,
                    ]);
                    // Also soft delete all times for this date
                    $existingDate->times()->update([
                        'time_active' => false,
                        'time_delete' => true,
                    ]);
                }
            }

            // Smart update for links - similar approach but links usually don't have constraints
            $existingLinks    = $project->links()->get()->keyBy('id');
            $submittedLinkIds = [];

            if ($request->has('links') && is_array($request->links)) {
                foreach ($request->links as $linkData) {
                    if (! empty($linkData['link_name']) && ! empty($linkData['link_url'])) {
                        // Check if this is an existing link by ID
                        $existingLink = null;
                        if (isset($linkData['id']) && $existingLinks->has($linkData['id'])) {
                            $existingLink = $existingLinks->get($linkData['id']);
                        }

                        if ($existingLink) {
                            // Update existing link
                            $existingLink->update([
                                'link_name'       => $linkData['link_name'],
                                'link_url'        => $linkData['link_url'],
                                'link_limit'      => $linkData['link_limit'] ?? false,
                                'link_time_start' => $linkData['link_limit'] ? $linkData['link_time_start'] : null,
                                'link_time_end'   => $linkData['link_limit'] ? $linkData['link_time_end'] : null,
                                'link_delete'     => false,
                            ]);
                            $submittedLinkIds[] = $existingLink->id;
                        } else {
                            // Create new link
                            $newLink = $project->links()->create([
                                'link_name'       => $linkData['link_name'],
                                'link_url'        => $linkData['link_url'],
                                'link_limit'      => $linkData['link_limit'] ?? false,
                                'link_time_start' => $linkData['link_limit'] ? $linkData['link_time_start'] : null,
                                'link_time_end'   => $linkData['link_limit'] ? $linkData['link_time_end'] : null,
                                'link_delete'     => false,
                            ]);
                            $submittedLinkIds[] = $newLink->id;
                        }
                    }
                }
            }

            // Remove links not in submission
            foreach ($existingLinks as $existingLink) {
                if (! in_array($existingLink->id, $submittedLinkIds)) {
                    // Soft delete links to be consistent with dates and times
                    $existingLink->update([
                        'link_delete' => true,
                    ]);
                }
            }

            DB::commit();

            // Log project update
            $this->logProjectUpdated($project, [
                'updated_by_admin' => true,
                'dates_updated'    => count($request->dates),
                'total_times'      => collect($request->dates)->sum(function ($date) {
                    return count($date['times']);
                }),
                'links_updated'    => $request->has('links') ? count($request->links) : 0,
            ]);

            return redirect()->route('hrd.admin.projects.show', $project->id)
                ->with('success', 'Project updated successfully!');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->withInput()->withErrors(['error' => 'Failed to update project: ' . $e->getMessage()]);
        }
    }

    public function adminProjectDelete(Request $request, $id)
    {
        $project = HrProject::with([
            'dates.times.seats',
            'attends',
            'results',
            'resultHeader',
            'links',
        ])->findOrFail($id);

        // Start transaction to ensure data consistency
        DB::beginTransaction();

        // Store project data for logging before deletion
        $projectData = [
            'id'            => $project->id,
            'name'          => $project->project_name,
            'type'          => $project->project_type,
            'dates_count'   => $project->dates->count(),
            'attends_count' => $project->attends->count(),
            'results_count' => $project->results->count(),
            'links_count'   => $project->links->count(),
        ];

        // Force delete all related data to avoid foreign key constraint violations
        // Use forceDelete() to bypass soft deletes and foreign key constraints

        try {
            // Delete in correct order based on foreign key constraints from migration

            // 1. Delete results first (they reference attends)
            $project->results()->forceDelete();

            // 2. Delete result headers (they reference project)
            if ($project->resultHeader) {
                $project->resultHeader->forceDelete();
            }

            // 3. Delete attendance records (they reference project, dates, times)
            $project->attends()->delete();

            // 4. Delete seat assignments (they reference times)
            $timeIds = $project->dates->pluck('times')->flatten()->pluck('id');
            if ($timeIds->isNotEmpty()) {
                HrSeat::whereIn('time_id', $timeIds)->delete();
            }

            // 5. Delete time slots (they reference dates)
            $dateIds = $project->dates->pluck('id');
            if ($dateIds->isNotEmpty()) {
                HrTime::whereIn('date_id', $dateIds)->delete();
            }

            // 6. Delete dates (they reference project)
            $project->dates()->delete();

            // 7. Delete links (they reference project)
            $project->links()->forceDelete();

            // 8. Delete groups (they reference project)
            $project->groups()->forceDelete();

            // 9. Finally, delete the project itself
            $project->delete();
        } catch (\Exception $e) {
            // If forceDelete fails due to foreign key constraints, use raw SQL approach
            $projectId = $project->id;

            // Delete all related data using raw SQL to bypass foreign key constraints
            // Order based on foreign key dependencies from migration schema

            // 1. Delete results first (they reference attends)
            // DB::statement("DELETE FROM hr_results WHERE project_id = ?", [$projectId]);

            // // 2. Delete result headers (they reference project)
            // DB::statement("DELETE FROM hr_result_headers WHERE project_id = ?", [$projectId]);

            // // 3. Delete attendance records (they reference project, dates, times)
            // DB::statement("DELETE FROM hr_attends WHERE project_id = ?", [$projectId]);

            // // 4. Delete seat assignments (they reference times)
            // DB::statement("DELETE FROM hr_seats WHERE time_id IN (SELECT id FROM hr_times WHERE date_id IN (SELECT id FROM hr_dates WHERE project_id = ?))", [$projectId]);

            // // 5. Delete time slots (they reference dates)
            // DB::statement("DELETE FROM hr_times WHERE date_id IN (SELECT id FROM hr_dates WHERE project_id = ?)", [$projectId]);

            // // 6. Delete dates (they reference project)
            // DB::statement("DELETE FROM hr_dates WHERE project_id = ?", [$projectId]);

            // // 7. Delete links (they reference project)
            // DB::statement("DELETE FROM hr_links WHERE project_id = ?", [$projectId]);

            // // 8. Delete groups (they reference project)
            // DB::statement("DELETE FROM hr_groups WHERE project_id = ?", [$projectId]);

            // // 9. Finally, delete the project itself
            // DB::statement("DELETE FROM hr_projects WHERE id = ?", [$projectId]);
        }

        // Log project deletion after successful deletion
        $this->logProjectDeleted($projectData, [
            'deleted_by_admin' => true,
            'deletion_reason'  => 'admin_request',
        ]);

        DB::commit();

        // Return JSON response for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'      => true,
                'message'      => 'Project and all related data deleted successfully.',
                'redirect_url' => route('hrd.admin.index'),
            ], 200);
        }

        // Return redirect for regular form submissions
        return redirect()->route('hrd.admin.index')
            ->with('success', 'Project and all related data deleted successfully.');
    }

    // ========================================================================
    // ADMIN REGISTRATION MANAGEMENT
    // ========================================================================

    public function adminProjectRegistrations($id)
    {
        $project = HrProject::with([
            'dates.times',
            'attends.user',
            'attends.date',
            'attends.time',
        ])->findOrFail($id);

        // Calculate total statistics from all registrations (before pagination)
        $totalRegistrations = $project->attends()
            ->where('attend_delete', false)
            ->count();

        $attendedCount = $project->attends()
            ->where('attend_delete', false)
            ->whereNotNull('attend_datetime')
            ->count();

        $notAttendedCount = $project->attends()
            ->where('attend_delete', false)
            ->whereNull('attend_datetime')
            ->count();

        $approvedCount = $project->attends()
            ->where('attend_delete', false)
            ->whereNotNull('approve_datetime')
            ->count();

        // Start with base query for active registrations
        $query = $project->attends()
            ->where('attend_delete', false)
            ->with(['user', 'date', 'time']);

        // Apply search filter for userid
        if (request('search_userid')) {
            $searchUserid = request('search_userid');
            $query->whereHas('user', function ($userQuery) use ($searchUserid) {
                $userQuery->where('userid', 'LIKE', '%' . $searchUserid . '%');
            });
        }

        // Apply attendance status filter
        if (request('filter_attend')) {
            if (request('filter_attend') === 'attended') {
                $query->whereNotNull('attend_datetime');
            } elseif (request('filter_attend') === 'not_attended') {
                $query->whereNull('attend_datetime');
            }
        }

        // Apply approval status filter
        if (request('filter_approve')) {
            if (request('filter_approve') === 'approved') {
                $query->whereNotNull('approve_datetime');
            } elseif (request('filter_approve') === 'not_approved') {
                $query->whereNull('approve_datetime');
            }
        }

        // Get paginated results
        $registrations = $query->orderBy('created_at', 'desc')->paginate(20);

        // Preserve search parameters in pagination links
        $registrations->appends(request()->query());

        return view('hrd.admin.projects.participants.registration-management', compact(
            'project',
            'registrations',
            'totalRegistrations',
            'attendedCount',
            'notAttendedCount',
            'approvedCount'
        ));
    }

    /**
     * Store a new registration (admin function)
     */
    public function adminRegistrationStore(Request $request, $projectId)
    {
        $request->validate([
            'user_id'                => 'required|exists:users,userid',
            'time_id'                => 'required|exists:hr_times,id',
            'attend_datetime'        => 'nullable',
            'attend_datetime_value'  => 'nullable|date_format:Y-m-d\TH:i',
            'approve_datetime'       => 'nullable',
            'approve_datetime_value' => 'nullable|date_format:Y-m-d\TH:i',
        ]);

        try {
            $project = HrProject::findOrFail($projectId);
            $time    = HrTime::findOrFail($request->time_id);
            $date    = $time->date;

            // Find user by userid and get the actual user id
            $user = User::where('userid', $request->user_id)->first();
            if (! $user) {
                return redirect()->back()->with('error', 'User not found with the provided user ID.');
            }

            // Check if user is already registered for this time slot
            $existingRegistration = $project->attends()
                ->where('user_id', $user->id)
                ->where('time_id', $request->time_id)
                ->where('attend_delete', false)
                ->first();

            if ($existingRegistration) {
                return redirect()->back()->with('error', 'User is already registered for this time slot.');
            }

            // Check capacity if time slot is limited
            if ($time->time_limit) {
                $currentRegistrations = $time->attends()->where('attend_delete', false)->count();
                if ($currentRegistrations >= $time->time_max) {
                    return redirect()->back()->with('error', 'This time slot is full.');
                }
            }

            // Prepare datetime values
            $attendDatetime  = null;
            $approveDatetime = null;

            if ($request->attend_datetime && $request->attend_datetime_value) {
                $attendDatetime = $request->attend_datetime_value;
            }

            if ($request->approve_datetime && $request->approve_datetime_value) {
                $approveDatetime = $request->approve_datetime_value;
            }

            // Create registration
            $attendance = $project->attends()->create([
                'date_id'          => $date->id,
                'time_id'          => $request->time_id,
                'user_id'          => $user->id,
                'attend_datetime'  => $attendDatetime,
                'approve_datetime' => $approveDatetime,
                'attend_delete'    => false,
            ]);

            // Dispatch seat assignment job if project has seat assignment enabled
            if ($project->project_seat_assign) {
                HrAssignSeatForAttendance::dispatch($attendance->id);
            }

            // Log admin registration creation
            $this->logUserRegistration($project, $user, [$request->time_id], [
                'created_by_admin'    => true,
                'attendance_datetime' => $attendDatetime,
                'approval_datetime'   => $approveDatetime,
            ]);

            return redirect()->back()->with('success', 'Registration created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create registration: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing registration (admin function)
     */
    public function adminRegistrationUpdate(Request $request, $projectId, $registrationId)
    {
        $request->validate([
            'time_id'                => 'required|exists:hr_times,id',
            'attend_datetime'        => 'nullable',
            'attend_datetime_value'  => 'nullable|date_format:Y-m-d\TH:i',
            'approve_datetime'       => 'nullable',
            'approve_datetime_value' => 'nullable|date_format:Y-m-d\TH:i',
        ]);

        try {
            $registration = HrAttend::findOrFail($registrationId);
            $time         = HrTime::findOrFail($request->time_id);

            // Check if new time slot is different and if it's full
            if ($registration->time_id != $request->time_id) {
                if ($time->time_limit) {
                    $currentRegistrations = $time->attends()->where('attend_delete', false)->count();
                    if ($currentRegistrations >= $time->time_max) {
                        return redirect()->back()->with('error', 'The selected time slot is full.');
                    }
                }

                // Check if user is already registered for the new time slot
                $existingRegistration = $registration->project->attends()
                    ->where('user_id', $registration->user_id)
                    ->where('time_id', $request->time_id)
                    ->where('attend_delete', false)
                    ->where('id', '!=', $registrationId)
                    ->first();

                if ($existingRegistration) {
                    return redirect()->back()->with('error', 'User is already registered for this time slot.');
                }
            }

            // Prepare datetime values
            $attendDatetime  = null;
            $approveDatetime = null;

            if ($request->attend_datetime && $request->attend_datetime_value) {
                $attendDatetime = $request->attend_datetime_value;
            }

            if ($request->approve_datetime && $request->approve_datetime_value) {
                $approveDatetime = $request->approve_datetime_value;
            }

            $registration->update([
                'time_id'          => $request->time_id,
                'date_id'          => $time->date_id,
                'attend_datetime'  => $attendDatetime,
                'approve_datetime' => $approveDatetime,
            ]);

            // Log admin registration update
            $this->logAdminAction('REGISTRATION_UPDATED', 'registration', $registration->id, [
                'user_id'             => $registration->user_id,
                'user_name'           => $registration->user->name,
                'time_id'             => $request->time_id,
                'attendance_datetime' => $attendDatetime,
                'approval_datetime'   => $approveDatetime,
            ]);

            return redirect()->back()->with('success', 'Registration updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update registration: ' . $e->getMessage());
        }
    }

    /**
     * Delete a registration (admin function)
     */
    public function adminRegistrationDelete(Request $request, $projectId, $registrationId)
    {
        try {
            $registration = HrAttend::findOrFail($registrationId);
            $project      = HrProject::findOrFail($projectId);

            // Log admin registration deletion
            $this->logUserUnregistration($registration->project, $registration->user, $registration, [
                'deleted_by_admin' => true,
                'deletion_reason'  => 'admin_request',
            ]);

            $registration->update(['attend_delete' => true]);
            $registration->removeSeatAssignment();

            return redirect()->back()->with('success', 'Registration deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete registration: ' . $e->getMessage());
        }
    }

    /**
     * Approve a registration (admin function)
     */
    public function adminApproveRegistration(Request $request, $projectId)
    {
        $request->validate([
            'attend_id' => 'required|exists:hr_attends,id',
        ]);

        try {
            $registration = HrAttend::findOrFail($request->attend_id);

            // Check if this registration belongs to the project
            if ($registration->project_id != $projectId) {
                return response()->json(['error' => 'Registration does not belong to this project.'], 400);
            }

            $registration->update([
                'approve_datetime' => now(),
            ]);

            // Log registration approval
            $this->logRegistrationApproval($registration, 'approved', [
                'approved_by_admin' => true,
            ]);

            return response()->json(['success' => 'Registration approved successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to approve registration: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Unapprove a registration (admin function)
     */
    public function adminUnapproveRegistration(Request $request, $projectId)
    {
        $request->validate([
            'attend_id' => 'required|exists:hr_attends,id',
        ]);

        try {
            $registration = HrAttend::findOrFail($request->attend_id);

            // Check if this registration belongs to the project
            if ($registration->project_id != $projectId) {
                return response()->json(['error' => 'Registration does not belong to this project.'], 400);
            }

            $registration->update([
                'approve_datetime' => null,
            ]);

            // Log registration unapproval
            $this->logRegistrationApproval($registration, 'unapproved', [
                'unapproved_by_admin' => true,
            ]);

            return response()->json(['success' => 'Registration unapproved successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to unapprove registration: ' . $e->getMessage()], 500);
        }
    }

    public function adminProjectApprovals($projectId)
    {
        $project = HrProject::findOrFail($projectId);

        // Get filter parameters
        $filterDate = request('filter_date');
        $filterTime = request('filter_time');

        // Build query for registrations that have been attended
        $query = $project->attends()
            ->where('attend_delete', false)
            ->whereNotNull('attend_datetime') // Only show attended registrations
            ->with(['user', 'date', 'time'])
            ->orderBy('created_at', 'desc');

        // Apply date filter only if a specific date is selected
        if ($filterDate && $filterDate !== '') {
            $query->whereHas('date', function ($q) use ($filterDate) {
                $q->whereDate('date_datetime', $filterDate)
                    ->where('date_delete', false);
            });
        }

        // Apply time filter only if a specific time is selected
        if ($filterTime && $filterTime !== '') {
            $query->whereHas('time', function ($q) use ($filterTime) {
                $q->where('id', $filterTime)
                    ->where('time_delete', false);
            });
        }

        $registrations = $query->get();

        // Get all available dates for the filter dropdown (only non-deleted dates)
        $availableDates = $project->dates()
            ->where('date_delete', false)
            ->orderBy('date_datetime', 'asc')
            ->get();

        // Get available times for the selected date (for time filter dropdown)
        $availableTimes = collect();
        if ($filterDate && $filterDate !== '') {
            $selectedDate = $project->dates()
                ->where('date_delete', false)
                ->whereDate('date_datetime', $filterDate)
                ->first();

            if ($selectedDate) {
                $availableTimes = $selectedDate->times()
                    ->where('time_delete', false)
                    ->orderBy('time_start', 'asc')
                    ->get();
            }
        } else {
            // If no date selected, get all times from all dates
            $availableTimes = $project->dates()
                ->where('date_delete', false)
                ->with(['times' => function ($query) {
                    $query->where('time_delete', false)->orderBy('time_start', 'asc');
                }])
                ->get()
                ->flatMap(function ($date) {
                    return $date->times->map(function ($time) use ($date) {
                        return (object) [
                            'id'         => $time->id,
                            'time_title' => $date->date_title . ' - ' . $time->time_title,
                            'time_start' => $time->time_start,
                            'time_end'   => $time->time_end,
                        ];
                    });
                });
        }

        return view('hrd.admin.projects.participants.approval-management', compact(
            'project',
            'registrations',
            'availableDates',
            'availableTimes',
            'filterDate',
            'filterTime'
        ));
    }

    public function adminBulkApprove(Request $request, $projectId)
    {
        $request->validate([
            'attend_ids'     => 'required|array',
            'attend_ids.*'   => 'exists:hr_attends,id',
            'filter_date'    => 'nullable|date',
            'filter_time_id' => 'nullable|exists:hr_times,id',
        ]);

        try {
            $project = HrProject::findOrFail($projectId);

            // Build query for filtering
            $query = $project->attends()
                ->where('attend_delete', false)
                ->where('approve_datetime', null); // Only unapproved registrations

            // Apply filters
            if ($request->filter_date) {
                $query->whereHas('date', function ($q) use ($request) {
                    $q->whereDate('date_datetime', $request->filter_date)
                        ->where('date_delete', false);
                });
            }

            if ($request->filter_time_id) {
                $query->whereHas('time', function ($q) use ($request) {
                    $q->where('id', $request->filter_time_id)
                        ->where('time_delete', false);
                });
            }

            // If specific attend_ids provided, use them; otherwise use filtered results
            if (! empty($request->attend_ids)) {
                $query->whereIn('id', $request->attend_ids);
            }

            $registrations = $query->get();

            // Update all filtered registrations
            $updatedCount = $registrations->count();
            $registrations->each(function ($registration) {
                $registration->update(['approve_datetime' => now()]);
            });

            // Log bulk approval operation
            $this->logBulkOperation('BULK_APPROVAL', $project, $updatedCount, [
                'filter_date'    => $request->filter_date,
                'filter_time_id' => $request->filter_time_id,
                'specific_ids'   => ! empty($request->attend_ids),
            ]);

            return response()->json([
                'success' => "Successfully approved {$updatedCount} registration(s).",
                'count'   => $updatedCount,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to bulk approve: ' . $e->getMessage()], 500);
        }
    }

    // ========================================================================
    // SEAT MANAGEMENT & ASSIGNMENT
    // ========================================================================

    /**
     * Trigger seat assignment for all projects
     */
    public function triggerSeatAssignment(Request $request)
    {
        try {
            $request->validate([
                'project_id' => 'required|integer|exists:hr_projects,id',
            ]);

            $project = HrProject::findOrFail($request->project_id);

            // Check if project has seat assignment enabled
            if (! $project->project_seat_assign) {
                return response()->json([
                    'error' => 'Seat assignment is not enabled for this project.',
                ], 400);
            }

            // Dispatch the bulk assignment job for the specific project
            HrProjectSeatAssignment::dispatch($project->id);

            // Log seat assignment trigger
            $this->logBulkOperation('SEAT_ASSIGNMENT_TRIGGER', $project, 0, [
                'triggered_by_admin' => true,
                'job_queued'         => true,
            ]);

            $message = "Seat assignment triggered successfully for project: {$project->project_name}. The bulk assignment job has been queued.";

            return response()->json([
                'success'      => true,
                'message'      => $message,
                'project_name' => $project->project_name,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to trigger seat assignment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get seat data for a project
     */
    public function getProjectSeats($projectId)
    {
        try {
            $project = HrProject::with([
                'dates.times.seats.user',
                'dates.times.attends.user',
            ])->findOrFail($projectId);

            $seatData = [];

            foreach ($project->dates as $date) {
                foreach ($date->times as $time) {
                    // Get seat assignments for this time slot
                    $seats = $time->seats()
                        ->where('seat_delete', false)
                        ->with('user')
                        ->get()
                        ->map(function ($seat) {
                            return [
                                'seat_number'  => $seat->seat_number,
                                'user_id'      => $seat->user_id,
                                'real_user_id' => $seat->user->userid ?? 'Unknown',
                                'user_name'    => $seat->user->name ?? 'Unknown',
                                'department'   => $seat->department ?? 'Unknown',
                            ];
                        })
                        ->toArray();

                    // Get registrations for this time slot
                    $registrations = $time->attends()
                        ->where('attend_delete', false)
                        ->with('user')
                        ->get()
                        ->map(function ($attend) use ($seats) {
                            $seat = collect($seats)->firstWhere('user_id', $attend->user_id);
                            return [
                                'user_id'          => $attend->user_id,
                                'real_user_id'     => $attend->user->userid ?? 'Unknown',
                                'user_name'        => $attend->user->name ?? 'Unknown',
                                'department'       => $attend->user->department ?? 'Unknown',
                                'seat_number'      => $seat ? $seat['seat_number'] : null,
                                'attend_datetime'  => $attend->attend_datetime,
                                'approve_datetime' => $attend->approve_datetime,
                            ];
                        })
                        ->toArray();

                    $seatData[] = [
                        'date'                => $date->date_title,
                        'time'                => $time->time_title,
                        'time_id'             => $time->id,
                        'time_start'          => \Carbon\Carbon::parse($time->time_start)->format('H:i'),
                        'time_end'            => \Carbon\Carbon::parse($time->time_end)->format('H:i'),
                        'time_limit'          => $time->time_limit,
                        'time_max'            => $time->time_max,
                        'seats'               => $seats,
                        'registrations'       => $registrations,
                        'total_seats'         => count($seats),
                        'total_registrations' => count($registrations),
                        'unassigned_count'    => count(array_filter($registrations, function ($reg) {
                            return $reg['seat_number'] === null;
                        })),
                    ];
                }
            }

            return response()->json([
                'project'      => $project->project_name,
                'seat_data'    => $seatData,
                'project_info' => [
                    'id'                  => $project->id,
                    'seat_assign_enabled' => $project->project_seat_assign,
                    'total_dates'         => $project->dates->count(),
                    'total_times'         => $project->dates->sum(function ($date) {
                        return $date->times->count();
                    }),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to get seat data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show seat management page for a project
     */
    public function adminProjectSeatManagement($id)
    {
        try {
            $project = HrProject::with([
                'dates.times.seats.user',
                'dates.times.attends.user',
            ])->findOrFail($id);

            return view('hrd.admin.projects.logistics.seat-management', compact('project'));

        } catch (\Exception $e) {
            return redirect()->route('hrd.admin.index')
                ->with('error', 'ไม่พบโปรเจกต์ที่ระบุ');
        }
    }

    /**
     * Manually assign a seat to a user
     */
    public function assignManualSeat(Request $request, $projectId)
    {
        try {
            $request->validate([
                'time_id' => 'required|integer|exists:hr_times,id',
                'user_id' => 'required|integer|exists:users,id',
            ]);
            $project = HrProject::findOrFail($projectId);
            $time    = HrTime::findOrFail($request->time_id);

            // Verify the time belongs to this project
            if ($time->date->project_id != $project->id) {
                return response()->json(['error' => 'Invalid time slot for this project.'], 400);
            }

            // Check if user is registered for this time slot
            $registration = HrAttend::where('project_id', $project->id)
                ->where('time_id', $request->time_id)
                ->where('user_id', $request->user_id)
                ->where('attend_delete', false)
                ->first();

            if (! $registration) {
                return response()->json(['error' => 'User is not registered for this time slot.'], 400);
            }

            // Find user by ID (since user_id from frontend is the User model's id)
            $user = User::find($request->user_id);

            if (! $user) {
                return response()->json(['error' => 'User not found.'], 404);
            }

            // Use database transaction to prevent race conditions
            return \DB::transaction(function () use ($request, $user, $registration, $time) {
                // Check if user already has a seat in this time slot
                $existingUserSeat = HrSeat::where('time_id', $request->time_id)
                    ->where('user_id', $user->id)
                    ->where('seat_delete', false)
                    ->first();

                if ($existingUserSeat) {
                    return response()->json(['error' => 'User already has a seat assigned for this time slot.'], 400);
                }

                $userDepartment = $registration->user->department ?? 'Unknown';

                // Get current seat assignments for this time slot
                $currentSeats = HrSeat::where('time_id', $request->time_id)
                    ->where('seat_delete', false)
                    ->get()
                    ->keyBy('seat_number');

                // Determine max seats
                if (! $time->time_limit) {
                    $maxSeats = 999999; // Very high number for unlimited
                } else {
                    $maxSeats = $time->time_max ?? 100;
                }

                $assignedSeat = null;

                // First, try to find a seat that doesn't have adjacent users from the same department
                for ($seatNumber = 1; $seatNumber <= $maxSeats; $seatNumber++) {
                    if ($this->isSeatAvailable($seatNumber, $currentSeats) &&
                        $this->isSeatSuitableForDepartment($seatNumber, $userDepartment, $currentSeats)) {

                        $assignedSeat = $seatNumber;
                        break;
                    }
                }

                // If no suitable seat found, try to find any available seat within the limit
                if (! $assignedSeat) {
                    for ($seatNumber = 1; $seatNumber <= $maxSeats; $seatNumber++) {
                        if ($this->isSeatAvailable($seatNumber, $currentSeats)) {
                            $assignedSeat = $seatNumber;
                            break;
                        }
                    }
                }

                // Create seat assignment if found
                if ($assignedSeat) {
                    // Check if seat assignment already exists for this seat number
                    $existingSeat = HrSeat::where('time_id', $request->time_id)
                        ->where('seat_number', $assignedSeat)
                        ->where('seat_delete', false)
                        ->first();

                    if ($existingSeat) {
                        return response()->json(['error' => "Seat number {$assignedSeat} is already assigned to another user."], 400);
                    }

                    // Create new seat assignment
                    HrSeat::create([
                        'time_id'     => $request->time_id,
                        'user_id'     => $user->id,
                        'department'  => $userDepartment,
                        'seat_number' => $assignedSeat,
                        'seat_delete' => false,
                    ]);

                    // Log manual seat assignment
                    $this->logSeatAssignment($time, $user, $assignedSeat, 'manual', [
                        'assigned_by_admin' => true,
                    ]);

                    return response()->json([
                        'success'     => true,
                        'message'     => 'Seat assigned successfully.',
                        'seat_number' => $assignedSeat,
                        'user_name'   => $registration->user->name,
                        'department'  => $userDepartment,
                    ]);
                } else {
                    if ($time->time_limit) {
                        return response()->json(['error' => "No seat available - time slot is full (limit: {$time->time_max})"], 400);
                    } else {
                        return response()->json(['error' => 'No seat available - unexpected error'], 400);
                    }
                }
            });

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to assign seat: ' . $e->getMessage()], 500);
        }
    }

    // ========================================================================
    // PRIVATE HELPER METHODS - SEAT MANAGEMENT
    // ========================================================================

    /**
     * Check if a seat is available
     */
    private function isSeatAvailable(int $seatNumber, $currentSeats): bool
    {
        return ! $currentSeats->has($seatNumber);
    }

    /**
     * Check if a seat is suitable for a department (no adjacent same department)
     */
    private function isSeatSuitableForDepartment(int $seatNumber, string $userDepartment, $currentSeats): bool
    {
        // Check adjacent seats (left and right)
        $leftSeat  = $currentSeats->get($seatNumber - 1);
        $rightSeat = $currentSeats->get($seatNumber + 1);

        // If adjacent seats exist and have the same department, this seat is not suitable
        if ($leftSeat && $leftSeat->department === $userDepartment) {
            return false;
        }

        if ($rightSeat && $rightSeat->department === $userDepartment) {
            return false;
        }

        return true;
    }

    /**
     * Remove a seat assignment
     */
    public function removeManualSeat(Request $request, $projectId)
    {
        try {
            $request->validate([
                'time_id' => 'required|integer',
                'user_id' => 'required|integer',
            ]);

            // Find user by ID (since user_id from frontend is the User model's id)
            $user = User::find($request->user_id);

            if (! $user) {
                return response()->json(['error' => 'User not found.'], 404);
            }

            $seat = HrSeat::where('time_id', $request->time_id)
                ->where('user_id', $user->id)
                ->where('seat_delete', false)
                ->first();

            if (! $seat) {
                return response()->json(['error' => 'ไม่พบการจัดที่นั่งที่ระบุ'], 404);
            }

            // Log seat removal
            $time = HrTime::find($request->time_id);
            $this->logSeatRemoval($time, $user, $seat->seat_number, [
                'removed_by_admin' => true,
            ]);

            $seat->update(['seat_delete' => true]);

            return response()->json(['success' => 'ลบการจัดที่นั่งสำเร็จ']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'เกิดข้อผิดพลาดในการลบการจัดที่นั่ง: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Clear all seats for a specific time slot
     */
    public function clearTimeSeats(Request $request, $projectId)
    {
        try {
            $request->validate([
                'time_id' => 'required|integer|exists:hr_times,id',
            ]);

            $project = HrProject::findOrFail($projectId);
            $time    = HrTime::findOrFail($request->time_id);

            // Verify the time belongs to this project
            if ($time->date->project_id != $project->id) {
                return response()->json(['error' => 'Invalid time slot for this project.'], 400);
            }

            // Soft delete all seats for this time slot
            $deletedCount = HrSeat::where('time_id', $request->time_id)
                ->where('seat_delete', false)
                ->update(['seat_delete' => true]);

            // Log bulk seat clearing
            $this->logBulkOperation('CLEAR_SEATS', $project, $deletedCount, [
                'time_id'    => $request->time_id,
                'time_title' => $time->time_title,
            ]);

            return response()->json([
                'success'       => true,
                'message'       => "ล้างที่นั่งทั้งหมดสำเร็จ ({$deletedCount} รายการ)",
                'deleted_count' => $deletedCount,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'เกิดข้อผิดพลาดในการล้างที่นั่ง: ' . $e->getMessage()], 500);
        }
    }

    // ========================================================================
    // EXPORT METHODS
    // ========================================================================

    /**
     * Export all date registrations for a project
     */
    public function exportAllDateRegistrations($projectId)
    {
        $project = HrProject::findOrFail($projectId);

        // Get all registrations for the project
        $registrations = HrAttend::with(['user', 'date', 'time'])
            ->where('project_id', $projectId)
            ->where('attend_delete', false)
            ->get();

        // Log export operation
        $this->logExportOperation($project, 'all_date_registrations', 'excel');

        return Excel::download(new AllDateExport($projectId), 'AllDateExport_' . $project->project_name . '_' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export DBD format for a project
     */
    public function exportDBD($projectId)
    {
        $project = HrProject::findOrFail($projectId);

        // Log export operation
        $this->logExportOperation($project, 'dbd_format', 'excel');

        return Excel::download(new DBDExport($projectId), 'DBDExport_' . $project->project_name . '_' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export Onebook format for a project
     */
    public function exportOnebook($projectId)
    {
        $project = HrProject::findOrFail($projectId);

        // Log export operation
        $this->logExportOperation($project, 'onebook_format', 'excel');

        return Excel::download(new OnebookExport($projectId), 'OnebookExport_' . $project->project_name . '_' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export date-specific registrations
     */
    public function exportDateRegistrations($dateId)
    {
        $date = HrDate::with('project')->findOrFail($dateId);

        // Get all registrations for this specific date
        $registrations = HrAttend::with(['user', 'time'])
            ->where('date_id', $dateId)
            ->where('attend_delete', false)
            ->get();

        // Log export operation
        $this->logExportOperation($date->project, 'date_specific_registrations', 'excel', [
            'date_id'    => $dateId,
            'date_title' => $date->date_title,
        ]);

        return Excel::download(new DateExport($dateId), 'DateExport_' . $date->date_title . '_' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export time-specific registrations as PDF
     */
    public function exportTimePDF($timeId)
    {
        $time = HrTime::with(['date.project', 'attends.user'])->findOrFail($timeId);

        // Get all registrations for this specific time slot
        $registrations = HrAttend::with(['user'])
            ->where('time_id', $timeId)
            ->where('attend_delete', false)
            ->get();

        // Log export operation
        $this->logExportOperation($time->date->project, 'time_specific_attendance', 'pdf', [
            'time_id'             => $timeId,
            'time_title'          => $time->time_title,
            'registrations_count' => $registrations->count(),
        ]);

        $pdf = Pdf::loadView('hrd.admin.export.attendance-pdf', compact('time', 'registrations'));

        return $pdf->stream($time->time_title . '_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Calculate registration state for a project
     */
    private function calculateProjectRegistrationState($project)
    {
        $now    = now();
        $userId = auth()->id();

        // Check registration period
        $canRegister = $project->project_active &&
        ! $project->project_delete &&
        $now >= $project->project_start_register &&
        $now <= $project->project_end_register &&
        $project->project_type !== 'attendance';

        $isUpcoming = $now < $project->project_start_register;
        $isExpired  = $now > $project->project_end_register;

        // Get user registrations for this project
        $userRegistrations = $project->attends
            ->where('user_id', $userId)
            ->where('attend_delete', false);

        $registrationCount = $userRegistrations->count();
        $attendedCount     = $userRegistrations->where('attend_datetime', '!=', null)->count();

        // Calculate attendance status based on project type and user attendance
        $attendanceStatus = $this->calculateAttendanceStatus($project, $userRegistrations);

        return [
            'canRegister'       => $canRegister,
            'isUpcoming'        => $isUpcoming,
            'isExpired'         => $isExpired,
            'registrationCount' => $registrationCount,
            'attendedCount'     => $attendedCount,
            'projectType'       => $project->project_type,
            'attendanceStatus'  => $attendanceStatus,
        ];
    }

    /**
     * Calculate attendance status message based on project type and user attendance
     */
    private function calculateAttendanceStatus($project, $userRegistrations)
    {
        // If user has no registrations, return null
        if ($userRegistrations->count() === 0) {
            return null;
        }

        // For attendance type projects
        if ($project->project_type === 'attendance') {
            return 'มีการเข้าร่วมแล้ว';
        }

        // For single type projects
        if ($project->project_type === 'single') {
            return 'มีการลงทะเบียนแล้ว';
        }

        // For multiple type projects
        if ($project->project_type === 'multiple') {
            // Get all dates for this project
            $projectDates = $project->dates->where('date_delete', false);
            $totalDates   = $projectDates->count();

            if ($totalDates === 0) {
                return 'ลงทะเบียนแล้ว';
            }

            // Count how many dates the user has attended
            $attendedDates = $userRegistrations
                ->where('attend_delete', false)
                ->pluck('date_id')
                ->unique()
                ->count();

            if ($attendedDates === $totalDates) {
                return 'ลงทะเบียนแล้ว';
            } else {
                return 'ลงทะเบียนแล้วบางส่วน';
            }
        }

        return null;
    }

    // ========================================================================
    // ADMIN RESULTS MANAGEMENT
    // ========================================================================

    /**
     * Show result management page for a project
     */
    public function adminProjectResults($id)
    {
        $project = HrProject::with(['resultHeader', 'results.attend.user'])->findOrFail($id);

        // Get results grouped by user_id to avoid duplicates
        $results = HrResult::with(['attend.user'])
            ->where('project_id', $id)
            ->get()
            ->groupBy('user_id')
            ->map(function ($userResults) {
                // Return the first result for each user (they should all have the same data)
                return $userResults->first();
            })
            ->values();

        return view('hrd.admin.projects.evaluation.result-management', compact('project', 'results'));
    }

    /**
     * Import results from Excel file
     */
    public function adminProjectResultsImport(Request $request, $id)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            $project = HrProject::findOrFail($id);

            Excel::import(new HrResultsImport($id), $request->file('excel_file'));

            // Log successful import
            $this->logImportOperation($project, 'results', 0, 0, [], [
                'file_name' => $request->file('excel_file')->getClientOriginalName(),
            ]);

            return redirect()->route('hrd.admin.projects.results.index', $id)
                ->with('success', 'Results imported successfully!');
        } catch (\Exception $e) {
            // Log import error
            $project = HrProject::findOrFail($id);
            $this->logImportOperation($project, 'results', 0, 0, [$e->getMessage()], [
                'file_name' => $request->file('excel_file')->getClientOriginalName(),
            ]);

            return redirect()->route('hrd.admin.projects.results.index', $id)
                ->with('error', 'Error importing results: ' . $e->getMessage());
        }
    }

    /**
     * Export results template
     */
    public function adminProjectResultsTemplate($id)
    {
        $project = HrProject::with(['resultHeader', 'attends.user'])->findOrFail($id);

        return Excel::download(
            new ResultsTemplateExport($project),
            'ResultsTemplate_' . $project->project_name . '.xlsx'
        );
    }

    /**
     * Clear all results for a project
     */
    public function adminProjectResultsClear(Request $request, $id)
    {
        $project = HrProject::findOrFail($id);

        // Delete all results for this project
        HrResult::where('project_id', $id)->delete();

        // Log results clearing
        $this->logBulkOperation('CLEAR_RESULTS', $project, $project->results->count(), [
            'cleared_by_admin' => true,
            'results_count'    => $project->results->count(),
            'header_deleted'   => $project->resultHeader ? true : false,
        ]);

        // Delete result header
        if ($project->resultHeader) {
            $project->resultHeader->delete();
        }

        return redirect()->route('hrd.admin.projects.results.index', $id)
            ->with('success', 'All results cleared successfully!');
    }

    // ========================================================================
    // ADMIN USER MANAGEMENT
    // ========================================================================

    /**
     * Show all users for admin management
     */
    public function adminUsers()
    {
        $users = User::orderBy('name')->paginate(20);
        return view('hrd.admin.user-management', compact('users'));
    }

    /**
     * Show user attendances
     */
    public function adminUserAttendances($userId)
    {
        $user        = User::findOrFail($userId);
        $attendances = HrAttend::with(['project', 'date', 'time'])
            ->where('user_id', $userId)
            ->where('attend_delete', false)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('hrd.admin.attendance-tracking', compact('user', 'attendances'));
    }

    /**
     * Search users
     */
    public function adminUserSearch(Request $request)
    {
        $userid = $request->input('userid');

        $users = User::where('userid', 'like', "%{$userid}%")
            ->orWhere('name', 'like', "%{$userid}%")
            ->orWhere('position', 'like', "%{$userid}%")
            ->orWhere('department', 'like', "%{$userid}%")
            ->orderBy('name')
            ->get();

        // Ensure admin field is properly included in the response
        $usersData = $users->map(function ($user) {
            return [
                'id'         => $user->id,
                'userid'     => $user->userid,
                'name'       => $user->name,
                'position'   => $user->position,
                'department' => $user->department,
                'admin'      => (bool) $user->admin, // Explicitly cast to boolean
                'role'       => $user->role,
            ];
        });

        return response()->json([
            'data' => $usersData,
        ]);
    }

    /**
     * Reset user password
     */
    public function adminUserResetPassword(Request $request)
    {
        $request->validate([
            'userid' => 'required|exists:users,userid',
        ]);

        $user = User::where('userid', $request->userid)->firstOrFail();
        $user->update(['password' => bcrypt($request->userid)]);

        // Log password reset
        $this->logAdminAction('PASSWORD_RESET', 'user', $user->id, [
            'user_name'      => $user->name,
            'userid'         => $user->userid,
            'reset_by_admin' => true,
        ]);

        return response()->json([
            'message' => 'Password reset successfully for ' . $user->name,
        ]);
    }

    // ========================================================================
    // UTILITY FUNCTIONS & GROUP MANAGEMENT
    // ========================================================================

    /**
     * Cleanup duplicate seats for a project
     */
    public function cleanupDuplicateSeats($projectId)
    {
        $project = HrProject::findOrFail($projectId);

        // Get all times for this project
        $times = HrTime::whereHas('date', function ($query) use ($projectId) {
            $query->where('project_id', $projectId);
        })->get();

        $cleanedCount = 0;

        foreach ($times as $time) {
            // Clean up exact duplicates (same user_id, department, seat_number)
            $duplicates = HrSeat::where('time_id', $time->id)
                ->select('user_id', 'department', 'seat_number')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('user_id', 'department', 'seat_number')
                ->having('count', '>', 1)
                ->get();

            foreach ($duplicates as $duplicate) {
                // Keep the first seat, delete the rest
                $seatsToDelete = HrSeat::where('time_id', $time->id)
                    ->where('user_id', $duplicate->user_id)
                    ->where('department', $duplicate->department)
                    ->where('seat_number', $duplicate->seat_number)
                    ->orderBy('created_at', 'desc')
                    ->skip(1)
                    ->take($duplicate->count - 1)
                    ->delete();

                $cleanedCount += $seatsToDelete;
            }

            // Clean up seat number conflicts (same seat number assigned to different users)
            $seatConflicts = HrSeat::where('time_id', $time->id)
                ->select('seat_number')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('seat_number')
                ->having('count', '>', 1)
                ->get();

            foreach ($seatConflicts as $conflict) {
                // Keep the first assignment, delete the rest
                $seatsToDelete = HrSeat::where('time_id', $time->id)
                    ->where('seat_number', $conflict->seat_number)
                    ->orderBy('created_at', 'desc')
                    ->skip(1)
                    ->take($conflict->count - 1)
                    ->delete();

                $cleanedCount += $seatsToDelete;
            }

            // Clean up user conflicts (same user assigned to multiple seats)
            $userConflicts = HrSeat::where('time_id', $time->id)
                ->select('user_id')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('user_id')
                ->having('count', '>', 1)
                ->get();

            foreach ($userConflicts as $conflict) {
                // Keep the first assignment, delete the rest
                $seatsToDelete = HrSeat::where('time_id', $time->id)
                    ->where('user_id', $conflict->user_id)
                    ->orderBy('created_at', 'desc')
                    ->skip(1)
                    ->take($conflict->count - 1)
                    ->delete();

                $cleanedCount += $seatsToDelete;
            }
        }

        // Log cleanup operation
        $this->logBulkOperation('CLEANUP_DUPLICATE_SEATS', $project, $cleanedCount, [
            'cleanup_type'  => 'duplicate_and_conflicting_seats',
            'cleaned_count' => $cleanedCount,
        ]);

        return response()->json([
            'success'       => true,
            'message'       => "Cleaned up {$cleanedCount} duplicate/conflicting seats",
            'cleaned_count' => $cleanedCount,
        ]);
    }

    /**
     * Show project groups management page
     */
    public function adminProjectGroups($projectId)
    {
        $project = HrProject::findOrFail($projectId);

        // Get all groups for this project
        $groupedData = HrGroup::with('user')
            ->where('project_id', $projectId)
            ->orderBy('group')
            ->get()
            ->groupBy('group');

        // Transform the data to match view expectations
        $groups = [];
        foreach ($groupedData as $groupName => $members) {
            $groups[] = [
                'name'    => $groupName,
                'members' => $members,
            ];
        }

        // Get all users who have attended this project
        $attendedUsers = HrAttend::with('user')
            ->whereHas('date', function ($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })
            ->where('attend_delete', false)
            ->get()
            ->pluck('user')
            ->unique('userid')
            ->values();

        return view('hrd.admin.projects.participants.group-management', compact('project', 'groups', 'attendedUsers'));
    }

    /**
     * Store a new group assignment
     */
    public function adminGroupStore(Request $request, $projectId)
    {
        $request->validate([
            'user_id' => 'required|string',
            'group'   => 'required|string|max:255',
        ]);

        // Search for user by userid
        $user = User::where('userid', $request->user_id)->first();

        if (! $user) {
            return redirect()->back()->with('error', 'ไม่พบผู้ใช้ที่มีรหัส: ' . $request->user_id);
        }

        // Note: Users can be added to groups even if they haven't attended yet
        // This allows for pre-assignment of groups before attendance

        // Check if user is already in a group for this project
        $existingGroup = HrGroup::where('project_id', $projectId)
            ->where('user_id', $user->id)
            ->first();

        $project = HrProject::findOrFail($projectId);

        if ($existingGroup) {
            // Update existing group assignment
            $oldGroup = $existingGroup->group;
            $existingGroup->update([
                'group' => $request->group,
            ]);

            // Log group update
            $this->logGroupAssignment($project, $user, $request->group, 'updated', [
                'updated_by_admin' => true,
                'old_group'        => $oldGroup,
            ]);

            return redirect()->back()->with('success', 'อัปเดตการจัดกลุ่มผู้ใช้ ' . $user->name . ' (รหัส: ' . $request->user_id . ') จากกลุ่ม ' . $oldGroup . ' เป็นกลุ่ม ' . $request->group . ' เรียบร้อยแล้ว');
        } else {
            // Create new group assignment
            HrGroup::create([
                'project_id' => $projectId,
                'user_id'    => $user->id,
                'group'      => $request->group,
            ]);

            // Log group assignment
            $this->logGroupAssignment($project, $user, $request->group, 'assigned', [
                'assigned_by_admin' => true,
            ]);

            return redirect()->back()->with('success', 'จัดกลุ่มผู้ใช้ ' . $user->name . ' (รหัส: ' . $request->user_id . ') เข้ากลุ่ม ' . $request->group . ' เรียบร้อยแล้ว');
        }
    }

    /**
     * Update a group assignment
     */
    public function adminGroupUpdate(Request $request, $projectId, $groupId)
    {
        $request->validate([
            'group' => 'required|string|max:255',
        ]);

        $group = HrGroup::where('project_id', $projectId)
            ->where('id', $groupId)
            ->firstOrFail();

        $group->update([
            'group' => $request->group,
        ]);

        // Log group update
        $project = HrProject::findOrFail($projectId);
        $user    = User::find($group->user_id);
        $this->logGroupAssignment($project, $user, $request->group, 'updated', [
            'updated_by_admin' => true,
            'old_group'        => $group->getOriginal('group'),
        ]);

        return redirect()->back()->with('success', 'อัปเดตการจัดกลุ่มเรียบร้อยแล้ว');
    }

    /**
     * Delete a group assignment
     */
    public function adminGroupDelete(Request $request, $projectId, $groupId)
    {
        $group = HrGroup::where('project_id', $projectId)
            ->where('id', $groupId)
            ->firstOrFail();

        // Log group deletion
        $project = HrProject::findOrFail($projectId);
        $user    = User::find($group->user_id);
        $this->logGroupAssignment($project, $user, $group->group, 'deleted', [
            'deleted_by_admin' => true,
        ]);

        $group->delete();

        return redirect()->back()->with('success', 'ลบการจัดกลุ่มเรียบร้อยแล้ว');
    }

    /**
     * Import groups from Excel file
     */
    public function adminGroupImport(Request $request, $projectId)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            $project = HrProject::findOrFail($projectId);

            Excel::import(new HrGroupsImport($projectId), $request->file('import_file'));

            $importResults = session('import_results', []);

            // Log import operation
            $this->logImportOperation($project, 'groups',
                $importResults['imported'] ?? 0,
                $importResults['skipped'] ?? 0,
                $importResults['errors'] ?? [],
                [
                    'file_name' => $request->file('import_file')->getClientOriginalName(),
                ]
            );

            $message = "นำเข้าข้อมูลเสร็จสิ้น ";
            $message .= "นำเข้า: {$importResults['imported']} รายการ, ";
            $message .= "ข้าม: {$importResults['skipped']} รายการ";

            if (! empty($importResults['errors'])) {
                $message .= " มีข้อผิดพลาดเกิดขึ้นระหว่างการนำเข้า";
                return redirect()->back()
                    ->with('warning', $message)
                    ->with('import_errors', $importResults['errors']);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            // Log import error
            $project = HrProject::findOrFail($projectId);
            $this->logImportOperation($project, 'groups', 0, 0, [$e->getMessage()], [
                'file_name' => $request->file('import_file')->getClientOriginalName(),
            ]);

            return redirect()->back()->with('error', 'การนำเข้าล้มเหลว: ' . $e->getMessage());
        }
    }

    /**
     * Download Excel template for group import
     */
    public function adminGroupTemplate($projectId)
    {
        $project = HrProject::findOrFail($projectId);

        return Excel::download(new HrGroupsTemplateExport($projectId),
            'group_import_template_' . $project->project_name . '.xlsx');
    }

}
