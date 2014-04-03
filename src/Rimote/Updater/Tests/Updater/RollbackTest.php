<?php

namespace Rimote\Updater\Tests\Updater;

use PHPUnit_Framework_TestCase;

use Rimote\Updater\Updater\Exception\UpdateException;
use Rimote\Updater\Updater\Rollback;
use Rimote\Updater\Util\Database\Commands;
use Rimote\Updater\Util\Logger;

/**
 * Mock of PDO
 */ 
class RbMockPDO extends \PDO
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
class RbMockCommands extends Commands
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

class RollbackTest extends PHPUnit_Framework_TestCase
{
    public function testRollback()
    {
        $rollback = new Rollback(new Logger);
        $return_status = $rollback->perform(new RbMockPDO, new RbMockCommands);
        
        $this->assertEquals(true, $return_status);
    }
}
