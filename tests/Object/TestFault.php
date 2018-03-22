<?php

namespace Dgame\Soap\Test\Object;

/**
 * Class TestFault
 * @package Dgame\Soap\Test\Object
 */
final class TestFault
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
    public function setFaultcode(string $faultcode): void
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
    public function setFaultstring(string $faultstring): void
    {
        $this->faultstring = $faultstring;
    }
}