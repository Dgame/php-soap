<?php

namespace Dgame\Soap\Component;

use Dgame\Soap\Attribute\XmlnsAttribute;

/**
 * Class Envelope
 * @package Dgame\Soap\Component
 */
final class Envelope extends NamedNode
{
    public function __construct()
    {
        parent::__construct('soap');

        $this->setAttribute(new XmlnsAttribute('soap', 'http://schemas.xmlsoap.org/soap/envelope/'));
    }
}