<?php

namespace Rimote\Updater\Tests\Updater;

use PHPUnit_Framework_TestCase;

use Rimote\Updater\Updater\Exception\UpdateException;
use Rimote\Updater\Updater\Update;
use Rimote\Updater\Util\Database\Commands;
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
    
    public function query($sql)
    {
        return true;
    }
}

/**
 * Mock of Commands
 */
class MockCommands extends Commands
{
    public function __construct()
    {
        $mock_commands = array(
            'DELETE * FROM table',
            'INSERT INTO table SET foo="bar"',
            'UPDATE table SET foo="foobar"'
        );
        
        foreach ($mock_commands as $command) {
            $this->container[] = $command;
        }
    }
}

class UpdateTest extends PHPUnit_Framework_TestCase
{
    public function testRun()
    {
        $update = new Update(new Logger);
        $return_status = $update->run(new mockPDO, new MockCommands);
        
        $this->assertEquals(true, $return_status);
    }
}
