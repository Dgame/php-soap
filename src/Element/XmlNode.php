<?php

namespace Dgame\Soap\Element;

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
     * @param string                $name
     * @param ElementInterface|null $element
     *
     * @return bool
     */
    public function hasElementWithName(string $name, ElementInterface &$element = null): bool
    {
        foreach ($this->getElements() as $element) {
            if ($element->getName() === $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $name
     *
     * @return ElementInterface|null
     */
    public function getElementByName(string $name): ?ElementInterface
    {
        return $this->hasElementWithName($name, $element) ? $element : null;
    }

    /**
     * @param string        $name
     * @param callable|null $create
     *
     * @return ElementInterface
     */
    public function getOrSetElementByName(string $name, callable $create = null): ElementInterface
    {
        $element = $this->getElementByName($name);
        if ($element === null) {
            $element = $create === null ? new XmlElement($name) : $create($name);
            $this->appendElement($element);
        }

        return $element;
    }
}
