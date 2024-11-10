<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Category;

class CategoriesController extends Controller
{
	public function __construct() {
	}

    public function show_for_article($id) {
        return $this->show($id,'article');
    }

    public function show_by_url($url)
    {
        $category = Category::where('url',$url)->first();
        $type ='article';
        $list = $category->articles()->latest()->get();

        return view('categories.show', compact('category','type','list'));
    }

    /**
     * Displays a specific category
     *
     * @param  integer $id The category ID
     * @return Response
     */
    private function show($id,$type)
    {
        $category = Category::findOrFail($id);
        if($type=='article')
            $list = $category->articles()->latest()->get();
        else {

        }

        return view('categories.show', compact('category','type','list'));
    }

}
