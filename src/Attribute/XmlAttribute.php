<?php

namespace Dgame\Soap\Attribute;

use Dgame\Soap\Hydrator\VisitorInterface;
use Dgame\Soap\PrefixableInterface;

/**
 * Class XmlAttribute
 * @package Dgame\Soap\Attribute
 */
class XmlAttribute extends Attribute implements PrefixableInterface
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

        if ($prefix !== null) {
            $this->setPrefix($prefix);
        }
    }

    /**
     * @return string
     */
    final public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     */
    final public function setPrefix(string $prefix)
    {
        $prefix = trim($prefix);
        if (strlen($prefix) !== 0) {
            $this->prefix = $prefix;
        }
    }

    /**
     * @return bool
     */
    final public function hasPrefix(): bool
    {
        return $this->prefix !== null;
    }

    /**
     * @param VisitorInterface $visitor
     */
    public function accept(VisitorInterface $visitor)
    {
        $visitor->visitXmlAttribute($this);
    }
}