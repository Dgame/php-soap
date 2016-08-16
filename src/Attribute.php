<?php

namespace Dgame\Soap;

/**
 * Class Attribute
 * @package Dgame\Soap
 */
final class Attribute
{
    /**
     * @var string
     */
    private $name = '';
    /**
     * @var string
     */
    private $value = '';

    use NamespaceTrait;

    /**
     * Attribute constructor.
     *
     * @param string $name
     * @param string $value
     */
    public function __construct(string $name, string $value)
    {
        $this->name  = $name;
        $this->value = $value;
    }

    /**
     * @param array $change
     */
    public function onNamespaceChange(array $change)
    {
        if ($this->name === $change['old'] && !empty($change['new'])) {
            $this->name = $change['new'];
        }
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getIdentifier() : string
    {
        if ($this->hasNamespace()) {
            return sprintf('%s:%s', $this->getNamespace(), $this->name);
        }

        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue() : string
    {
        return $this->value;
    }
}