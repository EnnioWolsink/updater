<?php

namespace Rimote\Updater\Tests\Util;

use PHPUnit_Framework_TestCase;

use Rimote\Updater\Updater\Exception\UnexpectedException;
use Rimote\Updater\Util\DatabaseCredentials;

class DatabaseCredentialsTest extends PHPUnit_Framework_TestCase
{
    public function testFetchDatabaseCredentials()
    {
        $success = false;
        $temp_file = tempnam(sys_get_temp_dir(), "db_");
        
        // Load mock credentials
        $dsn = 'mysql:dbname=database;host=localhost;';
        $username = 'foo';
        $password = 'bar';
        
        $mock_db_credentials = <<<EOT
<?php
return array(
    'dsn' => "{$dsn}",
    'username' => '{$username}',
    'password' => '{$password}'
);
EOT;
        file_put_contents($temp_file, $mock_db_credentials);
        
        // Load config and check if we can iterate the object
        $db_credentials = new DatabaseCredentials($temp_file);
        
        try {
            foreach (require $temp_file as $key => $db_credential) {
                if ($$key != $db_credential) {
                    throw new UnexpectedException("Comparision of credentials failed:");
                }
                
                $success = true;
            }
        } catch (UnexpectedException $e) {
            $success = false;
        }
        
        $this->assertEquals(true, $success);
    }
}
