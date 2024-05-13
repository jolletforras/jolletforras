<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Commendation extends Model
{
    use HasFactory;

    protected $fillable = [	'title','body','url','slug','public','active','approved','meta_title','meta_image','meta_description','has_image'];
    
    protected $dates=['updated_at']; //így használhatóak a carbon függvényel, pl. $recommand->updated_at->format('Y-m-d');
    
    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(GroupTag::class)->withTimestamps();
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class)->withTimestamps();
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
