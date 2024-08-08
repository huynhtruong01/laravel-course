<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $posts = [
        [
            'id' => 1,
            'title' => 'Post 2',
        ]
    ];

    public function index()
    {
        return response()->json($this->posts);
    }

    public function show()
    {
        return response()->json(['title' => 'Post detail']);
    }
}
