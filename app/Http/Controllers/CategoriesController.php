<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class CategoriesController extends Controller
{
	public function __construct() {
	}

	public function index(Request $request)
	{
		$categories = Auth::user()->categories()->get();

		return view('categories.index', compact('categories'));
	}
}
