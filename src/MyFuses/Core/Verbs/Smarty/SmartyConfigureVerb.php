<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2020 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

use Candango\MyFuses\Core\AbstractVerb;


class SmartyConfigureVerb extends AbstractVerb
{

    private $instanceName = "default";

    private $theme = "";

    private $type = "smarty";

    private $path;

    private $children = array();


    /**
     *
     */
    public function getInstanceName() {
        return $this->instanceName;
    }

    /**
     *
     */
    public function setInstanceName($instanceName) {
        $this->instanceName = $instanceName;
    }


    /**
     *
     */
    public function getTheme() {
        return $this->theme;
    }

    /**
     *
     */
    public function setTheme($theme) {
        $this->theme = $theme;
    }

    /**
     *
     */
    public function getPath() {
        return str_replace("\\","/",$this->path);
    }

    /**
     *
     */
    public function setPath($path)
    {
        if(substr( $path, -1 ) != DIRECTORY_SEPARATOR) {
            $path = $path . DIRECTORY_SEPARATOR;
        }
        $this->path = $path;
    }

    /**
     * Get verb data
     *
     * @return array
     */
    public function getData()
    {
        $data = parent::getData();
        $data["attributes" ]["name"] = $this->getInstanceName();
        $data["attributes" ]["theme"] = $this->getTheme();
        $data["attributes" ]["path"] = $this->getPath();
        if( count( $this->children ) < 0) {
            $data[ "children" ] = $this->children;
        }
        return $data;
    }

    /**
     * Set verb data
     *
     * @param array $data
     */
    public function setData( $data )
    {
        parent::setData($data);
        if(isset($data["attributes"]["name"]))
        {
            if ($data["attributes"]["name"]!="default" &&
                $data["attributes"]["name"]!="")
            $this->setInstanceName($data["attributes"]["name"]);
        }
        if(isset($data["attributes"]["theme"]))
        {
            $this->setTheme($data["attributes"]["theme"]);
        }
        if(isset($data["attributes"]["path"]))
        {
            $this->setPath($data["attributes"]["path"]);
        } else {
            $this->setPath("templates");
        }
        if( isset($data["children"])) {
            $this->children = $data["children"];
        }
    }

    /**
     * Return the parsed code
     *
     * @return string
     */
    public function getParsedCode($commented, $identLevel)
    {
        $applicationPath =
            $this->getAction()->getCircuit()->getApplication()->getPath();

        $applicationName =
            $this->getAction()->getCircuit()->getApplication()->getName();

        $parsedPath = $this->getAction()->getCircuit()->getApplication()->
        getController()->getParsedPath();

        $applicationParsedPath = $parsedPath . DIRECTORY_SEPARATOR .
            $applicationName . DIRECTORY_SEPARATOR . "smarty" .
            DIRECTORY_SEPARATOR . "templates_c" . DIRECTORY_SEPARATOR;

        $strOut = parent::getParsedCode( $commented, $identLevel );


        $strName= "";

        if($this->getName() != "default")
        {
            $strName = " , \"" . $this->getInstanceName() . "\"";
        }

        if($this->getTheme() != "")
        {
            $property = array(
                "name" => "templateProperty",
                "namespace" => "smarty",
                "attributes" => array(
                    "name" => "theme",
                    "value" => addslashes($this->getTheme()) ));

            $this->templateParameters[] = $property;
        }

        $property = array(
            "name" => "templateProperty",
            "namespace" => "smarty",
            "attributes" => array(
                "name" => "applicationPath",
                "value" => addslashes($applicationPath) ));

        $this->templateParameters[] = $property;

        $property = array(
            "name" => "templateProperty",
            "namespace" => "smarty",
            "attributes" => array(
                "name" => "applicationParsedPath",
                "value" => addslashes($applicationParsedPath) ));

        $this->templateParameters[] = $property;
        $strOut .= str_repeat("\t", $identLevel);
        $strOut .= "\$parameters = array();\n";

        $strTemplateHandler = "Candango\MyFuses\Util\Template\TemplateHandler";

        foreach($this->templateParameters as $parameter)
        {
            $strOut .= str_repeat( "\t", $identLevel );
            $strOut .= "\$parameters[] = " . $strTemplateHandler .
                "::buildParameter" . "(\"" . $parameter['attributes']['name'] .
                "\", \"" . $parameter[ 'attributes' ]['value'] . "\");\n";
        }
        $strOut .= str_repeat( "\t", $identLevel );
        $strOut .= $strTemplateHandler . "::" . "configureHandler(\"" .
            $this->type . "\", \$parameters" . $strName .
            ");\n\n";
        return $strOut;
    }

}
