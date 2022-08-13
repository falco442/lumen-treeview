<?php

namespace App\Http\Controllers;

use App\Models\Post;
use falco442\Treeview;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostsController extends Controller
{
    public function populate(Request $request, Response $response)
    {
        $ids = [];
        for ($i = 0; $i < 300; $i++) {
            $posts = Post::all();
            $post = new Post;
            $post->text = sha1(uniqid());
            if ($posts) {
                $ids = $posts->map(function ($post) {
                    return $post->id;
                });

                if ($ids && count($ids)) {
                    $post->parent_id = array_rand($ids->toArray());
                }
            }
//            die(print_r($post));
            $post->save();
        }
        return \response()->json($ids);
    }

    public function index(Request $request, Response $response)
    {
        return \response()->json(Post::all()->map(function ($item) {
            if ($item->parent_id === 0) {
                $item->parent_id = null;
            }
            return $item;
        })->toArray(), 200);
    }

    public function tree()
    {
        $posts = Post::all()->transform(function ($item) {
            if ($item->parent_id === 0) {
                $item->parent_id = null;
            }
            return $item;
        })->toArray();

        return \response()->json(Treeview::getTree($posts));
    }
}
