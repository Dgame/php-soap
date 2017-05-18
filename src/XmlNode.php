<?php

namespace Dgame\Soap;

use Dgame\Soap\Hydrator\VisitorInterface;

/**
 * Class XmlNode
 * @package Dgame\Soap
 */
class XmlNode extends XmlElement
{
    /**
     * @var XmlElement[]
     */
    private $elements = [];
    /**
     * @var bool
     */
    private $childPrefixInheritance = false;

    /**
     * @param string $prefix
     */
    public function setPrefix(string $prefix)
    {
        parent::setPrefix($prefix);

        foreach ($this->elements as $element) {
            $this->syncPrefix($element);
        }
    }

    /**
     * @param XmlElement $element
     */
    public function appendElement(XmlElement $element)
    {
        $this->elements[] = $element;
        $this->syncPrefix($element);
    }

    /**
     * @param XmlElement $element
     */
    private function syncPrefix(XmlElement $element)
    {
        if (!$element->hasPrefix() && $this->hasPrefix()) {
            $element->setPrefix($this->getPrefix());
        }
    }

    /**
     * @return XmlElement[]|XmlNode[]
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * @return bool
     */
    public function hasElements(): bool
    {
        return !empty($this->elements);
    }

    /**
     * @param VisitorInterface $visitor
     */
    public function accept(VisitorInterface $visitor)
    {
        $visitor->visitXmlNode($this);
    }

    /**
     * @return bool
     */
    final public function isChildPrefixInheritanceEnabled(): bool
    {
        return $this->childPrefixInheritance;
    }

    /**
     *
     */
    final public function enableChildPrefixInheritance()
    {
        $this->childPrefixInheritance = true;
    }

    /**
     *
     */
    final public function disableChildPrefixInheritance()
    {
        $this->childPrefixInheritance = false;
    }
}