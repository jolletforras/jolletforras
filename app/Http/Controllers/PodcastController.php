<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Podcast;

class PodcastController extends Controller
{

    public function index()
    {
        $podcasts = Podcast::latest()->get();

        return view('podcasts.index', compact('podcasts'));
    }
}
