<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Group extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'name', 'description', 'agreement', 'slug', 'public', 'webpage_name', 'webpage_url', 'location',
        'city', 'zip_code', 'lat', 'lng'
    ];

    protected $dates=['updated_at', 'deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class)->members();
    }

    public function tags()
    {
        return $this->belongsToMany(GroupTag::class)->withTimestamps();
    }

    public function members()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function noadmins()
    {
        return $this->belongsToMany(User::class)->wherePivot('admin',0)->withTimestamps();
    }


    public function admins()
    {
        return $this->belongsToMany(User::class)->wherePivot('admin',1)->withTimestamps();
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

    public function getMemberListAttribute()
    {
        return $this->members->pluck('id')->all();
    }

    public function getAdminListAttribute()
    {
        return $this->admins->pluck('id')->all();
    }

    public function getNoadminListAttribute()
    {
        return $this->noadmins->pluck('id')->all();
    }

    public function getTagListAttribute()
    {
        return $this->tags->pluck('id')->all();
    }

    public function isMember() {
        return $this->members->contains('id', Auth::user()->id);
    }

    public function isAdmin() {
        return Auth::check() && $this->admins->contains('id', Auth::user()->id);;
    }

    public function location() {
        $location = "";

        if($this->city=="Budapest") {
            if ($this->location != '') {
                $location .= $this->location;
                if (!is_numeric(stripos($this->location, "Budapest"))) {  //nincs benne a Budapest szó
                    $location .= ", Budapest";
                }
            } else {
                $location .= "Budapest";
            }
        }
        else {
            if($this->location!='' && $this->location!=$this->city) {
                $location .= $this->location.", ";
            }
            $location .= $this->city;
        }

        return $location;
    }
}
