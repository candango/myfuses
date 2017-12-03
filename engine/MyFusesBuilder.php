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
 * MyFusesBuilder - MyFusesBuilder.php
 *
 * Interface defining a MyFuses Builder.
 *
 * @category   controller
 * @package    myfuses.engine
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      7af9bea446be73248961f6db1b72500de3903243
 */
interface MyFusesBuilder
{
    /**
     * Builds the application structure
     *
     * @param Application $application
     */
    public function buildApplication(Application $application);

    /**
     * Add one application build listener
     *
     * @param MyFusesApplicationBuilderListener $listener
     */
    public function addApplicationBuilderListener(
        MyFusesApplicationBuilderListener $listener);

    /**
     * Return builder application
     *
     * @return Application
     */    
    public function getApplication();    

    /**
     * Set builder application
     *
     * @param Application $application
     */
    public function setApplication(Application $application);
}
