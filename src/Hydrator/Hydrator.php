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
     * @var bool
     */
    public $debug = false;

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
        for ($i = $this->footprints->count() - 1; $i >= 0; $i--) {
            $footprints[] = $this->footprints[$i];
        }

        return $this->strategy->processFootprint(implode('.', $footprints));
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
        $this->debug($footprints);
        $this->strategy->setAttribute($footprints, $attribute);
    }

    /**
     * @param ElementInterface $element
     *
     * @return bool
     */
    private function pushElement(ElementInterface $element): bool
    {
        $this->footprints->push($element->getName());
        $footprints = $this->getFootprints();
        $this->debug($footprints);

        return $this->strategy->pushElement($footprints, $element);
    }

    /**
     * @param string $message
     */
    private function debug(string $message): void
    {
        if ($this->debug) {
            var_dump($message);
        }
    }

    /**
     * @param bool $pushed
     */
    private function popElement(bool $pushed): void
    {
        $this->footprints->pop();
        if ($pushed) {
            $this->strategy->popElement();
        }
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
        $pushed = $this->pushElement($element);
        $this->traverseAttributes($element);
        $this->popElement($pushed);
    }

    /**
     * @param XmlElementInterface $element
     */
    public function visitXmlElement(XmlElementInterface $element): void
    {
        $pushed = $this->pushElement($element);
        $this->traverseAttributes($element);
        $this->popElement($pushed);
    }

    /**
     * @param XmlNodeInterface $node
     */
    public function visitXmlNode(XmlNodeInterface $node): void
    {
        $pushed = $this->pushElement($node);
        $this->traverseAttributes($node);
        foreach ($node->getElements() as $element) {
            $element->accept($this);
        }
        $this->popElement($pushed);
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
