<?php

namespace Dgame\Soap\Test\Object;

/**
 * Class Fault
 * @package Dgame\Soap\Test\Object
 */
final class Fault
{
    /**
     * @var string
     */
    private $faultcode;
    /**
     * @var string
     */
    private $faultstring;

    /**
     * @return string
     */
    public function getFaultcode(): string
    {
        return $this->faultcode;
    }

    /**
     * @param string $faultcode
     */
    public function setFaultcode(string $faultcode)
    {
        $this->faultcode = $faultcode;
    }

    /**
     * @return string
     */
    public function getFaultstring(): string
    {
        return $this->faultstring;
    }

    /**
     * @param string $faultstring
     */
    public function setFaultstring(string $faultstring)
    {
        $this->faultstring = $faultstring;
    }
}