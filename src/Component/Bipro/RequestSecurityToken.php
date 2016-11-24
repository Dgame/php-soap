<?php

namespace Dgame\Soap\Component\Bipro;

use Dgame\Soap\Attribute\DefaultXmlnsAttribute;
use Dgame\Soap\Attribute\XmlnsAttribute;
use Dgame\Soap\Component\AbstractNode;
use Dgame\Soap\element\XmlElement;

/**
 * Class RequestSecurityToken
 * @package Dgame\Soap\Component\Bipro
 */
class RequestSecurityToken extends AbstractNode
{
    /**
     * RequestSecurityToken constructor.
     *
     * @param Version $version
     */
    public function __construct(Version $version)
    {
        parent::__construct();

        $this->attachAttribute(new DefaultXmlnsAttribute('http://schemas.xmlsoap.org/ws/2004/08/addressing'));
        $this->attachAttribute(
            new XmlnsAttribute('ns2', 'http://schemas.xmlsoap.org/ws/2005/02/trust')
        );
        $this->attachAttribute(
            new XmlnsAttribute('ns3', 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd')
        );
        $this->attachAttribute(
            new XmlnsAttribute('ns4', 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd')
        );
        $this->attachAttribute(
            new XmlnsAttribute('ns5', 'http://www.w3.org/2000/09/xmldsig#')
        );
        $this->attachAttribute(
            new XmlnsAttribute('ns6', 'http://schemas.xmlsoap.org/ws/2004/09/policy')
        );

        $this->attachElement($version);
        $this->attachElement(new XmlElement('TokenType', 'http://schemas.xmlsoap.org/ws/2005/02/sc/sct'));
        $this->attachElement(new XmlElement('RequestType', 'http://schemas.xmlsoap.org/ws/2005/02/trust/Issue'));
    }
}