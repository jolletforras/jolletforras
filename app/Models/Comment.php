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

    public function getUpdatedAtAttribute($date)
    {
        //return (new Carbon($date))->format('Y-m-d');
        return Carbon::parse($date)->format('Y-m-d H:i');
    }
}
