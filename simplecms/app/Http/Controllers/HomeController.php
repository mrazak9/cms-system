<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Page;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the homepage
     */
    public function index()
    {
        // Get latest posts
        $posts = Post::published()->latest()->take(6)->get();

        return view('home', compact('posts'));
    }
}
