<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Creation extends Model
{
    use HasFactory;

    protected $fillable = [	'title','body','url','has_image','slug','public','active','iframe_code','meta_title','meta_image','meta_description','category_id'];
    
    protected $dates=['updated_at']; //így használhatóak a carbon függvényel, pl. $recommand->updated_at->format('Y-m-d');
    
    public function user()
    {
    	return $this->belongsTo(User::class);
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
