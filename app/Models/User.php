<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Auth;

class User extends Authenticatable
{
    //use SoftDeletes;

    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false; // put this code

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'activation_code', 'activated', 'full_name', 'location', 'city', 'zip_code', 'introduction', 'intention', 'interest', 'slug', 'lat', 'lng',
        'login_code', 'email_sent_at', 'facebook_url', 'webpage_name', 'webpage_url', 'birth_year', 'public', 'last_login', 'new_post', 'updated_at', 'has_article', 'has_creation'
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

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['updated_at'];

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

    //ezeket hozta létre
    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    //ezen csoportok tagjai
    public function member_of_groups()
    {
        return $this->belongsToMany(Group::class);
    }

    //ezen kezdemenyezesek tagjai
    public function member_of_projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function skill_tags()
    {
        return $this->belongsToMany(UserSkill::class)->withTimestamps();
    }

    public function interest_tags()
    {
        return $this->belongsToMany(UserInterest::class)->withTimestamps();
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

    public function creations()
    {
        return $this->hasMany(Creation::class);
    }

    public function newsletters()
    {
        return $this->hasMany(Newsletter::class);
    }

    public function groupnews()
    {
        return $this->hasMany(Groupnews::class);
    }

    public function projectnews()
    {
        return $this->hasMany(Projectnews::class);
    }

    public function notices()
    {
        return $this->hasMany(Notice::class);
    }

    public function commendations()
    {
        return $this->hasMany(Commendation::class);
    }

    public function getTagListAttribute()
    {
        return $this->skill_tags->lists('id')->all();
    }

    public function getCreatedAtAttribute($date)
    {
        //return (new Carbon($date))->format('Y-m-d');
        return Carbon::parse($date)->format('Y-m-d');
    }

    public function getWebpagesAttribute() {

        $webpages = array();

        $names = explode(",",$this->webpage_name);
        $urls  = explode(",",$this->webpage_url);

        $num = count($names) > count($urls) ? count($urls) : count($names);

        for ($i = 0; $i < $num; $i++) {
            $webpages[] = '<a href="'.trim($urls[$i]).'" target="_blank">'.trim($names[$i]).'</a>';
        }

        return implode(', ',$webpages);
    }

    public function getStatusTxtAttribute() {
        return $this->status == 0 ? "emailcím nincs megerősítve" : ($this->status == 1 ? "emailcím megerősítve" : ($this->status == 3 ? "aktív" : "deaktíválta magát"));
    }

    public function incompleteProfile() {
        return
            strlen($this->name)<2 ||
            strlen($this->city)=='' ||
            strlen($this->introduction)<config('constants.LENGTH_INTRO') ||
            $this->skill_tags->count()==0;
    }

    public function myProfile() {
        return Auth::check() && Auth::user()->id==$this->id;
    }

    public function isAdminInGroup() {
        $groups = DB::table('group_user')->where('user_id',$this->id)->where('admin',1)->get();

        return $groups->isNotEmpty();
    }

    public function scopeMembers($query)
    {
        $query->where('status','=', 3);
    }

    public function scopeAdmins($query)
    {
        $query->where('admin','=', 1);
    }

    public function getFullLocationAttribute() {
        $location = "";
        if($this->city=="Budapest") {
            if($this->location!='') {
                $location .= $this->location;
                $location .= !is_numeric(stripos($this->location,"Budapest")) ? ", Budapest" : "";
            }
            else {
                $location .= "Budapest";
            }
        }
		else {
            $location .= ($this->location!='' && $this->location!=$this->city) ? $this->location.', ' : '';
			$location .=  $this->city;
        }

        return $location;
    }

    public function incNewPost($notifiable_id,$type,$commenter_id) {

        $table = $type=='Forum' ? 'forums' : 'events';
        $two_weeks_before = date( 'Y-m-d', strtotime('-2 weeks'));

        DB::table('users')
        ->join('group_user', 'group_user.user_id', '=', 'users.id')
        ->join($table, $table.'.group_id', '=', 'group_user.group_id')
        ->join('notices', 'notices.user_id', '=', 'users.id')
        ->where($table.'.id', $notifiable_id)
        ->where('users.id','<>', $commenter_id)
        ->where('notices.notifiable_id', $notifiable_id)
        ->where('notices.type',$type)
        ->where(function($query) use($two_weeks_before) {
            $query->where('notices.new',0)
                ->orWhere('notices.updated_at','<',$two_weeks_before);
        })
        ->update( ['users.new_post' => DB::raw('users.new_post + 1')]);


        /*
        'UPDATE users AS u
        INNER JOIN group_user AS gu ON gu.user_id = u.id
        INNER JOIN forums AS f ON f.group_id = gu.group_id
        INNER JOIN notices AS n ON n.user_id = u.id
        SET u.new_post = u.new_post+1
        WHERE f.id=93 AND u.id<>1 AND n.notifiable_id=93 AND n.type="Forum" AND (n.`new`=0 OR n.updated_at<'2023.12.19');
         */

        return true;
    }

    //beállítja a new_post értéket aszerint hogy hány olyan csoport téma/esemény van, ahol még nem olvasta el a bejegyzést vagy a legújabb hozzászólásokat
    public function adjustGroupNewPostAll() {
        DB::table('users')->update(['new_post' => 0]);

        $query = "
            UPDATE users AS u
            INNER JOIN (
                SELECT user_id, count(*) AS new_post
                FROM notices
                WHERE updated_at>CURDATE() - INTERVAL 14 DAY AND new>0
                GROUP BY user_id
            ) AS n ON n.user_id=u.id
            SET u.new_post = n.new_post";
        DB::statement($query);
    }

    //beállítja a new_post értéket aszerint hogy hány olyan csoport téma/esemény van, ahol még nem olvasta el a bejegyzést vagy a legújabb hozzászólásokat
    public function adjustGroupNewPost($user_id) {
        DB::table('users')->where('id',$user_id)->update(['new_post' => 0]);

        $query = "
            UPDATE users AS u
            INNER JOIN (
                SELECT user_id, count(*) AS new_post
                FROM notices
                WHERE updated_at>CURDATE() - INTERVAL 14 DAY AND new>0 AND user_id=?
                GROUP BY user_id
            ) AS n ON n.user_id=u.id
            SET u.new_post = n.new_post";
        DB::update($query,[$user_id]);
    }


    //beállítja a user_new_post értéket aszerint hogy hány olyan írás, alkotás van, ahol még nem olvasta el a bejegyzést és nem a sajátja
    public function adjustUserNewPostAll($num_new_posts,$two_weeks_before) {
        $query = "
            UPDATE users AS u
            LEFT JOIN (
                SELECT user_id, count(id) AS read_it
                FROM usernotices
                GROUP BY user_id
            ) AS un ON un.user_id=u.id
            LEFT JOIN (
                SELECT user_id, count(id) AS post FROM articles WHERE created_at>='".$two_weeks_before."' GROUP BY user_id
            ) AS a ON a.user_id=u.id
            LEFT JOIN (
                SELECT user_id, count(id) AS post FROM creations WHERE created_at>='".$two_weeks_before."' GROUP BY user_id
            ) AS c ON c.user_id=u.id
            SET u.user_new_post = GREATEST($num_new_posts-IFNULL(un.read_it,0)-IFNULL(a.post,0)-IFNULL(c.post,0),0)";
        DB::update($query);
    }
}
