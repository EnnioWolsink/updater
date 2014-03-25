<?php
namespace Rimote\Updater\Controller;

use Rimote\Updater\Util\Config;

class AppController
{
    /**
     * Container for PDO object
     */
    protected $pdo = null;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        // Create PDO object
        $config = Config::fetch();
        $db = $config['database'];
        $dsn = "mysql:dbname={$db['database']};host={$db['host']}";
        
        $this->pdo = new \PDO($dsn, $db['username'], $db['password']);
    }
}