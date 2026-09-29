<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrGroupDefinition extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'max_members',
        'sort_order',
    ];

    protected $casts = [
        'max_members' => 'integer',
        'sort_order'  => 'integer',
    ];

    public function project()
    {
        return $this->belongsTo(HrProject::class, 'project_id');
    }

    public function memberCount(): int
    {
        return HrGroup::where('project_id', $this->project_id)
            ->where('group', $this->name)
            ->count();
    }

    public function hasCapacity(): bool
    {
        if ($this->max_members === null) {
            return true;
        }

        return $this->memberCount() < $this->max_members;
    }

    public function remainingSlots(): ?int
    {
        if ($this->max_members === null) {
            return null;
        }

        return max(0, $this->max_members - $this->memberCount());
    }

    public static function normalizeDepartment(?string $department): string
    {
        return mb_strtolower(trim((string) $department));
    }

    /**
     * Auto mode: allow join if group has capacity and joining would not split departments
     * when another group without this department still has room.
     */
    public function departmentAllowedForAutoJoin(?string $userDepartment, array $groupDepartmentMap, iterable $allDefinitions): bool
    {
        if (! $this->hasCapacity()) {
            return false;
        }

        $userDepartment = self::normalizeDepartment($userDepartment);
        if ($userDepartment === '') {
            return true;
        }

        $currentGroupDepartments = $groupDepartmentMap[$this->name] ?? [];
        if (! in_array($userDepartment, $currentGroupDepartments, true)) {
            return true;
        }

        foreach ($allDefinitions as $other) {
            if ($other->id === $this->id || ! $other->hasCapacity()) {
                continue;
            }

            $otherDepartments = $groupDepartmentMap[$other->name] ?? [];
            if (! in_array($userDepartment, $otherDepartments, true)) {
                return false;
            }
        }

        return true;
    }
}
