<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

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

        return \response()->json($this->getTree($posts));
    }

    private function getTree(array $array, $parentIdField = 'parent_id', $idField = 'id', $childrenField = 'children')
    {
        $roots = (new Collection($array))->filter(function ($root) use ($parentIdField) {
            return $root[$parentIdField] === null;
        });
        $tree = $roots->map(function ($root) use ($array, $idField, $childrenField, $parentIdField) {
            return $this->getNode($array, null, $parentIdField, $idField, $childrenField, $root);
        });
        return array_values($tree->toArray());
    }

    private function getNode(array &$array, $id = null, $parentIdField = 'parent_id', $idField = 'id', $childrenField = 'children', $node = null)
    {
        $collection = new Collection($array);
        $nodeKey = 0;
        if (!$node) {
            $node = $collection->first(function ($item, $key) use ($idField, $id, &$nodeKey) {
                $nodeKey = $key;
                return $item[$idField] === $id;
            });
            $collection->forget($nodeKey);
        }


        [$children, $array] = $collection->partition(function ($item) use ($parentIdField, $id) {
            return $item[$parentIdField] === $id;
        });


        $children = array_values($children->toArray());
        $array = array_values($array->toArray());

        $node[$childrenField] = (new Collection($children))->map(function ($n) use (&$array, $parentIdField, $idField, $childrenField, $node) {
            $n = $this->getNode($array, $n[$idField], $parentIdField, $idField, $childrenField, $n);
            return $n;
        })->toArray();
        return $node;
    }
}
