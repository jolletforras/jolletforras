<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProjectTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'user_id'
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class)->withTimestamps();
    }

    public function getTagList() {
        $tags =  [''=>''] + DB::table('project_tags')
                ->join('project_project_tag', 'project_project_tag.project_tag_id', '=', 'project_tags.id')
                ->join('projects', 'projects.id', '=', 'project_project_tag.project_id')
                ->whereNotNull('projects.lat')->whereNotNull('projects.lng')
                ->pluck('project_tags.name', 'project_tags.id')->all();

        return $tags;
    }
}
