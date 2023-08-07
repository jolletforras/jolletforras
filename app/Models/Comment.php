<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'deleted'
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function commenter()
    {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('Y-m-d H:i');
    }


    public function getUpdatedAtAttribute($date)
    {
        //return (new Carbon($date))->format('Y-m-d');
        return Carbon::parse($date)->format('Y-m-d H:i');
    }

    public function getSinceAttribute($date)
    {
        $now = Carbon::now();
        $created_at = Carbon::parse($this->created_at);

        $minutes = $created_at->diffInMinutes($now);

        if($minutes<60) {
            $since = $created_at->diffInMinutes($now).' p.' ;
        }
        elseif ($minutes<1440) {  //24*60 = 1 nap
            $since = $created_at->diffInHours($now).' ó.';
        }
        elseif ($minutes<10080) { //24*60*7 = 1 hét
            $since = $created_at->diffInWeeks($now).' n.';
        }
        elseif ($minutes<=40320) { //24*60*7*4 = 4 hét
            $since = $created_at->diffInWeeks($now).' hete';
        }
        else {
            $since = $created_at->diffInMonths($now).' hónapja';
        }

        return $since;
    }
}
