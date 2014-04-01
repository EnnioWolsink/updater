<?php

namespace Rimote\Updater\Util\Database;

use Rimote\Updater\Updater\Exception\UnexpectedException;
use Rimote\Updater\Util\BaseIterator;

class Credentials extends BaseIterator
{
    protected $container = array();
    protected $position = 0;
    
    /**
     * Load database credentials file into class memory
     */
    public function __construct($config_file)
    {
        if (!file_exists($config_file)) {
            throw new UnexpectedException("Config file not found. Please create `" 
                . $config_file . "`.\n");
        }
        
        // Note: using require_once breaks the unit test
        foreach (require $config_file as $key => $credential) {
            $this->container[$key] = $credential;
        }
    }
}