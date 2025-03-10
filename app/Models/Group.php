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
        'name', 'meta_description', 'description', 'agreement', 'member_info', 'admin_info', 'ask_motivation', 'slug', 'public', 'knowledge_tab', 'knowledge_info', 'user_visibility', 'webpage_name', 'webpage_url', 'location',
        'city', 'zip_code', 'lat', 'lng', 'counter', 'status', 'last_news_at'
    ];

    protected $dates=['updated_at', 'deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(GroupTag::class)->withTimestamps();
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class)->withTimestamps();
    }


    public function creations()
    {
        return $this->belongsToMany(Creation::class)->withTimestamps();
    }


    public function commendations()
    {
        return $this->belongsToMany(Commendation::class)->withTimestamps();
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class)->withTimestamps();
    }

    public function members()
    {
        return $this->belongsToMany(User::class)->withPivot(['motivation','updated_at'])->where('status','=', 3)->withTimestamps();
    }

    public function themes()
    {
        return $this->hasMany(Forum::class);
    }

    public function events()
    {
        return $this->hasMany(Forum::class);
    }

    public function newss()
    {
        return $this->hasMany(Groupnews::class);
    }

    public function noadmins()
    {
        return $this->belongsToMany(User::class)->wherePivot('admin',0)->withTimestamps();
    }


    public function admins()
    {
        return $this->belongsToMany(User::class)->wherePivot('admin',1)->withTimestamps();
    }

    public function members_ask_new_post_notice()
    {
        return $this->belongsToMany(User::class)->wherePivot('new_post_notice',1)->withTimestamps();
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

    public function getNoGroupMembersListAttribute()
    {
        return User::members()->whereNotIn('id', $this->member_list)->orderBy('name', 'ASC')->pluck('name', 'id');
    }

    public function getMemberListWithNewPostNoticeAttribute()
    {
        return $this->members->where('new_post_notice',1)->pluck('id')->all();
    }

    public function isMember() {
        return Auth::check() && $this->members->contains('id', Auth::user()->id);
    }

    public function isAdmin() {
        return Auth::check() && $this->admins->contains('id', Auth::user()->id);
    }

    public function isActive() {
        return $this->status=='active';
    }

    public function hasNews() {
        if(Auth::check()) {
            $newss = $this->newss()->get();
        }
        else {
            $newss = $this->newss()->where('visibility','public')->get();
        }

        return $newss->isNotEmpty();
    }

    public function get_location() {
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

    //ezt most nem használom
    public function get_card_text() {
        $tags_text = $card_text ='';
        $tags = array();
        if(Auth::check() && $this->tags->isNotEmpty()) {
            foreach($this->tags as $tag) {
                $tags[] = $tag->name;
            }
            $tags_text .= implode(', ',$tags);
        }
        $length_tag_text = strlen($tags_text);
        $length_text = strlen($this->description)+$length_tag_text;
        if($length_text>500) {
            $card_text .= nl2br(mb_substr($this->description,0,400-$length_tag_text));
            $card_text .= '<a href="'.url('csoport',$this->id).'/'.$this->slug.'">... tovább</a>';
        }
        else {
            $card_text .= nl2br($this->description);
        }

        return $card_text;
    }
}
