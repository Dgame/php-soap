<?php

namespace Dgame\Soap\Test\Object;

/**
 * Class TestResult
 * @package Dgame\Soap\Test\Object
 */
final class TestResult
{
    /**
     * @var TestOrt[]
     */
    private $orte = [];

    /**
     * @param TestOrt $ort
     */
    public function appendTestOrt(TestOrt $ort)
    {
        $this->orte[] = $ort;
    }

    /**
     * @return TestOrt[]
     */
    public function getOrte(): array
    {
        return $this->orte;
    }
}