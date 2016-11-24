<?php

namespace Dgame\Soap\Component\Bipro;

use Dgame\Soap\Attribute\XmlnsAttribute;
use Dgame\Soap\Element\XmlElement;

/**
 * Class Version
 * @package Dgame\Soap\Component\Bipro
 */
class Version extends XmlElement
{
    const NAME = 'BiPROVersion';

    /**
     * BiProVersion constructor.
     *
     * @param string $version
     */
    public function __construct(string $version)
    {
        parent::__construct(self::NAME, $version);

        $this->attachAttribute(new XmlnsAttribute('allgemein', 'http://www.bipro.net/namespace/allgemein'));
    }
}