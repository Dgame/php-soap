<?php

namespace Dgame\Soap\Component;

use Dgame\Soap\Attribute\SoapAttribute;
use Dgame\Soap\Attribute\XmlnsAttribute;

/**
 * Class Security
 * @package Dgame\Soap\Component
 */
final class Security extends NamedNode
{
    public function __construct()
    {
        parent::__construct('wsse');

        $this->setAttribute(new XmlnsAttribute('wsse', 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd'));
        $this->setAttribute(new XmlnsAttribute('wsu', 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd'));
        $this->setAttribute(new SoapAttribute('mustUnderstand', 1));
    }
}