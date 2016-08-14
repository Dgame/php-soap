<?php

use Dgame\Soap\Component\Bipro\Version;
use Dgame\Soap\Component\Bipro\RequestSecurityToken;
use Dgame\Soap\Component\Bipro\UsernameToken;
use Dgame\Soap\Component\Body;
use Dgame\Soap\Component\Envelope;
use Dgame\Soap\Component\Header;
use Dgame\Soap\Component\Security;

require_once 'vendor/autoload.php';

$envelope = new Envelope();

$security = new Security();
$token    = new UsernameToken('Foo', 'Bar');
$security->appendNode($token);

$header = new Header();
$header->appendNode($security);

$rst  = new RequestSecurityToken(new Version('2.1.6.1.1'));
$body = new Body();
$body->appendNode($rst);

$envelope->appendNode($header);
$envelope->appendNode($body);

print '<pre>';
print htmlentities($envelope->assemble()->saveXML());