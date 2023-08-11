<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'body', 'looking_for', 'slug', 'counter', 'public', 'admin'
    ];

    protected $dates=['updated_at', 'deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class)->members();
    }

    public function tags()
    {
        return $this->belongsToMany(ProjectSkill::class)->withTimestamps();
    }

    public function members()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function admins()
    {
        return $this->belongsToMany(User::class)->wherePivot('admin',1)->withTimestamps();
    }


    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function isMember() {
        return $this->members->contains('id', Auth::user()->id);
    }

    public function getAdminListAttribute()
    {
        return $this->admins->pluck('id')->all();
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('Y-m-d');
    }


    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('Y-m-d H:i');
    }

    public function getMemberListAttribute()
    {
        return $this->members->lists('id')->all();
    }

    public function getTagListAttribute()
    {
        return $this->tags->lists('id')->all();
    }
}
