# php-soap

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Dgame/php-soap/badges/quality-score.png?b=master&s=49f58edec3c1ad598272b2c5bd0d6eecccd35ac7)](https://scrutinizer-ci.com/g/Dgame/php-soap/?branch=master)

[![Build Status](https://travis-ci.org/Dgame/php-soap.svg?branch=master)](https://travis-ci.org/Dgame/php-soap)

## Bipro

### RequestTokenService
```php
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
```
results in
```xml
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Header>
    <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" soap:mustUnderstand="1">
      <wsse:UsernameToken>
        <wsse:Username>Foo</wsse:Username>
        <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">Bar</wsse:Password>
      </wsse:UsernameToken>
    </wsse:Security>
  </soap:Header>
  <soap:Body>
    <ns2:RequestSecurityToken xmlns="http://schemas.xmlsoap.org/ws/2004/08/addressing" xmlns:ns2="http://schemas.xmlsoap.org/ws/2005/02/trust" xmlns:ns3="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" xmlns:ns4="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:ns5="http://www.w3.org/2000/09/xmldsig#" xmlns:ns6="http://schemas.xmlsoap.org/ws/2004/09/policy">
      <ns2:TokenType>http://schemas.xmlsoap.org/ws/2005/02/sc/sct</ns2:TokenType>
      <ns2:RequestType>http://schemas.xmlsoap.org/ws/2005/02/trust/Issue</ns2:RequestType>
      <allgemein:BiPROVersion xmlns:allgemein="http://www.bipro.net/namespace/allgemein">2.1.6.1.1</allgemein:BiPROVersion>
    </ns2:RequestSecurityToken>
  </soap:Body>
</soap:Envelope>
```

### ListShipments
```php
$envelope = new Envelope();

$security = new Security();
$token    = new SecurityContextToken('bipro:7860072500822840554');
$security->appendNode($token);

$header = new Header();
$header->appendNode($security);

$request  = new Request(new Version('2.1.4.1.1'));
$shipment = new ListShipments($request);

$body = new Body();
$body->appendNode($shipment);

$envelope->appendNode($header);
$envelope->appendNode($body);

print '<pre>';
print htmlentities($envelope->assemble()->saveXML());
```
results in
```xml
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Header>
    <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" soap:mustUnderstand="1">
      <wsc:SecurityContextToken xmlns:wsc="http://schemas.xmlsoap.org/ws/2005/02/sc">
        <wsc:Identifier>bipro:7860072500822840554</wsc:Identifier>
      </wsc:SecurityContextToken>
    </wsse:Security>
  </soap:Header>
  <soap:Body>
    <transfer:ListShipments xmlns:transfer="http://www.bipro.net/namespace/transfer">
      <transfer:Request>
        <allgemein:BiPROVersion xmlns:allgemein="http://www.bipro.net/namespace/allgemein">2.1.4.1.1</allgemein:BiPROVersion>
        <transfer:BestaetigeLieferungen>false</transfer:BestaetigeLieferungen>
      </transfer:Request>
    </transfer:ListShipments>
  </soap:Body>
</soap:Envelope>
```

### GetShipment
```php
$envelope = new Envelope();

$security = new Security();
$token    = new SecurityContextToken('bipro:7860072500822840554');
$security->appendNode($token);

$header = new Header();
$header->appendNode($security);

$request             = new Request(new Version('2.1.4.1.1'));
$request->id         = 1;
$request->consumerId = 1;
$shipment            = new GetShipment($request);

$body = new Body();
$body->appendNode($shipment);

$envelope->appendNode($header);
$envelope->appendNode($body);

print '<pre>';
print htmlentities($envelope->assemble()->saveXML());
```
results in
```xml
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Header>
    <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" soap:mustUnderstand="1">
      <wsc:SecurityContextToken xmlns:wsc="http://schemas.xmlsoap.org/ws/2005/02/sc">
        <wsc:Identifier>bipro:7860072500822840554</wsc:Identifier>
      </wsc:SecurityContextToken>
    </wsse:Security>
  </soap:Header>
  <soap:Body>
    <transfer:GetShipment xmlns:transfer="http://www.bipro.net/namespace/transfer">
      <transfer:Request>
        <allgemein:BiPROVersion xmlns:allgemein="http://www.bipro.net/namespace/allgemein">2.1.4.1.1</allgemein:BiPROVersion>
        <transfer:ConsumerID>1</transfer:ConsumerID>
        <transfer:ID>1</transfer:ID>
        <transfer:BestaetigeLieferungen>false</transfer:BestaetigeLieferungen>
      </transfer:Request>
    </transfer:GetShipment>
  </soap:Body>
</soap:Envelope>
```
