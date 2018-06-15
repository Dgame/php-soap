<?php

namespace Dgame\Soap\Importer;

use Closure;

/**
 * Class ImportBindingDelegate
 * @package Dgame\Soap\Importer
 */
final class ImportBindingDelegate implements BindingInterface
{
    /**
     * @var bool
     */
    private $bound = false;
    /**
     * @var Closure
     */
    private $closure;

    /**
     * ImportBinding constructor.
     *
     * @param Closure $closure
     */
    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    /**
     * @return bool
     */
    public function isBound(): bool
    {
        return $this->bound;
    }

    /**
     * @param mixed $value
     */
    public function __invoke($value): void
    {
        $closure = $this->closure;
        $closure($value);
        $this->bound = true;
    }
}
