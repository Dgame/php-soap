<?php

namespace Dgame\Soap\Component\Bipro;

use Dgame\Soap\Node;

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
        $this->appendAttributes(
            [
                'xmlns' => [
                    'http://schemas.xmlsoap.org/ws/2004/08/addressing',
                    'ns2' => 'http://schemas.xmlsoap.org/ws/2005/02/trust',
                    'ns3' => 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd',
                    'ns4' => 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd',
                    'ns5' => 'http://www.w3.org/2000/09/xmldsig#',
                    'ns6' => 'http://schemas.xmlsoap.org/ws/2004/09/policy'
                ]
            ]
        );
    }

    /**
     * @return string
     */
    final public function getTokenType() : string
    {
        return $this->tokenType;
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
    final public function getRequestType() : string
    {
        return $this->requestType;
    }

    /**
     * @param string $requestType
     */
    final public function setRequestType(string $requestType)
    {
        $this->requestType = $requestType;
    }

    /**
     * @return Version
     */
    final public function getBiproVersion() : Version
    {
        return $this->biproVersion;
    }
}