<?php

namespace Dgame\Soap\Component\Bipro;

use Dgame\Soap\XmlNode;

/**
 * Class UsernameToken
 * @package Dgame\Soap\Component\Bipro
 */
class UsernameToken extends XmlNode
{
    /**
     * @var string
     */
    private $username;
    /**
     * @var Password
     */
    private $password;

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
        $this->password = new Password($password);
    }

    /**
     * @return string
     */
    final public function getUsername() : string
    {
        return $this->username;
    }

    /**
     * @return Password
     */
    final public function getPassword() : Password
    {
        return $this->password;
    }

    /**
     * @return array
     */
    public function export() : array
    {
        return ['username', 'password'];
    }
}