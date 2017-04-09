<?php

namespace Dgame\Soap\Test\Object;

/**
 * Class OrtsTeil
 * @package Dgame\Soap\Test\Object
 */
final class OrtsTeil
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var Strassen
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
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return Strassen
     */
    public function getStrassen(): Strassen
    {
        return $this->strassen;
    }

    /**
     * @param Strassen $strassen
     */
    public function setStrassen(Strassen $strassen)
    {
        $this->strassen = $strassen;
    }
}