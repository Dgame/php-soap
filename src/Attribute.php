<?php

namespace Dgame\Soap;

use Dgame\Soap\Visitor\AttributeAssembler;
use Dgame\Soap\Visitor\AttributeVisitor;

/**
 * Class Attribute
 * @package Dgame\Soap
 */
class Attribute
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $value;

    /**
     * Attribute constructor.
     *
     * @param string $name
     * @param string $value
     */
    public function __construct(string $name, string $value)
    {
        $this->name  = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    final public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    final public function getValue() : string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    final public function getClassName() : string
    {
        static $class = null;
        if ($class === null) {
            $class = basename(str_replace('\\', '/', static::class));
        }

        return $class;
    }

    /**
     * @param AttributeVisitor $visitor
     */
    public function accept(AttributeVisitor $visitor)
    {
        $visitor->visitAttribute($this);
    }
}