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

require_once "myfuses/core/verbs/ParameterizedVerb.class.php";

/**
 * IncludeVerb  - IncludeVerb.php
 * 
 * This verb includes one file in processes exection.
 *
 * @category   controller
 * @package    myfuses.core.verbs
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      cea291d61da569150c494630909371bd6ff6e3c
 */
class IncludeVerb extends ParameterizedVerb
{
    /**
     * Verb file
     *
     * @var string
     */
    private $file;

    /**
     * The circuit name
     * 
     * @var string
     */
    private $circuitName = "";

    /**
     * The include content variable
     * 
     * @var string
     */
    private $contentVariable;

    /**
     * Return the verb file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set the verb file
     *
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Return the circuit name
     *
     * @return string
     */
    public function getCircuitName()
    {
        return $this->circuitName;
    }

    /**
     * Set the circuit circuitName
     *
     * @param string $circuitName
     */
    public function setCircuitName($circuitName)
    {
        $this->circuitName = $circuitName;
    }

    /**
     * Return the content variable
     *
     * @return string
     */
    public function getContentVariable()
    {
        return $this->contentVariable;
    }

    /**
     * Set the content variable
     *
     * @param string $contentVariable
     */
    public function setContentVariable($contentVariable)
    {
        $this->contentVariable = $contentVariable;
    }

    public function getData()
    {
        $data = parent::getData();
        $data['attributes']['file'] = $this->getFile();

        if (!is_null($this->getContentVariable())) {
            $data['attributes']['contentvariable'] =
                $this->getContentVariable();
        }
        return $data;
    }

    public function setData($data)
    {
        parent::setData($data);

        foreach ($data['attributes'] as $attributeName => $attribute) {
            switch (strtolower($attributeName)) {
                case "circuit":
                    $this->setCircuitName($attribute);
                    break;
                case "contentvariable":
                case "variable":
                    $this->setContentVariable($attribute);
                    break;
                case "file":
                case "template":
                    $file = $attribute;
                    if (!MyFusesFileHandler::hasExtension($file, "php")) {
                        $file .= ".php";
                    }
                    $this->setFile($file);
                    break;
            }
        }
    }

	/**
     * Return the real parsed code
     *
     * @return string
     */
    public function getRealParsedCode($commented, $identLevel)
    {
        $appName = $this->getAction()->getCircuit()->
            getApplication()->getName();

        if ($this->getCircuitName() != "") {
            $circuitName = $this->getCircuitName();    
        } else {
            $circuitName = $this->getAction()->getCircuit()->getName();
        }

        $controllerClass = $this->getAction()->getCircuit()->
	        getApplication()->getControllerClass();

        $fileCall = $controllerClass . "::getInstance()->getApplication( \"" . 
            $appName . "\" )->getCircuit( \"" . $circuitName .
            "\" )->getCompletePath()";

        $strOut = str_repeat( "\t", $identLevel );
        $strOut .= $this->getIncludeFileString($fileCall . "." .
            " DIRECTORY_SEPARATOR . \"" . $this->getFile() . "\"", 
            $this->getContentVariable());
        return $strOut;
    }
}
