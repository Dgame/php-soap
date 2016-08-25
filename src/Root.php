<?php

namespace Dgame\Soap;

use Dgame\Soap\Visitor\DocumentAssembler;
use DOMDocument;

/**
 * Class Root
 * @package Dgame\Soap
 */
class Root extends XmlNode
{
    /**
     * @param DOMDocument $document
     */
    public function assemble(DOMDocument $document)
    {
        $assembler = new DocumentAssembler($document);
        $this->accept($assembler);
    }
}