<?php

namespace Dgame\Soap\Attribute;

use Dgame\Soap\Element\Element;
use Dgame\Soap\PrefixableInterface;
use Dgame\Soap\PrefixTrait;
use Dgame\Soap\Visitor\AttributeVisitorInterface;
use Dgame\Soap\Visitor\PrefixInheritVisitor;

class XmlAttribute extends Attribute implements PrefixableInterface
{
    use PrefixTrait;

    public function __construct(string $name, string $value, string $prefix = null)
    {
        parent::__construct($name, $value);

        $this->prefix = $prefix;
    }

    final public function getNamespace(): string
    {
        if ($this->hasPrefix()) {
            return sprintf('%s:%s', $this->getName(), $this->getPrefix());
        }

        return $this->getName();
    }

    public function attachedBy(Element $element)
    {
        parent::attachedBy($element);

        $visitor = new PrefixInheritVisitor($this);
        $element->accept($visitor);
    }

    public function accept(AttributeVisitorInterface $visitor)
    {
        $visitor->visitXmlAttribute($this);
    }

    public function prefixUsedBy(Element $element)
    {
        $this->increaseUsage();
    }
}