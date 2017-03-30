<?php

namespace Dgame\Soap\Attribute;

use Dgame\Soap\Hydrator\Attribute\AttributeHydratorInterface;

/**
 * Class XmlAttribute
 * @package Dgame\Soap\Attribute
 */
class XmlAttribute extends Attribute
{
    /**
     * @var null|string
     */
    private $prefix;

    /**
     * XmlAttribute constructor.
     *
     * @param string      $name
     * @param string|null $value
     * @param string|null $prefix
     */
    public function __construct(string $name, string $value = null, string $prefix = null)
    {
        parent::__construct($name, $value);

        $this->prefix = $prefix;
    }

    /**
     * @return null|string
     */
    final public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     */
    final public function setPrefix(string $prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @param AttributeHydratorInterface $hydrator
     */
    public function hydration(AttributeHydratorInterface $hydrator)
    {
        $hydrator->visitXmlAttribute($this);
    }
}