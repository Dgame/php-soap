<?php

namespace Dgame\Soap\Hydrator\Dom;

use Dgame\Soap\Dom\Translator;
use Dgame\Soap\Element;
use Dgame\Soap\Hydrator\ClassMapper;
use Dgame\Soap\Hydrator\Hydrate;
use Dgame\Soap\Hydrator\HydrateProcedure;
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
     * @return array
     */
    public function hydrateDocument(DOMDocument $document): array
    {
        $elements = Translator::new()->translateDocument($document);
        $output   = [];
        foreach ($elements as $element) {
            $procedure = $this->hydrate($element);
            if ($procedure->isValid()) {
                $output[] = $procedure->getHydrate()->getObject();
            }
        }

        return $output;
    }

    /**
     * @param DOMNode $node
     *
     * @return HydrateProcedure
     */
    public function hydrateNode(DOMNode $node): HydrateProcedure
    {
        $element = Translator::new()->translateNode($node);
        if ($element === null) {
            return new HydrateProcedure($this->mapper);
        }

        return $this->hydrate($element);
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