<?php

namespace falco442;

use Illuminate\Support\Collection;

class Treeview
{
    public static function getTree(array $array, $parentIdField = 'parent_id', $idField = 'id', $childrenField = 'children')
    {
        $roots = (new Collection($array))->filter(function ($root) use ($parentIdField) {
            return $root[$parentIdField] === null;
        });
        $tree = $roots->map(function ($root) use ($array, $idField, $childrenField, $parentIdField) {
            return self::getNode($array, null, $parentIdField, $idField, $childrenField, $root);
        });
        return array_values($tree->toArray());
    }

    public static function getNode(array &$array, $id = null, $parentIdField = 'parent_id', $idField = 'id', $childrenField = 'children', $node = null)
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
            $n = self::getNode($array, $n[$idField], $parentIdField, $idField, $childrenField, $n);
            return $n;
        })->toArray();
        return $node;
    }
}
