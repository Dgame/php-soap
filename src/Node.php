<?php

namespace Dgame\Soap;

use Dgame\Soap\Visitor\AttributeVisitorInterface;
use Dgame\Soap\Visitor\NodeVisitorInterface;

/**
 * Class Node
 * @package Dgame\Soap
 */
class Node extends XmlTag implements AttributeVisitorInterface
{
    /**
     * @var array
     */
    private $children = [];

    /**
     * Element constructor.
     *
     * @param string|null $name
     * @param string|null $prefix
     */
    public function __construct(string $name = null, string $prefix = null)
    {
        $name = $name ?? basename(str_replace('\\', '/', static::class));
        parent::__construct($name, $prefix);
    }

    /**
     * @param Node $parent
     */
    public function inheritPrefix(Node $parent)
    {
        if ($parent->hasPrefix() && !$this->hasPrefix()) {
            $this->prefix = $parent->getPrefix();

            foreach ($this->children as $child) {
                $child->inheritPrefix($this);
            }
        }
    }

    /**
     * @param Node $child
     */
    final public function appendChild(Node $child)
    {
        $child->inheritPrefix($this);

        $this->children[] = $child;
    }

    /**
     * @return Node[]
     */
    final public function getChildren() : array
    {
        return $this->children;
    }

    /**
     * @param AttributeInterface $attribute
     */
    public function appendAttribute(AttributeInterface $attribute)
    {
        $attribute->accept($this);
    }

    /**
     * @param Attribute $attribute
     */
    public function visitAttribute(Attribute $attribute)
    {
        parent::appendAttribute($attribute);
    }

    /**
     * @param XmlnsAttribute $attribute
     */
    public function visitXmlnsAttribute(XmlnsAttribute $attribute)
    {
        if (!$this->hasPrefix()) {
            $this->prefix = $attribute->getPrefix();
        }

        parent::appendAttribute($attribute);
    }

    /**
     * @param DefaultXmlnsAttribute $attribute
     */
    public function visitDefaultXmlnsAttribute(DefaultXmlnsAttribute $attribute)
    {
        parent::appendAttribute($attribute);
    }

    /**
     * @param SoapAttribute $attribute
     */
    public function visitSoapAttribute(SoapAttribute $attribute)
    {
        parent::appendAttribute($attribute);
    }

    /**
     * @param NodeVisitorInterface $visitor
     */
    public function accept(NodeVisitorInterface $visitor)
    {
        $visitor->visitNode($this);
    }

    /**
     * @return array
     */
    public function getPropertyExport() : array
    {
        return [];
    }
}