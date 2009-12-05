<?php
require_once 'PHPUnit/Framework.php';

require_once 'myfuses_1.1.x/MyFuses.class.php';

class MyFusesTest extends PHPUnit_Framework_TestCase {

    public function testInstatiating() {
        
        $myFuses = MyFuses::getInstance();
        
        $this->assertEquals( 'MyFuses', get_class( $myFuses ) );
        
        
    }
    
}