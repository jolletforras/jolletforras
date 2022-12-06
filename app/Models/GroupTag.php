<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GroupTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'user_id'
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class)->withTimestamps();
    }

    public function getTagList() {
        $tags =  [''=>''] + DB::table('group_tags')
                ->join('group_group_tag', 'group_group_tag.group_tag_id', '=', 'group_tags.id')
                ->join('groups', 'groups.id', '=', 'group_group_tag.group_id')
                ->whereNotNull('groups.lat')->whereNotNull('groups.lng')
                ->pluck('group_tags.name', 'group_tags.id')->all();

        return $tags;
    }
}
