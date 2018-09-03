<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2018 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

namespace Candango\MyFuses\I18n;

use Candango\MyFuses\Controller;
use Candango\MyFuses\Core\Application;
use Candango\MyFuses\Util\FileHandler;

/**
 * MyFuses I18n Handler class - I18nHandler.php
 *
 * Utility to handle usual I18n operations.
 *
 * @category   I18n
 * @package    Candango.MyFuses.I18n
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      c36c8ff941c440d0c01ea0341e03345dd9727b10
 */
abstract class I18nHandler
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
     * @var I18NHandler
     */
    private static $instance;

    /**
     * Method that execute all steps to configure I18n
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
     * Load I18n files
     */
    public function loadFiles()
    {
        $application = Controller::getApplication();

        Controller::getInstance()->createApplicationPath($application);

        $i18nPath = FileHandler::sanitizePath(
            Controller::getApplication()->getParsedPath() . "i18n");

        $i18nFile = $i18nPath . "locale.data.php";

        if (file_exists($i18nFile)) {
            $i18nData = require $i18nFile;

            I18nContext::setTime($i18nData['last_load_time']);

            unset($i18nData['last_load_time']);

            I18nContext::setContext($i18nData);
        } else {
            I18nContext::setStore(true);
        }

        if ($this->mustLoad()) {
            foreach (Controller::getInstance()->getI18nPaths() as $path) {
                $path = FileHandler::sanitizePath($path);
                if (FileHandler::isAbsolutePath($path)) {
                    $this->digPath($path);
                } else {
                    foreach(Controller::getInstance()->getApplications()
                            as $key => $application) {
                        if ($key != Application::DEFAULT_APPLICATION_NAME) {
                            $this->digPath($application->getPath() . $path);
                        }
                    }
                }
            }

            I18nContext::setTime(time());
            I18nContext::setStore(true);
        }
        //var_dump(MyFusesI18nContext::getContext());die();
    }

    private function mustLoad()
    {
        foreach (Controller::getInstance()->getI18nPaths() as $path) {
            $path = FileHandler::sanitizePath($path);
            if (FileHandler::isAbsolutePath($path)) {
                if ($this->checkPath($path)) {
                    return true;
                }
            } else {
                foreach (Controller::getInstance()->getApplications()
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
            $it = new \RecursiveDirectoryIterator($path);
            foreach (new \RecursiveIteratorIterator($it, 1) as $subdir) {
                if ($subdir->getFileName() != "." &&
                    $subdir->getFileName() != "..") {
                    if ($subdir->isDir()) {
                        $expFile = FileHandler::sanitizePath($subdir) .
                            "expressions.xml";
                        if (file_exists($expFile)) {
                            if (filemtime($expFile) > I18nContext::getTime()) {
                                return true;
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     * Dig the given path to find I18n files
     *
     * @param string $path
     */
    private function digPath($path)
    {
        $path = FileHandler::sanitizePath($path);

        if (file_exists($path)) {

            $dir = dir($path);

            while (false !== ($subdir = $dir->read())) {
                $localePath = FileHandler::sanitizePath(
                    $path . $subdir
                );

                $locale = $subdir;

                if (file_exists($localePath . "expressions.xml")) {
                    if (filemtime($localePath . "expressions.xml") >
                        I18nContext::getTime()) {
                        $doc = $this->loadFile($localePath . "expressions.xml");
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

                                I18nContext::setExpression(
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
            return @new \SimpleXMLElement(file_get_contents($file));
        } catch (\Exception $e) {
            // FIXME handle error
            echo "<b>" . Controller::getApplication()->getCompleteFile() .
                "<b><br>";
            die($e->getMessage());
        }
    }

    public abstract function storeFiles();

    /*private static function storeFiles($exps) {
        $path = MyFusesFileHandler::sanitizePath(
            MyFuses::getApplication()->getParsedPath() . 'I18n');
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
     * Return one I18NHandler implementation
     *
     * @return I18NHandler
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            switch (Controller::getI18nType()) {
                case self::NATIVE_TYPE:
                    self::$instance = new NativeI18nHandler();
                    break;
            }    
        }
        return self::$instance;
    }
}
