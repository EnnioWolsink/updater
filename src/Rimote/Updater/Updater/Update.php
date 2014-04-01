<?php

namespace Rimote\Updater\Updater;

use PDO;
use Rimote\Updater\Updater\Exception\UpdateException;
use Rimote\Updater\Util\Database\Commands;
use Rimote\Updater\Util\Logger;

/**
 * Update
 */
class Update
{
    /**
     * Instance of Rimote\Updater\Util\Logger
     */
    private $logger;
    
    /**
     * Constructor
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * Run update
     * 
     * @throws UpdateException
     */
    public function run(\PDO $pdo, Commands $db_commands)
    {
        // Run each database command
        foreach ($db_commands as $db_command) {
            try {
                $pdo->query($db_command);
            } catch (\PDOException $e) {
                throw new UpdateException("FATAL ERROR: the following query failed:" 
                    . $db_command . " Reason: " . $e->getMessage());
            }
        }
        
        return true;
    }
}