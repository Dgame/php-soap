<?php

namespace Dgame\Soap\Component;

use Dgame\Soap\XmlElement;

/**
 * Class UsernameToken
 * @package Dgame\Soap\Component
 */
final class UsernameToken extends NamedNode
{
    public function __construct(string $username, string $password)
    {
        parent::__construct('wsse');

        $this->appendElement(new XmlElement('Username', $username));
        $this->appendElement(new Password($password));
    }
}