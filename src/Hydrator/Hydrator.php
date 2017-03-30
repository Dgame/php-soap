<?php

namespace Dgame\Soap\Hydrator;

use Dgame\Soap\Element;
use Dgame\Soap\XmlTranslator;
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
     * @param DOMDocument $document
     *
     * @return array
     */
    public function hydrateDocument(DOMDocument $document): array
    {
        $translator = new XmlTranslator();
        $elements   = $translator->translateDocument($document);

        $output = [];
        foreach ($elements as $element) {
            $hydrat = $this->hydrate($element);
            if ($hydrat->isValid()) {
                $output[] = $hydrat->getHydratable();
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
        $translator = new XmlTranslator();
        $element    = $translator->translateNode($node);

        return $this->hydrate($element);
    }

    /**
     * @param Element $element
     *
     * @return HydrateProcedure
     */
    private function hydrate(Element $element): HydrateProcedure
    {
        $hydrat = new HydrateProcedure($this->mapper);
        $element->hydration($hydrat);

        return $hydrat;
    }
}