<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except'=>['index','aboutus','socialagreement','aboutsite','connection','datahandling']]);
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
     * Show what happened in the last weeks.
     *
     * @return \Illuminate\Http\Response
     */
    public function lastweeks()
    {
        $users = User::with('skill_tags')->members()->where('created_at','>', date("Y-m-d",strtotime("-1 month")))->latest('updated_at')->get();
        $groups = Group::with('user', 'members', 'tags')->where('created_at','>', date("Y-m-d",strtotime("-1 month")))->latest('updated_at')->get();

        return view('lastweeks',compact('users','groups'));
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

        /*$fileName=$request->file('file')->getClientOriginalName();
        $request->file('file')->storeAs('public/images', $fileName);
        return response()->json(['location'=>"/storage/images/$fileName"]);*/

        //$fileName=$request->file('file')->getClientOriginalName();
        // $imgpath = request()->file('file')->store('public/images');
        //return response()->json(['location' => "/storage/$imgpath"]);
        //request()->file('file')->store('public/images');
        //return response()->json(['location'=>"/storage/images/$fileName"]);


        $base_path=base_path().'/public/images/posts/';
        $fileName=$request->file('file')->getClientOriginalName();
        $request->file('file')->move($base_path,$fileName);
        return response()->json(['location'=>"/images/posts/$fileName"]);


        /* $mainImage = $request->file('file');

         $fileName=time().'.'.$mainImage->getClientOriginalName();
         Image::make($mainImage)->save(public_path('images/posts/'.$fileName));

         return json_encode(['location' => asset('images/posts/'.$fileName)]);*/

    }

}
