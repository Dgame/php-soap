<?php

namespace Dgame\Soap\Component\Bipro;

use Dgame\Soap\Node;

/**
 * Class UsernameToken
 * @package Dgame\Soap\Component\Bipro
 */
class UsernameToken extends Node
{
    /**
     * @var null|string
     */
    private $username = null;
    /**
     * @var Password|null
     */
    private $password = null;

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
    public function getPropertyExport() : array
    {
        return ['username', 'password'];
    }
}