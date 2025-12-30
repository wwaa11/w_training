<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_type',
        'project_name',
        'project_detail',
        'project_seat_assign',
        'project_group_assign',
        'project_start_register',
        'project_end_register',
        'project_register_today',
        'project_active',
        'project_delete',
    ];

    protected $casts = [
        'project_start_register' => 'datetime',
        'project_end_register'   => 'datetime',
        'project_seat_assign'    => 'boolean',
        'project_group_assign'   => 'boolean',
        'project_register_today' => 'boolean',
        'project_active'         => 'boolean',
        'project_delete'         => 'boolean',
    ];

    // Relationships
    public function dates()
    {
        return $this->hasMany(HrDate::class, 'project_id')->active();
    }

    public function attends()
    {
        return $this->hasMany(HrAttend::class, 'project_id');
    }

    public function activeAttends()
    {
        return $this->hasMany(HrAttend::class, 'project_id')->where('attend_delete', false);
    }

    public function links()
    {
        return $this->hasMany(HrLink::class, 'project_id')->where('link_delete', false)->active();
    }

    public function resultHeader()
    {
        return $this->hasOne(HrResultHeader::class, 'project_id');
    }

    public function results()
    {
        return $this->hasMany(HrResult::class, 'project_id');
    }

    public function groups()
    {
        return $this->hasMany(HrGroup::class, 'project_id');
    }

    public function onebook()
    {
        return $this->hasOne(HrOnebook::class, 'project_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('project_active', true)->where('project_delete', false);
    }

    public function scopeAvailableForRegistration($query)
    {
        $now = now();
        return $query->where('project_active', true)
            ->where('project_delete', false)
            ->where('project_end_register', '>=', $now);
    }

    // Project capacity and availability methods
    public function getCapacityStats()
    {
        $totalCapacity    = 0;
        $totalRegistered  = 0;
        $availableSlots   = 0;
        $fullTimeSlots    = 0;
        $limitedTimeSlots = 0;
        $totalTimeSlots   = 0;

        foreach ($this->dates->where('date_delete', false) as $date) {
            foreach ($date->times->where('time_delete', false) as $time) {
                $totalTimeSlots++;
                if ($time->time_limit) {
                    $limitedTimeSlots++;
                    $totalCapacity += $time->time_max;
                    $currentRegistrations = $time->activeAttends->count();
                    $totalRegistered += $currentRegistrations;
                    $availableSlots += ($time->time_max - $currentRegistrations);

                    if ($currentRegistrations >= $time->time_max) {
                        $fullTimeSlots++;
                    }
                }
            }
        }

        return [
            'totalTimeSlots'   => $totalTimeSlots,
            'limitedTimeSlots' => $limitedTimeSlots,
            'totalCapacity'    => $totalCapacity,
            'totalRegistered'  => $totalRegistered,
            'availableSlots'   => $availableSlots,
            'fullTimeSlots'    => $fullTimeSlots,
        ];
    }

    public function isFull()
    {
        if ($this->project_type === 'attendance') {
            return false; // Attendance projects don't have capacity limits
        }

        $stats = $this->getCapacityStats();
        return $stats['totalTimeSlots'] > 0 && $stats['fullTimeSlots'] === $stats['totalTimeSlots'];
    }

    public function hasAvailableSlots()
    {
        $stats = $this->getCapacityStats();
        return $stats['availableSlots'] > 0;
    }

    public function getAvailableSlotsCount()
    {
        $stats = $this->getCapacityStats();
        return $stats['availableSlots'];
    }

    public function getFullTimeSlotsCount()
    {
        $stats = $this->getCapacityStats();
        return $stats['fullTimeSlots'];
    }

    public function getLimitedTimeSlotsCount()
    {
        $stats = $this->getCapacityStats();
        return $stats['limitedTimeSlots'];
    }

    public function getTotalCapacity()
    {
        $stats = $this->getCapacityStats();
        return $stats['totalCapacity'];
    }

    public function getTotalRegistered()
    {
        $stats = $this->getCapacityStats();
        return $stats['totalRegistered'];
    }

    public function isRegistrationExpired()
    {
        return now() > $this->project_end_register;
    }

    public function isRegistrationActive()
    {
        return now() >= $this->project_start_register && now() <= $this->project_end_register;
    }

    /**
     * Get count of unique users who have registered for this project
     */
    public function getUniqueParticipantsCount()
    {
        return $this->activeAttends()
            ->distinct('user_id')
            ->count('user_id');
    }

    /**
     * Get count of unique users who have attended (check-in) for this project
     */
    public function getUniqueAttendedCount()
    {
        return $this->activeAttends()
            ->whereNotNull('attend_datetime')
            ->distinct('user_id')
            ->count('user_id');
    }
}
