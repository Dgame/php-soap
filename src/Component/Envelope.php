<?php

namespace Dgame\Soap\Component;

use Dgame\Soap\Root;

/**
 * Class Envelope
 * @package Dgame\Soap\Component
 */
class Envelope extends Root
{
    /**
     * Envelope constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->appendAttributes(
            [
                'xmlns' => [
                    'soap' => 'http://schemas.xmlsoap.org/soap/envelope/'
                ]
            ]
        );
    }
}