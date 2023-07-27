<?php

namespace noIT\soap\components;

use noIT\soap\SoapServerModule as SOAP;
use yii\base\Component;
use yii\base\ErrorException;
use yii\helpers\Inflector;

class SoapRequestFormatter extends Component
{
    /**
     * This function expects that all nodes are declared in the target namespace of this webservice.
     * All attributes will be ignored
     * @param \DOMElement $root
     * @return mixed
     */
    protected static function convertToArray($root)
    {
        $result = [];
        $groups = [];
        $children = $root->childNodes;
        foreach ($children as $child) {
            if ($child->nodeType !== XML_ELEMENT_NODE) {
            //if (in_array($child->nodeType, [XML_TEXT_NODE, XML_CDATA_SECTION_NODE])) {
                continue;
            }

            if (!isset($result[$child->localName])) {
                $result[$child->localName] = self::convertToArray($child);
                if ($child->localName === "row") {
                    $result[$child->localName] = [$result[$child->localName]];
                    $groups[$child->localName] = true;
                }
            } else {
                if (!isset($groups[$child->localName])) {
                    $result[$child->localName] = [$result[$child->localName]];
                    $groups[$child->localName] = true;
                }
                $result[$child->localName][] = self::convertToArray($child);
            }
        }
        if (empty($result)) {
            return $root->nodeValue;
        }
        return $result;
    }

    /**
     * @return string
     */
    public static function getXmlErrors() {
        $errorsString = "";
        foreach (libxml_get_errors() as $error) {
            $errorsString .= "Error $error->code: " . trim($error->message) . ";";
        }
        libxml_clear_errors();
        return $errorsString;
    }

    public static function processRequest($request)
    {
        /** @var SOAP $module */
        $module = SOAP::getInstance();
        $module->log($request, "info", "SOAP_message");
        libxml_use_internal_errors(true);
        $loadExternalEntities = libxml_disable_entity_loader(true);
        $domObj = new \DOMDocument();
        if (!$domObj->loadXML($request)) {
            throw new ErrorException("Invalid XML: " . self::getXmlErrors(), 400);
        }
        libxml_disable_entity_loader(false);
        $schema = $module->params['configDir'] . DIRECTORY_SEPARATOR . "schemas" . DIRECTORY_SEPARATOR . "Soap.xsd";
        if (!is_readable($schema)) {
            throw new ErrorException("Can't find XMLSchema for SOAP request");
        }
        if (!$domObj->schemaValidate($schema, LIBXML_SCHEMA_CREATE)) {
            throw new ErrorException("XML is not valid: " . self::getXmlErrors(), 400);
        }
        libxml_disable_entity_loader($loadExternalEntities);
        $xPath = new \DOMXPath($domObj);
        $rootRequest = $xPath->query(
            '/*[local-name()="Envelope"]
                       /*[local-name()="Body"]
                       /node()
                       /node()[not(self::text()[not(normalize-space())])]')[0];
        return [
            'action' => $rootRequest->parentNode->localName,
            'action_id' => Inflector::camel2id($rootRequest->parentNode->localName),
            'request' => self::convertToArray($rootRequest)
        ];
    }
}