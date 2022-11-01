<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    //use SoftDeletes;

    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'activation_code', 'activated', 'full_name', 'location',
        'city', 'zip_code', 'introduction', 'intention', 'interest', 'slug', 'lat', 'lng',
        'facebook_url', 'webpage_name', 'webpage_url', 'birth_year', 'public', 'last_login'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password','remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //A user can start many panel discussions (forums).
    public function forums()
    {
        return $this->hasMany(Forum::class);
    }

    public function ideas()
    {
        return $this->hasMany(Idea::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function tags()
    {
        return $this->belongsToMany(UserSkill::class)->withTimestamps();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }

    public function getTagListAttribute()
    {
        return $this->tags->lists('id')->all();
    }

    public function getCreatedAtAttribute($date)
    {
        //return (new Carbon($date))->format('Y-m-d');
        return Carbon::parse($date)->format('Y-m-d');
    }

    public function incompleteProfile() {
        return
            strlen($this->name)<2 ||
            strlen($this->city)=='' ||
            strlen($this->introduction)<config('constants.LENGTH_INTRO') ||
            $this->tags->count()==0;
    }

    public function scopeMembers($query)
    {
        $query->where('status','=', 3);
    }

    public function scopeAdmins($query)
    {
        $query->where('admin','=', 1);
    }
}
