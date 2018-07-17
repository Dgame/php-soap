<?php

namespace Dgame\Soap\Test;

use Dgame\Soap\Components\Body;
use Dgame\Soap\Components\Envelope;
use Dgame\Soap\Components\Header;
use Dgame\Soap\Translator\BuiltinToPackageTranslator;
use Dgame\Soap\Translator\PackageToBuiltinTranslator;
use Dgame\Soap\Visitor\AttributeElementPrefixInheritance;
use Dgame\Soap\Visitor\ElementPrefixInheritance;
use DOMDocument;
use PHPUnit\Framework\TestCase;

/**
 * Class RebuildTest
 * @package Dgame\Soap\Test
 */
final class RebuildTest extends TestCase
{
    public function testRebuildTime(): void
    {
        $package = new BuiltinToPackageTranslator();
        $builtin = new PackageToBuiltinTranslator();

        $doc1 = new DOMDocument();
        $this->assertTrue($doc1->load(__DIR__ . '/resources/soap_1.xml'));

        $start = time();
        $node  = $package->translate($doc1);
        $end   = time();

        $this->assertTrue(($end - $start) < 2);

        $doc2 = $builtin->translate($node);

        $this->assertXmlStringEqualsXmlString($doc1->saveXML(), $doc2->saveXML());
    }

    public function testCorrectNamespaceRebuildWithPreprocessor(): void
    {
        $package = new BuiltinToPackageTranslator();
        $builtin = new PackageToBuiltinTranslator();
        $builtin->appendPreprocessor(new AttributeElementPrefixInheritance());
        $builtin->appendPreprocessor(new ElementPrefixInheritance());

        $doc1 = new DOMDocument();
        $this->assertTrue($doc1->load(__DIR__ . '/resources/ns-rebuild-1.xml'));

        $node  = $package->translate($doc1);
        $doc2  = $builtin->translate($node);

        $this->assertXmlStringEqualsXmlString($doc1->saveXML(), $doc2->saveXML());
    }

    public function testNamespaceRebuildWithPreprocessor(): void
    {
        $package = new BuiltinToPackageTranslator();
        $builtin = new PackageToBuiltinTranslator();
        $builtin->appendPreprocessor(new AttributeElementPrefixInheritance());
        $builtin->appendPreprocessor(new ElementPrefixInheritance());

        $doc1 = new DOMDocument();
        $this->assertTrue($doc1->load(__DIR__ . '/resources/ns-rebuild-2.xml'));

        $node  = $package->translate($doc1);
        $doc2  = $builtin->translate($node);

        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/resources/ns-rebuild-2-result.xml', $doc2->saveXML());
    }

    public function testRebuildWithPreprocessor(): void
    {
        $builtin = new PackageToBuiltinTranslator();
        $builtin->appendPreprocessor(new AttributeElementPrefixInheritance());
        $builtin->appendPreprocessor(new ElementPrefixInheritance());

        $envelope = new Envelope();
        $envelope->appendElement(new Header());
        $envelope->appendElement(new Body());

        $node = $builtin->translate($envelope);

        $this->assertXmlStringEqualsXmlString('<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"><soap:Header/><soap:Body/></soap:Envelope>', $node->saveXML());
    }
}
