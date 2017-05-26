<?php

namespace Dgame\Soap;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Visitor\AttributePrefixInheritanceVisitor;
use Dgame\Soap\Visitor\ElementVisitorInterface;

/**
 * Class XmlElement
 * @package Dgame\Soap
 */
class XmlElement extends Element implements PrefixableInterface
{
    /**
     * @var null|string
     */
    private $prefix;

    /**
     * XmlElement constructor.
     *
     * @param string      $name
     * @param string|null $prefix
     * @param string|null $value
     */
    public function __construct(string $name, string $prefix = null, string $value = null)
    {
        parent::__construct($name, $value);

        $this->prefix = $prefix;
    }

    /**
     * @param Attribute $attribute
     */
    public function setAttribute(Attribute $attribute)
    {
        parent::setAttribute($attribute);

        $attribute->accept(new AttributePrefixInheritanceVisitor($this));
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
    public function setPrefix(string $prefix)
    {
        $this->prefix = $prefix;
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
     * @param ElementVisitorInterface $visitor
     */
    public function accept(ElementVisitorInterface $visitor)
    {
        $visitor->visitXmlElement($this);
    }
}