<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use Validator;

class CategoriesController extends Controller
{
    public function __construct()
    {
    }


    /**
     * Upload category image
     *
     * @return Response
     */
    public function uploadImage($id, $title)
    {
        $category = Category::findOrFail($id);
        $url = $category->type == 'article' ? 'irasok': 'alkotasok';

        return view('categories.upload_image', compact('id', 'title', 'url'));
    }


    /**
     * Save category image;
     *
     * @return Response
     */
    public function saveImage($id, $title, Request $request)
    {
        $rules = [
            'image' => 'required|mimes:jpeg,png,gif|max:3072'
        ];

        $messages = [
            'image.required' => 'Képfájl kiválasztása szükséges',
            'image.mimes' => 'A kép fájltípusa .jpg, .png, .gif kell legyen',
            'image.max' => 'A kép nem lehet nagyobb mint :max KB',
        ];

        //dd($request);
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('temakor/' . $id . '/' . $title . '/kepfeltoltes')
                ->withErrors($validator)
                ->withInput();
        }

        $imagename = $id;
        $base_path = base_path() . '/public/images/categories/';
        $tmpimagename = 'tmp_' . $imagename . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->move($base_path, $tmpimagename);

        $tmpfile = $base_path . $tmpimagename;
        generateImage($tmpfile, 400, 1, $base_path . $imagename . '.jpg');//1=>width
        unlink($tmpfile);

        $category = Category::findOrFail($id);
        $category->photo_counter++;
        $category->save();

        $url = $category->type == 'article' ? 'irasok': 'alkotasok';

        return redirect('/'.$url.'/' . $id . '/' . $title)->with('message', 'A témakör képet sikeresen feltöltötted!');


    }
}
