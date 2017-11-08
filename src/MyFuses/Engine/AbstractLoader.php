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

namespace Candango\MyFuses\Engine;

use Candango\MyFuses\Core\Application;
use Candango\MyFuses\Core\Circuit;
/**
 * AbstractMyFusesLoader - AbstractMyFusesLoader.php
 *
 * Abstract MyFuses loader.
 *
 * @category   controller
 * @package    myfuses.Engine
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      4ea81cee237c94b5349825934ecad7e2675c7355
 */
abstract class AbstractLoader implements Loader
{
    private $applicationData = array();

    private $applicationLoaderListeners = array();

    /**
     * Loader application
     * 
     * @var Application
     */
    private $application;

    /**
     * Return the application
     *
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Set the loader Application
     *
     * @param Application $application
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;
    }

    public function &getCachedApplicationData()
    {
        return $this->applicationData;
    }

    // TODO: This is probably not being used. Should we keep it!?
    private function setCachedApplicationData($applicationData)
    {
        $this->applicationData = $applicationData;
    }

    public function destroyCachedApplicationData()
    {
        unset($this->applicationData['application']);
    }

    private function isCached()
    {
        if ($this->getApplication()->getController()->isMemcacheEnabled()) {
            return !($this->applicationData === false);
        } else {
            return is_file($this->getApplication()->getCompleteCacheFile());
        }
    }

    /**
     * Load the application
     *
     */
    public function loadApplication()
    {
        $locale = $this->getApplication()->getLocale();

        foreach($this->getApplicationLoadListeners() as $listener) {
            $listener->loadInitialized($this->getApplication());
        }

        if($this->getApplication()->getController()->isMemcacheEnabled()) {
            $this->applicationData = unserialize($this->getApplication()->
                getController()->getMemcache()->get(
                $this->getApplication()->getTag()));
        }

        if($this->isCached()) {
            $default = $this->getApplication()->isDefault();
            if (!$this->getApplication()->getController(
            )->isMemcacheEnabled()) {
                include $this->getApplication()->getCompleteCacheFile();

                // correcting cached application reference
                $this->setApplication(
                    $this->getApplication()->getController()->getApplication( 
                        $this->application->getName()));
                $this->getApplication()->setLoader($this);
            }
            $this->getApplication()->setDefault($default);

            if (MyFuses::getApplication()->isDebugAllowed()) {
                MyFuses::getInstance()->getDebugger()->registerEvent(
                    new MyFusesDebugEvent(MyFusesDebugger::MYFUSES_CATEGORY,
                        "Application " . $this->getApplication()->getName() .
                        " Restored"));
            }

            if ($this->getApplication()->getMode() === "development") {
                $this->doLoadApplication();
            }

            if ($this->getApplication()->getMode() === 'production') {
                if ($this->applicationWasModified()) {
                    $this->doLoadApplication();
                }
            }
        } else {
            $this->doLoadApplication();
        }

        foreach ($this->getApplicationLoadListeners() as $listener) {
            $listener->loadPerformed($this, $this->applicationData);
        }

        if ($this->getApplication()->getLocale() != $locale) {
            $this->getApplication()->setLocale($locale);
        }

        /*if ($this->getApplication()->getMode() === 'production') {
            foreach ($this->applicationData['application']['children']
                as $child ) {
                if (strtolower($child['name']) === 'circuits') {
                    foreach($child['children'] as $circuitChild) {
                        $this->loadCircuit($circuitChild);
                    }
                }
            }    
        } else {
            foreach ($this->applicationData['application']['children']
                as $child) {
                if (strtolower($child['name']) === 'circuits') {
                    foreach($child['children'] as $circuitChild) {
                        $this->loadCircuit($circuitChild);
                    }
                }
            }
        }*/
    }

    protected function doLoadApplication()
    {
        $data = $this->getApplicationData();

        $this->getApplication()->setLastLoadTime(time());

        $this->applicationData['application'] = $data;

        $this->getApplication()->setParse(true);
        $this->getApplication()->setStore(true);

        MyFuses::getInstance()->getDebugger()->registerEvent(
            new MyFusesDebugEvent( MyFusesDebugger::MYFUSES_CATEGORY,
                "Application " . $this->getApplication()->getName() .
                " Loaded"));
    }

    public function loadCircuit(Circuit $circuit)
    {
        $data = null;

        if ($circuit->getApplication()->getMode() === "development") {
            $data = $this->doLoadCircuit($circuit);
        }

        if ($circuit->getApplication()->getMode() === "production") {
            if (!file_exists($circuit->getCompleteCacheFile())) {
                $data = $this->doLoadCircuit($circuit);
            } else {
                include $circuit->getCompleteCacheFile();
                $data = $circuit->getData();
                if($this->circuitWasModified($circuit->getName() ) ||
                    $this->applicationWasModified()) {
                    $data = $this->doLoadCircuit($circuit);
                } else {
                    $circuit->setModified(false);
                }       
            }
        }
        return $data;
    }

    protected function doLoadCircuit(Circuit $circuit)
    {
        $data = $this->getCircuitData($circuit);

        $circuit->setLastLoadTime(time());
        $circuit->setModified(true);

        MyFuses::getInstance()->getDebugger()->registerEvent(
            new MyFusesDebugEvent(MyFusesDebugger::MYFUSES_CATEGORY,
                "Loading circuit \"" . $circuit->getName() . "\""));
        return $data;
    }

    /**
     * Returns a loader by type.
     *
     * Currently only the xml loader is available.
     *
     * @param int $whichLoader
     * @return AbstractLoader
     */
    public static function getLoader($whichLoader)
    {
        $loaderArray = array(
            Loader::XML_LOADER => __NAMESPACE__ . "\\Loaders\\XmlLoader"
        );
        return new $loaderArray[$whichLoader]();
    }

    /**
     * Add one application load listener
     *
     * @param MyFusesApplicationLoaderListener $listener
     */
    public function addApplicationLoadListener(
        MyFusesApplicationLoaderListener $listener)
    {
        $this->applicationLoaderListeners[] = $listener;
    }

    /**
     * Return all application load listerners
     *
     * @return array
     */
    private function getApplicationLoadListeners()
    {
        return $this->applicationLoaderListeners;
    }
}
