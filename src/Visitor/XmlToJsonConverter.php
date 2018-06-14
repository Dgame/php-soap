<?php

namespace Dgame\Soap\Visitor;

use Dgame\Soap\Element\ElementInterface;
use Dgame\Soap\Element\XmlElementInterface;
use Dgame\Soap\Element\XmlNodeInterface;
use function Dgame\Ensurance\ensure;

/**
 * Class XmlToJsonConverter
 * @package Dgame\Soap\Visitor
 */
final class XmlToJsonConverter implements ElementVisitorInterface
{
    /**
     * @var array
     */
    private $output = [];

    /**
     * @return array
     */
    public function getOutput(): array
    {
        return $this->output;
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    private static function convertValue($value)
    {
        if (is_numeric($value)) {
            if (strpos($value, '.') !== false || strpos($value, ',') !== false) {
                return filter_var($value, FILTER_VALIDATE_FLOAT);
            }

            return filter_var($value, FILTER_VALIDATE_INT);
        }

        if (in_array($value, ['true', 'false'])) {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }

        return $value;
    }

    /**
     * @param ElementInterface $element
     */
    public function visitElement(ElementInterface $element): void
    {
        $name  = $element->getName();
        $value = $element->getValue();

        $this->output[$name] = self::convertValue($value);
    }

    /**
     * @param XmlElementInterface $element
     */
    public function visitXmlElement(XmlElementInterface $element): void
    {
        $this->visitElement($element);
    }

    /**
     * @param XmlNodeInterface $node
     */
    public function visitXmlNode(XmlNodeInterface $node): void
    {
        $self = new self();
        foreach ($node->getElements() as $element) {
            $element->accept($self);
        }

        $this->appendNode($node->getName(), $self);
    }

    /**
     * @param string $name
     * @param self   $self
     */
    private function appendNode(string $name, self $self): void
    {
        if (array_key_exists($name, $this->output)) {
            $this->makeMultiArray($name);

            $this->output[$name][] = $self->getOutput();
        } else {
            $this->output[$name] = $self->getOutput();
        }
    }

    /**
     * @param string $name
     */
    private function makeMultiArray(string $name): void
    {
        if (is_array($this->output[$name])) {
            $key = key($this->output[$name]);
            if (!is_int($key)) {
                $output = $this->output[$name];

                $this->output[$name]   = [];
                $this->output[$name][] = $output;
            }
        } else {
            ensure($this->output[$name])->isEmpty();
            $this->output[$name] = [];
        }
    }
}