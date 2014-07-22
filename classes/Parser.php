<?php
/**
 * Converts json:XML to PHP-values and vice versa.
 *
 * @license    http://opensource.org/licenses/lgpl-2.1.php LGPL 2.1 or higher
 * @copyright  2012 Roland Wilczek
 * @version    SVN: $Id: Json.php 12896 2014-06-21 14:36:06Z rwilczek $
 * @link       http://www.web-appz.de/
 */

namespace webappz\jsonml;

/**
 * Converts json:XML to PHP-values and vice versa.
 */
interface Parser
{
    const NS = 'http://web-appz.de/jsonml';

    /**
     * Convert a PHP-value to a json:XML-element.
     *
     * If the value is an object, it's members will be read using foreach {}.
     * Supports \JsonSerializable.
     *
     * @param mixed $value
     * @return \DOMElement having the json:XML namespace
     */
    public function encodeXML($value);

    /**
     * Convert a json:XML-element to a valid JSON-string
     *
     * @param \DOMElement $node requires the json:XML namespace
     * @return mixed
     */
    public function decodeXML(\DOMElement $node);

    /**
     * Validate the given node against the json::XML Schema
     * and throw an exception on failure.
     *
     * Validates instances of DOMDocument as well as instances of DOMElement.
     * Validating solitaire instances of DOMAttr however will throw an exception.
     * Attributes may only be validated within the context of a DOMElement.
     *
     * @param \DOMNode $node
     * @throws Exception
     */
    public function validate(\DOMNode $node);
}
