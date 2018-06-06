<?php

namespace Dgame\Soap\Wsdl;

use Dgame\Soap\Wsdl\Elements\ComplexType;
use Dgame\Soap\Wsdl\Elements\Element;
use Dgame\Soap\Wsdl\Elements\Extension;
use Dgame\Soap\Wsdl\Elements\Restriction\RestrictionInterface;
use Dgame\Soap\Wsdl\Elements\SimpleType;
use function Dgame\Ensurance\enforce;

/**
 * Class SoapOperation
 * @package Dgame\Soap\Wsdl
 */
final class SoapOperation
{
    /**
     * @var XsdAdapterInterface
     */
    private $xsd;
    /**
     * @var Element
     */
    private $element;
    /**
     * @var SoapElement[]
     */
    private $elements = [];

    /**
     * SoapOperation constructor.
     *
     * @param XsdAdapterInterface $xsd
     * @param string              $operation
     */
    public function __construct(XsdAdapterInterface $xsd, string $operation)
    {
        $this->xsd     = $xsd;
        $this->element = $xsd->findElementByNameInDeep($operation);
    }

    /**
     * @return XsdAdapterInterface
     */
    public function getXsd(): XsdAdapterInterface
    {
        return $this->xsd;
    }

    /**
     * @return Element
     */
    public function getElement(): Element
    {
        return $this->element;
    }

    /**
     * @return SoapElement[]
     */
    public function getSoapElements(): array
    {
        if (!empty($this->elements)) {
            return $this->elements;
        }

        $this->elements = [];

        $complex = $this->getElement()->getComplexType();
        enforce($complex !== null)->orThrow('Can only collect elements from a ComplexType');

        if ($complex->hasExtensions()) {
            $extension      = $complex->getFirstExtension();
            $this->elements = $this->loadParentElements($extension);
        }

        $this->elements = array_merge($this->collectElementsFrom($complex), $this->elements);

        return $this->elements;
    }

    /**
     * @param Extension $extension
     *
     * @return SoapElement[]
     */
    private function loadParentElements(Extension $extension): array
    {
        $name    = $extension->getPrefixedName();
        $parent  = $this->getXsd()->findElementByNameInDeep($name);
        if ($parent === null) {
            return [];
        }

        $complex = $parent->getComplexType();
        enforce($complex !== null)->orThrow('Can only collect elements from a ComplexType');

        $elements = array_merge(
            $this->collectExtensionElements($extension),
            $this->collectElementsFrom($complex)
        );

        $prefix = $extension->getPrefix();
        $uri    = $this->getXsd()->getUriByPrefix($prefix);

        array_walk($elements, function (SoapElement $element) use ($uri): void {
            $element->setUri($uri);
        });

        return $elements;
    }

    /**
     * @param Extension $extension
     *
     * @return SoapElement[]
     */
    private function collectExtensionElements(Extension $extension): array
    {
        return $this->collectElements($extension->getElements());
    }

    /**
     * @param ComplexType $complex
     *
     * @return SoapElement[]
     */
    private function collectElementsFrom(ComplexType $complex): array
    {
        $elements = [];
        if ($complex->hasExtensions()) {
            $extension = $complex->getFirstExtension();
            $elements  = array_merge($elements, $this->loadParentElements($extension));
        }

        $childs = $complex->getElements();

        return array_merge($this->collectElements($childs), $elements);
    }

    /**
     * @param Element[] $childs
     *
     * @return SoapElement[]
     */
    private function collectElements(array $childs): array
    {
        $elements = [];
        foreach ($childs as $child) {
            $name = $child->getAttribute('name');

            $elements[$name] = $this->createChildElement($child);
        }

        return $elements;
    }

    /**
     * @param Element $child
     *
     * @return SoapElement
     */
    private function createChildElement(Element $child): SoapElement
    {
        $min  = $child->getAttribute('minOccurs');
        $max  = $child->getAttribute('maxOccurs');
        $name = $child->getAttribute('name');
        $type = $child->getAttribute('type');

        $restrictions = $this->loadRestrictions($type);
        $element      = $this->getXsd()->findElementByNameInDeep($type);
        if ($element !== null && $element->isComplexType($complex)) {
            $node = new SoapNode($name, (int) $min, (int) $max, $restrictions);
            $node->setChildElements($this->collectElementsFrom($complex));

            return $node;
        }

        return new SoapElement($name, (int) $min, (int) $max, $restrictions);
    }

    /**
     * @param string $type
     *
     * @return RestrictionInterface[]
     */
    private function loadRestrictions(string $type): array
    {
        if (strpos($type, ':') === false) {
            return [];
        }

        $element = $this->getXsd()->findElementByNameInDeep($type);
        /** @var SimpleType $simple */
        if ($element !== null && $element->isSimpleType($simple)) {
            return $simple->getRestrictions();
        }

        return [];
    }
}
