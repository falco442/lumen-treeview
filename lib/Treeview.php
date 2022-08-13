<?php

namespace falco442;

use Illuminate\Support\Collection;

class Treeview
{
    /**
     * Create a new tree from an array of arrays; Returns the array of roots, with each tree attached
     *
     * @return array
     */
    public static function getTree(array $array, string $parentIdField = 'parent_id', string $idField = 'id', string $childrenField = 'children')
    {
        $roots = (new Collection($array))->filter(function ($root) use ($parentIdField) {
            return $root[$parentIdField] === null;
        });
        $tree = $roots->map(function ($root) use ($array, $idField, $childrenField, $parentIdField) {
            return self::getNode($array, null, $parentIdField, $idField, $childrenField, $root);
        });
        return array_values($tree->toArray());
    }

    /**
     * Create a tree relative to a node, attaching the tree to the node
     *
     * @return array
     */
    public static function getNode(array &$array, $id = null, string $parentIdField = 'parent_id', string $idField = 'id', string $childrenField = 'children', $node = null)
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
