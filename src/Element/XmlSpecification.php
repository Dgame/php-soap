<?php

namespace Dgame\Soap\Element;

use Dgame\Soap\Attribute\Attribute;

/**
 * Class XmlSpecification
 * @package Soap\Element
 */
final class XmlSpecification extends Element
{
    /**
     * XmlSpecification constructor.
     */
    public function __construct()
    {
        parent::__construct('xml');

        $this->setAttribute(new Attribute('version', '1.0'));
        $this->setAttribute(new Attribute('encoding', 'utf-8'));
    }
}
