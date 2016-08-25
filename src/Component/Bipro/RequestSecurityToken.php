<?php

namespace Dgame\Soap\Component\Bipro;

use Dgame\Soap\DefaultXmlnsAttribute;
use Dgame\Soap\XmlNode;
use Dgame\Soap\XmlnsAttribute;

/**
 * Class RequestSecurityToken
 * @package Dgame\Soap\Component\Bipro
 */
class RequestSecurityToken extends XmlNode
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
     * @var Version
     */
    private $biproVersion;

    /**
     * RequestSecurityToken constructor.
     *
     * @param Version $version
     */
    public function __construct(Version $version)
    {
        parent::__construct();

        $this->biproVersion = $version;

        $this->setAttribute(new DefaultXmlnsAttribute('http://schemas.xmlsoap.org/ws/2004/08/addressing'));
        $this->setAttribute(
            new XmlnsAttribute('ns2', 'http://schemas.xmlsoap.org/ws/2005/02/trust')
        );
        $this->setAttribute(
            new XmlnsAttribute('ns3', 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd')
        );
        $this->setAttribute(
            new XmlnsAttribute('ns4', 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd')
        );
        $this->setAttribute(
            new XmlnsAttribute('ns5', 'http://www.w3.org/2000/09/xmldsig#')
        );
        $this->setAttribute(
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
    public function export() : array
    {
        return ['tokenType', 'requestType', 'biproVersion'];
    }
}