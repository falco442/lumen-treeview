# falco442/lumen-treeview

[![Total Downloads](https://img.shields.io/packagist/dt/falco442/lumen-treeview)](https://packagist.org/packages/falco442/lumen-treeview)
[![Latest Stable Version](https://img.shields.io/packagist/v/falco442/lumen-treeview)](https://packagist.org/packages/falco442/lumen-treeview)
[![License](https://img.shields.io/packagist/l/falco442/lumen-treeview)](https://packagist.org/packages/falco442/lumen-treeview)

This package is intended for generating a tree from flat data (array); it makes use of the class `Illuminate\Support\Collection`, so is intended for Lumen/Laravel projects.

# Requirements

- PHP >= 8.0
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- laravel/lumen-framework >= 9.0

# Parameters

### Static method `getTree()`

| name             | type   | default value   | description                                                                                              |
|------------------|--------|-----------------|----------------------------------------------------------------------------------------------------------|
| `$array`         | array  | none. required. | this is the array to pass.                                                                               |
| `$parentIdField` | string | `'parent_id'`   | the name for the parent field with which is constructed the relation                                     |
| `$idField`       | string | `'id'`          | the name for the main field of the array, usually the primary key of the table from which data are taken |
| `$childrenField` | string | `'children'`    | the name for the field which to put the children in, in the returned tree                                |

### Static method `getNode()`

| name             | type               | default value   | description                                                                                                             |
|------------------|--------------------|-----------------|-------------------------------------------------------------------------------------------------------------------------|
| `$array`         | array              | none. required. | this is the array to pass. **Is taken by reference**                                                                    |
| `'$id'`          | string \|\| number | none. required. | The id of the node to take for which construct the tree                                                                 |
| `$parentIdField` | string             | `'parent_id'`   | the name for the parent field with which is constructed the relation                                                    |
| `$idField`       | string             | `'id'`          | the name for the main field of the array, usually the primary key of the table from which data are taken                |
| `$childrenField` | string             | `'children'`    | the name for the field which to put the children in, in the returned tree                                               |
| `'$node'`        | array              | null            | The node instance to pass to the method. **optional**, and not to be used to construct the tree. **Internal use only.** |

## Usage

### Get all trees (root nodes as array of nodes)

You can call the function `getTree()` to an array of arrays (for example a collection got from Eloquent) like this:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use falco442\Treeview;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostsController extends Controller
{
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
```

### Get single node tree

You can call the static method of the class TreeView to retrieve the tree relative to a node like this, inserting the id
as parameter
ID:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use falco442\Treeview;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostsController extends Controller
{
    public function tree()
    {
        $posts = Post::all()->transform(function ($item) {
            if ($item->parent_id === 0) {
                $item->parent_id = null;
            }
            return $item;
        })->toArray();

        return \response()->json(Treeview::getTree($posts, 101));
    }
}
```
