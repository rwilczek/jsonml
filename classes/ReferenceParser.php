<?php
/**
 * Concrete implementation of webappz\jsonml\Json
 *
 * @license    http://opensource.org/licenses/lgpl-2.1.php LGPL 2.1 or higher
 * @copyright  2012 Roland Wilczek
 * @version    SVN: $Id: Json.php 12890 2014-06-21 13:37:28Z rwilczek $
 * @link       http://www.web-appz.de/
 */

namespace webappz\jsonml;

/**
 * Concrete implementation of webappz\jsonml\Json
 */
class ReferenceParser implements Parser
{
    /**
     * @var \DOMDocument
     */
    private $dom;

    /**
     * Concrete implementation of webappz\jsonml\Json
     */
    public function __construct()
    {
        $this->dom = new \DOMDocument;
    }

    /**
     * Convert a PHP-value to a json:XML-element.
     *
     * If the value is an object, it's members will be read using foreach {}.
     * Supports \JsonSerializable.
     *
     * @param mixed $value
     * @return \DOMElement having the json:XML namespace
     * @throws Exception
     */
    public function encodeXML($value)
    {
        if ($this->isJsonNode($value)) {
            return $value;
        }

        $type = gettype($value);
        if (in_array($type, array('object', 'array', 'boolean', 'NULL'))) {
            return $this->{'encode' . $type}($value);
        };
        if ($type == 'resource') {
            throw new Exception('Cannot convert a resource to json:XML');
        }
        return $this->encodeScalar($value);
    }

    /**
     * Convert a json:XML-element to a PHP value
     *
     * @param \DOMElement $node requires the json:XML namespace
     * @return mixed
     * @throws Exception
     */
    public function decodeXML(\DOMElement $node)
    {
        if (!$this->isJsonNode($node)) {
            throw new Exception('Cannot parse unknown namespace ' . $node->namespaceURI);
        }

        switch ($node->localName) {
            case 'number':
                if (false !== strpos($node->nodeValue, '.')) {
                    return (double) $node->nodeValue;
                }
                return (int) $node->nodeValue;
            case 'true':
                return true;
            case 'false':
                return false;
            case 'string':
                return $node->nodeValue;
            case 'null':
                return null;
            case 'array':
                return $this->decodeArray($node);
                break;
            case 'object':
                return $this->decodeObject($node);
            default:
                throw new Exception('Cannot parse unknown element ' . $node->tagName);
        }
    }

    private function decodeArray(\DOMElement $array)
    {
        $result = array();
        foreach ($array->childNodes as $childNode) {
           $result[] = $this->decodeXML($childNode);
        }
        return $result;
    }

    private function decodeObject(\DOMElement $object)
    {
        $result = array();
        foreach ($object->childNodes as $member) {
            /* @var $member \DOMElement */
            $result[$member->getAttribute('name')] = $this->decodeXML($member->firstChild);
        }
        return (object) $result;
    }

    /**
     * Creates and returns an <array>-element from an array
     *
     * @param mixed[] $values
     * @return \DOMNode
     */
    private function encodeArray(array $values)
    {
        if ($this->isAssociative($values)) {
            return $this->encodeObject($values);
        }
        $element = $this->createElement('array');
        /* @var $element \DOMElement */
        foreach ($values as $key => $value) {
            $node = $this->encodeXML($value);
            $element->appendChild($node);
        }
        return $element;
    }

    /**
     * Tells if an array is considered to be associative.
     *
     * An array is considered to be associative, if
     * - it has at least one non-integer-key, or
     * - the integer-keys do not begin with 0, or
     * - the integer-keys do not increase strictly by 1
     *
     * @param mixed[] $values
     * @return boolean
     */
    private function isAssociative(array $values)
    {
        if (empty($values)) {
            return false;
        }
        return array_keys($values) !== range(0, count($values) - 1);
    }

    /**
     * Creates and returns a <boolean>-Element
     *
     * @param boolean $value
     * @return \DOMNode
     */
    private function encodeBoolean($value)
    {
        return $this->createElement($value? 'true': 'false');
    }

    /**
     * Creates and returns an <object>-element from an object
     *
     * @param object $object
     * @return \DOMNode
     */
    private function encodeObject($object)
    {
        if ($object instanceof \JsonSerializable) {
            return $this->encodeXML($object->jsonSerialize());
        }

        $element = $this->createElement('object');
        foreach ($object as $name => $value) {
            $member = $this->createElement('member');
            $member->setAttribute('name', $name);
            $member->appendChild($this->encodeXML($value));
            $element->appendChild($member);
        }
        return $element;
    }

    /**
     * Creates and returns a DOMNode according to the value's type.
     *
     * @param scalar $value
     * @return \DOMNode
     */
    private function encodeScalar($value)
    {
        if (is_integer($value) || is_float($value)) {
            return $this->createElement('number', $value);
        }
        return $this->createElement(gettype($value), $value);
    }

    /**
     * Creates and returns a <null>-Element
     *
     * @return \DOMNode
     */
    private function encodeNull()
    {
        return $this->createElement('null');
    }

    /**
     * Create a new json:XML element
     *
     * @param string $name
     * @param string $value
     * @return \DOMElement
     */
    private function createElement($name, $value = '')
    {
        return $this->dom->createElementNS(self::NS, $name, $value);
    }

    /**
     * Tells if the given value is a json:XML-element
     *
     * @param mixed $value
     * @return boolean
     */
    private function isJsonNode($value)
    {
        if (!$value instanceof \DOMNode) {
            return false;
        }
        return $value->namespaceURI == self::NS;
    }
}
