<?php
require_once "myfuses/core/AbstractAction.class.php";
require_once "myfuses/core/CircuitAction.class.php";

/**
 * Enter description here...
 *
 */
class FuseAction extends AbstractAction implements CircuitAction {
    
    /**
     * Enter description here...
     *
     * @var Circuit
     */
    private $circtuit;
    
    /**
     * Enter description here...
     *
     * @var array
     */
    private $verbs = array();
    
    private $xfas = array();
    
    public function __construct( Circuit $circuit ) {
        $this->setCircuit( $circuit );
    }
    
    
    /**
     * Return Circuit Action complete name.<br>
     * Complete name is circuit name plus dot plus action name.
     *
     * return string
     */
    public function getCompleteName() {
        return $this->getCircuit()->getName() . "." . $this->getName();
    }
    
	/**
     * Enter description here...
     *
     * @return Circuit
     */
    public function getCircuit() {
         return $this->circtuit;
    }
    
    /**
     * Enter description here...
     *
     * @param Circuit $circuit
     */
    public function setCircuit( Circuit &$circuit ) {
        $this->circtuit = &$circuit;
    }
    
    /**
     * Enter description here...
     *
     * @param Verb $verb
     */
    public function addVerb( Verb $verb ) {
        $this->verbs[] = $verb;
        $verb->setAction( $this );
    }
    
    /**
     * Enter description here...
     *
     * @param string $name
     * @return Verb
     */
    public function getVerb( $name ) {
        return $this->verbs[ $name ];
    }
    
    /**
     * Enter description here...
     *
     * @return array
     */
    public function getVerbs() {
        return $this->verbs;
    }
    
    public function getXFAs() {
        return $this->xfas;
    }
    
    public function addXFA( $name, $value ) {
        $this->xfas[ $name ] = $value;
    }
    
    public function getXfa( $name ) {
        return $this->xfas[ $name ];
    }
    
    /**
     * Enter description here...
     *
     * @return string
     */
	public function getCachedCode() {
	    $strOut = "\$action = new FuseAction( \$circuit );\n";
        $strOut .= "\$action->setName( \"" . $this->getName() . "\" );";
        $strOut .= $this->getVerbsCachedCode();
        return $strOut;
	}
    
	/**
     * Returns all Action Verbs cache code
     * 
     * @return string
     */
    private function getVerbsCachedCode() {
        
        $strOut = "\n";
        
        foreach( $this->verbs as $verb ) {
            $strOut .= $verb->getCachedCode() . "\n";
            $strOut .= "\$action->addVerb( \$verb );\n\n";
        }
        
        return $strOut;
    }
	
    public function getParsedCode(  $comented, $identLevel ) {
        
        $strOut = "";
        
        $plugins = $this->getCircuit()->getApplication()->getPlugins( Plugin::PRE_FUSEACTION_PHASE );
        
        foreach( $plugins as $plugin ) {
            $strOut .= $plugin->getParsedCode( $comented, $identLevel );
        }
        
        
        $strOut .= "var_dump( \"Current action: " . $this->getName() . "\" );";
        
        if( $this->getName() != "prefuseaction" && $this->getName() != "postfuseaction" ) {
            $action = $this->circtuit->getPreFuseAction();
        }
        
        if( !is_null( $action ) ) {
            $strOut .= $action->getParsedCode( $comented, $identLevel );    
        }
        
        foreach( $this->verbs as $verb ) {
            $strOut .= $verb->getParsedCode( $comented, $identLevel );
        }
        
        $action = $this->circtuit->getPostFuseAction();
        
        if( !is_null( $action ) ) {
            if( $this->getName() != "prefuseaction" && $this->getName() != "postfuseaction" ) {
                $strOut .= $action->getParsedCode( $comented, $identLevel );
            }
        }
        
        $plugins = $this->getCircuit()->getApplication()->getPlugins( Plugin::POST_FUSEACTION_PHASE );
        
        foreach( $plugins as $plugin ) {
            $strOut .= $plugin->getParsedCode( $comented, $identLevel );
        }
        return $strOut;
    }
    
    public function getComments( $identLevel ) {
        return "";
    }
    
	public function doAction() {
	    
	}
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */