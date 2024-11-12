<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use Illuminate\Support\Str;

class CreationCategoriesController extends Controller
{
    public function __construct()
    {
    }


    /**
     * Displays a specific category
     *
     * @param  integer $id The category ID
     * @return Response
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        if(Auth::check()) {
            $creations = $category->creations()->latest()->get();
        }
        else {
            $creations = $category->creations()->where('public',1)->latest()->get();
        }


        return view('creationcategories.show', compact('category', 'creations'));
    }

    /**
     * Create a category
     *
     * @return Response
     */
    public function create()
    {
        return view('creationcategories.create');
    }

    /**
     * Store a category
     *
     * @return Response
     */
    public function store(Request $request)
    {

        $category = Auth::user()->categories()->create([
            'title' => $request->get('title'),
            'meta_description' => $request->get('meta_description'),
            'body' => $request->get('body'),
            'type' => 'creation',
            'slug' => Str::slug($request->get('title')),
        ]);

        $url = 'alkotasok/' . $category->id . '/' . $category->slug;

        return redirect($url)->with('message', 'Az új témakört sikeresen felvetted!');
    }

    /**
     * Edit a category
     *
     * @param  integer $id The category ID
     * @return Response
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        if (Auth::user()->id != $category->user->id) {
            return redirect('/');
        }


        return view('creationcategories.edit', compact('category'));
    }

    /**
     * Update a category
     *
     * @param  integer $id The category ID
     * @return Response
     */
    public function update($id, Request $request)
    {
        $category = Category::findOrFail($id);

        $category->update([
            'title' => $request->get('title'),
            'meta_description' => $request->get('meta_description'),
            'body' => $request->get('body'),
            'slug' => Str::slug($request->get('title')),
        ]);

        $url = 'alkotasok/' . $category->id . '/' . $category->slug;

        return redirect($url)->with('message', 'A témakört sikeresen módosítottad!');
    }
}
