<?php

namespace Rimote\Updater\Updater;

use PDO;
use Rimote\Updater\Updater\Exception\UpdateException;
use Rimote\Updater\Util\Logger;
use Rimote\Updater\Util\ParseDatabaseDump;

/**
 * Update
 */
class Update
{
    /**
     * Constants
     */
    const DS = DIRECTORY_SEPARATOR;
    const DB_CREDENTIALS = 'db_credentials.php';
    const DB_DUMPFILE = 'db_changes.sql';
    
    /**
     * Instance of Rimote\Updater\Util\Logger
     */
    private $logger;
    
    /**
     * Iterateable container with database commands
     */
    private $db_commands;
    
    /**
     * Instance of \PDO
     */
    private $pdo = null;
    
    /**
     * Constructor
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * Setup PDO connection
     */
    public function setupPDO($db_credentials)
    {
        // Initialize PDO
        $this->pdo = new PDO($db_credentials['dsn'], $db_credentials['username'], 
            $db_credentials['password']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
    
    /**
     * Initialize update, sets several instance variables for re-use later on.
     * 
     * We assume our update scripts are in a subdirectory in the given directory,
     * with the version number as directory name     
     *
     * @param string $version_number The version number of the update, e.g. 1.2.3-rc1
     * @param string $updates_root The root path of all updates
     * @todo check existence of custom update PHP script
     * @throws UpdateException
     */
    public function init($version_number, $updates_root)
    {
        $update_dir = $updates_root . self::DS . $version_number;
        $db_dumpfile = $update_dir . self::DS . self::DB_DUMPFILE;
        $db_credentials_file = $update_dir . self::DS . self::DB_CREDENTIALS;
        
        // Sanity check: can we find our update directory?
        if (!file_exists($update_dir)) {
            throw new UpdateException("Update directory not found. Path used: " . $update_dir);
        }
        
        // Setup database, if we got credentials and a dump file to process
        if (file_exists($db_dumpfile) && file_exists($db_credentials_file)) {
            $this->setupPDO(require_once $db_credentials_file);
            
            // Parse and store database commands
            $this->db_commands = new ParseDatabaseDump($db_dumpfile);
        } else {
            throw new UpdateException("Database credentials and/or database dump file not found");
        }
    }
    
    /**
     * Run update
     * 
     * @throws UpdateException
     */
    public function run(\PDO $pdo, DBCommandsContainer $db_commands)
    {
        // Run each database command
        foreach ($db_commands as $db_command) {
            // $db_command = instance of DatabaseCommand
            try {
                $db_command->execute($pdo); // Wrapper to enable unit testing.
            } catch (\Exception $e) {
                throw new UpdateException("FATAL ERROR: the following query failed:" 
                    . $db_command->getQuery() . " Reason: " . $e->getMessage());
            }
        }
        
        return true;
    }
}