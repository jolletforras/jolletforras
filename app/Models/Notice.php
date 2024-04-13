<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Support\Facades\DB;

class Notice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'group_id', 'notifiable_id', 'user_id', 'type', 'comment_id', 'new', 'email', 'email_sent', 'ask_notice', 'read_it', 'login_code'
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

        $query->where('user_id',Auth::user()->id)->where('updated_at','>',$two_weeks_before)->orderBy('updated_at', 'DESC');
    }

    static function setNullUpdatedAt($group_id,$user_id) {
        $query = "
          UPDATE notices 
          SET updated_at=NULL 
          WHERE group_id=? AND user_id=?";
        DB::update($query,[$group_id,$user_id]);
    }
}
