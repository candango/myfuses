<?php
class MyFusesSecurityContext {
	
	public static function registerApplication( Application $application ) {
		
		if( !session_start() ) {
			var_dump( "" );
		}
		
	}
	
}