<?php

namespace Dgame\Soap\Test\Object;

/**
 * Class Envelope
 * @package Dgame\Soap\Test\Object
 */
final class Envelope
{
    /**
     * @var Body
     */
    private $body;

    /**
     * @return Body
     */
    public function getBody(): Body
    {
        return $this->body;
    }

    /**
     * @param Body $body
     */
    public function setBody(Body $body)
    {
        $this->body = $body;
    }
}