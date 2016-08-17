<?php

namespace Dgame\Soap\Component\Bipro;

use Dgame\Soap\DefaultXmlnsAttribute;
use Dgame\Soap\Node;
use Dgame\Soap\XmlnsAttribute;

/**
 * Class RequestSecurityToken
 * @package Dgame\Soap\Component\Bipro
 */
class RequestSecurityToken extends Node
{
    /**
     * @var string
     */
    private $tokenType = 'http://schemas.xmlsoap.org/ws/2005/02/sc/sct';
    /**
     * @var string
     */
    private $requestType = 'http://schemas.xmlsoap.org/ws/2005/02/trust/Issue';
    /**
     * @var null|Version
     */
    private $biproVersion = null;

    /**
     * RequestSecurityToken constructor.
     *
     * @param Version $version
     */
    public function __construct(Version $version)
    {
        parent::__construct();

        $this->biproVersion = $version;

        $this->appendAttribute(new DefaultXmlnsAttribute('http://schemas.xmlsoap.org/ws/2004/08/addressing'));
        $this->appendAttribute(
            new XmlnsAttribute('ns2', 'http://schemas.xmlsoap.org/ws/2005/02/trust')
        );
        $this->appendAttribute(
            new XmlnsAttribute('ns3', 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd')
        );
        $this->appendAttribute(
            new XmlnsAttribute('ns4', 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd')
        );
        $this->appendAttribute(
            new XmlnsAttribute('ns5', 'http://www.w3.org/2000/09/xmldsig#')
        );
        $this->appendAttribute(
            new XmlnsAttribute('ns6', 'http://schemas.xmlsoap.org/ws/2004/09/policy')
        );
    }

    /**
     * @return Version
     */
    public function getBiproVersion() : Version
    {
        return $this->biproVersion;
    }

    /**
     * @param string $tokenType
     */
    final public function setTokenType(string $tokenType)
    {
        $this->tokenType = $tokenType;
    }

    /**
     * @return string
     */
    final public function getTokenType() : string
    {
        return $this->tokenType;
    }

    /**
     * @param string $requestType
     */
    final public function setRequestType(string $requestType)
    {
        $this->requestType = $requestType;
    }

    /**
     * @return string
     */
    final public function getRequestType(): string
    {
        return $this->requestType;
    }

    /**
     * @return array
     */
    public function getPropertyExport() : array
    {
        return ['tokenType', 'requestType', 'biproVersion'];
    }
}