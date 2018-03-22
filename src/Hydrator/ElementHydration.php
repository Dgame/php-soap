<?php

namespace Dgame\Soap\Hydrator;

use Dgame\Soap\Element;
use Dgame\Soap\Visitor\ElementVisitorInterface;
use Dgame\Soap\XmlElement;
use Dgame\Soap\XmlNode;
use Monolog\Registry;

/**
 * Class Hydrate
 * @package Dgame\Soap\Hydrator
 */
final class ElementHydration implements ElementVisitorInterface
{
    /**
     * @var ClassMapper
     */
    private $mapper;
    /**
     * @var Hydrate
     */
    private $hydrate;

    /**
     * ElementHydration constructor.
     *
     * @param ClassMapper $mapper
     */
    public function __construct(ClassMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @return Hydrate
     */
    public function getHydrate(): Hydrate
    {
        return $this->hydrate;
    }

    /**
     * @return bool
     */
    public function isHydrated(): bool
    {
        return $this->hydrate !== null && $this->hydrate->hasFacade();
    }

    /**
     * @param Element $element
     */
    public function visitElement(Element $element): void
    {
        if (!$this->hydrate($element)) {
            $this->assign($element);
        }
    }

    /**
     * @param XmlElement $element
     */
    public function visitXmlElement(XmlElement $element): void
    {
        if (!$this->hydrate($element)) {
            $this->assign($element);
        }
    }

    /**
     * @param XmlNode $node
     */
    public function visitXmlNode(XmlNode $node): void
    {
        if (!$this->hydrate($node)) {
            $this->assign($node);
        }

        foreach ($node->getElements() as $element) {
            $this->hydrateChild($element);
        }
    }

    /**
     * @param Element $element
     */
    private function hydrateChild(Element $element): void
    {
        $hydration = new self($this->mapper);
        $element->accept($hydration);

        if ($hydration->isHydrated()) {
            $this->append($hydration->getHydrate());
        } else {
            $this->assign($element);
        }
    }

    /**
     * @param Element $element
     */
    private function assign(Element $element): void
    {
        if ($this->isHydrated() && $element->hasValue()) {
            $this->hydrate->assign($element->getName(), $element->getValue());
        } else {
            Registry::getInstance(Hydrator::class)->warning(
                'Could neither hydrate or assign {name}',
                ['name' => $element->getName()]
            );
        }
    }

    /**
     * @param Element $element
     *
     * @return bool
     */
    private function hydrate(Element $element): bool
    {
        $hydrate = new Hydrate($this->mapper, $element);
        if ($hydrate->hasFacade()) {
            $hydration = new AttributeHydration($hydrate);
            $hydration->hydrate($element);

            $this->append($hydrate);

            return true;
        }

        return false;
    }

    /**
     * @param Hydrate $hydrate
     */
    private function append(Hydrate $hydrate): void
    {
        if ($this->isHydrated()) {
            $this->hydrate->append($hydrate);
        } else {
            $this->hydrate = $hydrate;
        }
    }
}