<?php

namespace Dgame\Soap\Components;

/**
 * Class Fault
 * @package Dgame\Soap\Components
 */
class Fault
{
    /**
     * @var string
     */
    private $message;
    /**
     * @var string
     */
    private $code;

    /**
     * Fault constructor.
     *
     * @param string|null $message
     * @param string|null $code
     */
    public function __construct(string $message = null, string $code = null)
    {
        $this->message = $message;
        $this->code    = $code;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }
}
