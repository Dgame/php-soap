<?php

namespace Dgame\Soap\Component;

use function Dgame\Iterator\separate;
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
        parent::__construct(separate(static::class, '\\')->last(), null, $prefix);
    }
}