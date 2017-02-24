<?php
/**
 * Unittest for rephORM
 *
 * @license    http://opensource.org/licenses/lgpl-2.1.php LGPL 2.1 or higher
 * @copyright  2012 Roland Wilczek
 * @version    SVN: $Id: JsonTest.php 12458 2014-02-06 16:49:28Z rwilczek $
 * @link       http://www.web-appz.de/
 */

namespace webappz\jsonxml;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function assertIsTypedNode(\DOMNode $node, string $type)
    {
        $this->assertSame(Parser::NS, $node->namespaceURI);
        $this->assertSame($type, $node->tagName);
    }

    public static function associativeArrays()
    {
        return [
            [ [0 => 1, 1 => 2, 'foo' => 3]], // string key
            [ [1 => 1, 2 => 2, 3 => 3]],     // not starting with 0
            [ [0 => 1, 2 => 2, 3 => 3]],     // gap in keys
            [ [1 => 1, 0 => 2, 2 => 3]],     // order does  matter
        ];
    }

    public static function indexedArrays()
    {
        return [
            [ [] ],
            [ [1, 2, 3] ],
            [ [0 => 1, 1 => 2, 2 => 3] ],
        ];
    }

    public static function values()
    {
        return [
            [''],
            ['foo'],
            [-1],
            [0],
            [1],
            [-1.0],
            [0.0],
            [1.0],
            [true],
            [false],
            [null],
            [[]],
            [new \stdClass]
        ];
    }

    public static function arbitraryValues()
    {
        return [
            ['foo'],
            ['["foo","bar"]'],
            ['{"foo":"bar", "boo":"baz"}'],
            ['1'],
            ['1.0'],
            ['true'],
            ['false'],
            ['null'],
            [''],
            [' '],

            [null],
            [1],
            [0],
            [-1],
            [true],
            [false],

            [0.1],
            [0.0],
            [-0.1],

            [[]],
            [[1, 2, 3]],
            [[1, 2.0, true, null, new \stdClass]],
            [[1, [1, 2, 3], 3]],

            [json_decode('{"foo" : "bar"}')],
            [json_decode('{"a":1,"b":2,"c":3,"d":4,"e":5}')],
            [json_decode('{"a":1,"b":[1, 2, 3],"c":3,"d":4,"e":5}')],

        ];
    }
}
