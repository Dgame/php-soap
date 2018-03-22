<?php

namespace Dgame\Soap\Test\Object;

/**
 * Class TestEnvelope
 * @package Dgame\Soap\Test\Object
 */
final class TestEnvelope
{
    /**
     * @var TestBody
     */
    private $body;

    /**
     * @return TestBody
     */
    public function getBody(): TestBody
    {
        return $this->body;
    }

    /**
     * @param TestBody $body
     */
    public function setTestBody(TestBody $body): void
    {
        $this->body = $body;
    }
}