<?php

namespace Dgame\Soap;

/**
 * Class Element
 * @package Dgame\Soap
 */
use Dgame\Soap\Visitor\AttributeVisitor;
use Dgame\Soap\Visitor\DocumentAssembler;
use Dgame\Soap\Visitor\ElementVisitor;

/**
 * Class Element
 * @package Dgame\Soap
 */
class Element implements AttributeVisitor
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $value;
    /**
     * @var Attribute[]
     */
    private $attributes = [];

    /**
     * Element constructor.
     *
     * @param string|null $name
     * @param string|null $value
     */
    public function __construct(string $name = null, string $value = null)
    {
        $this->name  = $name ?? basename(str_replace('\\', '/', static::class));
        $this->value = $value;
    }

    /**
     * @return string
     */
    final public function getName() : string
    {
        return $this->name;
    }

    /**
     * @param string $value
     */
    final public function setValue(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    final public function getValue() : string
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    final public function hasValue() : bool
    {
        return !empty($this->value);
    }

    /**
     * @param Attribute $attribute
     */
    final public function setAttribute(Attribute $attribute)
    {
        $attribute->accept($this);

        $this->attributes[] = $attribute;
    }

    /**
     * @return Attribute[]
     */
    final public function getAttributes() : array
    {
        return $this->attributes;
    }

    /**
     * @return bool
     */
    final public function hasAttributes() : bool
    {
        return !empty($this->attributes);
    }

    /**
     * @return string
     */
    final public function getClassName() : string
    {
        static $class = null;
        if ($class === null) {
            $class = basename(str_replace('\\', '/', static::class));
        }

        return $class;
    }

    /**
     * @param ElementVisitor $visitor
     */
    public function accept(ElementVisitor $visitor)
    {
        $visitor->visitElement($this);
    }

    /**
     * @param Attribute $attribute
     */
    public function visitAttribute(Attribute $attribute)
    {
    }

    /**
     * @param XmlAttribute $attribute
     */
    public function visitXmlAttribute(XmlAttribute $attribute)
    {
    }

    /**
     * @param XmlnsAttribute $attribute
     */
    public function visitXmlnsAttribute(XmlnsAttribute $attribute)
    {
    }

    /**
     * @param DefaultXmlnsAttribute $attribute
     */
    public function visitDefaultXmlnsAttribute(DefaultXmlnsAttribute $attribute)
    {
    }

    /**
     * @param SoapAttribute $attribute
     */
    public function visitSoapAttribute(SoapAttribute $attribute)
    {
    }
}