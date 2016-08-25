<?php

namespace Dgame\Soap\Component;

use Dgame\Soap\SoapAttribute;
use Dgame\Soap\XmlNode;
use Dgame\Soap\XmlnsAttribute;

/**
 * Class Security
 * @package Dgame\Soap\Component
 */
class Security extends XmlNode
{
    /**
     * Security constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setAttribute(
            new XmlnsAttribute('wsse', 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd')
        );
        $this->setAttribute(
            new XmlnsAttribute('wsu', 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd')
        );
        $this->setAttribute(new SoapAttribute('mustUnderstand', 1));
    }
}