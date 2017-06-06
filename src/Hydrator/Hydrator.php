<?php

namespace Dgame\Soap\Hydrator;

use Dgame\Soap\Element;
use Dgame\Soap\XmlElement;
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
     * @var array
     */
    private $warnings = [];

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
     * @return array
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    /**
     * @return bool
     */
    public function hasWarnings(): bool
    {
        return !empty($this->warnings);
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

        $this->warnings[] = 'Could not hydrate document';

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

        $this->warnings[] = 'Could not translate: ' . $node->nodeName;

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

        $this->warnings[] = 'Could not hydrate: ' . $element->getName();

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

        if ($procedure->hasWarnings()) {
            $this->warnings[] = $procedure->getWarnings();
        }

        return $procedure;
    }
}