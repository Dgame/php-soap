<?php

namespace Dgame\Soap\Test;

use Dgame\Soap\Element\XmlElementInterface;
use Dgame\Soap\Element\XmlNodeInterface;
use Dgame\Soap\Translator\BuiltinToPackageTranslator;
use DOMDocument;
use PHPUnit\Framework\TestCase;

/**
 * Class BuiltTest
 * @package Dgame\Soap\Test
 */
final class TranslatorTest extends TestCase
{
    public function testXmlElementTranslation(): void
    {
        $doc        = new DOMDocument('1.0');
        $translator = new BuiltinToPackageTranslator();
        $node       = $translator->translate($doc->createElement('test', 'foobar'));
        $this->assertNotNull($node);
        $this->assertInstanceOf(XmlElementInterface::class, $node);
        $this->assertEquals('test', $node->getName());
        $this->assertTrue($node->hasValue());
        $this->assertTrue($node->hasAttributes());
        $this->assertCount(1, $node->getAttributes());
        $this->assertEquals('test', $node->getName());
        $this->assertEquals('foobar', $node->getValue());
        $this->assertEquals('xml', $node->getAttributeByIndex(0)->getName());
        $this->assertEquals('http://www.w3.org/XML/1998/namespace', $node->getAttributeByIndex(0)->getValue());

        $node = $translator->translate($doc->createElement('test', '42'));
        $this->assertNotNull($node);
        $this->assertInstanceOf(XmlElementInterface::class, $node);
        $this->assertEquals('test', $node->getName());
        $this->assertTrue($node->hasValue());
        $this->assertTrue($node->hasAttributes());
        $this->assertCount(1, $node->getAttributes());
        $this->assertEquals('test', $node->getName());
        $this->assertEquals(42, $node->getValue());
        $this->assertEquals('xml', $node->getAttributeByIndex(0)->getName());
        $this->assertEquals('http://www.w3.org/XML/1998/namespace', $node->getAttributeByIndex(0)->getValue());

        $element = $doc->createElementNs('http://www.example.com/bar', 'bar:test', 'foobar');
        $element->setAttribute('id', 42);
        $node = $translator->translate($element);
        $this->assertNotNull($node);
        $this->assertInstanceOf(XmlElementInterface::class, $node);
        $this->assertEquals('test', $node->getName());
        $this->assertTrue($node->hasValue());
        $this->assertTrue($node->hasAttributes());
        $this->assertCount(2, $node->getAttributes());
        $this->assertTrue($node->hasPrefix());
        $this->assertEquals('bar', $node->getPrefix());
        $this->assertEquals('test', $node->getName());
        $this->assertEquals('foobar', $node->getValue());
        $this->assertEquals('xml', $node->getAttributeByIndex(0)->getName());
        $this->assertEquals('http://www.w3.org/XML/1998/namespace', $node->getAttributeByIndex(0)->getValue());
        $this->assertEquals('id', $node->getAttributeByIndex(1)->getName());
        $this->assertEquals(42, $node->getAttributeByIndex(1)->getValue());
    }

    public function testXmlNodeTranslation(): void
    {
        $doc        = new DOMDocument('1.0');
        $translator = new BuiltinToPackageTranslator();
        $element    = $doc->createElementNs('http://www.example.com/abc', 'abc:test');
        $element->setAttribute('id', 23);
        $element->appendChild($doc->createElement('name', 'Franz'));
        $element->appendChild($doc->createElement('age', '42'));
        /** @var XmlNodeInterface $node */
        $node = $translator->translate($element);
        $this->assertNotNull($node);
        $this->assertInstanceOf(XmlNodeInterface::class, $node);
        $this->assertEquals('test', $node->getName());
        $this->assertFalse($node->hasValue());
        $this->assertTrue($node->hasAttributes());
        $this->assertTrue($node->hasElements());
        $this->assertTrue($node->hasPrefix());
        $this->assertEquals('abc', $node->getPrefix());
        $this->assertEquals('test', $node->getName());
        $this->assertFalse($node->hasValue());
        $this->assertCount(3, $node->getAttributes());
        $this->assertEquals('id', $node->getAttributeByIndex(2)->getName());
        $this->assertEquals(23, $node->getAttributeByIndex(2)->getValue());
        $this->assertInstanceOf(XmlElementInterface::class, $node->getElementByIndex(1));
        $this->assertInstanceOf(XmlElementInterface::class, $node->getElementByIndex(1));
        $this->assertCount(2, $node->getElements());
        $this->assertEquals('name', $node->getElementByIndex(0)->getName());
        $this->assertEquals('Franz', $node->getElementByIndex(0)->getValue());
        $this->assertEquals('age', $node->getElementByIndex(1)->getName());
        $this->assertEquals(42, $node->getElementByIndex(1)->getValue());
    }

