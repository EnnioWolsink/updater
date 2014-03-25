<?php

namespace Rimote\Updater\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Rimote\Updater\Util\Logger;
use Rimote\Updater\Updater\Rollback;

class RollbackCommand extends Command
{
    private $logger;
    private $rollback;
    
    public function __construct($name = null,
        Logger $logger,
        Rollback $rollback)
    {
        parent::__construct($name);
        $this->rollback = $rollback;
    }
    
    protected function configure()
    {
        $this->setName('rollback')
            ->setDescription('Rollback to previous release.');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rollback = $this->rollback;
        $rollback->rollback();
        
        $output->writeln("Rollback completed succesfully.");
    }
}