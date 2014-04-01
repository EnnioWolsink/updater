<?php

namespace Rimote\Updater\Util\Database;

use \PDO;
use Rimote\Updater\Updater\Exception\UnexpectedException;

class Connection
{
    private $pdo = null;
    
    /**
     * Setup PDO connection
     */
    public function __construct($db_credentials)
    {
        // Initialize PDO
        $this->pdo = new PDO($db_credentials['dsn'], $db_credentials['username'], 
            $db_credentials['password']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
    
    /**
     * Get PDO handle
     */
    public function getHandle()
    {
        return $this->pdo;
    }
}