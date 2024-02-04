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


    public function getUsed()
    {
        $result = DB::table('group_tags')
            ->select('group_tags.id','group_tags.name','group_tags.slug')
            ->join('group_group_tag', 'group_group_tag.group_tag_id', '=', 'group_tags.id')
            ->join('groups', 'groups.id', '=', 'group_group_tag.group_id')
            ->where('groups.status','active')
            ->get();

        return $result;
    }

    public function getNewsUsed()
    {
        $result = DB::table('group_tags')
            ->select('group_tags.id','group_tags.name','group_tags.slug')
            ->join('group_group_tag', 'group_group_tag.group_tag_id', '=', 'group_tags.id')
            ->join('groups', 'groups.id', '=', 'group_group_tag.group_id')
            ->join('news', 'news.group_id', '=', 'groups.id')
            ->where('groups.status','active')
            ->get();

        return $result;
   }

    public function getCommendationUsed()
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

    public function getLocalTagList() {
        $tags =  DB::table('group_tags')
                ->join('group_group_tag', 'group_group_tag.group_tag_id', '=', 'group_tags.id')
                ->join('groups', 'groups.id', '=', 'group_group_tag.group_id')
                ->whereNotNull('groups.lat')->whereNotNull('groups.lng')
                ->pluck('group_tags.name', 'group_tags.id')->all();

        return $tags;
    }
}
