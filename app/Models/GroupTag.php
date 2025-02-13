<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GroupTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'name', 'slug', 'user_id'
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class)->withTimestamps();
    }

    public function commendations()
    {
        return $this->belongsToMany(Commendation::class)->withTimestamps();
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class)->withTimestamps();
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class)->withTimestamps();
    }

    static function getUsed()
    {
        $result = DB::table('group_tags')
            ->select('group_tags.id','group_tags.name','group_tags.slug')
            ->join('group_group_tag', 'group_group_tag.group_tag_id', '=', 'group_tags.id')
            ->join('groups', 'groups.id', '=', 'group_group_tag.group_id')
            ->where('groups.status','active')
            ->get();

        return $result;
    }

    static function getGroupNewsUsed()
    {
        $result = DB::table('group_tags')
            ->select('group_tags.id','group_tags.name','group_tags.slug')
            ->join('group_group_tag', 'group_group_tag.group_tag_id', '=', 'group_tags.id')
            ->join('groups', 'groups.id', '=', 'group_group_tag.group_id')
            ->join('groupnews', 'groupnews.group_id', '=', 'groups.id')
            ->where('groups.status','active')
            ->distinct()
            ->get();

        return $result;
   }

    static function getProjectNewsUsed()
    {
        $result = DB::table('group_tags')
            ->select('group_tags.id','group_tags.name','group_tags.slug')
            ->join('group_tag_project', 'group_tag_project.group_tag_id', '=', 'group_tags.id')
            ->join('projects', 'projects.id', '=', 'group_tag_project.project_id')
            ->join('projectnews', 'projectnews.project_id', '=', 'projects.id')
            ->where('projects.status','active')
            ->distinct()
            ->get();

        return $result;
    }

    static function getCommendationUsed()
    {
        $result = DB::table('group_tags')
            ->select('group_tags.id','group_tags.name','group_tags.slug')
            ->join('commendation_group_tag', 'commendation_group_tag.group_tag_id', '=', 'group_tags.id')
            ->join('commendations', 'commendations.id', '=', 'commendation_group_tag.commendation_id')
            ->where('commendations.active',1)
            ->where('commendations.approved',1)
            ->get();

        return $result;
    }

    static function getProjectUsed()
    {
        $result = DB::table('group_tags')
            ->select('group_tags.id','group_tags.name','group_tags.slug')
            ->join('group_tag_project', 'group_tag_project.group_tag_id', '=', 'group_tags.id')
            ->join('projects', 'projects.id', '=', 'group_tag_project.project_id')
            ->get();

        return $result;
    }

    static function getLocalGroupTagList() {
        $tags =  DB::table('group_tags')
                ->join('group_group_tag', 'group_group_tag.group_tag_id', '=', 'group_tags.id')
                ->join('groups', 'groups.id', '=', 'group_group_tag.group_id')
                ->whereNotNull('groups.lat')->whereNotNull('groups.lng')
                ->pluck('group_tags.name', 'group_tags.id')->all();

        return $tags;
    }

    static function getLocalProjectTagList() {
        $tags =  DB::table('group_tags')
            ->join('group_tag_project', 'group_tag_project.group_tag_id', '=', 'group_tags.id')
            ->join('projects', 'projects.id', '=', 'group_tag_project.project_id')
            ->whereNotNull('projects.lat')->whereNotNull('projects.lng')
            ->pluck('group_tags.name', 'group_tags.id')->all();

        return $tags;
    }
}
