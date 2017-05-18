<?php

namespace Dgame\Soap\Component;

use Dgame\Soap\Attribute\XmlnsAttribute;
use Dgame\Soap\XmlElement;

/**
 * Class RequestSecurityToken
 * @package Dgame\Soap\Component
 */
final class RequestSecurityToken extends NamedNode
{
    public function __construct()
    {
        parent::__construct('ns2');

        $this->setAttribute(new XmlnsAttribute('', 'http://schemas.xmlsoap.org/ws/2004/08/addressing'));

        $namespaces = [
            'ns2' => 'http://schemas.xmlsoap.org/ws/2005/02/trust',
            'ns3' => 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd',
            'ns4' => 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd',
            'ns5' => 'http://www.w3.org/2000/09/xmldsig#',
            'ns6' => 'http://schemas.xmlsoap.org/ws/2004/09/policy',
        ];

        foreach ($namespaces as $name => $value) {
            $this->setAttribute(new XmlnsAttribute($name, $value));
        }

        $this->appendElement(new XmlElement('TokenType', 'http://schemas.xmlsoap.org/ws/2005/02/sc/sct'));
        $this->appendElement(new XmlElement('RequestType', 'http://schemas.xmlsoap.org/ws/2005/02/trust/Issue'));
    }
}