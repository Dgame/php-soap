<?php

namespace Dgame\Soap;

/**
 * Class Envelope
 * @package Dgame\Soap
 */
class Envelope extends Root
{
    /**
     * Envelope constructor.
     */
    public function __construct()
    {
        parent::__construct('Envelope');

        $this->appendAttributes(
            [
                'xmlns' => [
                    'soap' => 'http://schemas.xmlsoap.org/soap/envelope/'
                ]
            ]
        );
    }
}