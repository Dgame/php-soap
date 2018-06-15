<?php

namespace Dgame\Soap\Wsdl;

use Dgame\Soap\Wsdl\Elements\ComplexType;
use Dgame\Soap\Wsdl\Elements\Element;
use Dgame\Soap\Wsdl\Elements\SimpleType;
use Dgame\Soap\Wsdl\Http\HttpClient;
use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;
use Enrise\Uri;
use function Dgame\Ensurance\enforce;
use function Dgame\Ensurance\ensure;

/**
 * Class Xsd
 * @package Dgame\Soap\Wsdl
 */
final class Xsd implements XsdAdapterInterface
{
    private const W3_SCHEMA       = 'http://www.w3.org/2001/XMLSchema';
    private const SCHEMA_LOCATION = 'schemaLocation';

    /**
     * @var DOMElement
     */
    private $element;
    /**
     * @var DOMXPath
     */
    private $xpath;
    /**
     * @var array
     */
    private $imports = [];
    /**
     * @var string
     */
    private $location;
    /**
     * @var string
     */
    private $namespace;
    /**
     * @var self[]
     */
    private $schemas = [];

    /**
     * Xsd constructor.
     *
     * @param DOMElement  $element
     * @param string|null $location
     */
    public function __construct(DOMElement $element, string $location = null)
    {
        $this->namespace = $element->getAttribute('targetNamespace');
        $this->location  = $location;
        $this->element   = $element;

        $this->resolveIncludes();
    }

    /**
     * @param Wsdl $wsdl
     *
     * @return self[]
     */
    public static function load(Wsdl $wsdl): array
    {
        enforce($wsdl->isValid())->orThrow('Invalid WSDL "%s" given', $wsdl->getLocation());

        $nodes = $wsdl->getDocument()->getElementsByTagNameNS(self::W3_SCHEMA, 'schema');
        enforce($nodes->length !== 0)->orThrow('There is no Schema');

        $schemas = [];
        for ($i = 0, $c = $nodes->length; $i < $c; $i++) {
            $node     = $nodes->item($i);
            $location = self::getSchemaLocation($node);

            $schema = new self($node, $wsdl->getLocation());
            $schema = $schema->tryLoadXsdByUri($location);

            ensure($schema)->isNotNull()->orThrow('Could not load "%s"', $location);

            $schemas[$schema->getNamespace()] = $schema;
        }

        return $schemas;
    }

    /**
     * @param DOMElement $element
     *
     * @return null|string
     */
    private static function getSchemaLocation(DOMElement $element): ?string
    {
        $includes = $element->getElementsByTagNameNS(self::W3_SCHEMA, 'include');
        $location = self::findSchemaLocationIn($includes);
        if (!empty($location)) {
            return $location;
        }

        $imports  = $element->getElementsByTagNameNS(self::W3_SCHEMA, 'import');
        $location = self::findSchemaLocationIn($imports);

        return $location;
    }

    /**
     * @param DOMNodeList $nodes
     *
     * @return null|string
     */
    private static function findSchemaLocationIn(DOMNodeList $nodes): ?string
    {
        for ($i = 0, $c = $nodes->length; $i < $c; $i++) {
            $node = $nodes->item($i);
            if ($node->hasAttribute(self::SCHEMA_LOCATION)) {
                return $node->getAttribute(self::SCHEMA_LOCATION);
            }
        }

        return null;
    }

    /**
     *
     */
    private function resolveIncludes(): void
    {
        $nodes = $this->element->getElementsByTagNameNS(self::W3_SCHEMA, 'include');
        for ($i = 0, $c = $nodes->length; $i < $c; $i++) {
            $include = $nodes->item($i);

            $location = $include->getAttribute('schemaLocation');
            
            $xsd = $this->loadXsdByUri($location);
            foreach ($xsd->getChildNodes() as $child) {
                $node = $this->getDocument()->importNode($child, true);
                $include->parentNode->appendChild($node);
            }
        }

        foreach ($nodes as $include) {
            $include->parentNode->removeChild($include);
        }
    }

    /**
     * @return DOMDocument
     */
    public function getDocument(): DOMDocument
    {
        return $this->element->ownerDocument;
    }

