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
        $exception = null;
        
        // Run each database command
        foreach ($db_commands as $db_command) {
            try {
                $pdo->query($db_command);
            } catch (\PDOException $e) {
                $previous_exception = (!is_null($exception)) ? $exception : null;
                $exception = new UpdateException(
                    $e->getMessage(),
                    $previous_exception
                );
            }
        }
        
        // Re-throw any buffered exceptions
        if(!is_null($exception)) {
            throw new UpdateException(
                "One or more queries failed", 
                $exception
            );
        }
        
        return true;
    }
}