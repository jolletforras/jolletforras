<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'notifiable_id', 'user_id', 'type', 'comment_id', 'email', 'email_sent', 'ask_notice', 'login_code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFindBy($query,$notifiable_id,$user_id,$type)
    {
        $query->where('notifiable_id',$notifiable_id)->where('user_id',$user_id)->where('type', $type);
    }
}
