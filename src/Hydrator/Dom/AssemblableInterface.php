<?php

namespace Dgame\Soap\Hydrator\Dom;

use Dgame\Soap\Element;

/**
 * Interface AssemblableInterface
 * @package Dgame\Soap\Hydrator\Dom
 */
interface AssemblableInterface
{
    /**
     * @return Element
     */
    public function assemble(): Element;
}