<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Forum extends Model
{
    use HasFactory;

    protected $fillable = [	'title','body','slug','group_id', 'active', 'announcement' ];
    
    protected $dates=['updated_at']; //így használhatóak a carbon függvényel, pl. $forum->updated_at->format('Y-m-d');
    
    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function tags()
    {
    	return $this->belongsToMany(ForumTag::class)->withTimestamps();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    
    public function getUpdatedAtAttribute($date)
    {
    	//return (new Carbon($date))->format('Y-m-d');
    	return Carbon::parse($date)->format('Y-m-d H:i');
    }
    
    public function getTagListAttribute()
    {
    	return $this->tags->lists('id')->all();
    }
/*
    public function getGroupAttribute()
    {
        return $this->group->all();
    }*/
}
