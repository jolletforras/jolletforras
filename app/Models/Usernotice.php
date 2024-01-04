<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Support\Facades\DB;

class Usernotice extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'post_id', 'user_id', 'type', 'post_created_at'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFindBy($query,$user_id,$post_id,$type)
    {
        $query->where('user_id',$user_id)->where('post_id',$post_id)->where('type', $type);
    }
}
