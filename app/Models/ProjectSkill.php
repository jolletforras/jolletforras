<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectSkill extends Model
{
    protected $fillable = [
        'name', 'slug', 'user_id'
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class)->withTimestamps();
    }
}
