<?php

namespace Dgame\Soap\Component;

use Dgame\Soap\Attribute\XmlAttribute;

/**
 * Class Password
 * @package Dgame\Soap\Component
 */
final class Password extends NamedNode
{
    public function __construct(string $password)
    {
        parent::__construct();

        $this->setAttribute(new XmlAttribute('Type', 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText'));
        $this->setValue($password);
    }
}