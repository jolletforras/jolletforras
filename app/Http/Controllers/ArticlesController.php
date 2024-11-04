<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Traits\TagTrait;
use App\Http\Requests;
use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use App\Models\Group;
use App\Models\Usernotice;
use App\Models\GroupTag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ArticlesController extends Controller
{
    use TagTrait;

	public function __construct() {
		$this->middleware('auth', ['except'=>['index','show','show_user_articles']]);
        $this->show_options = ['just_profile'=>'csak a profilomnál','portal_too'=>'az Írások menüben is'];
	}

	public function index(Request $request)
	{
		$articles = Article::where('show', 'portal_too')->where('status', 'active')->latest()->get();

		return view('articles.index', compact('articles'));
	}


    public function show_group_articles($group_id)
    {
        $group = Group::findOrFail($group_id);
        $articles = $group->articles()->where('status', 'active')->latest()->get();

        return view('articles.index', compact('articles','group'));
    }


    public function show_user_articles($user_id)
    {
        $user = User::findOrFail($user_id);
        $articles = $user->articles()->where('status', 'active')->latest()->get();
        $tab = "articles";

        return view('articles.user_articles', compact('user','articles','tab'));
    }


    /**
     * Displays a specific article
     *
     * @param  integer $id The article ID
     * @return Response
     */
    public function show($id)
    {
        $article = Article::findOrFail($id);
        $comments = Comment::where('commentable_type', 'App\Models\Article')->where('commentable_id', $id)->get();

        $group_where_admin_have_article_url = array();
        if(Auth::check()) {
            $user = Auth()->user();
            $user_id = $user->id;
            $notice = Usernotice::findBy($user_id,$id,'Article')->first();
            if(is_null($notice)) {
                Usernotice::create(['user_id' => $user_id, 'post_id' => $id, 'type' => 'Article', 'post_created_at' => $article->created_at]);
                $user_new_post = $user->user_new_post - 1;
                $user->user_new_post = $user_new_post>0 ? $user_new_post : 0;
                $user->save();
            }
        }

        return view('articles.show', compact('article','comments'));
    }


	public function create(Request $request)
	{
        $show_options = $this->show_options;

        $tags = GroupTag::get()->pluck('name', 'id');
        $categories =  Auth::user()->categories()->pluck('title');
            //["Gyertyános", "Társadalmi"];

		return view('articles.create',compact('show_options','tags','categories'));
	}
	
	public function store(Request $request)
	{
        $description = $request->get('body');

        $article = Auth::user()->articles()->create([
            'title' => $request->get('title'),
            'meta_description' => $request->get('meta_description'),
            'body' => $description,
            'short_description' => justbr($description,700),
            'image' => getfirstimage($description),
            'slug' => Str::slug($request->get('title')),
            'show' => $request->get('show')
        ]);

        Auth::user()->has_article = 1;
        Auth::user()->save();

        User::members()->where('id','<>',Auth::user()->id)->increment('user_new_post', 1);

        $tag_list=$this->getTagList($request->input('tag_list'), 'App\Models\ArticleTag');
        $article->tags()->attach($tag_list);

        if($request->get('show')=='portal_too') {
            return redirect('irasok');
        }
        else {
            return redirect('profil/'.Auth::user()->id.'/'.Auth::user()->slug.'/irasok');
        }
	}

	/**
	 * Edit a specific article
	 *
	 * @param  integer $id The forum ID
	 * @return Response
	 */
	public function edit($id, Request $request)
	{
		$article = Article::findOrFail($id);

        //ha nem a szerző, vagy nem a portál admin, akkor főoldalra irányít
		if(!(Auth::user()->id == $article->user_id || Auth::user()->admin)) {
			return redirect('/');
		}

        $show_options = $this->show_options;

        $categories =  Auth::user()->categories()->pluck('title','id');

        $tags = GroupTag::get()->pluck('name', 'id');

        $selected_tags = null;
        if(isset($article->tags))
            $selected_tags = $article->tags->pluck('id')->toArray();

		return view('articles.edit', compact('article','show_options','tags','selected_tags','categories'));
	}

	/**
	 * Update a specific article/event
	 *
	 * @param  integer $id The forum ID
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$article = Article::findOrFail($id);

        $description = $request->get('body');

        $article->update([
            'title' => $request->get('title'),
            'meta_description' => $request->get('meta_description'),
            'body' => $description,
            'short_description' => justbr($description,700),
            'image' => getfirstimage($description),
            'slug' => Str::slug($request->get('title')),
            'show' => $request->get('show'),
            'status' => $request->has('inactive') ? 'inactive' : 'active',
            'category_id' => $request->get('category')
        ]);

        $tag_list=$this->getTagList($request->input('tag_list'), 'App\Models\ArticleTag');
        $article->tags()->sync($tag_list);

        if($request->get('show')=='portal_too') {
            return redirect('irasok')->with('message', 'Az írást sikeresen módosítottad! - '.$request->get('title'));
        }
        else {
            return redirect('profil/'.Auth::user()->id.'/'.Auth::user()->slug.'/irasok')->with('message', 'Az írást sikeresen módosítottad! - '.$request->get('title'));
        }
	}

    public function delete($id) {
        $article = Article::findOrFail($id);
        $user = $article->user;

        if(Auth::check() && Auth::user()->id==$user->id) {
            //töröl mindent a user notice-ban ezzel az írással kapcsolatban
            Usernotice::where('post_id',$article->id)->where('type','Article')->delete();

            //töröl minden ezzel az írással kapcsolatos hozzászólást
            Comment::where('commentable_id',$article->id)->where('commentable_type','App\Models\Article')->delete();

            //törli a képet is ha van
            if(!empty($article->image)) {
                $base_path=base_path().'/public/images/posts/';
                unlink($base_path.$article->image);
            }

            //törli az írással kapcsolatos címkézést
            DB::table('article_group_tag')->where('article_id',$id)->delete();

            //törli az írást
            $article->delete();

            return redirect('/profil/'.$user->id.'/'.$user->slug.'/irasok/')->with('message', 'Az írást sikeresen törölted.');
        }

        return redirect('/');
    }
}
