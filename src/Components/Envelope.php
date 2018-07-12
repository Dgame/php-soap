<?php

namespace Dgame\Soap\Components;

use Dgame\Soap\Attribute\XmlnsAttribute;
use Dgame\Soap\Element\XmlNode;

/**
 * Class Envelope
 * @package Dgame\Soap\Components
 */
final class Envelope extends XmlNode
{
    /**
     * Envelope constructor.
     *
     * @param string $name
     */
    public function __construct(string $name = 'Envelope')
    {
        parent::__construct($name);
        $this->setAttribute(new XmlnsAttribute('soap', 'http://schemas.xmlsoap.org/soap/envelope/'));
    }
}
