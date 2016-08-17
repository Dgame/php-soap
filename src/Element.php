<?php

namespace Dgame\Soap;

use Dgame\Soap\Visitor\NodeVisitorInterface;

/**
 * Class Element
 * @package Dgame\Soap
 */
class Element extends Node
{
    /**
     * @var null|string
     */
    private $value = null;

    /**
     * Element constructor.
     *
     * @param string|null $name
     * @param string|null $value
     * @param string|null $prefix
     */
    public function __construct(string $name = null, string $value = null, string $prefix = null)
    {
        parent::__construct($name, $prefix);

        $this->value = $value;
    }

    /**
     * @return bool
     */
    final public function hasValue() : bool
    {
        return !empty($this->value);
    }

    /**
     * @return string
     */
    final public function getValue() : string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    final public function setValue(string $value)
    {
        $this->value = $value;
    }

    /**
     * @param NodeVisitorInterface $visitor
     */
    public function accept(NodeVisitorInterface $visitor)
    {
        $visitor->visitElement($this);
    }
}