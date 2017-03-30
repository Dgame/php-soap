<?php

namespace Dgame\Soap\Test\Object;

use Dgame\Soap\Hydrator\Hydratable;

/**
 * Class Car
 * @package Dgame\Soap\Test\Object
 */
final class Car extends Hydratable
{
    /**
     * @var string
     */
    private $marke;

    /**
     * @param string $marke
     */
    public function setMarke(string $marke)
    {
        $this->marke = $marke;
    }

    /**
     * @return string
     */
    public function getMarke(): string
    {
        return $this->marke;
    }
}