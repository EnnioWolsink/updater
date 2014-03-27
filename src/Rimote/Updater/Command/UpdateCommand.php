<?php

namespace Rimote\Updater\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Rimote\Updater\Updater\Update;
use Rimote\Updater\Updater\Exception\UpdateException;
use Rimote\Updater\Util\Logger;

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
            ->setDescription('Perform the update.')
            ->addArgument(
                '<version_number>',
                InputArgument::REQUIRED,
                'The version number we are going to update to.'
            )
            ->addArgument(
                '<updates_dir>',
                InputArgument::REQUIRED,
                'The path to the directory containing custom update scripts'
            );
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $update = $this->update;
        $version_number = $input->getArgument('<version_number>');
        $updates_root = $input->getArgument('<updates_dir>');
        
        // Determine update directory
        $update_directory = $updates_root . DIRECTORY_SEPARATOR . $version_number;
        
        // Attempt to put it all together
        try {
            $db_credentials = new DatabaseConfig($update_directory); // TDD
            $db_commands = new DBCommandsContainer($update_directory); // TDD
            
            $pdo = new DatabaseConnection($db_credentials); // TDD
            $update->run($pdo, $db_commands);
        } catch (UpdateException $e) {
            $output->writeln('<error>ERROR: ' . $e->getMessage() . '</error>');
            exit;
        }
        
        // Done
        $output->writeln("All updates completed succesfully.");
    }
}