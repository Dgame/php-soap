<?php

namespace Dgame\Soap\Test\Object;

/**
 * Class Result
 * @package Dgame\Soap\Test\Object
 */
final class Result
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