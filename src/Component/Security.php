<?php

namespace Dgame\Soap\Component;

use Dgame\Soap\Attribute\SoapAttribute;
use Dgame\Soap\Attribute\XmlnsAttribute;

/**
 * Class Security
 * @package Dgame\Soap\Component
 */
class Security extends AbstractNode
{
    /**
     * Security constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->attachAttribute(
            new XmlnsAttribute('wsse', 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd')
        );
        $this->attachAttribute(
            new XmlnsAttribute('wsu', 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd')
        );
        $this->attachAttribute(new SoapAttribute('mustUnderstand', 1));
    }
}