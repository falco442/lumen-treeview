<?php

namespace falco442\Tests;

use falco442\Treeview;
use Tests\TestCase;

class TreeviewTest extends TestCase
{

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->arr = [
            ['id' => 1, 'parent_id' => null],
            ['id' => 2, 'parent_id' => 1]
        ];
    }

    public function testGetTree()
    {
        $arr = $this->arr;
        $newArr = Treeview::getTree($arr);
        foreach ($newArr as $item) {
            $this->testNode($item);
        }
    }

    private function testNode($node)
    {
        $this->assertIsArray($node, 'Node is not an array');
        $this->assertArrayHasKey('id', $node, 'Node has not key \'id\'');
        $this->assertArrayHasKey('parent_id', $node, 'Node has not key \'parent_id\'');
        $this->assertArrayHasKey('children', $node, 'Node has not key \'parent_id\'');
        $this->assertIsArray($node['children'], '$newArr is not an array');
        foreach ($node['children'] as $newNode) {
            $this->testNode($newNode);
        }
    }

    public function testGetNode()
    {
        $arr = $this->arr;
        $newArr = Treeview::getNode($arr, 1);
        $this->testNode($newArr);
    }
}
