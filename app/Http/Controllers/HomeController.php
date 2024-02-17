<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\Forum;
use App\Models\Event;
use App\Models\Podcast;
use App\Models\Article;
use App\Models\Newsletter;
use App\Models\Groupnews;
use App\Models\Projectnews;
use App\Models\Commendation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except'=>['index','aboutus','socialagreement','aboutsite','connection','datahandling','lastweeks','spirituality']]);
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * Show the description about us.
     *
     * @return \Illuminate\Http\Response
     */
    public function aboutus()
    {
        return view('about_us');
    }

    /**
     * Show the description about social agreement.
     *
     * @return \Illuminate\Http\Response
     */
    public function socialagreement()
    {
        return view('social_agreement');
    }

    /**
     * Show the description about social agreement.
     *
     * @return \Illuminate\Http\Response
     */
    public function aboutsite()
    {
        return view('about_site');
    }

    /**
     * Show the ways of connection.
     *
     * @return \Illuminate\Http\Response
     */
    public function connection()
    {
        return view('connection');
    }

    /**
     * Show the ways of connection.
     *
     * @return \Illuminate\Http\Response
     */
    public function datahandling()
    {
        return view('data_handling');
    }

    /**
     * Show the spirituality of the page.
     *
     * @return \Illuminate\Http\Response
     */
    public function spirituality()
    {
        return view('spirituality');
    }


    /**
     * Show what happened in the last weeks.
     *
     * @return \Illuminate\Http\Response
     */
    public function lastweeks()
    {
        $date = date("Y-m-d",strtotime("-1 month"));

        if(Auth::check())
        {
            $users = User::with('skill_tags')->members()->where('created_at','>',$date)->latest('updated_at')->get();
            $groups = Group::with('user', 'members', 'tags')->where('created_at','>',$date)->latest('updated_at')->get();
            $events = Event::where('created_at','>',$date)->where('visibility','<>', 'group')->latest()->get();
            $commendations = Commendation::where('approved', 1)->where('active', 1)->where('created_at','>',$date)->latest()->get();
            $groupnewss = Groupnews::where('created_at','>',$date)->where('visibility','<>', 'group')->latest()->get();
            $projectnewss = Projectnews::where('created_at','>',$date)->where('visibility','<>', 'group')->latest()->get();
        }
        else {
            $users = User::with('skill_tags')->members()->where('created_at','>',$date)->where('public',1)->latest('updated_at')->get();
            $groups = Group::with('user', 'members', 'tags')->where('created_at','>',$date)->where('public',1)->latest('updated_at')->get();
            $events = Event::where('created_at','>',$date)->where('visibility','=', 'public')->latest()->get();
            $commendations = Commendation::where('approved', 1)->where('active', 1)->where('created_at','>',$date)->where('public',1)->latest()->get();
            $groupnewss = Groupnews::where('created_at','>',$date)->where('visibility','=', 'public')->latest()->get();
            $projectnewss = Projectnews::where('created_at','>',$date)->where('visibility','=', 'public')->latest()->get();
        }

        $podcasts = Podcast::latest()->where('created_at','>',$date)->get();
        $articles = Article::latest()->where('created_at','>',$date)->get();
        $newsletters = Newsletter::latest()->where('created_at','>',$date)->get();

        return view('lastweeks',compact('users','groups','events','podcasts','articles','newsletters','commendations','groupnewss','projectnewss'));
    }


    /**
     * Show the map.
     *
     * @return \Illuminate\Http\Response
     */
    public function map()
    {
        $users = User::members()->whereNotNull('lat')->whereNotNull('lng')->get();
        return view('map', compact('users'));
    }

    /**
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function events()
    {
        $szoveg = '
    			Itt csapatépítő programok listája szerepelne (idő, hely, leírás), illetve fel lehetne venni újakat is.<br> 
    			Adott programnál lehetne látni, hogy kik jelentkeztek eddig. Magam is jelentkezhetek rá, 
    			jelezhetem, hogy "Biztosan megyek" vagy "Még nem tudom biztosan"';
        return view('general', compact('szoveg'));
    }

    /**
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function ideas()
    {
        $szoveg = '
    			Itt lehetne látni a vállalkozási ötletek listáját. Az ötletek leírását, címkéit, illetve milyen kompetenciájú 
    			embereket keresnek hozzá (címkével is jelölve). Címke szerint lehet keresni.
    			Amelyik tetszik ezek közül az ötletek közül, arra lehetne jelentkezni. 
    			Az ötletgazda a jelentkezésemkor levelet kapna a profilommal és hogy milyen területre jelentkeztem.
    			<br><br>
    			Itt lehetne felvenni közösségi vállalkozás ötleteket, jelölve címkével, hogy milyen jellegű vállalkozás, 
    			így címke lapján is lehetne keresni vállalkozási ötletekre. Meg lehet adni, hogy milyen kompetenciájú 
    			embereket keresek hozzá (címkével is jelölve).	
    			';
        return view('general', compact('szoveg'));
    }


    /**
     * Upload image
     *
     *
     * @return Response
     */
    public function uploadimage(Request $request)
    {
         $base_path=base_path().'/public/images/posts/';
         $fileName=pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME);
         $imagename=Auth::user()->id.'-'.Str::slug($fileName).'-'.rand(1000,9999);

         $tmpimagename = 'tmp_'.$imagename.'.'.$request->file('file')->getClientOriginalExtension();
         $request->file('file')->move($base_path,$tmpimagename);

         $tmpfile=$base_path.$tmpimagename;
         generateImage($tmpfile, 740, 1, $base_path.$imagename.'.jpg');//1=>width
         unlink($tmpfile);
         return response()->json(['location'=>"/images/posts/$imagename.jpg"]);
    }

}
