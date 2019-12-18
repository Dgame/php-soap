<?php

namespace Dgame\Soap\Translator;

use Dgame\Soap\Attribute\AttributeInterface;
use Dgame\Soap\Attribute\XmlAttributeInterface;
use Dgame\Soap\Attribute\XmlnsAttribute;
use Dgame\Soap\Element\ElementInterface;
use Dgame\Soap\Element\XmlElementInterface;
use Dgame\Soap\Element\XmlNodeInterface;
use Dgame\Soap\PrefixedInterface;
use Dgame\Soap\ValuedInterface;
use Dgame\Soap\Visitor\AttributeVisitorInterface;
use Dgame\Soap\Visitor\ElementVisitorInterface;
use Dgame\Soap\Visitor\XmlNamespaceFinder;
use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Class PackageToBuiltinTranslator
 * @package Soap\Translator
 */
final class PackageToBuiltinTranslator implements ElementVisitorInterface, AttributeVisitorInterface
{
    /**
     * @var DOMDocument
     */
    private $document;
    /**
     * @var DOMElement
     */
    private $node;
    /**
     * @var ElementVisitorInterface[]
     */
    private $preprocessors = [];

    /**
     * @param ElementVisitorInterface $visitor
     *
     * @return PackageToBuiltinTranslator
     */
    public function appendPreprocessor(ElementVisitorInterface $visitor): self
    {
        $this->preprocessors[] = $visitor;

        return $this;
    }

    /**
     * @param ElementInterface $element
     * @param DOMNode|null     $node
     *
     * @return DOMDocument
     */
    public function translate(ElementInterface $element, DOMNode $node = null): DOMDocument
    {
        if ($node === null) {
            $this->node = $this->document = new DOMDocument('1.0', 'utf-8');
        } else {
            $this->node     = $node;
            $this->document = $node->ownerDocument ?? $this->node;
        }

        $this->preprocess($element);
        $element->accept($this);

        return $this->document;
    }

    /**
     * @param ElementInterface $element
     * @param DOMNode          $node
     */
    private function assemble(ElementInterface $element, DOMNode $node): void
    {
        $this->node->appendChild($node);
        $this->node = $node;
        foreach ($element->getAttributes() as $attribute) {
            $attribute->accept($this);
        }
    }

    /**
     * @param ElementInterface $element
     */
    public function visitElement(ElementInterface $element): void
    {
        $node = new DOMElement($element->getName(), $this->getValueExport($element));
        $this->assemble($element, $node);
    }

    /**
     * @param XmlElementInterface $element
     */
    public function visitXmlElement(XmlElementInterface $element): void
    {
        $node = $this->createDomNode($element);
        $this->assemble($element, $node);
    }

    /**
     * @param XmlNodeInterface $node
     */
    public function visitXmlNode(XmlNodeInterface $node): void
    {
        $this->visitXmlElement($node);
        foreach ($node->getElements() as $element) {
            $translator = new self();
            $translator->translate($element, $this->node);
        }
    }

    /**
     * @param AttributeInterface $attribute
     */
    public function visitAttribute(AttributeInterface $attribute): void
    {
        $attr = $this->node->setAttribute($attribute->getName(), $this->getValueExport($attribute)); // TODO: Fehlerhandling
        assert($attr !== false);
    }

    /**
     * @param XmlAttributeInterface $attribute
     */
    public function visitXmlAttribute(XmlAttributeInterface $attribute): void
    {
        $attr = $this->node->setAttribute(self::getPrefixedName($attribute), $this->getValueExport($attribute)); // TODO: Fehlerhandling
        assert($attr !== false);
    }

    /**
     * @param XmlnsAttribute $attribute
     */
    public function visitXmlnsAttribute(XmlnsAttribute $attribute): void
    {
        $this->node->setAttributeNS(
            'http://www.w3.org/2000/xmlns/',
            self::getPrefixedName($attribute),
            $attribute->getValue()
        );
    }

    /**
     * @param PrefixedInterface $prefixed
     *
     * @return string
     */
    private static function getPrefixedName(PrefixedInterface $prefixed): string
    {
        if ($prefixed->hasPrefix() && $prefixed->hasName()) {
            return sprintf('%s:%s', $prefixed->getPrefix(), $prefixed->getName());
        }

        return $prefixed->hasPrefix() ? $prefixed->getPrefix() : $prefixed->getName();
    }

    /**
     * @param XmlElementInterface $element
     *
     * @return DOMNode
     */
    private function createDomNode(XmlElementInterface $element): DOMNode
    {
        $name   = self::getPrefixedName($element);
        $finder = new XmlNamespaceFinder($element);
        if ($finder->hasNamespace()) {
            return $this->document->createElementNS($finder->getNamespace()->getValue(), $name, $this->getValueExport($element));
        }

        return $this->document->createElement($name, $this->getValueExport($element));
    }

    /**
     * @param ElementInterface $element
     */
    private function preprocess(ElementInterface $element): void
    {
        foreach ($this->preprocessors as $preprocessor) {
            $element->accept($preprocessor);
        }
    }

    /**
     * @param ValuedInterface $valued
     *
     * @return mixed
     */
    private function getValueExport(ValuedInterface $valued)
    {
        if (!$valued->hasValue()) {
            return null;
        }

        $value = $valued->getValue();

        return is_string($value) ? htmlspecialchars($value) : var_export($value, true);
    }
}
