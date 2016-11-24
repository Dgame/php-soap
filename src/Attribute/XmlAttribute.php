<?php

namespace Dgame\Soap\Attribute;

use Dgame\Soap\Element\Element;
use Dgame\Soap\PrefixableInterface;
use Dgame\Soap\PrefixTrait;
use Dgame\Soap\Visitor\AttributeVisitorInterface;
use Dgame\Soap\Visitor\PrefixInheritVisitor;

/**
 * Class XmlAttribute
 * @package Dgame\Soap\Attribute
 */
class XmlAttribute extends Attribute implements PrefixableInterface
{
    use PrefixTrait;

    /**
     * XmlAttribute constructor.
     *
     * @param string      $name
     * @param string      $value
     * @param string|null $prefix
     */
    public function __construct(string $name, string $value, string $prefix = null)
    {
        parent::__construct($name, $value);

        $this->prefix = $prefix;
    }

    /**
     * @return string
     */
    final public function getNamespace(): string
    {
        if ($this->hasPrefix()) {
            return sprintf('%s:%s', $this->getName(), $this->getPrefix());
        }

        return $this->getName();
    }

    /**
     * @param Element $element
     */
    public function attachedBy(Element $element)
    {
        parent::attachedBy($element);

        $visitor = new PrefixInheritVisitor($this);
        $element->accept($visitor);
    }

    /**
     * @param AttributeVisitorInterface $visitor
     */
    public function accept(AttributeVisitorInterface $visitor)
    {
        $visitor->visitXmlAttribute($this);
    }

    /**
     * @param Element $element
     */
    public function prefixUsedBy(Element $element)
    {
        $this->increaseUsage();
    }
}