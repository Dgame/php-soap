<?php

namespace Dgame\Soap\Test\Object;

/**
 * Class Strassen
 * @package Dgame\Soap\Test\Object
 */
final class Strassen
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