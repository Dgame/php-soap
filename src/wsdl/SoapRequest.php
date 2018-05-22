<?php

namespace Dgame\Soap\Wsdl;

use Dgame\Soap\Components\Body;
use Dgame\Soap\Components\Envelope;
use Dgame\Soap\Components\Header;

/**
 * Class SoapRequest
 * @package Dgame\Soap\Wsdl
 */
final class SoapRequest
{
    /**
     * @var string
     */
    private $operation;
    /**
     * @var string
     */
    private $action;
    /**
     * @var Body
     */
    private $body;
    /**
     * @var Header
     */
    private $header;

    /**
     * SoapRequest constructor.
     *
     * @param Wsdl   $wsdl
     * @param string $operation
     */
    public function __construct(Wsdl $wsdl, string $operation)
    {
        $this->operation = $operation;
        $this->action    = $wsdl->getSoapActionOfOperation($operation);
    }

    /**
     * @return string
     */
    public function getOperation(): string
    {
        return $this->operation;
    }

    /**
     * @return string
     */
    public function getSoapAction(): string
    {
        return $this->action;
    }

    /**
     * @return Body
     */
    public function getBody(): Body
    {
        if ($this->body === null) {
            $this->body = new Body();
        }

        return $this->body;
    }

    /**
     * @return Header
     */
    public function getHeader(): Header
    {
        if ($this->header === null) {
            $this->header = new Header();
        }

        return $this->header;
    }

    /**
     * @return Envelope
     */
    public function getEnvelope(): Envelope
    {
        $envelope = new Envelope();
        $envelope->appendElement($this->getHeader());
        $envelope->appendElement($this->getBody());

        return $envelope;
    }
}
