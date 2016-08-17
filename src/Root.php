<?php

namespace Dgame\Soap;

use Dgame\Soap\Visitor\AssemblerVisitor;
use DOMDocument;

/**
 * Class Root
 * @package Dgame\Soap
 */
class Root extends Node
{
    /**
     * @param DOMDocument $document
     */
    public function assemble(DOMDocument $document)
    {
        $assembler = new AssemblerVisitor($document);
        $this->accept($assembler);
    }
}