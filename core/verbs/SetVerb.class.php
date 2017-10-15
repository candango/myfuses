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
 * SetVerb  - SetVerb.php
 * 
 * This verb will create or set php global variables in runtime.
 *
 * @category   controller
 * @package    myfuses.core.verbs
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      5a0c505168c823101071032bec66f107a870dfff
 */
class SetVerb extends AbstractVerb
{
    private $variableName;

    private $value;

    private $evaluate = false;

    public function getVariableName()
    {
        return $this->variableName;
    }

    public function setVariableName($variableName)
    {
        $this->variableName = $variableName;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function isEvaluate()
    {
        return $this->evaluate;
    }

    public function setEvaluate($evaluate)
    {
        $this->evaluate = $evaluate;
    } 

    public function getData()
    {
        $data = parent::getData();

        if (!is_null($this->getVariableName())) {
            $data['attributes']['name'] = $this->getVariableName();
        }

        if ($this->isEvaluate()) {
            $data['attributes']['evaluate'] = "true";
        }
        $data['attributes']['value'] = $this->getValue();
        return $data;
    }
    
    public function setData($data)
    {
        parent::setData($data);

        if (isset($data['attributes']['name'])) {
            $this->setVariableName($data['attributes']['name']);
        }

        if (isset($data['attributes']['evaluate'])) {
            if ($data['attributes']['evaluate'] == 'true') {
                $this->setVariableName(true);
            }
        }

        $this->setValue($data['attributes']['value']);
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

        // resolving evaluate parameter
        $value = "";
        if ($this->isEvaluate()) {
            $value = "#" . $this->getValue() . "#";
        } else {
            $value = $this->getValue();
        }
        
        if (is_null($this->getVariableName())) {
            $strOut .= MyFusesContext::sanitizeHashedString("\"" . $value .
                    "\"") . ";\n";
        }
        else{
            $strOut .= self::getVariableSetString($this->getVariableName(),
                $value);
        }

        return $strOut; 
    }
}
