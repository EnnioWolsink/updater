<?php

namespace Rimote\Updater\Tests\Updater;

use PHPUnit_Framework_TestCase;

use Rimote\Updater\Updater\Exception\UpdateException;
use Rimote\Updater\Updater\Update;
use Rimote\Updater\Util\Logger;

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

class UpdateTest extends PHPUnit_Framework_TestCase
{
    public function testInitCheckExistenceUpdatesDirectory()
    {
        $mock_dir_root_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'deleteme';
        $mock_dir_path = $mock_dir_root_path . DIRECTORY_SEPARATOR . '1.2.3-rc1';
        
        @rmdir($mock_dir_path);
        @rmdir($mock_dir_root_path);
        
        @mkdir($mock_dir_root_path, 755);
        @mkdir($mock_dir_path, 755);

        try {
            $update = new Update(new Logger());
            $update->init('1.2.3-rc1', $mock_dir_root_path);
            $success = true;
        } catch (UpdateException $e) {
            echo $e->getMessage();
            $success = false;
        }
        
        @rmdir($mock_dir_path);
        @rmdir($mock_dir_root_path);

        $this->assertEquals(true, $success);
    }
    
    public function testSettingupPDOConnection()
    {
        $is_pdo = false;
        
        // Mock DB credentials
        $mock_credentials = array(
            'dsn' => "mysql:dbname=mockdb;" . 
                "host=localhost",
            'username' => 'user',
            'password' => 'pass'
        );
        
        try {
            $update = new Update(new Logger());
            $update->setupPDO($mock_credentials);
        } catch(\PDOException $e) {
            if (preg_match('#Access denied for#', $e->getMessage())) {
                $is_pdo = true;
            }
        }
        
        $this->assertEquals(true, $is_pdo);
    }
    
    public function testRun()
    {
        $update = new Update(new Logger());
        $return_status = $update->run();
        
        $this->assertEquals(true, $return_status);
    }
}
