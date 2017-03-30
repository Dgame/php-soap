<?php

namespace Dgame\Soap\Test\Object;

use Dgame\Soap\Hydrator\Hydratable;

/**
 * Class Ort
 * @package Dgame\Soap\Test\Object
 */
final class Ort extends Hydratable
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