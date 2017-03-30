<?php

namespace Dgame\Soap\Hydrator;

use Dgame\Soap\AssignableInterface;

/**
 * Class Hydratable
 * @package Dgame\Soap\Hydrator
 */
class Hydratable implements HydratableInterface
{
    /**
     * @param AssignableInterface $assignable
     */
    public function assign(AssignableInterface $assignable)
    {
        if ($assignable->hasValue()) {
            $this->assignValue($assignable->getName(), $assignable->getValue());
        }
    }

    /**
     * @param HydratableInterface $hydratable
     *
     * @return bool
     */
    public function append(HydratableInterface $hydratable): bool
    {
        return Method::of($hydratable->getClassName(), $this)->assign($hydratable);
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function assignValue(string $name, string $value)
    {
        if (!Method::of($name, $this)->assign($value) && property_exists($this, $name)) {
            $this->{$name} = $value;
        }
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        static $name = null;
        if ($name === null) {
            $name = string(static::class)->lastSegment('\\');
        }

        return $name;
    }
}