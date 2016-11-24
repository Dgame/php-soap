<?php

namespace Dgame\Soap\Element;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Visitor\ElementVisitableInterface;
use Dgame\Soap\Visitor\ElementVisitorInterface;

class Element implements ElementVisitableInterface
{
    private $name;
    private $value;
    private $attributes = [];

    public function __construct(string $name, string $value = null)
    {
        $this->name  = $name;
        $this->value = $value;
    }

    final public function getName(): string
    {
        return $this->name;
    }

    final public function setValue(string $value)
    {
        $this->value = $value;
    }

    final public function getValue(): string
    {
        return $this->value ?? '';
    }

    final public function hasValue(): bool
    {
        return !empty($this->value);
    }

    final public function attachAttribute(Attribute $attribute)
    {
        $attribute->attachedBy($this);

        $this->attributes[] = $attribute;
    }

    final public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function accept(ElementVisitorInterface $visitor)
    {
        $visitor->visitElement($this);
    }
}