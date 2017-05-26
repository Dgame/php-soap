<?php

namespace Dgame\Soap;

use Dgame\Soap\Visitor\ElementVisitorInterface;

/**
 * Class XmlElement
 * @package Dgame\Soap
 */
class XmlElement extends Element implements PrefixableInterface
{
    /**
     * @var null|string
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

        if ($prefix !== null) {
            $this->setPrefix($prefix);
        }
    }

    /**
     * @return bool
     */
    final public function hasPrefix(): bool
    {
        return !empty($this->prefix);
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
    public function setPrefix(string $prefix)
    {
        $this->prefix = trim($prefix);
    }

    /**
     * @return string
     */
    final public function getPrefixedName(): string
    {
        if ($this->hasPrefix()) {
            return sprintf('%s:%s', $this->getPrefix(), $this->getName());
        }

        return $this->getName();
    }

    /**
     * @param ElementVisitorInterface $visitor
     */
    public function accept(ElementVisitorInterface $visitor)
    {
        $visitor->visitXmlElement($this);
    }
}