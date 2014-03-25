<?php

namespace Rimote\Updater\Tests\Util;

use PHPUnit_Framework_TestCase;

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
}
