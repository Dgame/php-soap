<?php

namespace Dgame\Soap\Attribute;

use Dgame\Soap\PrefixTrait;
use Dgame\Soap\Visitor\AttributeVisitorInterface;

/**
 * Class XmlAttribute
 * @package Soap\Attribute
 */
class XmlAttribute extends Attribute implements XmlAttributeInterface
{
    use PrefixTrait;

    /**
     * @var int
     */
    private $prefixUsage = 0;

    /**
     * XmlAttribute constructor.
     *
     * @param string $name
     * @param mixed $value
     */
    public function __construct(string $name, $value)
    {
        parent::__construct($name, $value);
    }

    /**
     * @return int
     */
    public function getPrefixUsage(): int
    {
        return $this->prefixUsage;
    }

    /**
     * @return bool
     */
    public function isPrefixUsed(): bool
    {
        return $this->prefixUsage > 0;
    }

    /**
     *
     */
    public function incrementPrefixUsage(): void
    {
        $this->prefixUsage += 1;
    }

    /**
     * @param AttributeVisitorInterface $visitor
     */
    public function accept(AttributeVisitorInterface $visitor): void
    {
        $visitor->visitXmlAttribute($this);
    }
}
