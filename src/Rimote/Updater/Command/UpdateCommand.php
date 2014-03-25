<?php

namespace Rimote\Updater\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Rimote\Updater\Util\Logger;
use Rimote\Updater\Updater\Update;

class UpdateCommand extends Command
{
    private $logger;
    private $update;
    
    public function __construct($name = null,
        Logger $logger,
        Update $update)
    {
        parent::__construct($name);
        $this->update = $update;
    }
    
    protected function configure()
    {
        $this->setName('update')
            ->setDescription('Perform the update.');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $update = $this->update;
        
        // Run any updates
        foreach($update->get_updates() as $update) {
            $update->run();
        }
        
        $output->writeln("All updates completed succesfully.");
    }
}
