<?php

namespace Dgame\Soap;

use Dgame\Soap\Visitor\ElementVisitorInterface;

/**
 * Class XmlElement
 * @package Dgame\Soap
 */
class XmlElement extends Element
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * XmlElement constructor.
     *
     * @param string      $name
     * @param string|null $value
     * @param string|null $prefix
     */
    public function __construct(string $name, string $value = null, string $prefix = null)
    {
        parent::__construct($name, $value);

        $this->setPrefix($prefix ?? '');
    }

    /**
     * @return string
     */
    final public function getPrefixedName(): string
    {
        if ($this->hasPrefix()) {
            return sprintf('%s:%s', $this->prefix, $this->getName());
        }

        return $this->getName();
    }

    /**
     * @param string $prefix
     */
    public function setPrefix(string $prefix)
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
     * @return string
     */
    final public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @param ElementVisitorInterface $visitor
     */
    public function accept(ElementVisitorInterface $visitor)
    {
        $visitor->visitXmlElement($this);
    }
}