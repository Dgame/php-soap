<?php

namespace Dgame\Soap\Test;

use Dgame\Soap\Component\AcknowledgeShipment;
use Dgame\Soap\Component\BiPROVersion;
use Dgame\Soap\Component\Body;
use Dgame\Soap\Component\Envelope;
use Dgame\Soap\Component\GetShipment;
use Dgame\Soap\Component\Header;
use Dgame\Soap\Component\ListShipments;
use Dgame\Soap\Component\Request;
use Dgame\Soap\Component\RequestSecurityToken;
use Dgame\Soap\Component\Security;
use Dgame\Soap\Component\SecurityContextToken;
use Dgame\Soap\Component\UsernameToken;
use Dgame\Soap\Hydrator\Dom\Assembler;
use DOMDocument;
use PHPUnit\Framework\TestCase;

/**
 * Class BiproTest
 * @package Dgame\Soap\Test
 */
final class BiproTest extends TestCase
{
    public function testRequestTokenService()
    {
        $envelope = new Envelope();

        $security = new Security();
        $token    = new UsernameToken('Foo', 'Bar');
        $security->appendElement($token);

        $header = new Header();
        $header->appendElement($security);

        $rst = new RequestSecurityToken();
        $rst->appendElement(new BiPROVersion('2.1.6.1.1'));

        $body = new Body();
        $body->appendElement($rst);

        $envelope->appendElement($header);
        $envelope->appendElement($body);

//        print_r($envelope);
//        exit;

        $assembler = new Assembler();
        $envelope->accept($assembler);

        $doc = new DOMDocument('1.0', 'utf-8');
        $doc->load(__DIR__ . '/xml/bipro_login.xml');

        $this->assertEqualXMLStructure($assembler->getDocument()->documentElement, $doc->documentElement);
    }

    public function testBiproListShipment()
    {
        $envelope = new Envelope();

        $security = new Security();
        $token    = new SecurityContextToken('bipro:7860072500822840554');
        $security->appendElement($token);

        $header = new Header();
        $header->appendElement($security);

        $request = new Request(new BiPROVersion('2.1.4.1.1'));
        $request->setConfirm(false);
        $shipment = new ListShipments($request);

        $body = new Body();
        $body->appendElement($shipment);

        $envelope->appendElement($header);
        $envelope->appendElement($body);

        $assembler = new Assembler();
        $envelope->accept($assembler);

        $doc = new DOMDocument('1.0', 'utf-8');
        $doc->load(__DIR__ . '/xml/bipro_list.xml');

        $this->assertEqualXMLStructure($assembler->getDocument()->documentElement, $doc->documentElement);
    }

    public function testBiproGetShipment()
    {
        $envelope = new Envelope();

        $security = new Security();
        $token    = new SecurityContextToken('bipro:7860072500822840554');
        $security->appendElement($token);

        $header = new Header();
        $header->appendElement($security);

        $request = new Request(new BiPROVersion('2.1.4.1.1'));
        $request->setConsumerId(1);
        $request->setId(1);
        $request->setConfirm(false);
        $shipment = new GetShipment($request);

        $body = new Body();
        $body->appendElement($shipment);

        $envelope->appendElement($header);
        $envelope->appendElement($body);

        $assembler = new Assembler();
        $envelope->accept($assembler);

        $doc = new DOMDocument('1.0', 'utf-8');
        $doc->load(__DIR__ . '/xml/bipro_get.xml');

        $this->assertEqualXMLStructure($assembler->getDocument()->documentElement, $doc->documentElement);
    }

    public function testBiproAckShipment()
    {
        $envelope = new Envelope();

        $security = new Security();
        $token    = new SecurityContextToken('bipro:7860072500822840554');
        $security->appendElement($token);

        $header = new Header();
        $header->appendElement($security);

        $request = new Request(new BiPROVersion('2.1.4.1.1'));
        $request->setConsumerId(1);
        $request->setId(1);
        $shipment = new AcknowledgeShipment($request);

        $body = new Body();
        $body->appendElement($shipment);

        $envelope->appendElement($header);
        $envelope->appendElement($body);

        $assembler = new Assembler();
        $envelope->accept($assembler);

        $doc = new DOMDocument('1.0', 'utf-8');
        $doc->load(__DIR__ . '/xml/bipro_ack.xml');

        $this->assertEqualXMLStructure($assembler->getDocument()->documentElement, $doc->documentElement);
    }
}