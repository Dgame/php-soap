<?php

namespace Dgame\Soap\Wsdl;

use Dgame\Soap\Wsdl\Elements\ComplexType;
use Dgame\Soap\Wsdl\Elements\Element;
use Dgame\Soap\Wsdl\Elements\SimpleType;
use DOMDocument;
use DOMElement;
use DOMXPath;
use function Dgame\Ensurance\enforce;

/**
 * Class Xsd
 * @package Dgame\Soap\Wsdl
 */
final class Xsd
{
    /**
     * @var string
     */
    private $location;
    /**
     * @var DOMDocument
     */
    private $document;
    /**
     * @var
     */
    private $xpath;
    /**
     * @var bool|mixed
     */
    private $valid = false;
    /**
     * @var array
     */
    private $imports = [];

    /**
     * Xsd constructor.
     *
     * @param string $uri
     */
    public function __construct(string $uri)
    {
        $this->location = $uri;
        $this->document = new DOMDocument('1.0', 'utf-8');
        $this->valid    = $this->document->load($uri);
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @return DOMDocument
     */
    public function getDocument(): DOMDocument
    {
        return $this->document;
    }

    /**
     * @return DOMElement
     */
    public function getElement(): DOMElement
    {
        return $this->document->documentElement;
    }

    /**
     * @return DOMXPath
     */
    public function getXPath(): DOMXPath
    {
        if ($this->xpath === null) {
            $this->xpath = new DOMXPath($this->document);
        }

        return $this->xpath;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     *
     */
    private function loadImports(): void
    {
        $nodes = $this->document->getElementsByTagName('import');
        for ($i = 0, $c = $nodes->length; $i < $c; $i++) {
            $uri      = $nodes->item($i)->getAttribute('namespace');
            $location = $nodes->item($i)->getAttribute('schemaLocation');

            $this->imports[$uri] = $location;
        }
    }

    /**
     * @return array
     */
    public function getImports(): array
    {
        if (empty($this->imports)) {
            $this->loadImports();
        }

        return $this->imports;
    }

    /**
     * @param string $uri
     *
     * @return bool
     */
    public function hasImportWithNamespace(string $uri): bool
    {
        return array_key_exists($uri, $this->imports);
    }

    /**
     * @param string $uri
     *
     * @return string
     */
    public function getLocalImportLocationByUri(string $uri): string
    {
        if (empty($this->imports)) {
            $this->loadImports();
        }

        enforce($this->hasImportWithNamespace($uri))->orThrow('No import with name %s', $uri);

        return $this->imports[$uri];
    }

    /**
     * @param string $uri
     *
     * @return string
     */
    public function getImportLocationByUri(string $uri): string
    {
        $location = $this->getLocalImportLocationByUri($uri);

        return pathinfo($this->location, PATHINFO_DIRNAME) . '/' . $location;
    }

    /**
     * @param string $prefix
     * @param string $namespace
     *
     * @return string
     */
    public function getUriByPrefix(string $prefix, string $namespace = 'xmlns'): string
    {
        $attr = sprintf('%s:%s', $namespace, $prefix);

        return $this->getElement()->hasAttribute($attr) ? $this->getElement()->getAttribute($attr) : '';
    }

    /**
     * @param string $uri
     *
     * @return Xsd
     */
    public function getXsdByUri(string $uri): self
    {
        return new self($this->getImportLocationByUri($uri));
    }

    /**
     * @param string $prefix
     *
     * @return Xsd
     */
    public function getXsdByPrefix(string $prefix): self
    {
        $uri = $this->getUriByPrefix($prefix);

        return $this->getXsdByUri($uri);
    }

    /**
     * @param string $name
     *
     * @return Element[]
     */
    public function getAllElementsByName(string $name): array
    {
        $elements = [];

        $nodes = $this->getXPath()->query(sprintf('//xsd:element[@name="%s"]', $name));
        for ($i = 0, $c = $nodes->length; $i < $c; $i++) {
            $node = $nodes->item($i);

            $elements[$node->getAttribute('name')] = new Element($node);
        }

        return $elements;
    }

    public function getSimpleTypeByName(string $name): SimpleType
    {
        $nodes = $this->getXPath()->query(sprintf('//xsd:simpleType[@name="%s"]', $name));
        enforce($nodes->length !== 0)->orThrow('There is no simple type with name %s', $name);
        enforce($nodes->length === 1)->orThrow('There are multiple simple types with name %s', $name);

        return new SimpleType($nodes->item(0));
    }

    /**
     * @param string $name
     *
     * @return Element
     */
    public function getElementByName(string $name): Element
    {
        $nodes = $this->getXPath()->query(sprintf('//xsd:element[@name="%s"]', $name));
        enforce($nodes->length !== 0)->orThrow('There is no element with name %s', $name);
        enforce($nodes->length === 1)->orThrow('There are multiple elements with name %s', $name);

        return new Element($nodes->item(0));
    }

    /**
     * @param string $name
     *
     * @return ComplexType
     */
    public function getComplexTypeByName(string $name): ComplexType
    {
        $nodes = $this->getXPath()->query(sprintf('//xsd:complexType[@name="%s"]', $name));
        enforce($nodes->length !== 0)->orThrow('There is no complex type with name %s', $name);
        enforce($nodes->length === 1)->orThrow('There are multiple complex types with name %s', $name);

        return new ComplexType($nodes->item(0));
    }
}
