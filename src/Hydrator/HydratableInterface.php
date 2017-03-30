<?php

namespace Dgame\Soap\Hydrator;

use Dgame\Soap\AssignableInterface;

/**
 * Interface HydratInterface
 * @package Dgame\Soap\Hydrator
 */
interface HydratableInterface
{
    /**
     * @param AssignableInterface $assignable
     */
    public function assign(AssignableInterface $assignable);

    /**
     * @param HydratableInterface $hydratable
     *
     * @return bool
     */
    public function append(self $hydratable): bool;

    /**
     * @param string $name
     * @param string $value
     */
    public function assignValue(string $name, string $value);

    /**
     * @return string
     */
    public function getClassName(): string;
}