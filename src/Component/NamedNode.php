<?php

namespace Dgame\Soap\Component;

use Dgame\Soap\XmlNode;
use function Dgame\Iterator\separate;

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