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
 * MyFusesLoader - MyFusesLoader.php
 *
 * Interface defining a MyFuses Loader.
 *
 * @category   controller
 * @package    myfuses.Engine
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      4ea81cee237c94b5349825934ecad7e2675c7355
 */
interface Loader
{

    const XML_LOADER = 0;

    /**
     * Load the application
     *
     */
    public function loadApplication();

    public function applicationWasModified();

    public function circuitWasModified($name);
    
    public function getApplicationData();

    /**
     * Load one circuit
     *
     * @param Circuit $circuit
     */
    public function loadCircuit(Circuit $circuit);

    /**
     * Add one application load listener
     *
     * @param ApplicationLoaderListener $listener
     */
    public function addApplicationLoadListener(
        ApplicationLoaderListener $listener);

    /**
     * Return the application
     *
     * @return Application
     */
    public function getApplication();

    /**
     * Set the loader Application
     *
     * @param Application $application
     */
    public function setApplication(Application $application);
}
