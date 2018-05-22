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
     * @var Xsd
     */
    private $xsd;
    /**
     * @var string
     */
    private $operation;
    /**
     * @var SoapElement[]
     */
    private $elements = [];

    /**
     * SoapOperation constructor.
     *
     * @param Xsd    $xsd
     * @param string $operation
     */
    public function __construct(Xsd $xsd, string $operation)
    {
        $this->xsd       = $xsd;
        $this->operation = $operation;
    }

    /**
     * @return Xsd
     */
    public function getXsd(): Xsd
    {
        return $this->xsd;
    }

    /**
     * @return string
     */
    public function getOperation(): string
    {
        return $this->operation;
    }

    /**
     * @return SoapElement[]
     */
    public function getElements(): array
    {
        if (!empty($this->elements)) {
            return $this->elements;
        }

        $this->elements = [];

        $element = $this->xsd->getOneElementByName($this->operation);
        $complex = $element->getComplexType();

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
        $parent  = $this->xsd->findElementByNameInDeep($name);
        $complex = $parent->getComplexType();

        enforce($complex !== null)->orThrow('Can only collect elements from a ComplexType');

        $elements = array_merge(
            $this->collectExtensionElements($extension),
            $this->collectElementsFrom($complex)
        );

        $prefix = $extension->getPrefix();
        $uri    = $this->xsd->getUriByPrefix($prefix);

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
        $element      = $this->xsd->findElementByNameInDeep($type);
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

        $element = $this->xsd->findElementByNameInDeep($type);
        /** @var SimpleType $simple */
        if ($element !== null && $element->isSimpleType($simple)) {
            return $simple->getRestrictions();
        }

        return [];
    }
}
