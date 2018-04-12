<?php

namespace Dgame\Soap\Hydrator;

use Dgame\Soap\Attribute\AttributeInterface;
use Dgame\Soap\Attribute\XmlAttributeInterface;
use Dgame\Soap\Attribute\XmlnsAttribute;
use Dgame\Soap\Element\ElementInterface;
use Dgame\Soap\Element\XmlElementInterface;
use Dgame\Soap\Element\XmlNodeInterface;
use Dgame\Soap\Translator\BuiltinToPackageTranslator;
use Dgame\Soap\Visitor\AttributeVisitorInterface;
use Dgame\Soap\Visitor\ElementVisitorInterface;
use DOMNode;
use SplStack;

/**
 * Class Hydrator
 * @package Dgame\Soap\Hydrator
 */
final class Hydrator implements ElementVisitorInterface, AttributeVisitorInterface
{
    /**
     * @var HydratorStrategyInterface
     */
    private $strategy;
    /**
     * @var SplStack
     */
    private $footprints;

    /**
     * Hydrator constructor.
     *
     * @param HydratorStrategyInterface $strategy
     */
    public function __construct(HydratorStrategyInterface $strategy)
    {
        $this->footprints = new SplStack();
        $this->strategy   = $strategy;
    }

    /**
     * @return string
     */
    private function getFootprints(): string
    {
        $footprints = [];
        foreach ($this->footprints as $footprint) {
            $footprints[] = $this->strategy->processFootprint($footprint);
        }

        $footprints = implode('.', array_reverse($footprints));

        return $this->strategy->processFootprint($footprints);
    }

    /**
     * @param DOMNode $node
     */
    public function hydrate(DOMNode $node): void
    {
        $translator = new BuiltinToPackageTranslator();
        $element    = $translator->translate($node);
        if ($element !== null) {
            $element->accept($this);
        }
    }

    /**
     * @param AttributeInterface $attribute
     */
    private function pushAttribute(AttributeInterface $attribute): void
    {
        $this->footprints->push($attribute->getName());
        $footprints = $this->getFootprints();
        //        var_dump($footprints);
        $this->strategy->setAttribute($footprints, $attribute);
    }

    /**
     * @param ElementInterface $element
     */
    private function pushElement(ElementInterface $element): void
    {
        $this->footprints->push($element->getName());
        $footprints = $this->getFootprints();
        //        var_dump($footprints);
        $this->strategy->pushElement($footprints, $element);
    }

    /**
     *
     */
    private function popElement(): void
    {
        $this->footprints->pop();
        $this->strategy->popElement();
    }

    /**
     *
     */
    private function popAttribute(): void
    {
        $this->footprints->pop();
    }

    /**
     * @param AttributeInterface $attribute
     */
    public function visitAttribute(AttributeInterface $attribute): void
    {
        $this->pushAttribute($attribute);
        $this->popAttribute();
    }

    /**
     * @param XmlAttributeInterface $attribute
     */
    public function visitXmlAttribute(XmlAttributeInterface $attribute): void
    {
        $this->pushAttribute($attribute);
        $this->popAttribute();
    }

    /**
     * @param XmlnsAttribute $attribute
     */
    public function visitXmlnsAttribute(XmlnsAttribute $attribute): void
    {
        $this->pushAttribute($attribute);
        $this->popAttribute();
    }

    /**
     * @param ElementInterface $element
     */
    public function visitElement(ElementInterface $element): void
    {
        $this->pushElement($element);
        $this->traverseAttributes($element);
        $this->popElement();
    }

    /**
     * @param XmlElementInterface $element
     */
    public function visitXmlElement(XmlElementInterface $element): void
    {
        $this->pushElement($element);
        $this->traverseAttributes($element);
        $this->popElement();
    }

    /**
     * @param XmlNodeInterface $node
     */
    public function visitXmlNode(XmlNodeInterface $node): void
    {
        $this->pushElement($node);
        $this->traverseAttributes($node);
        foreach ($node->getElements() as $element) {
            $element->accept($this);
        }
        $this->popElement();
    }

    /**
     * @param ElementInterface $element
     */
    private function traverseAttributes(ElementInterface $element): void
    {
        foreach ($element->getAttributes() as $attribute) {
            $attribute->accept($this);
        }
    }
}