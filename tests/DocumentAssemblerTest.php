<?php

use Dgame\Soap\Component\Bipro\AcknowledgeShipment;
use Dgame\Soap\Component\Bipro\GetShipment;
use Dgame\Soap\Component\Bipro\ListShipments;
use Dgame\Soap\Component\Bipro\Request;
use Dgame\Soap\Component\Bipro\RequestSecurityToken;
use Dgame\Soap\Component\Bipro\SecurityContextToken;
use Dgame\Soap\Component\Bipro\UsernameToken;
use Dgame\Soap\Component\Bipro\Version;
use Dgame\Soap\Component\Body;
use Dgame\Soap\Component\Envelope;
use Dgame\Soap\Component\Header;
use Dgame\Soap\Component\Security;
use PHPUnit\Framework\TestCase;

class DocumentAssemblerTest extends TestCase
{
    public function testLoginRequestOutput()
    {
        $envelope = new Envelope();

        $security = new Security();
        $token    = new UsernameToken('Foo', 'Bar');
        $security->attachElement($token);

        $header = new Header();
        $header->attachElement($security);

        $rst  = new RequestSecurityToken(new Version('2.1.6.1.1'));
        $body = new Body();
        $body->attachElement($rst);

        $envelope->attachElement($header);
        $envelope->attachElement($body);

        $d1                     = new DOMDocument('1.0', 'utf-8');
        $d1->formatOutput       = false;
        $d1->preserveWhiteSpace = false;
        $d1->load(dirname(__FILE__) . '/xml/login_request.xml');

        $d2                     = new DOMDocument('1.0', 'utf-8');
        $d2->formatOutput       = false;
        $d2->preserveWhiteSpace = false;

        $envelope->assemble($d2);

        $this->assertEquals($d1->saveXML(), $d2->saveXML());
    }

    public function testListShipmentOutput()
    {
        $envelope = new Envelope();

        $security = new Security();
        $token    = new SecurityContextToken('bipro:7860072500822840554');
        $security->attachElement($token);

        $header = new Header();
        $header->attachElement($security);

        $request  = new Request(new Version('2.1.4.1.1'));
        $shipment = new ListShipments($request);

        $body = new Body();
        $body->attachElement($shipment);

        $envelope->attachElement($header);
        $envelope->attachElement($body);

        $d1                     = new DOMDocument('1.0', 'utf-8');
        $d1->formatOutput       = false;
        $d1->preserveWhiteSpace = false;
        $d1->load(dirname(__FILE__) . '/xml/list_shipment.xml');

        $d2                     = new DOMDocument('1.0', 'utf-8');
        $d2->formatOutput       = false;
        $d2->preserveWhiteSpace = false;

        $envelope->assemble($d2);

        $this->assertEquals($d1->saveXML(), $d2->saveXML());
    }

    public function testGetShipmentOutput()
    {
        $envelope = new Envelope();

        $security = new Security();
        $token    = new SecurityContextToken('bipro:7860072500822840554');
        $security->attachElement($token);

        $header = new Header();
        $header->attachElement($security);

        $request = new Request(new Version('2.1.4.1.1'));
        $request->setConsumerId(2);
        $request->setId(1);
        $shipment = new GetShipment($request);

        $body = new Body();
        $body->attachElement($shipment);

        $envelope->attachElement($header);
        $envelope->attachElement($body);

        $d1                     = new DOMDocument('1.0', 'utf-8');
        $d1->formatOutput       = false;
        $d1->preserveWhiteSpace = false;
        $d1->load(dirname(__FILE__) . '/xml/get_shipment.xml');

        $d2                     = new DOMDocument('1.0', 'utf-8');
        $d2->formatOutput       = false;
        $d2->preserveWhiteSpace = false;

        $envelope->assemble($d2);

        $this->assertEquals($d1->saveXML(), $d2->saveXML());
    }

    public function testAcknowledgeShipmentOutput()
    {
        $envelope = new Envelope();

        $security = new Security();
        $token    = new SecurityContextToken('bipro:7860072500822840554');
        $security->attachElement($token);

        $header = new Header();
        $header->attachElement($security);

        $request = new Request(new Version('2.1.4.1.1'));
        $request->setConsumerId(2);
        $request->setId(1);
        $shipment = new AcknowledgeShipment($request);

        $body = new Body();
        $body->attachElement($shipment);

        $envelope->attachElement($header);
        $envelope->attachElement($body);

        $d1                     = new DOMDocument('1.0', 'utf-8');
        $d1->formatOutput       = false;
        $d1->preserveWhiteSpace = false;
        $d1->load(dirname(__FILE__) . '/xml/ack_shipment.xml');

        $d2                     = new DOMDocument('1.0', 'utf-8');
        $d2->formatOutput       = false;
        $d2->preserveWhiteSpace = false;

        $envelope->assemble($d2);

        $this->assertEquals($d1->saveXML(), $d2->saveXML());
    }
}