    public function testDocumentTranslation(): void
    {
        $doc = new DOMDocument('1.0');
        $doc->load(__DIR__ . '/resources/test1.xml');
        $translator = new BuiltinToPackageTranslator();
        /** @var XmlNodeInterface $envelope */
        $envelope   = $translator->translate($doc);
        $this->assertEquals('soap-env', $envelope->getName());
        $this->assertCount(2, $envelope->getElements());
        /** @var XmlNodeInterface $child1 */
        $child1 = $envelope->getElementByIndex(0);
        /** @var XmlNodeInterface $child2 */
        $child2 = $envelope->getElementByIndex(1);
        $this->assertInstanceOf(XmlNodeInterface::class, $child1);
        $this->assertInstanceOf(XmlNodeInterface::class, $child2);
        $this->assertEquals('person', $child1->getName());
        $this->assertTrue($child1->hasElements());
        $this->assertCount(4, $child1->getElements());
        $this->assertTrue($child1->hasAttributes());
        $this->assertCount(1, $child1->getAttributes());
        $this->assertEquals('name', $child1->getAttributeByIndex(0)->getName());
        $this->assertEquals('Max Musterman', $child1->getAttributeByIndex(0)->getValue());
        $this->assertInstanceOf(XmlElementInterface::class, $child1->getElementByIndex(0));
        $this->assertEquals('car', $child1->getElementByIndex(0)->getName());
        $this->assertFalse($child1->getElementByIndex(0)->hasValue());
        $this->assertCount(2, $child1->getElementByIndex(0)->getAttributes());
        $this->assertEquals('marke', $child1->getElementByIndex(0)->getAttributeByIndex(0)->getName());
        $this->assertEquals('BMW', $child1->getElementByIndex(0)->getAttributeByIndex(0)->getValue());
        $this->assertInstanceOf(XmlElementInterface::class, $child1->getElementByIndex(1));
        $this->assertEquals('phone', $child1->getElementByIndex(1)->getName());
        $this->assertEquals(9, $child1->getElementByIndex(1)->getValue());
        $this->assertCount(1, $child1->getElementByIndex(1)->getAttributes());
        $this->assertEquals('name', $child1->getElementByIndex(1)->getAttributeByIndex(0)->getName());
        $this->assertEquals('iPhone', $child1->getElementByIndex(1)->getAttributeByIndex(0)->getValue());
        $this->assertInstanceOf(XmlElementInterface::class, $child1->getElementByIndex(2));
        $this->assertEquals('birth-place', $child1->getElementByIndex(2)->getName());
        $this->assertEquals('Hamburg', $child1->getElementByIndex(2)->getValue());
        $this->assertFalse($child1->getElementByIndex(2)->hasAttributes());
        $this->assertInstanceOf(XmlNodeInterface::class, $child1->getElementByIndex(3));
        $this->assertEquals('address', $child1->getElementByIndex(3)->getName());
        $this->assertFalse($child1->getElementByIndex(3)->hasValue());
        $this->assertFalse($child1->getElementByIndex(3)->hasAttributes());
        $this->assertCount(2, $child1->getElementByIndex(3)->getElements());
        $i = 0;
        foreach (['street' => 'Hauptstraße 1', 'plz' => '245698'] as $name => $value) {
            $this->assertEquals($name, $child1->getElementByIndex(3)->getElementByIndex($i)->getName());
            $this->assertEquals($value, $child1->getElementByIndex(3)->getElementByIndex($i)->getValue());
            $i++;
        }
        $this->assertEquals('person', $child2->getName());
        $this->assertTrue($child2->hasAttributes());
        $this->assertCount(1, $child2->getAttributes());
        $this->assertTrue($child2->hasElements());
        $this->assertCount(4, $child2->getElements());
        $this->assertEquals('name', $child2->getAttributeByIndex(0)->getName());
        $this->assertEquals('Dr. Dolittle', $child2->getAttributeByIndex(0)->getValue());
        $this->assertInstanceOf(XmlElementInterface::class, $child2->getElementByIndex(0));
        $this->assertEquals('car', $child2->getElementByIndex(0)->getName());
        $this->assertFalse($child2->getElementByIndex(0)->hasValue());
        $this->assertCount(2, $child2->getElementByIndex(0)->getAttributes());
        $this->assertEquals('marke', $child2->getElementByIndex(0)->getAttributeByIndex(0)->getName());
        $this->assertEquals('Audi', $child2->getElementByIndex(0)->getAttributeByIndex(0)->getValue());
        $this->assertInstanceOf(XmlElementInterface::class, $child2->getElementByIndex(1));
        $this->assertEquals('phone', $child2->getElementByIndex(1)->getName());
        $this->assertEquals('Xperia Z3', $child2->getElementByIndex(1)->getValue());
        $this->assertCount(1, $child2->getElementByIndex(1)->getAttributes());
        $this->assertEquals('name', $child2->getElementByIndex(1)->getAttributeByIndex(0)->getName());
        $this->assertEquals('Sony', $child2->getElementByIndex(1)->getAttributeByIndex(0)->getValue());
        $this->assertInstanceOf(XmlElementInterface::class, $child2->getElementByIndex(2));
        $this->assertEquals('birth-place', $child2->getElementByIndex(2)->getName());
        $this->assertEquals('München', $child2->getElementByIndex(2)->getValue());
        $this->assertFalse($child2->getElementByIndex(2)->hasAttributes());
        $this->assertInstanceOf(XmlElementInterface::class, $child2->getElementByIndex(3));
        $this->assertEquals('address', $child2->getElementByIndex(3)->getName());
        $this->assertFalse($child2->getElementByIndex(3)->hasValue());
        $this->assertFalse($child2->getElementByIndex(3)->hasAttributes());
        $this->assertCount(2, $child2->getElementByIndex(3)->getElements());
        $i = 0;
        foreach (['street' => 'Partkstraße', 'plz' => '365494'] as $name => $value) {
            $this->assertEquals($name, $child2->getElementByIndex(3)->getElements()[$i]->getName());
            $this->assertEquals($value, $child2->getElementByIndex(3)->getElements()[$i]->getValue());
            $i++;
        }
    }
}
