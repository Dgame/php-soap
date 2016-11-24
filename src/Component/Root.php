<?php

namespace Dgame\Soap\Component;

use Dgame\Soap\Visitor\DocumentAssembler;
use DOMDocument;

/**
 * Class Root
 * @package Dgame\Soap
 */
abstract class Root extends AbstractNode
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