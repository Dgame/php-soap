<?php

use Dgame\Soap\Element\ElementInterface;
use Dgame\Soap\Element\XmlElement;
use Dgame\Soap\Element\XmlNode;
use Dgame\Soap\Hydrator\DefaultHydratorStrategy;
use Dgame\Soap\Hydrator\Hydrator;
use Dgame\Soap\Translator\BuiltinToPackageTranslator;
use Dgame\Soap\Translator\PackageToBuiltinTranslator;
use Dgame\Soap\Visitor\AttributeElementPrefixInheritance;
use Dgame\Soap\Visitor\ElementPrefixInheritance;

require_once 'vendor/autoload.php';

final class Envelope extends XmlNode
{
    public function __construct(string $name = 'Envelope', $value = null)
    {
        parent::__construct($name, $value);

        $this->setNamespaceAttribute('soap', 'http://schemas.xmlsoap.org/soap/envelope/');
    }
}

final class Fault extends XmlNode
{
}

$envelope = new Envelope();
$envelope->appendElement(new XmlElement('age', 30));

$p2b = new PackageToBuiltinTranslator();
$p2b->appendPreprocessor(new AttributeElementPrefixInheritance());
$p2b->appendPreprocessor(new ElementPrefixInheritance());

$document = $p2b->translate($envelope);
$xml      = $document->saveXML();
print 'XML #1:' . PHP_EOL;
var_dump($xml);

$b2p     = new BuiltinToPackageTranslator();
$element = $b2p->translate($document);

//print_r($element);

$document = $p2b->translate($element);
$xml      = $document->saveXML();
print 'XML #2:' . PHP_EOL;
var_dump($xml);

$strategy = new DefaultHydratorStrategy();
$strategy->setCallback('envelope', function () {
    return new Envelope();
});
$strategy->setCallback('envelope.body.fault', function (ElementInterface $element, Envelope $envelope) {
    $fault = new Fault('fault');
    $envelope->appendElement($fault);

    return $fault;
});
$strategy->setCallback('envelope.body.fault.faultcode', function (ElementInterface $element, Fault $fault) {
    $fault->appendElement($element);
});
$hydator = new Hydrator($strategy);
$element->accept($hydator);

$doc = new DOMDocument('1.0', 'utf-8');
$doc->load('test.xml');

print '----' . PHP_EOL;
$hydator->hydrate($doc);

$element = $strategy->top();

$document = $p2b->translate($element);
$xml      = $document->saveXML();
print 'XML #3:' . PHP_EOL;
var_dump($xml);
