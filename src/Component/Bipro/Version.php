<?php

namespace Dgame\Soap\Component\Bipro;
use Dgame\Soap\Element;
use Dgame\Soap\XmlnsAttribute;

/**
 * Class Version
 * @package Dgame\Soap\Component\Bipro
 */
class Version extends Element
{
    /**
     * BiProVersion constructor.
     *
     * @param string $version
     */
    public function __construct(string $version)
    {
        parent::__construct('BiPROVersion', $version);

        $this->appendAttribute(new XmlnsAttribute('allgemein', 'http://www.bipro.net/namespace/allgemein'));
    }
}