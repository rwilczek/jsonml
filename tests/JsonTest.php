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

class JsonTest extends TestCase
{
    /**
     * Converts a JSON encoded string to a PHP value.
     *
     * @param string $json UTF-8 encoded string
     * @param boolean $assoc convert objects into associative arrays.
     * @param integer $depth User specified recursion depth.
     * @throws Exception
     * @return mixed
     */
    private function decode($json, $assoc = false, $depth = 512)
    {
        $result = json_decode($json, $assoc, $depth);
        $error  = json_last_error();
        if ($error != JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Cannot decode ' . $json);
        }
        return $result;
    }

    /**
     * Returns a string containing the JSON representation of value.
     *
     * @param mixed $value All string data must be UTF-8 encoded.
     * @param integer $options Option-bitmask
     * @throws Exception
     * @return string $json UTF-8 encoded string
     */
    private function encode($value, $options = 0)
    {
        if (is_resource($value)) {
            throw new \InvalidArgumentException('Unsupported type resource');
        }
        $result = json_encode($value, $options);
        $error  = json_last_error();
        if ($error != JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Cannot encode');
        }
        return $result;
    }

    public function testEncodeResource()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Unsupported type'
        );
        $this->encode(fopen(__FILE__, 'r'));
    }

    /**
     * @dataProvider associativeArrays
     */
    public function testEncodeAssociativeArray(array $value)
    {
        $result = $this->encode($value);
        $this->assertEquals((object) $value, $this->decode($result));
    }
}
