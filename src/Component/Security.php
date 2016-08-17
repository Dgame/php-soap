<?php

namespace Dgame\Soap\Component;

use Dgame\Soap\Node;
use Dgame\Soap\SoapAttribute;
use Dgame\Soap\XmlnsAttribute;

/**
 * Class Security
 * @package Dgame\Soap\Component
 */
class Security extends Node
{
    /**
     * Security constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->appendAttribute(
            new XmlnsAttribute('wsse', 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd')
        );
        $this->appendAttribute(
            new XmlnsAttribute('wsu', 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd')
        );
        $this->appendAttribute(new SoapAttribute('mustUnderstand', 1));
    }
}