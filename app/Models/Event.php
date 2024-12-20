<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;

class Event extends Model
{
    protected $fillable = [
        'title','meta_description','time','expiration_date','shorted_text','body','image','type','slug','visibility','group_id','created_at','updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class)->withPivot(['participate'])->withTimestamps();
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('Y-m-d');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('Y-m-d');
    }

    public function getTimeAttribute($date)
    {
        $time = Carbon::parse($date)->format('Y-m-d H:i');

        //return $time!=date("Y-m-d H:i") ? $time : '';
        return isset($date) ?  Carbon::parse($date)->format('Y-m-d H:i') : '';
    }

    public function isEditor() {
        return Auth::check() && $this->user_id == Auth::user()->id;
    }

    public function isGroupEvent() {
        return $this->group_id != 0;
    }
}
