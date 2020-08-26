<?php
/**
 * TemplateHandler - TemplateHandler.php
 *
 * Handles the Smarty Template Engine
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2020 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

namespace Candango\MyFuses\Util\Template\Smarty;

use Candango\MyFuses\Controller;
use Candango\MyFuses\Util\Template\TemplateHandler;
use Candango\MyFuses\Util\FileHandler;


class SmartyTemplateHandler extends TemplateHandler
{

    /**
     *
     * @var \Smarty
     */
    private $smarty;

    private $templateList = array();

    public function __construct()
    {

        $this->smarty = new \Smarty();
        $this->smarty->left_delimiter =  "{";
        $this->smarty->right_delimiter =  "}";

        $this->smarty->compile_check = true;

        $this->smarty->setPluginsDir(
            array(
                // the default under SMARTY_DIR
                "plugins",
                // myfuses smarty plugins
                Controller::ROOT_PATH . DIRECTORY_SEPARATOR . "Plugins" .
                DIRECTORY_SEPARATOR ."smarty"
            )
        );
    }

    public function setTemplatePath($path)
    {
        $path = FileHandler::sanitizePath($path);
        if(!is_null($this->getTheme()))
        {
            $path .= $this->getTheme();
        }

        if(FileHandler::isAbsolutePath($path))
        {
            $this->smarty->setTemplateDir($path);
        } else {
            // TODO: Is that correct?
            $this->smarty->setTemplateDir(
                Controller::getApplication()->getPath() . $path);
        }
    }

    public function addApplicationPath($path)
    {
        $templateDir = $this->smarty->getTemplateDir();

        foreach($templateDir as $key => $value)
        {
            if(!FileHandler::isAbsolutePath($value))
            {
                $templateDir[$key] = $path . $value;
            }
        }

        $this->smarty->setTemplateDir($templateDir);
    }

    public function addApplicationParsedPath($path)
    {
        if(!file_exists($path))
        {
            FileHandler::createPath($path);
        }
        $this->smarty->setCompileDir($path);
    }

    public function setTemplateCompilePath($path)
    {
        $path = FileHandler::sanitizePath($path);
        if(!is_null($this->getTheme()))
        {
            $path .= $this->getTheme();
        }

        if(FileHandler::isAbsolutePath($path))
        {
            $this->smarty->setCompileDir($path);
        } else {
            // TODO: Is that correct also?
            $this->smarty->setCompileDir(
                Controller::getApplication()->getPath() . $path);
        }
    }

    public function getSmarty()
    {
        return $this->smarty;
    }

    public function setDebugging($condition)
    {
        if(is_bool($condition))
        {
            $this->smarty->setDebugging($condition);
        } else {
            $this->smarty->setDebugging(($condition == "true") ? true : false);
        }
    }

    public function setCaching($condition)
    {
        if(is_bool($condition))
        {
            $this->smarty->setCaching($condition);
        } else {
            $this->smarty->setCaching(($condition == "true") ? true : false);
        }
    }

    public function setCompileCheck($condition)
    {
        if(is_bool($condition))
        {
            $this->smarty->setCompileCheck($condition);
        } else {
            $this->smarty->setCompileCheck(
                ($condition == "true") ? true : false);
        }
    }

    public function addFile( $file )
    {
        $this->templateList[] = $file;
    }

    // don't use that
    public function displayList() {
        for($i=0; $i < count($this->templateList); $i++)
            $this->smarty->display( $this->templateList[ $i ] );
    }

    public function assignListInMain()
    {
        //FIXME Turn this otional
        //Output Buffering to avoid problem with setting headers
        ob_start();
        $this->smarty->assign("MYFUSES_TEMPLATE_FILES", $this->templateList);
        $this->smarty->display("file:" . dirname( __FILE__ ) .
            DIRECTORY_SEPARATOR . "main.tpl");
        //Allow output
        ob_flush();
    }

    public function show()
    {
        if(!file_exists($this->smarty->getCompileDir()))
        {
            mkdir($this->smarty->getCompileDir(), 0777, true);
            $path = explode(DIRECTORY_SEPARATOR,
                substr(
                    $this->smarty->getCompileDir(). DIRECTORY_SEPARATOR,
                    0,
                    strlen(
                        $this->smarty->getCompileDir() .
                        DIRECTORY_SEPARATOR ) - 1
                )
            );
            while(Controller::getInstance()->getParsedPath() != (
                    implode( DIRECTORY_SEPARATOR, $path ) .
                    DIRECTORY_SEPARATOR ) ) {
                // FIXME fix some warning here
                // FIXME: are we doing 0777 here?
                chmod(implode(DIRECTORY_SEPARATOR, $path), 0777);
                $path = array_slice($path, 0, count($path) - 1);
            }
            // FIXME: are we doing 0777 here also?
            chmod($this->smarty->getCompileDir(), 0777);
        }
        $this->assignListInMain();
    }

    public function assign($name, $val)
    {
        $this->smarty->assign($name, $val);
    }

    public function setProperties($properties)
    {

        $propertyMethods = array(
            "theme" => "setTheme",
            "applicationPath" => "addApplicationPath",
            "applicationParsedPath" => "addApplicationParsedPath",
            "template_dir" => "setTemplatePath",
            "compile_dir" => "setTemplateCompilePath",
            "debuging" => "setDebugging",
            "caching" => "setCaching"
        );

        foreach($properties as $property)
        {
            if(isset($propertyMethods[$property['attributes']['name']]))
            {
                $method = $propertyMethods[$property['attributes']['name']];
                $this->$method($property['attributes']['value']);
            }
        }

        // forcing template path use theme
        $this->setTemplatePath($this->smarty->getTemplateDir()[0]);
        $this->setTemplateCompilePath($this->smarty->getCompileDir());
    }

}
