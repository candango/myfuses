<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * This product includes software developed by the Fusebox Corporation
 * (http://www.fusebox.org/).
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2017 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

namespace Candango\MyFuses\Core\Verbs;

/**
 * RelocateVerb  - RelocateVerb.php
 * 
 * Verb is used to redirect the browser to another url or xfa.
 *
 * @category   controller
 * @package    myfuses.core.verbs
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      5a0c505168c823101071032bec66f107a870dfff
 */
class RelocateVerb extends ParameterizedVerb
{
    /**
     * Url that verb will relocate to
     *
     * @var string
     */
    private $url;

    /**
     * eXit FuseAction that will be used to resolve the verb url
     *
     * @var string
     */
    private $xfa;

    private $variable = true;

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getXfa()
    {
        return $this->xfa;
    }

    public function setXfa($xfa)
    {
        $this->xfa = $xfa;
    }

    public function getVariable()
    {
        return $this->variable ? "true" : "false";
    }

    public function setVariable($variable)
    {
        $this->variable = ($variable === "true") ? true : false;
    }

    public function getData()
    {
        $data = parent::getData();
        if (!is_null($this->getUrl())) {
            $data['attributes']['url'] = $this->getUrl();
        }

        if (!is_null($this->getXfa())) {
            $data['attributes']['xfa'] = $this->getXfa();
        }

        return $data;
    }

    public function setData($data)
    {
        parent::setData($data);

        if (isset($data['attributes']['url'])) {
            $this->setUrl($data['attributes']['url']);
        }

        if(isset($data['attributes']['xfa'])) {
            $this->setXfa($data['attributes']['xfa']);
        }
    }

    /**
     * Return the parsed code
     *
     * @return string
     */
    public function getRealParsedCode($commented, $identLevel)
    {
        $strOut = str_repeat("\t", $identLevel);

        $controllerClass = $this->getAction()->getCircuit()->
	        getApplication()->getControllerClass();

	    $url = "";
        $arguments = "";
        $queryString = "";

        if (count($this->getParameters())) {
           $arguments = ", true";
            foreach ($this->getParameters() as $key => $value) {
                $queryString .= (($queryString == "") ? "" : "&") . $key . "=" .
                    $value;
            }
            $queryString = "\"" . $queryString . "\"";
        }

	    if (is_null($this->getXfa())) {
	        $url = "\"" . $this->getUrl() . "\" " .
                (($queryString == "") ? "" : ".") . $queryString;
	    } else {
	        $url = $controllerClass . "::getMySelfXfa(\"" .
                $this->getXfa() . "\"" . $arguments . ") " .
                (($queryString == "" ) ? "" : ".") .  $queryString;
	    }
	    $strOut .=  $controllerClass . "::sendToUrl(" . $url . ");\n\n";
        return $strOut;
    }
}
