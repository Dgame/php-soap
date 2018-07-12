<?php

namespace Dgame\Soap\Components;

use Dgame\Soap\Attribute\XmlnsAttribute;
use Dgame\Soap\Element\XmlNode;

/**
 * Class Envelope
 * @package Dgame\Soap\Components
 */
class Envelope extends XmlNode
{
    /**
     * Envelope constructor.
     */
    public function __construct()
    {
        parent::__construct('Envelope');
        $this->setAttribute(new XmlnsAttribute('soap', 'http://schemas.xmlsoap.org/soap/envelope/'));
    }
}
