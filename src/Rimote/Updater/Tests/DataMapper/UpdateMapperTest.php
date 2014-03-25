<?php

namespace Rimote\Updater\Tests\DataMapper;

use Rimote\Updater\DataMapper\UpdateMapper;
use Rimote\Updater\Entity\Update;

/**
 * Mock of PDO
 */ 
class mockPDO extends \PDO
{
    public function __construct()
    {
        return true;
    }
    
    public function prepare()
    {
        return new mockPDOStatement;
    }
}

/**
 * Mock of PDOStatement
 */
class mockPDOStatement extends \PDOStatement
{
    public function bindParam()
    {
        return true;
    }
    
    public function execute()
    {
        return true;
    }
    
    public function fetch()
    {
        return array();
    }
    
    public function fetchAll()
    {
        return array(
            array('token' => 'device1'),
            array('token' => 'device2'),
        );
    }
}

class UserMapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @todo, check if returned value is an object of the type "User", include namespace?
     */
    public function testUserMapperReturnsUpdateObject()
    {
        $pdo_mock = new mockPDO;
        $update_mapper = new UpdateMapper($pdo_mock);
        
        // Fetch user
        $update = $update_mapper->fromId(42);
        
        // Did we get an instance of Update?
        $this->assertInstanceOf('Rimote\Updater\Entity\Update', $update);
    }
}
