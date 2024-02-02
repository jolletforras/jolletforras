<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CommendationTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'name', 'slug', 'user_id'
    ];

    public function commendations()
    {
        return $this->belongsToMany(Commendation::class)->withTimestamps();
    }

    public function getTagList() {
        $tags =  DB::table('commendation_tags')
            ->join('commendation_commendation_tag', 'commendation_commendation_tag.commendation_tag_id', '=', 'commendation_tags.id')
            ->join('commendations', 'commendations.id', '=', 'commendation_commendation_tag.commendation_id')
            ->pluck('commendation_tags.name', 'commendation_tags.id')->all();

        return $tags;
    }
}
