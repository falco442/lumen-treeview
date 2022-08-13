# Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel/lumen-framework)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://img.shields.io/packagist/v/laravel/lumen-framework)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://img.shields.io/packagist/l/laravel/lumen)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We
believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain
out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction,
queueing, and caching.

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
```

### Get single node tree

You can call the static method of the class TreeView to retrieve the tree relative to a node like this, inserting the id
as parameter
ID:

```php
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
```

## Official Documentation

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## Contributing

Thank you for considering contributing to Lumen! The contribution guide can be found in
the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Lumen, please send an e-mail to Taylor Otwell at taylor@laravel.com. All
security vulnerabilities will be promptly addressed.

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
