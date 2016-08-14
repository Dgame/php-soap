<?php

namespace Dgame\Soap;

use DOMDocument;

/**
 * Class Root
 * @package Dgame\Soap
 */
class Root extends Node
{
    /**
     * @param string $version
     * @param string $encoding
     *
     * @return DOMDocument
     */
    public function assemble(string $version = '1.0', string $encoding = 'utf-8') : DOMDocument
    {
        $document               = new DOMDocument($version, $encoding);
        $document->formatOutput = true;

        $root = $this->createBy($document);
        $document->appendChild($root);

        $this->assembleAttributesIn($root);
        $this->assembleChildrenIn($root);

        return $document;
    }
}