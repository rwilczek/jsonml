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

class JsonSerializableTest extends TestCase
{
    /**
     * @var Parser
     */
    private $parser;

    protected function setUp()
    {
        $this->parser = new ReferenceParser;
    }

    public function testEncodeJsonSerializable()
    {
        $object = new SerializableObject('foo');

        $result = $this->parser->encodeXML($object);
        $this->assertSame('foo', $this->parser->decodeXML($result));
    }

    public function testEncodeXMLRecursiveJsonSerializable()
    {
        $inner  = new SerializableObject('foo');
        $outer  = new SerializableObject($inner);

        $result = $this->parser->encodeXML($outer);
        $this->assertIsTypedNode($result, 'string');
        $this->assertSame('foo', $result->nodeValue);
    }

    public function testEncodeXMLRecursiveJsonSerializableInArray()
    {
        $inner  = new SerializableObject('foo');
        $outer  = new SerializableObject($inner);

        $array = [$outer];
        $result = $this->parser->encodeXML($array);
        $this->assertIsTypedNode($result, 'array');
        $this->assertSame(['foo'], $this->parser->decodeXML($result));
    }

    public function testEncodeXMLRecursiveJsonSerializableInObject()
    {
        $inner = new SerializableObject('foo');
        $outer = new SerializableObject($inner);

        $object = (object) ['foo' => $outer];
        $result = $this->parser->encodeXML($object);


        $this->assertIsTypedNode($result, 'object');

        $this->assertEquals(
            (object) ['foo' => 'foo'],
            $this->parser->decodeXML($result)
        );
    }
}
