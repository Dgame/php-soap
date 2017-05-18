<?php

namespace Dgame\Soap\Component;

use Dgame\Soap\XmlNode;

/**
 * Class NamedNode
 */
class NamedNode extends XmlNode
{
    /**
     * NamedNode constructor.
     *
     * @param string|null $prefix
     */
    public function __construct(string $prefix = null)
    {
        $name = string(static::class)->lastSegment('\\');
        parent::__construct($name, null, $prefix);
    }
}