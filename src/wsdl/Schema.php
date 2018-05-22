<?php

namespace Dgame\Soap\wsdl;

use DOMDocument;
use DOMElement;
use function Dgame\Ensurance\enforce;

/**
 * Class Schema
 * @package Dgame\Soap\wsdl
 */
final class Schema extends Xsd
{
    /**
     * Schema constructor.
     *
     * @param DOMDocument $document
     */
    public function __construct(DOMDocument $document)
    {
        $nodes = $document->getElementsByTagNameNS(parent::W3_SCHEMA, 'schema');
        enforce($nodes->length !== 0)->orThrow('There is no Schema');
        enforce($nodes->length === 1)->orThrow('There are multiple Schemas');

        $location = $this->getSchemaLocation($nodes->item(0));
        enforce($location !== null)->orThrow('No location was found');

        parent::__construct($location);
    }

    /**
     * @param DOMElement $element
     *
     * @return null|string
     */
    private function getSchemaLocation(DOMElement $element): ?string
    {
        foreach (['import', 'include'] as $tag) {
            $location = $this->getLocationByTagName($element, $tag);
            if (!empty($location)) {
                return $location;
            }
        }

        return null;
    }

    /**
     * @param DOMElement $element
     * @param string     $tag
     *
     * @return null|string
     */
    private function getLocationByTagName(DOMElement $element, string $tag): ?string
    {
        $nodes = $element->getElementsByTagName($tag);
        if ($nodes->length === 0) {
            return null;
        }

        enforce($nodes->length === 1)->orThrow('There are multiple schemas');
        $location = $nodes->item(0)->getAttribute('schemaLocation');

        return $location;
    }
}
