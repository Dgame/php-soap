<?php

namespace Dgame\Soap;

/**
 * Class PrefixedTag
 * @package Dgame\Soap
 */
class XmlTag extends Tag
{
    /**
     * @var null|string
     */
    protected $prefix = null;

    /**
     * PrefixedTag constructor.
     *
     * @param string      $name
     * @param string|null $prefix
     */
    public function __construct(string $name, string $prefix = null)
    {
        parent::__construct($name);

        $this->prefix = $prefix;
    }

    /**
     * @return bool
     */
    final public function hasPrefix() : bool
    {
        return !empty($this->prefix);
    }

    /**
     * @return null|string
     */
    final public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param Node $parent
     */
    public function inheritPrefix(Node $parent)
    {
        if ($parent->hasPrefix() && !$this->hasPrefix()) {
            $this->prefix = $parent->getPrefix();
        }
    }

    /**
     * @return string
     */
    final public function getNamespace() : string
    {
        if ($this->hasPrefix()) {
            return sprintf('%s:%s', $this->prefix, $this->getName());
        }

        return $this->getName();
    }
}