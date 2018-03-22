<?php

namespace Dgame\Soap\Attribute;

use Dgame\Soap\Visitor\AttributeVisitorInterface;

/**
 * Class XmlAttribute
 * @package Dgame\Soap\Attribute
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
     * @param string      $name
     * @param string|null $value
     * @param string|null $prefix
     */
    public function __construct(string $name, string $value = null, string $prefix = null)
    {
        parent::__construct($name, $value);

        $this->setPrefix($prefix ?? '');
    }

    /**
     * @return string
     */
    final public function getPrefixedName(): string
    {
        if ($this->hasPrefix()) {
            return sprintf('%s:%s', $this->prefix, $this->getName());
        }

        return $this->getName();
    }

    /**
     * @param string $prefix
     */
    final public function setPrefix(string $prefix): void
    {
        $prefix = trim($prefix);
        if (strlen($prefix) !== 0) {
            $this->prefix = $prefix;
        }
    }

    /**
     * @return bool
     */
    final public function hasPrefix(): bool
    {
        return $this->prefix !== null;
    }

    /**
     * @return string
     */
    final public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @param AttributeVisitorInterface $visitor
     */
    public function accept(AttributeVisitorInterface $visitor): void
    {
        $visitor->visitXmlAttribute($this);
    }
}