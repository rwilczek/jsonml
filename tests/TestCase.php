<?php
/**
 * Unittest for rephORM
 *
 * @license    http://opensource.org/licenses/lgpl-2.1.php LGPL 2.1 or higher
 * @copyright  2012 Roland Wilczek
 * @version    SVN: $Id: JsonTest.php 12458 2014-02-06 16:49:28Z rwilczek $
 * @link       http://www.web-appz.de/
 */

namespace webappz\jsonml;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function assertIsTypedNode(\DOMNode $node, $type)
    {
        $this->assertSame(Parser::NS, $node->namespaceURI);
        $this->assertSame($type, $node->tagName);
    }

    public static function associativeArrays()
    {
        return array(
            array( array(0 => 1, 1 => 2, 'foo' => 3)), // string key
            array( array(1 => 1, 2 => 2, 3 => 3)),     // not starting with 0
            array( array(0 => 1, 2 => 2, 3 => 3)),     // gap in keys
            array( array(1 => 1, 0 => 2, 2 => 3)),     // order does  matter
        );
    }

    public static function indexedArrays()
    {
        return array(
            array( array() ),
            array( array(1, 2, 3) ),
            array( array(0 => 1, 1 => 2, 2 => 3) ),
        );
    }

    public static function values()
    {
        return array(
            array(''),
            array('foo'),
            array(-1),
            array(0),
            array(1),
            array(-1.0),
            array(0.0),
            array(1.0),
            array(true),
            array(false),
            array(null),
            array(array()),
            array(new \stdClass)
        );
    }

    public static function arbitraryValues()
    {
        return array(
            array('foo'),
            array('["foo","bar"]'),
            array('{"foo":"bar", "boo":"baz"}'),
            array('1'),
            array('1.0'),
            array('true'),
            array('false'),
            array('null'),
            array(''),
            array(' '),

            array(null),
            array(1),
            array(0),
            array(-1),
            array(true),
            array(false),

            array(0.1),
            array(0.0),
            array(-0.1),

            array(array()),
            array(array(1, 2, 3)),
            array(array(1, 2.0, true, null, new \stdClass)),
            array(array(1, array(1, 2, 3), 3)),

            array(json_decode('{"foo" : "bar"}')),
            array(json_decode('{"a":1,"b":2,"c":3,"d":4,"e":5}')),
            array(json_decode('{"a":1,"b":[1, 2, 3],"c":3,"d":4,"e":5}')),

        );
    }
}
