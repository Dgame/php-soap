<?php

use Dgame\Soap\Component\BiPROVersion;
use Dgame\Soap\Component\Body;
use Dgame\Soap\Component\Envelope;
use Dgame\Soap\Component\Header;
use Dgame\Soap\Component\RequestSecurityToken;
use Dgame\Soap\Component\Security;
use Dgame\Soap\Component\UsernameToken;
use Dgame\Soap\Hydrator\Dom\Assembler;

require_once 'vendor/autoload.php';

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

$assembler = new Assembler();
$envelope->accept($assembler);

print $assembler->getDocument()->saveXML();