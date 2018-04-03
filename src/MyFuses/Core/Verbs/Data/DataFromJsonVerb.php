<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2018 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

use Candango\MyFuses\Core\AbstractVerb;

/**
 * DataFromJsonVerb  - DataFromJsonVerb.php
 *
 * This verb will transform a json data to php.
 *
 * @category   controller
 * @package    myfuses.core.verbs.data
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      abc4657087ca6b6bbacd41ac18814c4a9b31be19
 */
class DataFromJsonVerb extends AbstractVerb
{
    private $url;

    private $varName;

    private $value;

    private $xfa;

    public function getVarName()
    {
        return $this->varName;
    }

    public function setVarName($varName)
    {
        $this->varName = $varName;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

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

    public function getData()
    {
        $data = parent::getData();
        $data['namespace'] = "data";

        if (!is_null($this->getVarName())) {
            $data['attributes']['name'] = $this->getVarName();
        }

        if (!is_null($this->getValue())) {
            $data['attributes']['value'] = $this->getValue();
        }

        if (!is_null($this->getUrl())) {
            $data['attributes']['url'] = $this->getUrl();
        }

        if (!is_null( $this->getXfa())) {
            $data['attributes']['xfa'] = $this->getXfa();
        }

        return $data;
    }

    /**
     * Set verb data
     * 
     * @param array $data
     */
    public function setData($data)
    {
        parent::setData($data);

        if (isset($data['attributes']['name'])) {
            $this->setVarName($data['attributes']['name']);
        }

        if (isset($data['attributes']['value'])) {
            $this->setValue($data['attributes']['value']);
        }

        if (isset($data['attributes']['url'])) {
            $this->setUrl($data['attributes']['url']);
        }

        if (isset($data['attributes']['xfa'])) {
            $this->setXfa($data['attributes']['xfa']);
        }
    }

    /**
     * Return the parsed code
     *
     * @return string
     */
    public function getParsedCode($commented, $identLevel)
    {
        $strOut = parent::getParsedCode($commented, $identLevel);
        $strOut .= str_repeat("\t", $identLevel);

        $strValue = "";
        $jsonUtilClass= "Candango\\MyFuses\\Util\\Data\\JsonUtil";
        if (is_null( $this->getValue())) {
            if (is_null($this->getUrl())) {
                $controllerClass = "Candango\\MyFuses\\Controller";
                $strValue = "#" . $jsonUtilClass . "::fromJsonUrl(" .
                    "" . $controllerClass . "::getMySelfXfa(\"" .
                    $this->getXfa() . "\"))#";
            } else {
                $strValue = "#" . $jsonUtilClass . "::fromJsonUrl(\"" .
                    $this->getUrl() . "\")#";
            }
        } else {
            $strOut .= "\$value = " . $jsonUtilClass . "::fromJson(\"" .
                $this->getValue() . "\");\n";

            $strValue = "#\$value#" ;
        }

        $strOut .= str_repeat("\t", $identLevel);
        $strOut .= self::getVariableSetString($this->getVarName(), $strValue);
        return $strOut;
    }
}
