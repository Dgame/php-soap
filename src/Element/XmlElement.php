<?php

namespace Dgame\Soap\Element;

use Dgame\Soap\PrefixableInterface;
use Dgame\Soap\PrefixTrait;
use Dgame\Soap\Visitor\ElementVisitorInterface;

/**
 * Class XmlElement
 * @package Dgame\Soap\element
 */
class XmlElement extends Element implements PrefixableInterface
{
    use PrefixTrait;

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

        $this->prefix = $prefix;
    }

    /**
     * @return string
     */
    final public function getNamespace(): string
    {
        if ($this->hasPrefix()) {
            return sprintf('%s:%s', $this->getPrefix(), $this->getName());
        }

        return $this->getName();
    }

    /**
     * @param PrefixableInterface $prefixable
     */
    final public function inheritPrefix(PrefixableInterface $prefixable)
    {
        if (!$this->hasPrefix() && $prefixable->hasPrefix()) {
            $this->prefix = $prefixable->getPrefix();
            $prefixable->prefixUsedBy($this);
        }
    }

    /**
     * @param ElementVisitorInterface $visitor
     */
    public function accept(ElementVisitorInterface $visitor)
    {
        $visitor->visitXmlElement($this);
    }

    /**
     * @param Element $element
     */
    public function prefixUsedBy(Element $element)
    {
    }
}