<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Notice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'notifiable_id', 'user_id', 'type', 'comment_id', 'new', 'email', 'email_sent', 'ask_notice', 'login_code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFindBy($query,$notifiable_id,$user_id,$type)
    {
        $query->where('notifiable_id',$notifiable_id)->where('user_id',$user_id)->where('type', $type);
    }

    public function scopeFindNew($query)
    {
        $two_weeks_before = date( 'Y-m-d', strtotime('-2 weeks'));

        $query->where('user_id',Auth::user()->id)->where('new','>',0)->where('updated_at','>',$two_weeks_before);
    }
}
