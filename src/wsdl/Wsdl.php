<?php

namespace Dgame\Soap\Wsdl;

use DOMDocument;
use DOMElement;
use function Dgame\Ensurance\enforce;

/**
 * Class Wsdl
 * @package Dgame\Soap\Wsdl
 */
final class Wsdl
{
    /**
     * @var DOMDocument
     */
    private $document;
    /**
     * @var bool
     */
    private $valid = false;
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
     * Wsdl constructor.
     *
     * @param string $uri
     */
    public function __construct(string $uri)
    {
        $this->document = new DOMDocument('1.0', 'utf-8');
        $this->valid    = $this->document->load($uri);
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * @return array
     */
    public function getOperationsWithSoapActions(): array
    {
        return array_combine($this->getSoapActions(), $this->getOperations());
    }

    /**
     * @return DOMElement
     */
    private function getBinding(): DOMElement
    {
        if ($this->binding === null) {
            $bindings = $this->document->getElementsByTagNameNS('http://schemas.xmlsoap.org/wsdl/', 'binding');
            enforce($bindings->length !== 0)->orThrow('There are no bindings');
            enforce($bindings->length === 1)->orThrow('There are multiple bindings');

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
        $operations = $binding->getElementsByTagNameNS('http://schemas.xmlsoap.org/wsdl/', 'operation');
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
        $actions = $binding->getElementsByTagNameNS('http://schemas.xmlsoap.org/wsdl/soap/', 'operation');
        for ($i = 0, $c = $actions->length; $i < $c; $i++) {
            $action = $actions->item($i);

            $this->actions[] = $action->getAttribute('soapAction');
        }

        return $this->actions;
    }

    /**
     * @return Xsd
     */
    public function getSchema(): Xsd
    {
        $schema = $this->document->getElementsByTagNameNS('http://www.w3.org/2001/XMLSchema', 'schema');
        enforce($schema->length !== 0)->orThrow('There is no Schema');
        enforce($schema->length === 1)->orThrow('There are multiple Schemas');

        $include = $schema->item(0)->getElementsByTagName('include');
        enforce($include->length !== 0)->orThrow('There is no include');
        enforce($include->length === 1)->orThrow('There are multiple includes');

        $location = $include->item(0)->getAttribute('schemaLocation');

        return new Xsd($location);
    }
}