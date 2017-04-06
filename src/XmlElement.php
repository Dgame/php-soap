<?php

namespace Dgame\Soap;

use Dgame\Soap\Hydrator\VisitorInterface;

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
     * @return string
     */
    final public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @return bool
     */
    final public function hasPrefix(): bool
    {
        return $this->prefix !== null;
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
     * @param VisitorInterface $visitor
     */
    public function accept(VisitorInterface $visitor)
    {
        $visitor->visitXmlElement($this);
    }
}