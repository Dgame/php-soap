<?php

namespace Dgame\Soap\Component;

use Dgame\Soap\Node;

/**
 * Class Security
 * @package Dgame\Soap\Component
 */
class Security extends Node
{
    /**
     * Security constructor.
     */
    public function __construct()
    {
        parent::__construct('Security');

        $this->appendAttributes(
            [
                'xmlns' => [
                    'wsse' => 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd',
                    'wsu'  => 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd'
                ],
                'soap'  => ['mustUnderstand' => 1],
            ]
        );
    }
}