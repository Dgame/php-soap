<?php

namespace Dgame\Soap\Attribute;

use Dgame\Soap\PrefixableInterface;
use Dgame\Soap\Visitor\AttributeVisitorInterface;

/**
 * Class XmlAttribute
 * @package Dgame\Soap\Attribute
 */
class XmlAttribute extends Attribute implements PrefixableInterface
{
    /**
     * @var null|string
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

        if ($prefix !== null) {
            $this->prefix = $prefix;
        }
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
     * @param string $prefix
     */
    final public function setPrefix(string $prefix)
    {
        $this->prefix = trim($prefix);
    }

    /**
     * @return string
     */
    final public function getPrefixedName(): string
    {
        if ($this->hasPrefix()) {
            return sprintf('%s:%s', $this->getPrefix(), $this->getName());
        }

        return $this->getName();
    }

    /**
     * @param AttributeVisitorInterface $visitor
     */
    public function accept(AttributeVisitorInterface $visitor)
    {
        $visitor->visitXmlAttribute($this);
    }
}