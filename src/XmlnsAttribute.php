<?php

namespace Dgame\Soap;

use Dgame\Soap\Visitor\AttributeVisitorInterface;

/**
 * Class XmlnsAttribute
 * @package Dgame\Soap
 */
class XmlnsAttribute extends PrefixedAttribute
{
    /**
     * XmlnsAttribute constructor.
     *
     * @param string      $value
     * @param string|null $prefix
     * @param string      $namespace
     */
    public function __construct(string $prefix, string $value)
    {
        parent::__construct($prefix, $value, 'xmlns');
    }

    /**
     * @param AttributeVisitorInterface $visitor
     */
    public function accept(AttributeVisitorInterface $visitor)
    {
        $visitor->visitXmlnsAttribute($this);
    }
}