<?php

namespace Dgame\Soap\Component\Bipro;

use Dgame\Soap\Attribute\XmlnsAttribute;
use Dgame\Soap\Component\AbstractNode;
use Dgame\Soap\element\XmlElement;

/**
 * Class SecurityContextToken
 * @package Dgame\Soap\Component\Bipro
 */
class SecurityContextToken extends AbstractNode
{
    /**
     * SecurityContextToken constructor.
     *
     * @param string $identifier
     */
    public function __construct(string $identifier)
    {
        parent::__construct();

        $this->attachAttribute(new XmlnsAttribute('wsc', 'http://schemas.xmlsoap.org/ws/2005/02/sc'));
        $this->attachElement(new XmlElement('Identifier', $identifier));
    }
}