<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2020 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

use Candango\MyFuses\Core\AbstractVerb;
use Candango\MyFuses\Process\Context;


class SmartyAssignVerb extends AbstractVerb
{

    private $instanceName = "default";

    private $variableName;

    private $variableValue;

    /**
     *
     */
    public function getInstanceName()
    {
        return $this->instanceName;
    }

    /**
     *
     */
    public function setInstanceName($instanceName)
    {
        $this->instanceName = $instanceName;
    }

    /**
     *
     */
    public function getVariableName()
    {
        return $this->variableName;
    }

    /**
     *
     */
    public function setVariableName($variableName)
    {
        $this->variableName = $variableName;
    }

    /**
     *
     */
    public function getVariableValue()
    {
        return $this->variableValue;
    }

    /**
     *
     */
    public function setVariableValue($variableValue)
    {
        $this->variableValue = $variableValue;
    }

    /**
     * Get verb data
     *
     * @return array
     */
    public function getData()
    {
        $data = parent::getData();
        if($this->getInstanceName() != "default")
        {
            $data['attributes']['template'] = $this->getInstanceName();
        }
        $data['attributes']['name'] = $this->getVariableName();
        $data['attributes']['value'] = $this->getVariableValue();
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
        if(isset($data['attributes']['template']))
        {
            $this->setInstanceName($data['attributes']['name']);
        }
        if(isset($data['attributes']['name']))
        {
            $this->setVariableName($data['attributes']['name']);
        }
        if(isset($data['attributes']['value']))
        {
            $this->setVariableValue($data['attributes']['value']);
        }
    }

    /**
     * Return the parsed code
     *
     * @return string
     */
    public function getParsedCode($commented, $identLevel)
    {
        $strOut = parent::getParsedCode($commented, $identLevel );
        $strTemplateHandler = "Candango\MyFuses\Util\Template\TemplateHandler";
        $strOut .= str_repeat("\t", $identLevel);
        $strOut .= "\$templateHandler = " . $strTemplateHandler .
            "::getInstance(\"" . $this->getInstanceName() . "\");\n";
        $strOut .= str_repeat("\t", $identLevel);
        if (is_null($this->getVariableName())) {
            $strOut .= str_repeat("\t", $identLevel);
            $strOut .= Context::sanitizeHashedString(
                "\$templateHandler->assign(\"" . $this->getVariableName() .
                "\", \"\"") . ");\n\n";
        }
        else {
            $strOut .= Context::sanitizeHashedString(
                    "\$templateHandler->assign(\"" . $this->getVariableName() .
                    "\", \"" . $this->getVariableValue() ) . "\");\n\n";
        }
        return $strOut;
    }

}
