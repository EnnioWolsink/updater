<?php

namespace Rimote\Updater\Tests\Updater;

use PHPUnit_Framework_TestCase;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Rimote\Updater\Command\UpdateCommand;
use Rimote\Updater\Updater\Update;
use Rimote\Updater\Updater\Exception\UpdateException;
use Rimote\Updater\Util\Logger;

class UpdateCommandTest extends PHPUnit_Framework_TestCase
{
    public function testConfigureName()
    {
        $update_command = new UpdateCommand(null, new Logger(), 
            new Update(new Logger()));

        $this->assertEquals('update', $update_command->getName());
    }
    
    public function testConfigureDescription()
    {
        $update_command = new UpdateCommand(null, new Logger(), 
            new Update(new Logger()));

        $this->assertEquals('Perform the update.', $update_command->getDescription());
    }
}
