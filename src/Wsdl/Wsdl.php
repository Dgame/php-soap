<?php

namespace Dgame\Soap\Wsdl;

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
        return $this->valid;
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

        ensure($operations)->isArray()
                           ->isLongerThan(0)
                           ->orThrow('No operation found by pattern %s', $pattern);
        ensure($operations)->isArray()
                           ->hasLengthOf(1)
                           ->orThrow('Ambiguous operation pattern %xs. Found multiple occurrences', $pattern);

        return array_pop($operations);
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
            $bindings = $this->document->getElementsByTagNameNS(self::WSDL_SCHEMA, 'binding');
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
     * @return Schema
     */
    public function getSchema(): Schema
    {
        return new Schema($this->document);
    }
}
