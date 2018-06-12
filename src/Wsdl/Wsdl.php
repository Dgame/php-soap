<?php

namespace Dgame\Soap\Wsdl;

use Dgame\Soap\Wsdl\Elements\Element;
use Dgame\Soap\Wsdl\Http\HttpClient;
use DOMDocument;
use DOMElement;
use function Dgame\Ensurance\enforce;
use function Dgame\Ensurance\ensure;

/**
 * Class Wsdl
 * @package Dgame\Soap\Wsdl
 */
final class Wsdl
{
    private const WSDL_SOAP_SCHEMA = 'http://schemas.xmlsoap.org/wsdl/soap/';
    private const WSDL_SCHEMA      = 'http://schemas.xmlsoap.org/wsdl/';

    /**
     * @var string
     */
    private $location;
    /**
     * @var DOMDocument
     */
    private $document;
    /**
     * @var DOMElement
     */
    private $binding;
    /**
     * @var array
     */
    private $operations = [];
    /**
     * @var array
     */
    private $actions = [];
    /**
     * @var Xsd[]
     */
    private $schemas = [];

    /**
     * Wsdl constructor.
     *
     * @param string $uri
     */
    public function __construct(string $uri)
    {
        $this->location = $uri;
        $this->document = HttpClient::instance()->loadDocument($uri);
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
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->document !== null;
    }

    /**
     * @param string $pattern
     *
     * @return array
     */
    public function getOperationsByPattern(string $pattern): array
    {
        $operations = [];
        foreach ($this->getOperations() as $operation) {
            if (preg_match($pattern, $operation) === 1) {
                $operations[] = $operation;
            }
        }

        return $operations;
    }

    /**
     * @param string $operation
     *
     * @return string
     */
    public function getSoapActionOfOperation(string $operation): string
    {
        $actions = $this->getOperationsWithSoapActions();
        ensure($actions)->isArray()
                        ->hasKey($operation)
                        ->orThrow('No action for operation "%s"', $operation);

        return $actions[$operation];
    }

    /**
     * @param string $pattern
     *
     * @return string
     */
    public function getOperationByPattern(string $pattern): string
    {
        $operations = $this->getOperationsByPattern($pattern);

        ensure($operations)->isNotEmpty()
                           ->orThrow('No operation found by pattern %s', $pattern);
        ensure($operations)->isArray()
                           ->hasLengthOf(1)
                           ->orThrow('Ambiguous operation pattern %s. Found multiple occurrences', $pattern);

        return array_pop($operations);
    }

    /**
     * @return array
     */
    public function getOperationsWithSoapActions(): array
    {
        return array_combine($this->getOperations(), $this->getSoapActions());
    }

    /**
     * @return DOMElement
     */
    private function getBinding(): DOMElement
    {
        if ($this->binding === null) {
            $bindings = $this->document->getElementsByTagNameNS(self::WSDL_SCHEMA, 'binding');
            enforce($bindings->length !== 0)->orThrow('There are no bindings');
            //            enforce($bindings->length === 1)->orThrow('There are %d bindings', $bindings->length);

            $this->binding = $bindings->item(0);
        }

        return $this->binding;
    }

    /**
     * @return array
     */
    public function getOperations(): array
    {
        if (!empty($this->operations)) {
            return $this->operations;
        }

        $binding    = $this->getBinding();
        $operations = $binding->getElementsByTagNameNS(self::WSDL_SCHEMA, 'operation');
        for ($i = 0, $c = $operations->length; $i < $c; $i++) {
            $operation = $operations->item($i);

            $this->operations[] = $operation->getAttribute('name');
        }

        return $this->operations;
    }

    /**
     * @return array
     */
    public function getSoapActions(): array
    {
        if (!empty($this->actions)) {
            return $this->actions;
        }

        $binding = $this->getBinding();
        $actions = $binding->getElementsByTagNameNS(self::WSDL_SOAP_SCHEMA, 'operation');
        for ($i = 0, $c = $actions->length; $i < $c; $i++) {
            $action = $actions->item($i);

            $this->actions[] = $action->getAttribute('soapAction');
        }

        return $this->actions;
    }

    /**
     * @return Xsd[]
     */
    public function getSchemas(): array
    {
        if (!empty($this->schemas)) {
            return $this->schemas;
        }

        $this->schemas = Xsd::load($this);

        return $this->schemas;
    }

    /**
     * @return Xsd
     */
    public function getFirstSchema(): Xsd
    {
        $schemas = $this->getSchemas();
        ensure($schemas)->isNotEmpty()->orThrow('No Schemas found');

        return reset($schemas);
    }

    /**
     * @param string $uri
     *
     * @return bool
     */
    public function hasSchemaWithUri(string $uri): bool
    {
        $schemas = $this->getSchemas();
        if (array_key_exists($uri, $schemas)) {
            return true;
        }

        foreach ($schemas as $schema) {
            if ($schema->hasImportLocation($uri)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $uri
     *
     * @return Xsd
     * @throws \Exception
     */
    public function getSchemaByUri(string $uri): Xsd
    {
        $schemas = $this->getSchemas();
        if (array_key_exists($uri, $schemas)) {
            return $schemas[$uri];
        }

        /** @var Xsd $schema */
        foreach ($schemas as $schema) {
            if ($schema->hasImportLocation($uri)) {
                return $schema->loadXsdByUri($uri);
            }
        }

        throw new \Exception('No Schema found with location ' . $uri);
    }

    /**
     * @param string $name
     *
     * @return Element[]
     */
    public function findAllElementInSchemas(string $name): array
    {
        $elements = [];
        foreach ($this->getSchemas() as $schema) {
            $elements = array_merge($elements, self::findElementsByNameInDeep($schema, $name));
        }

        return $elements;
    }

    /**
     * @param Xsd    $schema
     * @param string $name
     *
     * @return array
     */
    private static function findElementsByNameInDeep(Xsd $schema, string $name): array
    {
        $elements = [];

        $element = $schema->findElementByNameInDeep($name);
        if ($element !== null) {
            $elements[] = $element;
        }

        foreach ($schema->loadImportedSchemas() as $schema) {
            $elements = array_merge($elements, self::findElementsByNameInDeep($schema, $name));
        }

        return $elements;
    }

    /**
     * @param string $name
     *
     * @return Element|null
     */
    public function findOneElementInSchemas(string $name): ?Element
    {
        $elements = $this->findAllElementInSchemas($name);

        return empty($elements) ? null : reset($elements);
    }
}
