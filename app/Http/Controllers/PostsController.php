<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostsController extends Controller
{
    public function populate(Request $request, Response $response) {
        for ($i = 0; $i < 300; $i++) {
            $posts = Post::all();
        }
        return \response()->json($posts);
    }
}
