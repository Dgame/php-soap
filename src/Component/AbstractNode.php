<?php

namespace Dgame\Soap\Component;

use Dgame\Optional\Optional;
use Dgame\Soap\Element\Element;
use Dgame\Soap\element\XmlElement;
use Dgame\Soap\Element\XmlNode;
use function Dgame\Wrapper\assoc;
use function Dgame\Wrapper\string;

abstract class AbstractNode extends XmlNode
{
    public function __construct()
    {
        $class = string(static::class)->namespaceInfo()->getClass();

        parent::__construct($class);
    }

    final public function findAllElementsByName(string $name): array
    {
        return assoc($this->getElements())->filter(function (Element $element) use ($name) {
            return $element->getName() === $name;
        })->get();
    }

    final public function findElementByName(string $name): Optional
    {
        $result = $this->findAllElementsByName($name);

        return assoc($result)->popFront();
    }

    final public function setXmlValue(string $name, string $value)
    {
        /** @var Element $element */
        if ($this->findElementByName($name)->isSome($element)) {
            $element->setValue($value);
        } else {
            $this->attachElement(new XmlElement($name, $value));
        }
    }
}