<?php

namespace Dgame\Soap\Component\Bipro;

use Dgame\Soap\Element;

/**
 * Class Password
 * @package Dgame\Soap\Component\Bipro
 */
class Password extends Element
{
    /**
     * Password constructor.
     *
     * @param string $password
     */
    public function __construct(string $password)
    {
        parent::__construct('Password', $password);

        $this->appendAttributes(
            [
                ['Type' => 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText']
            ]
        );
    }
}