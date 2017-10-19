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
 * DebugDumpVerb  - DebugDumpVerb.php
 *
 * Provides a var_dump verb. Can be helpful for debugging.
 *
 * @category   controller
 * @package    myfuses.core.verbs.debug
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      f7e2c2ed2897bb16a0eab9a5c4cfc6a1fbd87205
 */
class DebugDumpVerb extends AbstractVerb
{
    private $variable;

    private $die = false;

    public function getVariable()
    {
        return $this->variable;
    }

    public function setVariable($variable)
    {
        $this->variable = $variable;
    }

    public function isDie()
    {
        return $this->die;
    }

    public function setDie($die)
    {
        if (is_bool($die)) {
            $this->die = (boolean) $die;
        } else {
            $this->die = ($die == "true") ? true : false;
        }
    }

    /**
     * Set verb data
     * 
     * @param array $data
     * @throws MyFusesVerbException
     */
    public function setData($data)
    {
        parent::setData($data);

        if (isset($data['attributes']['variable'])) {
            $this->setVariable($data['attributes']['variable']);
        } else  {
            $params = $this->getErrorParams();
            $params[ 'attrName' ] = "variable";
            throw new MyFusesVerbException($params,
                MyFusesVerbException::MISSING_REQUIRED_ATTRIBUTE);
        }

        if (isset($data['attributes']['die'])) {
            $this->setDie($data['attributes']['die']);
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
        $strOut .= "var_dump(" . $this->getVariable() . ");\n";

        if($this->isDie()) {
            $strOut .= str_repeat("\t", $identLevel);
            $strOut .= "die();\n";
        }
        return $strOut;
    }
}
