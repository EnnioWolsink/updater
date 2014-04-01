<?php

namespace Rimote\Updater\Tests\Util;

use PHPUnit_Framework_TestCase;

use Rimote\Updater\Updater\Exception\UnexpectedException;
use Rimote\Updater\Util\Database\Connection;

class ConnectionTest extends PHPUnit_Framework_TestCase
{
    public function testFetchDatabaseCredentials()
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
            $connection = new Connection($mock_credentials);
        } catch(\PDOException $e) {
            if (preg_match('#Access denied for#', $e->getMessage())) {
                $is_pdo = true;
            }
        }
        
        $this->assertEquals(true, $is_pdo);
    }
}
