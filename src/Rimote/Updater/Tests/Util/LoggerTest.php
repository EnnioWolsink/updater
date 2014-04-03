<?php

namespace Rimote\Updater\Tests\Util;

use PHPUnit_Framework_TestCase;

use Rimote\Updater\Updater\Exception\LoggerException;
use Rimote\Updater\Util\Logger;

class LoggerTest extends PHPUnit_Framework_TestCase
{
    public function testLoggerConcatenatesMessages()
    {
        $message1 = "foo bar";
        $message2 = "baz";
        $message3 = "quux";
        
        $logger = new Logger();
        $logger->log($message1);
        $logger->log($message2);
        $logger->log($message3);
        
        $expected =
            $message1 . "\n"
            . $message2 . "\n"
            . $message3 . "\n";
        
        $this->assertEquals($expected, $logger->getLog());
    }
    
    public function testWriteLog()
    {
        $temp_file = tempnam(sys_get_temp_dir(), "log_");
        
        $message1 = "foo bar";
        $message2 = "baz";
        $message3 = "quux";
        
        $logger = new Logger();
        $logger->setFile($temp_file);
        $logger->log($message1);
        $logger->log($message2);
        $logger->log($message3);
        $logger->write();
        
        $expected_output = <<<EOT
foo bar
baz
quux

EOT;
        
        $this->assertEquals($expected_output, file_get_contents($temp_file));
    }
    
    /**
     * @expectedException \Rimote\Updater\Updater\Exception\LoggerException
     */
    public function testWriteLogFail()
    {
        $temp_file = tempnam(sys_get_temp_dir(), "log_");
        
        $message1 = "foo bar";
        $message2 = "baz";
        $message3 = "quux";
        
        $logger = new Logger();
        $logger->log($message1);
        $logger->log($message2);
        $logger->log($message3);
        
        $logger->write();
    }
}