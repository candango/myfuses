<?php
class MyFusesSoapClient {
   
    private $types = array();
    
    private $functions = array();
    
    /**
	 * Soap client
     * 
     * @var SoapClient
     */
    private $soapClient;
    
    private $wsdl;
   
    public function __construct( $wsdl ) {
       
       $this->wsdl = $wsdl;
       
       $this->soapClient = new SoapClient( $this->wsdl );
       
       $this->parseTypes( $this->soapClient->__getTypes() );
       
       $this->parseFunctions( $this->soapClient->__getFunctions() );
       
       var_dump( $this->getFunctions() );
       
       
       var_dump( $this->getTypes() );
       
       $param = array( "CountryName" => "Brazil" );
       
       $result = $this->soapClient->__call( "GetCitiesByCountry", array( 'parameters' => $param ) );
       
       var_dump( $result->GetCitiesByCountryResult );die();
       
   }
   
   public function getTypes() {
       return $this->types;    
   }
   
   public function getFunctions() {
       return $this->functions;
   }
   
   private function parseType( $typeString ) {
       
       $types = preg_split( "/ |\n|;/", $typeString );
       $startState = false;
       $parametersState = false;
       $typeParameterState = true;
       
       $parameter = array( "name" => "", "value" => "" );
       
       $param = null;
       
       $tModel = null;
       
       $typeModel = array( "name" => "", "parameters" => array() );
       
	   foreach( $types as $type ) {
	       if( $type == "}" ) {
	           $parametersState = false;
	           $this->types[] = $tModel;    
	       }
	       
	       if( $startState ) {
	           $tModel[ "name" ] = $type;
	           $startState = false;
	       }
	       
	       if( $parametersState ) {
	           if( $type != "" ) {
	               if( $typeParameterState ) {
		               $param = $parameter;
		               $param[ "value" ] = $type;
		           }
		           else {
		               $param[ "name" ] = $type;
		               $tModel[ "parameters" ][] = $param;
		           }
		           $typeParameterState = !$typeParameterState;
	           }
	       }
	       
	       if( $type == "struct" ) {
	           $startState = true;
	           $tModel = $typeModel;    
	       }
	       
	       if( $type == "{" ) {
	           $parametersState = true;    
	       }
	       
	   }
       
   }
   
   private function parseTypes( $types ) {
       foreach( $types as $type ) {
           $this->parseType( $type );
       }
   }
   
   private function parseFunction( $function ) {
       $functionSplit = preg_split( "/ |\n|\(|\)/", $function );
       
       $startState = true;
       $nameState = false;
       $parametersState = false;
       $parameterNameState = true;
       
       $functionModel = array( "returnType" => "", 
           "name" => "", "parameters" => array() );
       
       $parameter = array( "name" => "", "value" => "" );
           
       $functionM = null;
       
       $param = null;
       
       foreach( $functionSplit as $key => $functionItem ) {
           if( $functionItem != "" ) {
               
               if( $parametersState ) {
	               if( $parameterNameState ) {
	                   $param = $parameter;
		               $param[ "name" ] = $functionItem;
	               }
	               else {
	                   $param[ "value" ] = $functionItem;
		               $functionM[ "parameters" ][] = $param;
	               }
                   $parameterNameState = !$parameterNameState;
	           }
               
               if( $nameState ) {
	               $functionM[ "name" ] = $functionItem;
	               $nameState = !$nameState;
	               $parametersState = !$parametersState;
	           }
               
               if( $startState ) {
	               $functionM = $functionModel;
	               $functionM[ "returnType" ] = $functionItem;
	               $startState = !$startState;
	               $nameState = !$nameState;
	           }
	           
	           
           }
           if( $key == count( $functionSplit ) - 1 ) {
               $this->functions[] = $functionM;
           }
       }
       
   }
   
   private function parseFunctions( $functions ) {
       foreach( $functions as $function  ) {
           $this->parseFunction( $function );
       }
   }
}