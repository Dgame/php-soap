<?php

namespace Dgame\Soap\Hydrator;

use Dgame\Object\ObjectFacade;
use Dgame\Soap\AssignableInterface;
use ICanBoogie\Inflector;

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
        return $this->assignValue($hydrat->getClassName(), $hydrat->getObject());
    }

    /**
     * @param AssignableInterface $assignable
     *
     * @return bool
     */
    public function assign(AssignableInterface $assignable): bool
    {
        if ($assignable->hasValue()) {
            return $this->assignValue($assignable->getName(), $assignable->getValue());
        }

        return false;
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return bool
     */
    public function assignValue(string $name, $value): bool
    {
        $names = [
            Inflector::get()->camelize($name, Inflector::DOWNCASE_FIRST_LETTER),
            Inflector::get()->camelize($name, Inflector::UPCASE_FIRST_LETTER)
        ];

        foreach ($names as $name) {
            if ($this->setValueByMethod($name, $value) || $this->setValueByProperty($name, $value)) {
                return true;
            }
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