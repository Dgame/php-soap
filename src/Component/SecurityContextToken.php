<?php

namespace Dgame\Soap\Component;

use Dgame\Soap\Attribute\XmlnsAttribute;
use Dgame\Soap\XmlElement;

/**
 * Class SecurityContextToken
 * @package Dgame\Soap\Component
 */
final class SecurityContextToken extends NamedNode
{
    /**
     * SecurityContextToken constructor.
     *
     * @param string $token
     */
    public function __construct(string $token)
    {
        parent::__construct('wsc');

        $this->setAttribute(new XmlnsAttribute('wsc', 'http://schemas.xmlsoap.org/ws/2005/02/sc'));
        $this->appendElement(new XmlElement('Identifier', $token));
    }
}