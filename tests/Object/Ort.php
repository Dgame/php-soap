<?php

namespace Dgame\Soap\Test\Object;

/**
 * Class Ort
 * @package Dgame\Soap\Test\Object
 */
final class Ort
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var OrtsTeil[]
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
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return OrtsTeil[]
     */
    public function getOrtsteile(): array
    {
        return $this->ortsteile;
    }

    /**
     * @param OrtsTeil $ortsteil
     */
    public function appendOrtsTeil(OrtsTeil $ortsteil)
    {
        $this->ortsteile[] = $ortsteil;
    }
}