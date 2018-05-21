<?php

namespace Dgame\Soap\Components;

/**
 * Class SoapRequest
 * @package Dgame\Soap\Components
 */
final class SoapRequest
{
    /**
     * @var Body
     */
    private $body;
    /**
     * @var Header
     */
    private $header;

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
