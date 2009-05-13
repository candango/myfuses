<?php
class MyFusesException extends Exception {
	
	const MYFUSES_APPLICATION_FILE_DOENST_EXISTS_TYPE = 
	   "myFuses Core Exception: Application File Doesn't Exists";
	
	private $description = "";
	
	private $detail = "";

	private $type = "";
	
    /**
     * 
     */
    function __construct( $message ) {
        parent::__construct( $message, 1 );

        $location = "<b>Location</b>: " .
                "<b>File:</b> " . $this->file . 
                " <b>Line:</b> " . $this->line;
        
        $this->detail .= "<br>$location";
    }
	
    public function getDetail() {
        return $this->detail;
    }
    
	public function getDescription() {
		return $this->description;
	}
	
	public function setDescription( $description ) {
		$this->description = $description;
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function setType( $type ) {
		$this->type = $type;
	}
	
    /**
     * 
     */
    function breakProcess() {
        ob_clean ();
        include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'exceptionMessage.php';
        $str = ob_get_contents();
        ob_clean ();
        print $str;
        die();
    }
	
    /**
     * Sets the current instance
     *
     * @param MyFusesException instance
     */
    static function setCurrentInstance( MyFusesException $instance ) {
        self::$currentInstance = $instance;
    }
    
    /**
     * Returns the current instance
     *
     * @return MyFusesException
     */
    static function getCurrentInstance() {
    	return self::$currentInstance;
    }
    
    /**
     * Returnt the location where the exception was found
     *
     * @param array trace
     * @return string
     */
    static function getTraceLocationString( $trace ) {
        return "<b>File:</b> " . @$trace[ 'file' ] . 
                " <b>Line:</b> " . @$trace[ 'line' ];
    }
    
    /**
     * Returns the trace function string
     *
     * @param array trace
     * @return string
     */
    static function getTraceFunctionString( $trace ) {
        $out = "";
        
        if ( isset( $trace[ 'class' ] ) ) {
            $out .= $trace[ 'class' ];
        }
        
        if ( isset( $trace[ 'type' ] ) ) {
            $out .= $trace[ 'type' ];
        }
        
        
        $out .= $trace[ 'function' ] . "(" ;
        
        if ( count( $trace[ 'args' ] ) ) {
            $out .= " ";
        }
        
        $traceX = array();
        if ( count( $trace[ 'args' ] ) ) {
            foreach( $trace[ 'args' ] as $key => $value ) {
                if ( is_object( $value ) ) {
                    @$traceX[ $key ] = "<b>" . get_class( $value ) . 
                        "</b> => '" . get_class( $value )  . "'";
                }
                else {
                    if( is_string( $value ) ) {
                        $value = ( strlen( $value ) < 40 ? $value : 
                            substr( $value, 0, 40 ) . "..." );
                    }
                    $traceX[ $key ] = "<b>" . gettype( $value ) . 
                        "</b> => '" . $value . "'"  ;
                }
            }
        }
        $out .= implode( ", ", $traceX );
        
        if ( count( $trace[ 'args' ] ) ) {
            $out .= " ";
        }
        
        $out .= ")" ;
        
        return $out;
    }
}