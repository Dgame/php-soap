<?php

use Dgame\Soap\Element\XmlElement;
use Dgame\Soap\Element\XmlNode;
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

$envelope = new Envelope();
$envelope->appendElement(new XmlElement('age', 30));

$p2b = new PackageToBuiltinTranslator();
$p2b->appendPreprocessor(new AttributeElementPrefixInheritance());
$p2b->appendPreprocessor(new ElementPrefixInheritance());

$document    = $p2b->translate($envelope);
$xml         = $document->saveXML();
print 'XML #1:' . PHP_EOL;
var_dump($xml);

$b2p     = new BuiltinToPackageTranslator();
$element = $b2p->translate($document);

//print_r($element);

$document = $p2b->translate($element);
$xml      = $document->saveXML();
print 'XML #2:' . PHP_EOL;
var_dump($xml);
