<?php

namespace Dgame\Soap\Element;

use Dgame\Soap\Attribute\XmlnsAttribute;
use Dgame\Soap\PrefixTrait;
use Dgame\Soap\Visitor\ElementVisitorInterface;

/**
 * Class XmlElement
 * @package Soap\Element
 */
class XmlElement extends Element implements XmlElementInterface
{
    use PrefixTrait;

    /**
     * XmlElement constructor.
     *
     * @param string      $name
     * @param mixed|null        $value
     * @param string|null $prefix
     */
    public function __construct(string $name, $value = null, string $prefix = null)
    {
        if (strpos($name, ':')) {
            assert(empty($prefix));

            list($prefix, $name) = explode(':', $name);
        }

        parent::__construct($name, $value);
        $this->setPrefix($prefix);
    }

    /**
     * @param ElementVisitorInterface $visitor
     */
    public function accept(ElementVisitorInterface $visitor): void
    {
        $visitor->visitXmlElement($this);
    }

    /**
     * @param string $prefix
     * @param string $uri
     */
    final public function setNamespaceAttribute(string $prefix, string $uri): void
    {
        $this->setPrefix($prefix);
        $this->setAttribute(new XmlnsAttribute($prefix, $uri));
    }
}
