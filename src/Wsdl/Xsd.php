<?php

namespace Dgame\Soap\Wsdl;

use Dgame\Soap\Wsdl\Elements\ComplexType;
use Dgame\Soap\Wsdl\Elements\Element;
use Dgame\Soap\Wsdl\Elements\SimpleType;
use DOMDocument;
use DOMElement;
use DOMXPath;
use function Dgame\Ensurance\enforce;
use function Dgame\Ensurance\ensure;

/**
 * Class Xsd
 * @package Dgame\Soap\Wsdl
 */
class Xsd
{
    protected const W3_SCHEMA = 'http://www.w3.org/2001/XMLSchema';

    /**
     * @var string
     */
    private $location;
    /**
     * @var DOMDocument
     */
    private $document;
    /**
     * @var DOMXPath
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

        if ($this->valid) {
            $this->resolveIncludes();
        }
    }

    /**
     *
     */
    private function resolveIncludes(): void
    {
        $nodes = $this->document->getElementsByTagNameNS(self::W3_SCHEMA, 'include');
        for ($i = 0, $c = $nodes->length; $i < $c; $i++) {
            $include = $nodes->item($i);
            if ($include === null) {
                continue;
            }

            $location = $include->getAttribute('schemaLocation');
            $location = pathinfo($this->location, PATHINFO_DIRNAME) . '/' . $location;

            $xsd = new self($location);
            if ($xsd->isValid()) {
                foreach ($xsd->getDocument()->documentElement->childNodes as $child) {
                    $node = $this->document->importNode($child, true);
                    $include->parentNode->appendChild($node);
                }
            }
            $include->parentNode->removeChild($include);
        }
    }

    /**
     * @return DOMDocument
     */
    public function getDocument(): DOMDocument
    {
        return $this->document;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @return DOMElement
     */
    public function getDomElement(): DOMElement
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
     * @return bool
     */
    public function hasUriWithPrefix(string $prefix, string $namespace = 'xmlns'): bool
    {
        $attr = sprintf('%s:%s', $namespace, $prefix);

        return $this->getDomElement()->hasAttribute($attr);
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

        return $this->getDomElement()->hasAttribute($attr) ? $this->getDomElement()->getAttribute($attr) : '';
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
        ensure($uri)->isNotEmpty()->orThrow('Empty Xsd-Uri');

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
            $name = $node->getAttribute('name');

            $elements[$name] = new Element($node);
        }

        $nodes = $this->getXPath()->query(sprintf('//xsd:simpleType[@name="%s"]', $name));
        for ($i = 0, $c = $nodes->length; $i < $c; $i++) {
            $node = $nodes->item($i);
            $name = $node->getAttribute('name');

            $elements[$name] = new SimpleType($node);
        }

        $nodes = $this->getXPath()->query(sprintf('//xsd:complexType[@name="%s"]', $name));
        for ($i = 0, $c = $nodes->length; $i < $c; $i++) {
            $node = $nodes->item($i);
            $name = $node->getAttribute('name');

            $elements[$name] = new ComplexType($node);
        }

        return $elements;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasOneElementWithName(string $name): bool
    {
        $elements = $this->getAllElementsByName($name);

        return count($elements) === 1;
    }

    /**
     * @param string $name
     *
     * @return Element
     */
    public function getOneElementByName(string $name): Element
    {
        $elements = $this->getAllElementsByName($name);

        ensure($elements)->isArray()->isLongerThan(0)->orThrow('There is no element with name %s', $name);
        ensure($elements)->isArray()->hasLengthOf(1)->orThrow('There are multiple elements with name %s', $name);

        return array_pop($elements);
    }

    /**
     * @param string $name
     *
     * @return Element|null
     */
    public function findElementByNameInDeep(string $name): ?Element
    {
        if (strpos($name, ':') === false) {
            return $this->hasOneElementWithName($name) ? $this->getOneElementByName($name) : null;
        }

        [$prefix, $name] = explode(':', $name);

        if ($this->hasOneElementWithName($name)) {
            return $this->getOneElementByName($name);
        }

        if (!$this->hasUriWithPrefix($prefix)) {
            return null;
        }

        $xsd = $this->getXsdByPrefix($prefix);

        return $xsd->hasOneElementWithName($name) ? $xsd->getOneElementByName($name) : null;
    }
}
