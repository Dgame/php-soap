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
     * @param string $class
     *
     * @return Hydrate
     */
    public static function new(string $class): self
    {
        return new self(new $class());
    }

    /**
     * @param Hydrate $hydrat
     *
     * @return bool
     */
    public function append(self $hydrat): bool
    {
        return $this->setValue($hydrat->getClassName(), $hydrat->getObject());
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
}