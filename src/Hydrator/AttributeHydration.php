<?php

namespace Dgame\Soap\Hydrator;

use Dgame\Soap\Attribute\Attribute;
use Dgame\Soap\Element;

/**
 * Class AttributeHydration
 * @package Dgame\Soap\Hydrator
 */
final class AttributeHydration
{
    /**
     * @var Hydrate
     */
    private $hydrate;

    /**
     * AttributeHydration constructor.
     *
     * @param Hydrate $facade
     */
    public function __construct(Hydrate $facade)
    {
        $this->hydrate = $facade;
    }

    /**
     * @return Hydrate
     */
    public function getHydrate(): Hydrate
    {
        return $this->hydrate;
    }

    /**
     * @param Element $element
     */
    public function hydrate(Element $element): void
    {
        $this->assignValue($element);
        $this->assignAttributes($element);
    }

    /**
     * @param Element $element
     */
    private function assignValue(Element $element): void
    {
        if ($element->hasValue()) {
            $this->hydrate->assign('value', $element->getValue());
        }
    }

    /**
     * @param Element $element
     */
    private function assignAttributes(Element $element): void
    {
        foreach ($element->getAttributes() as $attribute) {
            $this->assignAttribute($attribute);
        }
    }

    /**
     * @param Attribute $attribute
     */
    private function assignAttribute(Attribute $attribute): void
    {
        if ($attribute->hasValue()) {
            $this->hydrate->assign($attribute->getName(), $attribute->getValue());
        }
    }
}