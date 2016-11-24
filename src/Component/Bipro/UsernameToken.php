<?php

namespace Dgame\Soap\Component\Bipro;

use Dgame\Soap\Component\AbstractNode;
use Dgame\Soap\element\XmlElement;

/**
 * Class UsernameToken
 * @package Dgame\Soap\Component\Bipro
 */
class UsernameToken extends AbstractNode
{
    /**
     * UsernameToken constructor.
     *
     * @param string $username
     * @param string $password
     */
    public function __construct(string $username, string $password)
    {
        parent::__construct();

        $this->attachElement(new XmlElement('Username', $username));
        $this->attachElement(new Password($password));
    }
}