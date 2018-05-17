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
}
