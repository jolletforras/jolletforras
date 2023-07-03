<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Commendation extends Model
{
    use HasFactory;

    protected $fillable = [	'title','body','url','slug','public' ];
    
    protected $dates=['updated_at']; //így használhatóak a carbon függvényel, pl. $recommand->updated_at->format('Y-m-d');
    
    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('Y-m-d');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('Y-m-d');
    }

}
