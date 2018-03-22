<?php

namespace Dgame\Soap\Test\Object;

/**
 * Class TestBody
 * @package Dgame\Soap\Test\Object
 */
final class TestBody
{
    /**
     * @var TestFault
     */
    private $fault;
    /**
     * @var TestResult
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
     * @return TestFault
     */
    public function getFault(): TestFault
    {
        return $this->fault;
    }

    /**
     * @param TestFault $fault
     */
    public function setTestFault(TestFault $fault): void
    {
        $this->fault = $fault;
    }

    /**
     * @return TestResult
     */
    public function getResult(): TestResult
    {
        return $this->result;
    }

    /**
     * @param TestResult $result
     */
    public function setTestResult(TestResult $result): void
    {
        $this->result = $result;
    }
}