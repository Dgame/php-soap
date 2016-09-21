<?php

namespace Dgame\Soap;

use Dgame\Soap\Visitor\AttributeVisitor;

/**
 * Class XmlnsAttribute
 * @package Dgame\Soap
 */
class XmlnsAttribute extends XmlAttribute
{
    /**
     * @var int
     */
    private $usage = 0;

    /**
     * XmlnsAttribute constructor.
     *
     * @param string $prefix
     * @param string $value
     */
    public function __construct(string $prefix, string $value)
    {
        parent::__construct('xmlns', $value, $prefix);
    }

    /**
     *
     */
    final public function increaseUsage()
    {
        $this->usage++;
    }

    /**
     * @return int
     */
    final public function getUsage(): int
    {
        return $this->usage;
    }

    /**
     * @return bool
     */
    public function isUsed(): bool
    {
        return $this->usage > 0;
    }

    /**
     * @param AttributeVisitor $visitor
     */
    public function accept(AttributeVisitor $visitor)
    {
        $visitor->visitXmlnsAttribute($this);
    }
}
