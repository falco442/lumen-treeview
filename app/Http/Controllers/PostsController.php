<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class PostsController extends Controller
{
    public function populate(Request $request, Response $response) {
        $ids = [];
        for ($i = 0; $i < 300; $i++) {
            $posts = Post::all();
            $post = new Post;
            $post->text = sha1(uniqid());
            if ($posts) {
                $ids = $posts->map(function($post) {
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

    public function index(Request $request, Response $response) {
        return \response()->json(Post::all()->map(function ($item) {
            if ($item->parent_id === 0) {
                $item->parent_id = null;
            }
            return $item;
        })->toArray(), 200);
    }

    public function tree() {
        $posts = Post::all()->transform(function ($item) {
            if ($item->parent_id === 0) {
                $item->parent_id = null;
            }
            return $item;
        })->toArray();
        $node = $this->getNode($posts, 201);
        $this->show($node);
        return \response()->json($this->getNode($posts, 201));
    }

    private function getNode(array &$array, $id, $parentIdField = 'parent_id', $idField = 'id', $childrenField = 'children') {
        $collection = new Collection($array);
        $node = $collection->first(function($item) use ($idField, $id) {
            return $item[$idField] = $id;
        });
        [$children, $array] = $collection->partition(function($item) use ($parentIdField, $id) {
            return $item[$parentIdField] === $id;
        });
        $children = array_values($children->toArray());
        $array = $array->toArray();
        $node[$childrenField] = $children;
        (new Collection($children))->each(function($node) use ($array, $parentIdField, $idField, $childrenField) {
            $this->getNode($array, $node[$idField], $parentIdField, $idField, $childrenField);
        });
        return $node;
    }

    public function show($data) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die();
    }
}
