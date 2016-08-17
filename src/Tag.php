<?php

namespace Dgame\Soap;

/**
 * Class Tag
 * @package Dgame\Soap
 */
class Tag
{
    /**
     * @var null|string
     */
    private $name = null;
    /**
     * @var AttributeInterface[]
     */
    private $attributes = [];

    /**
     * Tag constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    final public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return AttributeInterface[]
     */
    final public function getAttributes() : array
    {
        return $this->attributes;
    }

    /**
     * @param AttributeInterface $attribute
     */
    public function appendAttribute(AttributeInterface $attribute)
    {
        $this->attributes[] = $attribute;
    }
}