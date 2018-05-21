<?php

namespace Dgame\Soap\Wsdl\Elements;

use Dgame\Soap\Wsdl\Elements\Restriction\RestrictionFactory;
use Dgame\Soap\Wsdl\Elements\Restriction\RestrictionInterface;
use DOMElement;

/**
 * Class SimpleType
 * @package Dgame\Soap\Wsdl\Elements
 */
final class SimpleType
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var DOMElement
     */
    private $element;

    /**
     * SimpleType constructor.
     *
     * @param DOMElement $element
     */
    public function __construct(DOMElement $element)
    {
        $this->name = $element->getAttribute('name');
        $this->element = $element;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return DOMElement
     */
    public function getElement(): DOMElement
    {
        return $this->element;
    }

    /**
     * @return RestrictionInterface
     */
    public function getFirstRestriction(): RestrictionInterface
    {
        $restrictions = $this->getRestrictions();

        return reset($restrictions);
    }

    /**
     * @return RestrictionInterface[]
     */
    public function getRestrictions(): array
    {
        $nodes = $this->element->getElementsByTagName('restriction');

        $restrictions = [];
        for ($i = 0, $c = $nodes->length; $i < $c; $i++) {
            $restrictions[] = RestrictionFactory::createFrom($nodes->item($i));
        }

        return $restrictions;
    }
}
