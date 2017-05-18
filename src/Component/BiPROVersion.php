<?php

namespace Dgame\Soap\Component;

use Dgame\Soap\Attribute\XmlnsAttribute;

/**
 * Class Version
 * @package Dgame\Soap\Component
 */
final class BiPROVersion extends NamedNode
{
    /**
     * BiPROVersion constructor.
     *
     * @param string $version
     */
    public function __construct(string $version)
    {
        parent::__construct('allgemein');

        $this->setAttribute(new XmlnsAttribute('allgemein', 'http://www.bipro.net/namespace/allgemein'));
        $this->setValue($version);
    }
}