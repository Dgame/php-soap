<?php

namespace Dgame\Soap\Hydrator;

use Dgame\Soap\XmlElement;

/**
 * Interface AssemblableInterface
 * @package Dgame\Soap\Hydrator
 */
interface AssemblableInterface
{
    /**
     * @return XmlElement
     */
    public function assemble(): XmlElement;
}