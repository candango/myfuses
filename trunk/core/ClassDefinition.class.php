<?php
/**
 * ClassDefinition  - ClassDefinition.class.php
 * 
 * This class handle all class declared in myfuses.xml.
 * 
 * PHP version 5
 * 
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 * 
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 * 
 * This product includes software developed by the Fusebox Corporation 
 * (http://www.fusebox.org/).
 * 
 * The Original Code is Fuses "a Candango implementation of Fusebox Corporation 
 * Fusebox" part .
 * 
 * The Initial Developer of the Original Code is Flávio Gonçalves Garcia.
 * Portions created by Flávio Gonçalves Garcia are Copyright (C) 2006 - 2007.
 * All Rights Reserved.
 * 
 * Contributor(s): Flávio Gonçalves Garcia.
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Gonçalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id$
 */

/**
 * ClassDefinition  - ClassDefinition.class.php
 * 
 * This class handle all class declared in myfuses.xml.
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Gonçalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 50
 */
class ClassDefinition implements ICacheable {
    
    /**
     * Class name
     *
     * @var string
     */
    private $name;
    
    /**
     * Class path
     *
     * @var string
     */
    private $path;
    
    /**
     * Application where the class will be used
     *
     * @var Application
     */
    private $application;
    
    /**
     * Return the class name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Set the class name
     *
     * @param string $name
     */
    public function setName( $name ) {
        $this->name = $name;
    }
    
    /**
     * Return the class path
     *
     * @return string
     */
    public function getPath() {
        return $this->path;
    }
    
    /**
     * Set the class path
     *
     * @param string $path
     */
    public function setPath( $path ) {
        $this->path = $path;
    }
    
    /**
     * Return the complete class path.
     * Complete class path is <applciation path>+<class path>
     *
     * @return string
     */
    public function getCompletePath() {
        return $this->getApplication()->getPath() . $this->getPath();
    }
    
    /**
     * Get the Class Definition Application
     * 
     * @return Application
     */
    public function getApplication() {
        return $this->application;
    }

    /**
     * Set the Class Definition Application
     *
     * @param Application $application
     */
    public function setApplication( Application $application ) {
        $this->application = $application;
    }
    
    public function getCachedCode() {
        $strOut = "\$class = new ClassDefinition();\n";
        
        $strOut .= "\$class->setName( \"" . $this->getName() . "\" );\n";
        
        $strOut .= "\$class->setPath( \"" .  
            addslashes( $this->getPath() ) . "\");\n";
        
        $strOut .= "\$application->addClass( \$class );\n";
        
        return $strOut;
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */