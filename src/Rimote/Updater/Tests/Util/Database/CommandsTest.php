<?php

namespace Rimote\Updater\Tests\Util;

use PHPUnit_Framework_TestCase;

use Rimote\Updater\Updater\Exception\UnexpectedException;
use Rimote\Updater\Util\Database\Commands;

class CommandsTest extends PHPUnit_Framework_TestCase
{
    public function testFetchDatabaseCommands()
    {
        $success = false;
        $temp_file = tempnam(sys_get_temp_dir(), "dump_");
        
        // Load mock commands
        $commands[0] = '--';
        $commands[1] = '-- Comment block, ignore me';
        $commands[2] = '--';
        $commands[3] = 'DELETE * FROM table';
        $commands[4] = 'INSERT INTO table SET foo="bar"';
        $commands[5] = 'UPDATE table SET foo="foobar"';
        
        $mock_commands = <<<EOT
$commands[0]
$commands[1]
$commands[2]
$commands[3]
$commands[4]
$commands[5]
EOT;
        file_put_contents($temp_file, $mock_commands);
        
        // Load commands and check if we can iterate the object
        $db_commands = new Commands($temp_file);
        
        try {
            $raw_data = file_get_contents($temp_file);
            $lines = explode("\n", $raw_data);
            
            foreach ($lines as $line_number => $line) {
                // Ignore commented lines
                if (preg_match('#^--#', $line)) {
                    continue;
                }
                
                if ($commands[$line_number] != $line) {
                    throw new UnexpectedException("Comparision of commands failed:");
                }
                
                $success = true;
            }
        } catch (UnexpectedException $e) {
            $success = false;
        }
        
        $this->assertEquals(true, $success);
    }
}
