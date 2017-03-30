<?php

namespace Dgame\Soap\Test\Object;

use Dgame\Soap\Hydrator\Hydratable;

/**
 * Class Strassen
 * @package Dgame\Soap\Test\Object
 */
final class Strassen extends Hydratable
{
    /**
     * @var array
     */
    private $strassen = [];

    /**
     * @param string $name
     */
    public function appendName(string $name)
    {
        $this->strassen[] = $name;
    }

    /**
     * @return array
     */
    public function getStrassen(): array
    {
        return $this->strassen;
    }
}