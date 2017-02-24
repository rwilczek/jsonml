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

class JsonTest extends TestCase
{
    /**
     * Converts a JSON encoded string to a PHP value.
     *
     * @param string $json UTF-8 encoded string
     * @param bool $assoc convert objects into associative arrays.
     * @param int $depth User specified recursion depth.
     * @throws Exception
     * @return mixed
     */
    private function decode(string $json, bool $assoc = false, int $depth = 512)
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
     * @param int $options Option-bitmask
     * @return string $json UTF-8 encoded string
     * @throws Exception
     */
    private function encode($value, int $options = 0) : string
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
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('*Unsupported type*');
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
