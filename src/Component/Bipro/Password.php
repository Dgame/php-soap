<?php

namespace Dgame\Soap\Component\Bipro;

use Dgame\Soap\Attribute;
use Dgame\Soap\XmlElement;

/**
 * Class Password
 * @package Dgame\Soap\Component\Bipro
 */
class Password extends XmlElement
{
    /**
     * Password constructor.
     *
     * @param string $password
     */
    public function __construct(string $password)
    {
        parent::__construct('Password', $password);

        $this->setAttribute(
            new Attribute('Type', 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText')
        );
    }
}