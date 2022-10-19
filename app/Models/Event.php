<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;

class Event extends Model
{
    protected $fillable = [
        'title','body','type','slug','visibility','group_id','created_at','updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->members();
    }

    public function editor()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('Y-m-d');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('Y-m-d H:i');
    }

    public function isEditor() {
        return Auth::check() && $this->user_id == Auth::user()->id;
    }
}
