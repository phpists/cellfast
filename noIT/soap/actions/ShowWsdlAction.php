<?php

namespace noIT\soap\actions;

use noIT\soap\SoapServerModule as SOAP;
use yii\base\Action;
use yii\base\ErrorException;
use yii\helpers\BaseFileHelper;

class ShowWsdlAction  extends Action
{
    /**
     * @param \DOMNodeList|\DOMNode $node
     * @return void
     */
    protected function removeDomNode($node)
    {
        if ($node instanceof \DOMNodeList) {
            foreach ($node as $nodeItem) {
                $nodeItem->parentNode->removeChild($nodeItem);
            }
        }
        elseif ($node instanceof \DOMNode) {
            $node->parentNode->removeChild($node);
        }
    }

    /**
     * @param \DOMNodeList|\DOMNode $node
     * @param \DOMNode $nodeContainer
     * @param boolean $deep
     * @return void
     */
    protected function importDomNode($node, $nodeContainer, $deep = true)
    {
        if ($node instanceof \DOMNodeList) {
            foreach ($node as $nodeItem) {
                $nodeContainer->insertBefore(
                    $nodeContainer->ownerDocument->importNode($nodeItem, $deep)
                );
            }
        }
        elseif ($node instanceof \DOMNode) {
            $nodeContainer->insertBefore(
                $nodeContainer->ownerDocument->importNode($node, $deep)
            );
        }
    }

    /**
     * @param string $pathToXml
     * @param \DOMNode|null $targetContainer
     * @return \DOMDocument|null
     */
    protected function makeExportableFile($pathToXml, $targetContainer = null)
    {
        if (!is_readable($pathToXml)) {
            return null;
        }
        $domObj = new \DOMDocument();
        if (!$domObj->load($pathToXml)) {
            return null;
        }
        $xPath = new \DOMXPath($domObj);
        $isFirstCall = false;
        if ($targetContainer === null) {
            $isFirstCall = true;
            $nodeTypesList = $xPath->query('//*[local-name()="types"]');
            $targetContainer = ($nodeTypesList->length !== 0)
                ? $nodeTypesList->item(0)
                : $domObj->documentElement->insertBefore(
                    $domObj->createElementNS($domObj->documentElement->namespaceURI, "types"),
                    $domObj->documentElement->firstChild);
        }
        $this->removeDomNode($xPath->query(
            '//*[local-name()="documentation"] | //*[local-name()="annotation"]'));
        $this->removeDomNode($xPath->query('//comment()'));
        $nodeList = $xPath->query('//*[local-name()="include"] | //*[local-name()="import"]');
        foreach ($nodeList as $nodeItem) {
            /** @var \DOMElement $nodeItem */
            if (!empty($nodeItem->getAttribute("location"))) {
                $relativePath = $nodeItem->getAttribute("location");
                $isSchema = false;
            }
            elseif (!empty($nodeItem->getAttribute("schemaLocation"))) {
                if ($nodeItem->localName === "import") {
                    $nodeItem->removeAttribute("schemaLocation");
                    continue;
                }
                $relativePath = $nodeItem->getAttribute("schemaLocation");
                $isSchema = true;
                $targetContainer = $domObj->documentElement;
            }
            else {
                $this->removeDomNode($nodeItem);
                continue;
            }
            $domToReplace = $this->makeExportableFile(
                BaseFileHelper::normalizePath(dirname($pathToXml) . DIRECTORY_SEPARATOR . $relativePath),
                $targetContainer
            );
            $domToReplace = ($isSchema) ? $domToReplace->documentElement : $domToReplace;
            $this->importDomNode($domToReplace->childNodes, $targetContainer);
            $this->removeDomNode($nodeItem);
        }
        if ($isFirstCall) {
            $nodeItem = $xPath->query('//*[local-name()="definitions"]/*[local-name()="service"]
                        /*[local-name()="port"]/*[local-name()="address"]')[0];
            if ($nodeItem !== null) {
                $nodeItem->setAttribute("location",
                    \Yii::$app->getRequest()->getHostInfo() . DIRECTORY_SEPARATOR . SOAP::getInstance()->id);
            }
            if (\Yii::$app->request->getUserAgent() === "1C+Enterprise/8.3") {
                $this->removeDomNode($xPath->query(
                    '//*[local-name()="definitions"]/*[local-name()="types"]/*[local-name()="schema" and
                    @targetNamespace="http://v8.1c.ru/8.1/data/core"]'));
            }
        }
        return $domObj;
    }

    public function run()
    {
        $module = SOAP::getInstance();
        $module->log("Activated route 'soap-actions/show-wsdl'", "info");
        $pathToWsdl = $module->params['configDir'] . DIRECTORY_SEPARATOR . $module->params['wsdlFile'];
        $result = $this->makeExportableFile($pathToWsdl);
        if ($result === null) {
            throw new ErrorException("Unable to locate wsdl file", 500);
        }
        $response = \Yii::$app->getResponse();
        $result->formatOutput = true;
        $response->content = $result->saveXML();
        $module->log("Completed route 'soap-actions/show-wsdl'", "info");
        return $response;
    }
}