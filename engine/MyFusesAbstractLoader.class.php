<?php
abstract class MyFusesAbstractLoader implements MyFusesLoader {
	
	/**
	 * (non-PHPdoc)
	 * @see myfuses/engine/MyFusesLoader#setApplicationParameter()
	 */
	public function setApplicationParameter( Application $application, 
	   $name, $value ) {
	   
	   $applicationParameters = array(
            "fuseactionVariable" => "setFuseactionVariable",
            "defaultFuseaction" => "setDefaultFuseaction",
            "precedenceFormOrUrl" => "setPrecedenceFormOrUrl",
            "debug" => "setDebug",
            "tools" => "setTools",
            "mode" => "setMode",
            "strictMode" => "setStrictMode",
            "password" => "setPassword",
            "parseWithComments" => "setParsedWithComments",
            "conditionalParse" => "setConditionalParse",
            "allowLexicon" => "setLexiconAllowed",
            "ignoreBadGrammar" => "setBadGrammarIgnored",
            "useAssertions" => "setAssertionsUsed",
            "scriptLanguage" => "setScriptLanguage",
            "scriptFileDelimiter" => "setScriptFileDelimiter",
            "maskedFileDelimiters" => "setMaskedFileDelimiters",
            "characterEncoding" => "setCharacterEncoding"
        );
        
        // putting into $application
        if( isset( $applicationParameters[ $name ] ) ) {
            $application->$applicationParameters[ $name ]( $value );
        }
	}
	
	public function addApplicationReference( Application $application, 
       CircuitReference $reference ) {
       $application->addReference( $reference );
    }
	
}