<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'body',
        'slug'
    ];


    public function user()
    {
        return $this->belongsTo('App\User')->members();
    }

    public function editor()
    {
    	return $this->belongsTo('App\User');
    }
}
