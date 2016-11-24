<?php

namespace Dgame\Soap\Component;

use Dgame\Soap\Attribute\XmlnsAttribute;

/**
 * Class Envelope
 * @package Dgame\Soap\Component
 */
class Envelope extends Root
{
    /**
     * Envelope constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->attachAttribute(new XmlnsAttribute('soap', 'http://schemas.xmlsoap.org/soap/envelope/'));
    }
}