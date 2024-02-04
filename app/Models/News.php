<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class News extends Model
{
    use HasFactory;

    protected $fillable = ['title','meta_description','body','image','slug','group_id','visibility'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function editor()
    {
    	return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(NewsTag::class)->withTimestamps();
    }

    public function getUpdatedAtAttribute($date)
    {
        //return (new Carbon($date))->format('Y-m-d');
        return Carbon::parse($date)->format('Y-m-d');
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('Y-m-d');
    }

    public function getNewsByGroupTagId($tag_id) {

        /*$result = DB::table('news')
            ->join('groups', 'groups.id', '=', 'news.group_id')
            ->join('group_group_tag', 'group_group_tag.group_id', '=', 'groups.id')
            ->join('group_tags', 'group_tags.id', '=', 'group_group_tag.group_tag_id')
            ->where('group_tags.id',$tag_id)
            ->where('groups.status','active')
            ->get();*/

      /*  SELECT n.*
FROM news AS n
INNER JOIN groups AS g ON g.id=n.group_id
INNER JOIN group_group_tag AS ggt ON ggt.group_id=g.id
INNER JOIN group_tags AS gt ON gt.id=ggt.group_tag_id
WHERE gt.id=11 AND g.`status`='active'
ORDER BY n.created_at DESC;*/

        $result = DB::table('group_tags')
            ->select('news.id', 'news.title', 'news.body', 'news.slug', 'news.group_id', 'groups.name AS group_name', 'groups.slug AS group_slug', 'groups.public AS group_is_public')
            ->join('group_group_tag', 'group_group_tag.group_tag_id', '=', 'group_tags.id')
            ->join('groups', 'groups.id', '=', 'group_group_tag.group_id')
            ->join('news', 'news.group_id', '=', 'groups.id')
            ->where('group_tags.id',$tag_id)
            ->where('groups.status','active')
            ->get();

        return $result;


        /*$query = "
            SELECT n.* 
            FROM group_tags AS gt
            INNER JOIN group_group_tag AS ggt ON ggt.group_tag_id=gt.id
            INNER JOIN groups AS g ON g.id=ggt.group_id
            INNER JOIN news AS n ON n.group_id=g.id
            WHERE gt.id=? AND g.`status`='active'
            ORDER BY n.created_at DESC";
        return DB::select($query,[$tag_id]);*/
    }
}
