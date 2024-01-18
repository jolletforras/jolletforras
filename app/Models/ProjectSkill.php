<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProjectSkill extends Model
{
    protected $fillable = [
        'name', 'slug', 'user_id'
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class)->withTimestamps();
    }

    public function getTagList() {
        $tags =  [''=>''] + DB::table('project_skills')
                ->join('project_project_skill', 'project_project_skill.project_skill_id', '=', 'project_skills.id')
                ->join('projects', 'projects.id', '=', 'project_project_skill.project_id')
                ->whereNotNull('projects.lat')->whereNotNull('projects.lng')
                ->pluck('project_skills.name', 'project_skills.id')->all();

        return $tags;
    }
}
