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
 * DataToJsonVerb  - DataToJsonVerb.php
 *
 * This verb will transform a php data to json. It could return the data,
 * print it and stop the process.
 *
 * @category   controller
 * @package    myfuses.core.verbs.data
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      ad02ee2bddf16c81259065fe9b8fbb5e8a911d06
 */
class DataToJsonVerb extends AbstractVerb
{
    private $jsonName;

    private $value;

    private $root;

    private $die = true;

    private $clean = false;

    public function getJsonName()
    {
        return $this->jsonName;
    }

    public function setJsonName($jsonName)
    {
        $this->jsonName = $jsonName;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function isDie()
    {
        return $this->die;
    }

    public function setDie($die)
    {
        $this->die = $die;
    }

    public function isClean()
    {
        return $this->clean;
    }

    public function setClean($clean)
    {
        $this->clean = $clean;
    }

    public function getRoot()
    {
        return $this->root;
    }

    public function setRoot($root)
    {
        $this->root = $root;
    }

    public function getData()
    {
        $data = parent::getData();
        $data['namespace'] = "data";

        if (!is_null( $this->getValue())) {
            $data['attributes']['value'] = $this->getValue();
        }

        if (!is_null($this->isClean())) {
            $data['attributes']['clean'] = $this->isClean() ? "true" : "false";
        }

        if(!is_null($this->isDie())) {
            $data['attributes']['die'] = $this->isDie() ? "true" : "false";
        }

        if (!is_null($this->getRoot())) {
            $data['attributes']['root'] = $this->getRoot();
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

        if (isset($data['attributes']['value'])) {
            $this->setValue($data['attributes']['value']);
        }

        if (isset($data['attributes']['clean'])) {
            $this->setClean(strtolower(
                $data['attributes']['clean']) == "true" ? true : false);
        }

        if (isset($data['attributes']['die'])) {
            $this->setDie(strtolower(
                $data['attributes']['die']) == "true" ? true : false);
        }

        if (isset($data['attributes']['root'])) {
            $this->setRoot($data['attributes']['root']);
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

        $controllerName = $this->getAction()->getCircuit()->getApplication(
                            )->getControllerClass();

        if (is_null($this->getJsonName())) {
            if ($this->isClean()) {
                $strOut .= str_repeat("\t", $identLevel);
                $strOut .= "ob_clean();\n";
            }

            $strOut .= str_repeat("\t", $identLevel);
            $jsonUtilClass= "Candango\\MyFuses\\Util\\Data\\JsonUtil";
            $strOut .= "echo(" . $jsonUtilClass . "::toJson(\"" .
                $this->getValue() . "\"));\n";

            if ($this->isDie()) {
                // Flushed global output buffer content
                $strOut .= str_repeat("\t", $identLevel);
                $strOut .= "\$strContent = " . $controllerName .
                    "::getInstance()->getResponseType() . \"; charset=\" . " .
                    $controllerName . "::getInstance()->getCurrentCircuit()->" .
                    "getApplication()->getCharacterEncoding();\n";
                $strOut .= str_repeat("\t", $identLevel);
                $strOut .= "header(\"Content-Type: \" . \$strContent);\n";
                $strOut .= str_repeat("\t", $identLevel);
                $strOut .= "ob_end_flush();\n";
                $strOut .= str_repeat("\t", $identLevel);
                $strOut .= "die();\n";
            }
        }
        $strOut .= "\n";
        return $strOut;
    }
}
