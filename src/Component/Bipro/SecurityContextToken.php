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
    public $identifier = null;

    /**
     * SecurityContextToken constructor.
     *
     * @param string $identifier
     */
    public function __construct(string $identifier)
    {
        parent::__construct();

        $this->identifier = $identifier;
        $this->appendAttributes(
            [
                'xmlns' => [
                    'wsc' => 'http://schemas.xmlsoap.org/ws/2005/02/sc'
                ]
            ]
        );
    }
}