<?php

namespace Dgame\Soap\Test\Object;

/**
 * Class TestStrassen
 * @package Dgame\Soap\Test\Object
 */
final class TestStrassen
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