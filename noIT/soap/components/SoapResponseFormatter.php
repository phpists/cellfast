<?php

namespace noIT\soap\components;

use noIT\soap\SoapServerModule as SOAP;
use yii\base\Component;
use yii\base\ErrorException;
use yii\web\Response;
use yii\web\ResponseFormatterInterface;

class SoapResponseFormatter extends Component implements ResponseFormatterInterface
{
    const NS_XMLNS = "http://www.w3.org/2000/xmlns/";
    const NS_SOAP_ENVELOPE = "http://schemas.xmlsoap.org/soap/envelope/";
    /* It is only necessary for the rpc encoded style
    const NS_SOAP_ENC = "http://schemas.xmlsoap.org/soap/encoding/";
    const NS_XML_SCHEMA = "http://www.w3.org/2001/XMLSchema";
    const NS_XML_SCHEMA_INSTANCE = "http://www.w3.org/2001/XMLSchema-instance";*/

    /** @var  \DOMDocument $dom */
    protected $dom;

    protected $tns;

    /**
     * This function expects that all nodes are declared in the target namespace of this webservice.
     * @param array $root
     * @param \DOMElement | \DOMNode $parentNode
     * @return void
     */
    protected function convertFromArray($root, $parentNode) {
        if (!is_array($root)) {
            return;
        }
        foreach ($root as $key => $value) {
            if (is_array($value)) {
                $parentNode = $parentNode->appendChild($this->dom->createElementNS($this->tns, $key));
                $this->convertFromArray($value, $parentNode);
            } else {
                $parentNode->appendChild($this->dom->createElementNS($this->tns, $key, $value));
            }
        }
    }

    /**
     * @return array
     */
    protected function createSoapWrapper($charset)
    {
        $this->dom = new \DOMDocument("1.0", $charset);
        $this->dom->formatOutput = true;
        $this->tns = SOAP::getInstance()->params['targetNameSpace'];
        $elemEnvelope = $this->dom->createElementNS(self::NS_SOAP_ENVELOPE,
            "SOAP-ENV:Envelope");
        $elemEnvelope->setAttributeNS(self::NS_XMLNS,
            "xmlns:ns1", $this->tns);
        /* It is only necessary for the rpc encoded style
        $elemEnvelope->setAttribute("SOAP-ENV:encodingStyle", self::NS_SOAP_ENC);
        $elemEnvelope->setAttributeNS(self::NS_XMLNS,
            "xmlns:xsd", self::NS_XML_SCHEMA);
        $elemEnvelope->setAttributeNS(self::NS_XMLNS,
            "xmlns:xsi", self::NS_XML_SCHEMA_INSTANCE);*/
        $elemHeader = $this->dom->createElementNS(self::NS_SOAP_ENVELOPE,
            "SOAP-ENV:Header");
        $elemBody = $this->dom->createElementNS(self::NS_SOAP_ENVELOPE,
            "SOAP-ENV:Body");
        $elemEnvelope->appendChild($elemHeader);
        $elemEnvelope->appendChild($elemBody);
        $this->dom->appendChild($elemEnvelope);
        return [
            'envelope' => $elemEnvelope,
            'header' => $elemHeader,
            'body' => $elemBody
        ];
    }

    public function format($response)
    {
        if ($response->data === null && $response->content === null && $response->stream === null) {
            throw new ErrorException("Empty response. Output buffer: " . ob_get_contents());
        }
        if (ob_get_length() !== false) {
            ob_end_clean();
        }
        if (!empty($response->content)) {
            return;
        }
        $wrapper = $this->createSoapWrapper($response->charset);
        if (is_array($response->data)) {
            $this->convertFromArray($response->data, $wrapper['body']);
        } elseif ($response->data instanceof \Exception || $response->data instanceof \Error) {
            $fault = $this->dom->createElementNS(self::NS_SOAP_ENVELOPE, "SOAP-ENV:Fault");
            $fault->appendChild($this->dom->createElement("faultcode",
                ($response->isClientError) ? "SOAP-ENV:Client" : "SOAP-ENV:Server"));
            $fault->appendChild($this->dom->createElement("faultstring",
                "Status: " . $response->statusCode . " - " . $response->statusText .
                ". Description: " . $response->data->getMessage()));
            $wrapper['body']->appendChild($fault);
        } else {
            throw new ErrorException("Unknown type of response data");
        }
        $response->content = $this->dom->saveXML();
        /** @var SOAP $module */
        $module = SOAP::getInstance();
        $module->log($response->content, "info", "SOAP_message");
    }

    public static function initResponse($contentType)
    {
        $response = \Yii::$app->getResponse();
        $response->clear();
        $response->format = Response::FORMAT_XML;
        $response->formatters['xml'] = ['class' => self::className()];
        $response->getHeaders()->set('Content-Type', "{$contentType}; charset=" . $response->charset);

        return $response;
    }
}
