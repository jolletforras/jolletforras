<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ArticleTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'name', 'slug', 'user_id'
    ];

    public function articles()
    {
        return $this->belongsToMany(Article::class)->withTimestamps();
    }
}
