<?php

namespace Dgame\Soap\Component\Bipro;

use Dgame\Soap\XmlElement;
use Dgame\Soap\XmlnsAttribute;

/**
 * Class Version
 * @package Dgame\Soap\Component\Bipro
 */
class Version extends XmlElement
{
    /**
     * BiProVersion constructor.
     *
     * @param string $version
     */
    public function __construct(string $version)
    {
        parent::__construct('BiPROVersion', $version);

        $this->setAttribute(new XmlnsAttribute('allgemein', 'http://www.bipro.net/namespace/allgemein'));
    }
}