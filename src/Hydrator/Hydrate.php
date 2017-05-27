<?php

namespace Dgame\Soap\Hydrator;

use Dgame\Object\ObjectFacade;
use Dgame\Soap\AssignableInterface;

/**
 * Class Hydrate
 * @package Dgame\Soap\Hydrator
 */
final class Hydrate extends ObjectFacade
{
    /**
     * @var string
     */
    private $name;

    /**
     * Hydrate constructor.
     *
     * @param string $name
     * @param string $class
     */
    public function __construct(string $name, string $class)
    {
        parent::__construct(new $class());

        $this->name = $name;
    }

    /**
     * @param string $name
     * @param string $class
     *
     * @return Hydrate
     */
    public static function new(string $name, string $class): self
    {
        return new self($name, $class);
    }

    /**
     * @param Hydrate $hydrate
     *
     * @return bool
     */
    public function append(self $hydrate): bool
    {
        foreach ([$hydrate->getName(), $hydrate->getClassName()] as $name) {
            if ($this->setValue($name, $hydrate->getObject())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param AssignableInterface $assignable
     *
     * @return bool
     */
    public function assign(AssignableInterface $assignable): bool
    {
        if ($assignable->hasValue()) {
            return $this->setValue($assignable->getName(), $assignable->getValue());
        }

        return false;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->getReflection()->getShortName();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}