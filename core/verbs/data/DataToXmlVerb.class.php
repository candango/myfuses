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

/**
 * DataToXmlVerb  - DataToXmlVerb.php
 *
 * This verb will transform a php data to xml. It could return the data,
 * print it and stop the process.
 *
 * @category   controller
 * @package    myfuses.core.verbs.data
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      0d9c1b955d299bb5ff84971d7240d07d6dc86120
 */
class DataToXmlVerb extends AbstractVerb
{
    private $xmlName;

    private $value;

    private $root;

    private $die = true;

    private $clean = false;

    public function getXmlName()
    {
        return $this->xmlName;
    }

    public function setXmlName($xmlName)
    {
        $this->xmlName = $xmlName;
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
        
        if( is_null( $this->getXmlName() ) ) {
            $data['attributes']['name'] = $this->getXmlName();
        }
        
        if( !is_null( $this->getValue() ) ) {
            $data['attributes']['value'] = $this->getValue();
        }
        
        if( !is_null( $this->isClean() ) ) {
            $data['attributes']['clean'] = $this->isClean() ?
                "true" : "false";
        }
        
        if( !is_null( $this->isDie() ) ) {
            $data['attributes']['die'] = $this->isDie() ?
                "true" : "false";
        }

        if( !is_null( $this->getRoot() ) ) {
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

        if (isset($data['attributes']['name'])) {
            $this->setXmlName($data['attributes']['name']);
        }

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
        $strOut .= str_repeat("\t", $identLevel);

        $controllerName = $this->getAction()->getCircuit()->getApplication(
                            )->getControllerClass();

        if (is_null( $this->getXmlName())) {
            if ($this->isClean()) {
                $strOut .= str_repeat("\t", $identLevel);
                $strOut .= "ob_clean();\n";
            }
            $strOut .= "print(MyFusesXmlUtil::toXml(\"" .
                $this->getValue() . "\"));\n";

            if ($this->isDie()) {
                // Flushed global output buffer content
                $strOut .= str_repeat("\t", $identLevel);
                $strOut .= "\t\$strContent = " . $controllerName .
                    "::getInstance()->getResponseType() . \"; charset=\" . " .
                    $controllerName . "::getInstance()->getCurrentCircuit()->" .
                    "getApplication()->getCharacterEncoding();\n";
                $strOut .= str_repeat("\t", $identLevel);
                $strOut .= "\theader(\"Content-Type: \" . \$strContent);\n";
                $strOut .= str_repeat("\t", $identLevel);
                $strOut .= "\tob_end_flush();\n";
                $strOut .= str_repeat("\t", $identLevel);
                $strOut .= "die();\n";
            }
        }
        return $strOut;
    }
}
