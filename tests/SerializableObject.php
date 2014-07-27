<?php
namespace webappz\jsonxml;

class SerializableObject implements \JsonSerializable
{
    /**
     * @var mixed
     */
    private $serializable;

    public function __construct($serializable)
    {
        $this->serializable = $serializable;
    }

    public function jsonSerialize()
    {
        return $this->serializable;
    }
}
