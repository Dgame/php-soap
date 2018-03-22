<?php

namespace Dgame\Soap\Test\Object;

/**
 * Class TestOrt
 * @package Dgame\Soap\Test\Object
 */
final class TestOrt
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var TestOrtsTeil[]
     */
    private $ortsteile = [];

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
     * @return TestOrtsTeil[]
     */
    public function getOrtsteile(): array
    {
        return $this->ortsteile;
    }

    /**
     * @param TestOrtsTeil $ortsteil
     */
    public function appendTestOrtsTeil(TestOrtsTeil $ortsteil): void
    {
        $this->ortsteile[] = $ortsteil;
    }
}