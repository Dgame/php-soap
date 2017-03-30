<?php

namespace Dgame\Soap\Test\Object;

use Dgame\Soap\Hydrator\Hydratable;

/**
 * Class Result
 * @package Dgame\Soap\Test\Object
 */
final class Result extends Hydratable
{
    /**
     * @var Ort[]
     */
    private $orte = [];

    /**
     * @param Ort $ort
     */
    public function appendOrt(Ort $ort)
    {
        $this->orte[] = $ort;
    }

    /**
     * @return Ort[]
     */
    public function getOrte(): array
    {
        return $this->orte;
    }
}