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

class ParserTest extends TestCase
{
    /**
     * @var Parser
     */
    private $parser;

    protected function setUp()
    {
        $this->parser = new ReferenceParser;
    }

    public function testImplementation()
    {
        $this->assertInstanceOf(Parser::class, $this->parser);
    }

    /**
     * @dataProvider values
     */
    public function testToNode($value)
    {
        $node = $this->parser->encodeXML($value);
        if (is_bool($value)) {
            $expectedType = $value? 'true': 'false';
        } elseif (is_integer($value) || is_float($value)) {
            $expectedType = 'number';
        } else {
            $expectedType = strtolower(gettype($value));
        }
        $this->assertIsTypedNode($node, $expectedType);
    }

    /**
     * @dataProvider values
     */
    public function testNodeToNode($value)
    {
        $input = $this->parser->encodeXML($value);
        $this->assertSame($input, $this->parser->encodeXML($input));
    }

    public function testResourceToNode()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessageRegExp('\'Cannot convert\'');
        $this->parser->encodeXML(fopen(__FILE__, 'r'));
    }

    /**
     * @dataProvider indexedArrays
     */
    public function testEncodeXMLIndexedArrays(array $value)
    {
        $node = $this->parser->encodeXML($value);
        $this->assertIsTypedNode($node, 'array');
        $this->assertSame($value, $this->parser->decodeXML($node));
    }

    /**
     * @dataProvider associativeArrays
     */
    public function testEncodeXMLAssociativeArrays(array $value)
    {
        $node = $this->parser->encodeXML($value);
        $this->assertIsTypedNode($node, 'object');
        $this->assertEquals((object) $value, $this->parser->decodeXML($node));
    }

    public function testDecodeXMLWithWrongNode()
    {
        $node = new \DOMElement('string', 'foo', 'http://someNS');

        $this->expectException(Exception::class);
        $this->expectExceptionMessageRegExp('*Cannot parse unknown namespace*');
        $this->parser->decodeXML($node);
    }

    public function testDecodeXMLInvalidNode()
    {
        $node = new \DOMElement('nonsense', 'foo', Parser::NS);
        $this->expectException(Exception::class);
        $this->expectExceptionMessageRegExp('*Cannot parse unknown element*');
        $this->parser->decodeXML($node);
    }

    public function testDecodeNumericInteger()
    {
        $node = new \DOMElement('number', '3', Parser::NS);

        $result = $this->parser->decodeXML($node);
        $this->assertSame(3, $result);
    }

    public function testDecodeNumericDouble()
    {
        $node = new \DOMElement('number', '.0', Parser::NS);

        $result = $this->parser->decodeXML($node);
        $this->assertSame(0.0, $result);
    }

    /**
     * @dataProvider arbitraryValues
     */
    public function testDecodeXML($value)
    {
        $node   = $this->parser->encodeXML($value);
        $result = $this->parser->decodeXML($node);

        if (is_object($value) || is_array($value) || is_integer($value) || is_float($value)) {
            $this->assertEquals($value, $result);
        } elseif (is_string($value)) {
            $this->assertSame($value, $result);
        } else {
            $this->assertSame($value, $result);
        }
    }

    /**
     * @dataProvider arbitraryValues
     */
    public function testValidateElements($value)
    {
        $this->parser->validate($this->parser->encodeXML($value)); // must pass without exception!
        $this->assertTrue(true);
    }

    public function testValidationFailure()
    {
        $dom = new \DOMDocument;
        $dom->loadXML('<string>foo</string>'); // invalid or missing namespace here
        $this->expectException(Exception::class);
        $this->expectExceptionMessageRegExp('*No matching global declaration*');

        $this->parser->validate($dom->documentElement);
    }
}
