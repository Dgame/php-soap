<?php

namespace Dgame\Soap\Element;

use function Dgame\Ensurance\enforce;
use Dgame\Soap\Visitor\ElementVisitorInterface;

/**
 * Class XmlNode
 * @package Soap\Element
 */
class XmlNode extends XmlElement implements XmlNodeInterface
{
    /**
     * @var ElementInterface[]
     */
    private $elements = [];

    /**
     * @return bool
     */
    final public function hasElements(): bool
    {
        return !empty($this->elements);
    }

    /**
     * @param ElementInterface $element
     */
    final public function appendElement(ElementInterface $element): void
    {
        $this->elements[] = $element;
    }

    /**
     * @param ElementInterface $element
     */
    final public function appendElementOnce(ElementInterface $element): void
    {
        $name = $element->getName();
        $this->removeElementsByName($name);
        $this->appendElement($element);
    }

    /**
     * @return ElementInterface[]
     */
    final public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * @param ElementVisitorInterface $visitor
     */
    public function accept(ElementVisitorInterface $visitor): void
    {
        $visitor->visitXmlNode($this);
    }

    /**
     * @param string   $name
     * @param callable $closure
     *
     * @return int
     */
    public function applyTo(string $name, callable $closure): int
    {
        $elements = $this->getElementsByName($name);
        foreach ($elements as $element) {
            $closure($element);
        }

        return count($elements);
    }

    /**
     * @param int $index
     *
     * @return ElementInterface
     */
    final public function getElementByIndex(int $index): ElementInterface
    {
        enforce(array_key_exists($index, $this->elements))->orThrow('No Element at index %d', $index);

        return $this->elements[$index];
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public function getElementsByName(string $name): array
    {
        $elements = [];
        foreach ($this->elements as $element) {
            if ($element->getName() === $name) {
                $elements[] = $element;
            }
        }

        return $elements;
    }

    /**
     * @param string $name
     *
     * @return int
     */
    public function removeElementsByName(string $name): int
    {
        $count = 0;
        foreach ($this->elements as $index => $element) {
            if ($element->getName() === $name) {
                $count++;
                unset($this->elements[$index]);
            }
        }

        return $count;
    }
}
