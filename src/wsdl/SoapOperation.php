<?php

namespace Dgame\Soap\Wsdl;

use Dgame\Soap\Wsdl\Elements\ComplexType;
use Dgame\Soap\Wsdl\Elements\Extension;

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
     * @return array
     */
    public function getElements(): array
    {
        $extension = $this->loadExtension();
        $parent    = $this->loadParent($extension);

        $elements = array_merge(
            $this->collectExtensionElements($extension),
            $this->collectParentElements($parent)
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
        $elements = [];
        foreach ($extension->getElements() as $element) {
            $min  = $element->getAttribute('minOccurs');
            $max  = $element->getAttribute('maxOccurs');
            $name = $element->getAttribute('name');
            $type = $element->getAttribute('type');

            $restrictions = $this->loadRestrictions($type);

            $elements[$name] = new SoapElement($name, (int) $min, (int) $max, $restrictions);
        }

        return $elements;
    }

    /**
     * @param ComplexType $parent
     *
     * @return SoapElement[]
     */
    private function collectParentElements(ComplexType $parent): array
    {
        $elements = [];
        foreach ($parent->getElements() as $element) {
            $min  = $element->getAttribute('minOccurs');
            $max  = $element->getAttribute('maxOccurs');
            $name = $element->getAttribute('name');
            $type = $element->getAttribute('type');

            $restrictions = $this->loadRestrictions($type);

            $elements[$name] = new SoapElement($name, (int) $min, (int) $max, $restrictions);
        }

        return $elements;
    }

    /**
     * @param string $type
     *
     * @return array
     */
    private function loadRestrictions(string $type): array
    {
        if (strpos($type, ':') === false) {
            return [];
        }

        [$prefix, $type] = explode(':', $type);
        $types           = $this->xsd->getXsdByPrefix($prefix);

        return $types->getSimpleTypeByName($type)->getRestrictions();
    }

    /**
     * @return Extension
     */
    private function loadExtension(): Extension
    {
        $outer   = $this->xsd->getElementByName($this->operation);
        $inner   = $outer->getOneElementByName('element');
        $type    = $inner->getAttribute('type');
        $complex = $this->xsd->getComplexTypeByName($type);

        return $complex->getExtension();
    }

    /**
     * @param Extension $extension
     *
     * @return ComplexType
     */
    private function loadParent(Extension $extension): ComplexType
    {
        $base   = $extension->getBase();
        $prefix = $extension->getPrefix();

        return $this->xsd->getXsdByPrefix($prefix)->getComplexTypeByName($base);
    }
}
