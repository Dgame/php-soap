<?php

namespace Dgame\Soap\Attribute;

use Dgame\Soap\Visitor\AttributeVisitorInterface;

/**
 * Class XmlnsAttribute
 * @package Soap\Attribute
 */
class XmlnsAttribute extends XmlAttribute
{
    /**
     * XmlnsAttribute constructor.
     *
     * @param string $name
     * @param string $value
     */
    public function __construct(string $name, string $value)
    {
        parent::__construct($name, $value);

        $this->setPrefix('xmlns');
    }

    /**
     * @param string $uri
     *
     * @return XmlnsAttribute
     */
    public static function fromUri(string $uri): self
    {
        $name = basename($uri);
        $len  = strlen($name);
        $len  = min($len, 4);

        return new self(substr($name, 0, $len), $uri);
    }

    /**
     * @param AttributeVisitorInterface $visitor
     */
    public function accept(AttributeVisitorInterface $visitor): void
    {
        $visitor->visitXmlnsAttribute($this);
    }
}
