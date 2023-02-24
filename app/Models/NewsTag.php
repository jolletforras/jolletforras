<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NewsTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'user_id'
    ];

	public function news()
    {
    	return $this->belongsToMany(News::class)->withTimestamps();
    }

    public function getTagsOfPublicNews()
    {
        return
            DB::table('news_tags')
                ->select('news_tags.id','news_tags.name','news_tags.slug')
                ->join('news_news_tag', 'news_news_tag.news_tag_id', '=', 'news_tags.id')
                ->join('news', 'news.id', '=', 'news_news_tag.news_id')
                ->where('news.visibility','<>','group')
                ->get();

        /*
            SELECT nt.*
            FROM news_tags AS nt
            INNER JOIN news_news_tag AS nnt ON nnt.news_tag_id=nt.id
            INNER JOIN news AS n ON n.id=nnt.news_id
            WHERE n.visibility<>'group'
         * */
    }
}
