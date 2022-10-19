<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Group extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'description', 'agreement', 'slug', 'public', 'webpage_name', 'webpage_url', 'location',
        'city', 'zip_code', 'lat', 'lng'
    ];

    protected $dates=['updated_at', 'deleted_at'];

    public function user()
    {
        return $this->belongsTo('App\Models\User')->members();
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\GroupTag')->withTimestamps();
    }

    public function members()
    {
        return $this->belongsToMany('App\Models\User')->withTimestamps();
    }

    public function noadmins()
    {
        return $this->belongsToMany('App\Models\User')->wherePivot('admin',0)->withTimestamps();
    }


    public function admins()
    {
        return $this->belongsToMany('App\Models\User')->wherePivot('admin',1)->withTimestamps();
    }

    public function comments()
    {
        return $this->morphMany('App\Models\Comment', 'commentable');
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('Y-m-d');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('Y-m-d');
    }

    public function getMemberListAttribute()
    {
        return $this->members->lists('id')->all();
    }

    public function getAdminListAttribute()
    {
        return $this->admins->lists('id')->all();
    }

    public function getNoadminListAttribute()
    {
        return $this->noadmins->lists('id')->all();
    }

    public function getTagListAttribute()
    {
        return $this->tags->lists('id')->all();
    }

    public function isMember() {
        return $this->members->contains('id', Auth::user()->id);
    }

    public function isAdmin() {
        return Auth::check() && $this->admins->contains('id', Auth::user()->id);;
    }

}
