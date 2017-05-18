<?php

namespace Dgame\Soap\Hydrator\Dom;

use Dgame\Soap\XmlElement;

/**
 * Interface AssemblableInterface
 * @package Dgame\Soap\Hydrator\Dom
 */
interface AssemblableInterface
{
    /**
     * @return XmlElement
     */
    public function assemble(): XmlElement;
}