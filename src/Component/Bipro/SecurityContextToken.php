<?php

namespace Dgame\Soap\Component\Bipro;

use Dgame\Soap\Node;

/**
 * Class SecurityContextToken
 * @package Dgame\Soap\Component\Bipro
 */
class SecurityContextToken extends Node
{
    /**
     * @var null|string
     */
    private $id = null;

    /**
     * SecurityContextToken constructor.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        parent::__construct();

        $this->id = $id;
        $this->appendAttributes(
            [
                'xmlns' => [
                    'wsc' => 'http://schemas.xmlsoap.org/ws/2005/02/sc'
                ]
            ]
        );
    }

    /**
     * @return string
     */
    final public function getId() : string
    {
        return $this->id;
    }
}