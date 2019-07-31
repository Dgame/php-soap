<?php

namespace Dgame\Soap\Element;

use function Dgame\Ensurance\enforce;
use Dgame\Soap\Attribute\AttributeInterface;
use Dgame\Soap\NameTrait;
use Dgame\Soap\ValueTrait;
use Dgame\Soap\Visitor\ElementVisitorInterface;

/**
 * Class Element
 * @package Soap\Element
 */
class Element implements ElementInterface
{
    use NameTrait, ValueTrait;

    /**
     * @var AttributeInterface[]
     */
    private $attributes = [];

    /**
     * Element constructor.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function __construct(string $name, $value = null)
    {
        $this->name = trim($name);
        $this->setValue($value);
    }

    /**
     * @return bool
     */
    final public function hasAttributes(): bool
    {
        return !empty($this->attributes);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    final public function hasAttributeWithName(string $name): bool
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->getName() === $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return AttributeInterface[]
     */
    final public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param int $index
     *
     * @return AttributeInterface
     */
    final public function getAttributeByIndex(int $index): AttributeInterface
    {
        enforce(array_key_exists($index, $this->attributes))->orThrow('No Attribute at index %d', $index);

        return $this->attributes[$index];
    }

    /**
     * @param string $name
     *
     * @return AttributeInterface
     * @throws \Exception
     */
    final public function getAttributeByName(string $name): AttributeInterface
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->getName() === $name) {
                return $attribute;
            }
        }

        throw new \Exception('Could not find Attribute ' . $name);
    }

    /**
     * @param AttributeInterface $attribute
     */
    final public function setAttribute(AttributeInterface $attribute): void
    {
        $this->attributes[] = $attribute;
    }

    /**
     * @param string $name
     *
     * @return AttributeInterface|null
     */
    final public function removeAttributeByName(string $name): ?AttributeInterface
    {
        foreach ($this->attributes as $key => $attribute) {
            if ($attribute->getName() === $name) {
                unset($this->attributes[$key]);

                return $attribute;
            }
        }

        return null;
    }

    /**
     * @param ElementVisitorInterface $visitor
     */
    public function accept(ElementVisitorInterface $visitor): void
    {
        $visitor->visitElement($this);
    }
}
