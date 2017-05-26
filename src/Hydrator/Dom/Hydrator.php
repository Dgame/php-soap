<?php

namespace Dgame\Soap\Hydrator\Dom;

use Dgame\Soap\Dom\Translator;
use Dgame\Soap\Element;
use Dgame\Soap\Hydrator\ClassMapper;
use Dgame\Soap\Hydrator\HydrateProcedure;
use Dgame\Soap\XmlElement;
use DOMDocument;
use DOMNode;

/**
 * Class Hydrator
 * @package Dgame\Soap\Hydrator\Dom
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
     * @param DOMDocument $document
     *
     * @return null|object
     */
    public function hydrateDocument(DOMDocument $document)
    {
        $element = Translator::new()->translateDocument($document);
        if ($element !== null) {
            return $this->hydrateElement($element);
        }

        return null;
    }

    /**
     * @param DOMNode $node
     *
     * @return null|object
     */
    public function hydrateNode(DOMNode $node)
    {
        $element = Translator::new()->translateNode($node);
        if ($element !== null) {
            return $this->hydrateElement($element);
        }

        return null;
    }

    /**
     * @param XmlElement $element
     *
     * @return null|object
     */
    private function hydrateElement(XmlElement $element)
    {
        $procedure = $this->hydrate($element);
        if ($procedure->isValid()) {
            return $procedure->getHydrate()->getObject();
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

    /**
     * @param Element $element
     *
     * @return HydrateProcedure
     */
    private function hydrate(Element $element): HydrateProcedure
    {
        $procedure = new HydrateProcedure($this->mapper);
        $element->accept($procedure);

        return $procedure;
    }
}