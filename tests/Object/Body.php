<?php

namespace Dgame\Soap\Test\Object;

use Dgame\Soap\Hydrator\Hydratable;

/**
 * Class Body
 * @package Dgame\Soap\Test\Object
 */
final class Body extends Hydratable
{
    /**
     * @var Fault
     */
    private $fault;
    /**
     * @var Result
     */
    private $result;

    /**
     * @return bool
     */
    public function hasFault(): bool
    {
        return $this->fault !== null;
    }

    /**
     * @return Fault
     */
    public function getFault(): Fault
    {
        return $this->fault;
    }

    /**
     * @param Fault $fault
     */
    public function setFault(Fault $fault)
    {
        $this->fault = $fault;
    }

    /**
     * @return Result
     */
    public function getResult(): Result
    {
        return $this->result;
    }

    /**
     * @param Result $result
     */
    public function setResult(Result $result)
    {
        $this->result = $result;
    }
}