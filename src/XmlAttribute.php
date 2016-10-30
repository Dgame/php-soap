<?php

namespace Dgame\Soap;

use Dgame\Soap\Visitor\AttributeVisitor;

/**
 * Class XmlAttribute
 * @package Dgame\Soap
 */
class XmlAttribute extends Attribute
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * XmlAttribute constructor.
     *
     * @param string $name
     * @param string $value
     * @param string $prefix
     */
    public function __construct(string $name, string $value, string $prefix)
    {
        parent::__construct($name, $value);

        $this->prefix = $prefix;
    }

    /**
     * @return bool
     */
    final public function hasPrefix(): bool
    {
        return !empty($this->prefix);
    }

    /**
     * @return string
     */
    final public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @return string
     */
    final public function getNamespace(): string
    {
        if ($this->hasPrefix()) {
            return sprintf('%s:%s', $this->getName(), $this->prefix);
        }

        return $this->getName();
    }

    /**
     * @param AttributeVisitor $visitor
     */
    public function accept(AttributeVisitor $visitor)
    {
        $visitor->visitXmlAttribute($this);
    }
}