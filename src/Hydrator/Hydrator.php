<?php

namespace Dgame\Soap\Hydrator;

use Dgame\Soap\Element;
use DOMDocument;
use DOMNode;

/**
 * Class Hydrator
 * @package Dgame\Soap\Hydrator
 */
final class Hydrator
{
    /**
     * @var ClassMapper
     */
    private $mapper;

    /**
     * Hydrator constructor.
     *
     * @param ClassMapper $mapper
     */
    public function __construct(ClassMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @return ClassMapper
     */
    public function getClassMapper(): ClassMapper
    {
        return $this->mapper;
    }

    /**
     * @param DOMNode $node
     *
     * @return object|null
     */
    public function hydrate(DOMNode $node)
    {
        $element = Translator::new()->translate($node);
        if ($element !== null) {
            $hydration = new ElementHydration($this->mapper);
            $element->accept($hydration);

            return $hydration->isHydrated() ? $hydration->getHydrate()->getFacade()->getObject() : null;
        }

        return null;
    }

    /**
     * @param AssemblableInterface $assemblable
     *
     * @return DOMDocument
     */
    public function assemble(AssemblableInterface $assemblable): DOMDocument
    {
        return $this->dehydrate($assemblable->assemble());
    }

    /**
     * @param Element $element
     *
     * @return DOMDocument
     */
    public function dehydrate(Element $element): DOMDocument
    {
        $assembler = new Assembler();
        $element->accept($assembler);

        return $assembler->getDocument();
    }
}