<?php

namespace Dgame\Soap\Component;

use Dgame\Soap\Root;
use Dgame\Soap\XmlnsAttribute;

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

        $this->setAttribute(new XmlnsAttribute('soap', 'http://schemas.xmlsoap.org/soap/envelope/'));
    }
}