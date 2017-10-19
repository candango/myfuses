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

require_once "myfuses/util/i18n/MyFusesI18nContext.class.php";

/**
 * MyFuses i18n Handler class - MyFusesI18nHandler.php
 *
 * Utility to handle usual i18n operations.
 *
 * @category   i18n
 * @package    myfuses.util.i18n
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      c36c8ff941c440d0c01ea0341e03345dd9727b10
 */
abstract class MyFusesI18nHandler
{
    /**
     * Native type constant
     *
     * @var string
     */
    const NATIVE_TYPE = "native";

    /**
     * Gettext type constant
     *
     * @var string
     */
    const GETTEXT_TYPE = "gettext";

    /**
     * Time stamp mark
     *
     * @var long
     */
    private $timeStamp;

    /**
     * Unique instance
     *
     * @var MyFusesI18nHandler
     */
    private static $instance;

    /**
     * Method that execute all steps to configure i18n
     */
    public function configure()
    {
        $this->markTimeStamp();
        $this->setLocale();

        if ($this->mustLoadFiles()) {
            $this->loadFiles();
        }
    }

    /**
     * Set handler locale
     */
    abstract public function setLocale();

    /**
     * Load i18n files
     */
    public function loadFiles()
    {
        $application = MyFuses::getApplication();

        MyFuses::getInstance()->createApplicationPath($application);

        $i18nPath = MyFusesFileHandler::sanitizePath(
            MyFuses::getApplication()->getParsedPath() . "i18n");
        $i18nFile = $i18nPath . "locale.data.php";

        if (file_exists($i18nFile)) {
            $i18nData = require $i18nFile;

            MyFusesI18nContext::setTime($i18nData['last_load_time']);

            unset($i18nData['last_load_time']);

            MyFusesI18nContext::setContext($i18nData);
        } else {
            MyFusesI18nContext::setStore(true);
        }

        MyFuses::getApplication()->getParsedPath();

        if ($this->mustLoad()) {
            foreach (MyFuses::getInstance()->getI18nPaths() as $path) {
                if (MyFusesFileHandler::isAbsolutePath($path)) {
                    $this->digPath($path);
                } else {
                    foreach(MyFuses::getInstance()->getApplications()
                        as $key => $application) {
                        if ($key != Application::DEFAULT_APPLICATION_NAME) {
                            $this->digPath($application->getPath() . $path);
                        }
                    }
                }
            }
            MyFusesI18nContext::setTime(time());
            MyFusesI18nContext::setStore(true);
        }
        //var_dump(MyFusesI18nContext::getContext());die();
    }

    private function mustLoad()
    {
        foreach (MyFuses::getInstance()->getI18nPaths() as $path) {
            if (MyFusesFileHandler::isAbsolutePath($path)) {
                if ($this->checkPath($path)) {
                    return true;
                }
            } else {
                foreach (MyFuses::getInstance()->getApplications()
                    as $key => $application) {
                    if( $key != Application::DEFAULT_APPLICATION_NAME ) {
                        if ($this->checkPath($application->getPath() .
                            $path)) {
                            return true;
                        }
                    }
                }
            }   
        }
        return false;
    }

    private function checkPath($path)
    {
        if (file_exists($path)) {
            $dir = dir( $path );

            while (false !== ($subdir = $dir->read())) {
                $localePath = MyFusesFileHandler::sanitizePath(
                    $path . $subdir);
                if(file_exists($localePath . "expressions.xml" )) {
                    if(filemtime($localePath . "expressions.xml") >
                        MyFusesI18nContext::getTime()) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Dig the given path to find i18n files
     *
     * @param string $path
     */
    private function digPath($path)
    {
        if (file_exists($path)) {

            $dir = dir($path);

            while (false !== ($subdir = $dir->read())) {
                $localePath = MyFusesFileHandler::sanitizePath(
                    $path . $subdir
                );

                $locale = $subdir;

                if (file_exists($localePath . "expressions.xml")) {
                    if (filemtime($localePath . "expressions.xml") >
                        MyFusesI18nContext::getTime()) {
                        $doc = $this->loadFile($localePath .
                                "expressions.xml");

                        foreach ($doc->expression as $expression) {
                            $name = "";
                            foreach ($expression->attributes() as $key =>
                                $attr) {
                                if ($key == 'name') {
                                    $name = "" . $attr;
                                }    
                            }

                            if ($name != "") {
                                $expression = htmlentities(
                                    $expression, ENT_NOQUOTES, 'UTF-8'
                                );

                                MyFusesI18nContext::setExpression(
                                    $locale, $name, "" . $expression
                                );
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Mark timestamp
     */
    public function markTimeStamp()
    {
        $this->timeStamp = time();
    }

    /**
     * Return marked timestamp
     *
     * @return long
     */
    public function getTimeStamp()
    {
        return $this->timeStamp;
    }

    private function mustLoadFiles()
    {
        return true;
    }

    private static function loadFile($file)
    {
        try {
            // FIXME put no warning modifier in SimpleXMLElement call
            return @new SimpleXMLElement(file_get_contents($file));
        } catch (Exception $e) {
            // FIXME handle error
            echo "<b>" . MyFuses::getApplication()->
                getCompleteFile() . "<b><br>";
            die($e->getMessage());
        }
    }

    public abstract function storeFiles();

    /*private static function storeFiles($exps) {
        $path = MyFusesFileHandler::sanitizePath(
            MyFuses::getApplication()->getParsedPath() . 'i18n');
        foreach ($exps as $locale => $expressions) {
            $strOut = self::getFileComments($locale);
            $strOut .= self::getFileHeaders($locale);
            $strOut .= self::getExpressions($locale, $expressions);

            $pathI18n = MyFusesFileHandler::sanitizePath($path . $locale);

            if (!file_exists($pathI18n)) {
                mkdir($pathI18n, 0777, true);
                chmod($pathI18n, 0777);
            }

            $pathI18n = MyFusesFileHandler::sanitizePath($pathI18n .
                "LC_MESSAGES");

            if (!file_exists($pathI18n)) {
                mkdir($pathI18n, 0777, true);
                chmod($pathI18n, 0777);
            }

            $fileI18n = $pathI18n . "myfuses.po";

            MyFusesFileHandler::writeFile( $fileI18n, $strOut );

            exec('msgfmt ' . $fileI18n . ' -o ' . $pathI18n . 'myfuses.mo');
        }
    }*/

    /**
     * Return one MyFusesI18nHandler implementation 
     *
     * @return MyFusesI18nHandler
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            switch (MyFuses::getI18nType()) {
                case self::NATIVE_TYPE:
                    self::$instance = new MyFusesNativeI18nHandler();
                    break;
            }    
        }
        return self::$instance;
    }
}
