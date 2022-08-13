<?php

namespace falco442\Tests;

use falco442\Treeview;
use Tests\TestCase;

class TreeviewTest extends TestCase
{
    public function testGetTree()
    {
        $arr = [
            ['id' => 1, 'parent_id' => null],
            ['id' => 2, 'parent_id' => 1]
        ];
        $this->assertIsArray($arr, 'Value is not an array');
        foreach ($arr as $key => $item) {
            $this->assertIsArray($item, 'Item is not an array');
            $this->assertArrayHasKey('id', $item, 'Array has not key \'id\'');
            $this->assertArrayHasKey('parent_id', $item, 'Array has not key \'parent_id\'');
        }

        $newArr = Treeview::getTree($arr);
        $this->assertIsArray($arr, '$newArr is not an array');

        foreach ($newArr as $key => $item) {
            $this->assertIsArray($item, 'Item is not an array');
            $this->assertArrayHasKey('id', $item, 'Array has not key \'id\'');
            $this->assertArrayHasKey('children', $item, 'Array has not key \'parent_id\'');
            $this->assertIsArray($item['children'], 'Children are not array');
        }
    }
}
