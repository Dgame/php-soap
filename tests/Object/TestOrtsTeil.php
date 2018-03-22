<?php

namespace Dgame\Soap\Test\Object;

/**
 * Class TestOrtsTeil
 * @package Dgame\Soap\Test\Object
 */
final class TestOrtsTeil
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var TestStrassen
     */
    private $strassen;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return TestStrassen
     */
    public function getStrassen(): TestStrassen
    {
        return $this->strassen;
    }

    /**
     * @param TestStrassen $strassen
     */
    public function setTestStrassen(TestStrassen $strassen): void
    {
        $this->strassen = $strassen;
    }
}