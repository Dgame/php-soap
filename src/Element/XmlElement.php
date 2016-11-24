<?php

namespace Dgame\Soap\element;

use Dgame\Soap\PrefixableInterface;
use Dgame\Soap\PrefixTrait;
use Dgame\Soap\Visitor\ElementVisitorInterface;

class XmlElement extends Element implements PrefixableInterface
{
    use PrefixTrait;

    public function __construct(string $name, string $value = null, string $prefix = null)
    {
        parent::__construct($name, $value);

        $this->prefix = $prefix;
    }

    final public function getNamespace(): string
    {
        if ($this->hasPrefix()) {
            return sprintf('%s:%s', $this->getPrefix(), $this->getName());
        }

        return $this->getName();
    }

    final public function inheritPrefix(PrefixableInterface $prefixable)
    {
        if (!$this->hasPrefix() && $prefixable->hasPrefix()) {
            $this->prefix = $prefixable->getPrefix();
            $prefixable->prefixUsedBy($this);
        }
    }

    public function accept(ElementVisitorInterface $visitor)
    {
        $visitor->visitXmlElement($this);
    }

    public function prefixUsedBy(Element $element)
    {
    }
}