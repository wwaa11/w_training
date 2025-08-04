<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'group',
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(HrProject::class, 'project_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Scopes
    public function scopeByProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
