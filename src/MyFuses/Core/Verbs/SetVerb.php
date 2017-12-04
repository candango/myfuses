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

use Candango\MyFuses\Core\AbstractVerb;
use Candango\MyFuses\Process\Context;

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
    /**
     * Name to be carried by the variable being set by the verb
     *
     * @var string
     */
    private $variableName;

    /**
     * Value to be set to the variable
     *
     * @var string
     */
    private $value;

    /**
     * Flag indicating if the verb should evaluate the value or not.
     *
     * @var bool
     */
    private $evaluate = false;

    /**
     * Flag indicating if the verb should append the variable value to the
     * previous value.
     *
     * @var bool
     */
    private $append = false;

    /**
     * Returns the variable name being set by the verb
     *
     * @return string
     */
    public function getVariableName()
    {
        return $this->variableName;
    }

    /**
     * Defines the variname to be set by the verb
     *
     * @param $variableName
     */
    public function setVariableName($variableName)
    {
        $this->variableName = $variableName;
    }

    /**
     * Returns the value to be set to the variable by the verb
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the value to be set to the variable by the verb
     *
     * @param $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Returns true if the value should be evaluated
     *
     * @return bool
     */
    public function mustEvaluate()
    {
        return $this->evaluate;
    }

    /**
     * Set the verb to evaluate the value or not.
     *
     * @param bool $evaluate
     */
    public function setEvaluate($evaluate)
    {
        $this->evaluate = $evaluate;
    }

    /**
     * Returns if the verb must append the value or not.
     *
     * @return bool
     */
    public function mustAppend()
    {
        return $this->append;
    }

    /**
     * Set if the verb must append the value or not.
     *
     * @param bool $append
     */
    public function setAppend($append)
    {
        $this->append = $append;
    }

    /**
     * Return the data representing this verb
     *
     * @return mixed
     */
    public function getData()
    {
        $data = parent::getData();

        if (!is_null($this->getVariableName())) {
            $data['attributes']['name'] = $this->getVariableName();
        }

        if ($this->mustEvaluate()) {
            $data['attributes']['evaluate'] = "true";
        }

        if ($this->mustAppend()) {
            $data['attributes']['append'] = "true";
        }

        $data['attributes']['value'] = $this->getValue();
        return $data;
    }

    /**
     * Set this verb with the data provided.
     *
     * @param array $data
     */
    public function setData($data)
    {
        parent::setData($data);

        if (isset($data['attributes']['name'])) {
            $this->setVariableName($data['attributes']['name']);
        }

        if (isset($data['attributes']['evaluate'])) {
            if ($data['attributes']['evaluate'] == 'true') {
                $this->setEvaluate(true);
            }
        }

        if (isset($data['attributes']['append'])) {
            if ($data['attributes']['append'] == 'true') {
                $this->setAppend(true);
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
        $isArray = false;
        $arrayName = "";

        if (strpos($this->getVariableName(), "[") !== false) {
            $isArray = true;
            $variableNameX = explode("[", $this->getVariableName());
            $arrayName = $variableNameX[0];
        }

        $strOut = parent::getParsedCode($commented, $identLevel);

        // resolving evaluate parameter
        $value = "";

        if ($this->mustEvaluate()) {
            $value = "#eval(\"" . $this->getValue() . "\")#";
        } else {
            $value = $this->getValue();
        }

        if (is_null($this->getVariableName())) {
            $strOut .= str_repeat("\t", $identLevel);
            $strOut .= Context::sanitizeHashedString("\"" . $value .
                    "\"") . ";\n";
        }
        else {
            if ($isArray) {
                $strOut .= str_repeat("\t", $identLevel);
                $strOut .= "$" . $this->getVariableName() . " = "  .
                    Context::sanitizeHashedString("\"" . $value . "\"") .
                    ";\n";
                $strOut .= self::getVariableSetString($arrayName, "#$" .
                    $arrayName . "#", $identLevel, $this->mustAppend());
            }
            else{
                $strOut .= self::getVariableSetString($this->getVariableName(),
                    $value, $identLevel, $this->mustAppend());
            }
        }

        return $strOut; 
    }
}