    /**
     * @return bool
     */
    public function hasLocation(): bool
    {
        return !empty($this->location);
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location ?? '';
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @return DOMXPath
     */
    public function getXPath(): DOMXPath
    {
        if ($this->xpath === null) {
            $this->xpath = new DOMXPath($this->element->ownerDocument);
        }

        return $this->xpath;
    }

    /**
     * @return DOMNodeList
     */
    public function getChildNodes(): DOMNodeList
    {
        return $this->element->childNodes;
    }

    /**
     *
     */
    private function loadImports(): void
    {
        $nodes = $this->element->getElementsByTagName('import');
        for ($i = 0, $c = $nodes->length; $i < $c; $i++) {
            $node = $nodes->item($i);
            $uri  = $node->getAttribute('namespace');
            if ($node->hasAttribute('schemaLocation')) {
                $this->imports[$uri] = $node->getAttribute('schemaLocation');
            }
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
    public function hasImportLocation(string $uri): bool
    {
        return array_key_exists($uri, $this->getImports());
    }

    /**
     * @param string $uri
     *
     * @return string
     */
    public function getLocalImportLocationByUri(string $uri): string
    {
        enforce($this->hasImportLocation($uri))->orThrow('No Import found with URI %s', $uri);

        return $this->imports[$uri];
    }

    /**
     * @param string $uri
     *
     * @return string
     */
    public function getImportLocationByUri(string $uri): string
    {
        return $this->getLocalImportLocationByUri($uri);
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

        return $this->element->hasAttribute($attr);
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

        return $this->element->hasAttribute($attr) ? $this->element->getAttribute($attr) : '';
    }

    /**
     * @param string $uri
     *
     * @return Xsd
     */
    public function loadXsdByUri(string $uri): self
    {
        $xsd = $this->tryLoadXsdByUri($uri);
        enforce($xsd !== null)->orThrow('Could not load XSD by Uri %s', $uri);

        return $xsd;
    }

    /**
     * @param string $location
     *
     * @return Xsd|null
     */
    public function tryLoadXsdByUri(string $location): ?self
    {
        if ($this->hasImportLocation($location)) {
            $location = $this->getImportLocationByUri($location);
        }

        foreach ($this->getPossibleLocations($location) as $location) {
            $document = HttpClient::instance()->loadDocument($location);
            if ($document !== null) {
                return new self($document->documentElement, $location);
            }
        }

        return null;
    }

    /**
     * @param string $location
     *
     * @return string[]
     */
    private function getPossibleLocations(string $location): array
    {
        $uri = new Uri($location);
        if ($uri->isAbsolute()) {
            return [$location];
        }

        $location     = ltrim($location, '/');
        $baseLocation = sprintf(
            '%s://%s/%s',
            parse_url($this->location, PHP_URL_SCHEME),
            parse_url($this->location, PHP_URL_HOST),
            $location
        );

        if (!$this->hasLocation()) {
            return [$baseLocation];
        }

        return [
            sprintf('%s/%s', pathinfo($this->location, PATHINFO_DIRNAME), $location),
            $baseLocation
        ];
    }

    /**
     * @param string $prefix
     *
     * @return Xsd
     */
    public function loadXsdByPrefix(string $prefix): self
    {
        $uri = $this->getUriByPrefix($prefix);
        ensure($uri)->isNotEmpty()->orThrow('Empty Xsd-Uri');

        if (!array_key_exists($uri, $this->schemas)) {
            $xsd = $this->tryLoadXsdByUri($uri);
            if ($xsd !== null) {
                $this->schemas[$uri] = $xsd;
            }
        }

        enforce(array_key_exists($uri, $this->schemas))->orThrow('No XSD with Uri %s was found', $uri);

        return $this->schemas[$uri];
    }

    /**
     * @return self[]
     */
    public function loadImportedSchemas(): array
    {
        foreach ($this->getImports() as $uri => $location) {
            if (array_key_exists($uri, $this->schemas)) {
                continue;
            }

            $xsd = $this->tryLoadXsdByUri($location);
            if ($xsd !== null) {
                $this->schemas[$uri] = $xsd;
            }
        }

        return $this->schemas;
    }

    /**
     * @param string $name
     *
     * @return Element[]
     */
    public function getAllElementsByName(string $name): array
    {
        $elements = [];

        if (!$this->element->hasAttributeNS('http://www.w3.org/2001/XMLSchema', 'xsd')) {
            $this->getXPath()->registerNamespace('xsd', 'http://www.w3.org/2001/XMLSchema');
        }

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
     * @return Element|null
     */
    public function getOneElementByName(string $name): ?Element
    {
        $elements = $this->getAllElementsByName($name);

        return empty($elements) ? null : reset($elements);
    }

    /**
     * @param string $name
     *
     * @return Element|null
     */
    public function findElementByNameInDeep(string $name): ?Element
    {
        if (strpos($name, ':') === false) {
            return $this->getOneElementByName($name);
        }

        [$prefix, $name] = explode(':', $name);

        $element = $this->getOneElementByName($name);
        if ($element !== null) {
            return $element;
        }

        if (!$this->hasUriWithPrefix($prefix)) {
            return null;
        }

        $xsd = $this->loadXsdByPrefix($prefix);

        return $xsd->getOneElementByName($name);
    }
}

