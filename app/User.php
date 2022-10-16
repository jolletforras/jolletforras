<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;
//use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable
{
    //use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'activation_code', 'activated', 'full_name', 'location',
        'city', 'zip_code', 'introduction', 'intention', 'interest', 'slug', 'lat', 'lng',
        'facebook_url', 'webpage_name', 'webpage_url', 'birth_year', 'public', 'last_login'
    ];

    protected $dates = ['deleted_at'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    //A user can start many panel discussions (forums).
    public function forums()
    {
    	return $this->hasMany('App\Forum');
    }

    public function ideas()
    {
        return $this->hasMany('App\Idea');
    }

    public function projects()
    {
        return $this->hasMany('App\Project');
    }

    public function groups()
    {
        return $this->hasMany('App\Group');
    }

    public function tags()
    {
    	return $this->belongsToMany('App\UserSkill')->withTimestamps();
    }

    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }

    public function events()
    {
        return $this->hasMany('App\Event');
    }

    public function articles()
    {
        return $this->hasMany('App\Article');
    }

    public function news()
    {
        return $this->hasMany('App\News');
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
