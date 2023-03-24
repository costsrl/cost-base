<?php
namespace CostBaseTest\Service\Invokables;

use PHPUnit_Framework_TestCase;

class TableGatewayTest extends PHPUnit_Framework_TestCase
{
    public $oTableGateway   = null;
    public $oServiceLocator = null;
    
    public function setUp(){
        $this->serviceLocator = $this->getMock('Laminas\ServiceManager\ServiceManager');
        $this->oServiceLocator = new \CostBase\Service\Invokables\TableGateway();
    }
    
    public function testAssertTrue(){
        $this->assertEquals('test', 'test');
    }
    
    public function testAssertFail(){
        $this->assertEquals('test', 'notest');
    }
    
    public function tearDown(){
        
    }
}

?>