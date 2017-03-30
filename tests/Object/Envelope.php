<?php

namespace Dgame\Soap\Test\Object;

use Dgame\Soap\Hydrator\Hydratable;

/**
 * Class Envelope
 * @package Dgame\Soap\Test\Object
 */
final class Envelope extends Hydratable
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