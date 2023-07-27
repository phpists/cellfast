<?php
return [
    'defaultRoute' => "frontend",
    'targetNameSpace' => "http://cellfast.ua/webapi",
    'wsdlFile' => "CellfastService.wsdl",
    'debug' => true, // TODO don't forget change to false in prod env
    'logSoapMessageFile' => "logs/soap_message.log",
    'soapContentType' => "application/xml",
//    'userAgent' => "1C+Enterprise/8.3",
];
