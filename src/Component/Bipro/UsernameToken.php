<?php

namespace Dgame\Soap\Component\Bipro;

use Dgame\Soap\Node;
use DOMElement;

/**
 * Class UsernameToken
 * @package Dgame\Soap\Component\Bipro
 */
class UsernameToken extends Node
{
    /**
     * @var null|string
     */
    public $username = null;
    /**
     * @var null|string
     */
    public $password = null;

    /**
     * UsernameToken constructor.
     *
     * @param string $username
     * @param string $password
     */
    public function __construct(string $username, string $password)
    {
        parent::__construct();

        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @param DOMElement $element
     */
    public function beforeAssemble(DOMElement $element)
    {
        if ($this->hasChild('Password')) {
            $this->getChild('Password')->appendAttributes(
                [
                    ['Type' => 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText']
                ]
            );
        }
    }
}