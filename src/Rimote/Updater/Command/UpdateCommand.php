<?php

namespace Rimote\Updater\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Rimote\Updater\Updater\Update;
use Rimote\Updater\Updater\Exception\UpdateException;
use Rimote\Updater\Updater\Exception\LoggerException;

use Rimote\Updater\Util\Database\Commands;
use Rimote\Updater\Util\Database\Connection;
use Rimote\Updater\Util\Database\Credentials;
use Rimote\Updater\Util\Logger;

class UpdateCommand extends Command
{
    private $logger;
    private $update;
    
    /**
     * Constants
     */
    const DS = DIRECTORY_SEPARATOR;
    const DB_CREDENTIALS = 'db_credentials.php';
    const DB_DUMPFILE = 'db_changes.sql';
    const LOG_FILE = 'updater.log';
    
    public function __construct($name = null,
        Logger $logger,
        Update $update)
    {
        parent::__construct($name);
        $this->update = $update;
        $this->logger = $logger;
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
            $credentials = new Credentials($update_directory . self::DS . 
                self::DB_CREDENTIALS);
            $connection = new Connection($credentials);
            $commands = new Commands($update_directory . self::DS . 
                self::DB_DUMPFILE);
            
            // Run update
            $update->run($connection->getHandle(), $commands);
        } catch (UpdateException $e) {
            $this->logger->setFile(self::LOG_FILE);
            $this->logger->log($e->getAllMessages(array('stack_traces' => true)));
            $this->logger->write();
            
            $output->writeln('<error>ERROR: ' . $e->getMessage() . 
                "\n" . 'Check ' . self::LOG_FILE . ' for details.</error>');
            exit;
        }
        
        // Done
        $output->writeln("All updates completed succesfully.");
    }
}