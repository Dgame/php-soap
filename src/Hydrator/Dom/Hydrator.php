<?php

namespace Dgame\Soap\Hydrator\Dom;

use Dgame\Soap\Dom\Translator;
use Dgame\Soap\Element;
use Dgame\Soap\Hydrator\ClassMapper;
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
        $translator = new Translator();
        $elements   = $translator->translateDocument($document);

        $output = [];
        foreach ($elements as $element) {
            $hydrate = $this->hydrate($element);
            if ($hydrate->isValid()) {
                $output[] = $hydrate->getHydratable();
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
        $translator = new Translator();
        $element    = $translator->translateNode($node);

        return $this->hydrate($element);
    }

    /**
     * @param AssemblableInterface $assemblable
     * @param DOMNode|null         $node
     *
     * @return DOMNode
     */
    public function assemble(AssemblableInterface $assemblable, DOMNode $node = null): DOMNode
    {
        return $this->dehydrate($assemblable->assemble(), $node);
    }

    /**
     * @param Element      $element
     * @param DOMNode|null $node
     *
     * @return DOMNode
     */
    public function dehydrate(Element $element, DOMNode $node = null): DOMNode
    {
        $node      = $node ?? new DOMDocument('1.0', 'utf-8');
        $assembler = new Assembler($node);

        $element->accept($assembler);

        return $node;
    }

    /**
     * @param Element $element
     *
     * @return HydrateProcedure
     */
    private function hydrate(Element $element): HydrateProcedure
    {
        $hydrate = new HydrateProcedure($this->mapper);
        $element->accept($hydrate);

        return $hydrate;
    }
}