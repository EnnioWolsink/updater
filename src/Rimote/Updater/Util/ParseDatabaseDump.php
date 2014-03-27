<?php

namespace Rimote\Updater\Util;

use Rimote\Updater\Updater\Exception\UnexpectedException;

class ParseDatabaseDump implements \Iterator
{
    /**
     * Container that contains all database commands
     */
    private $container = array();
    
    /**
     * Position tracker
     */
    private $position = 0;
    
    /**
     * Constructor
     *
     * Load database dump file into class memory
     */
    public function __construct($db_dumpfile)
    {
        if (!file_exists($db_dumpfile)) {
            throw new UnexpectedException('Could not find database dump file');
        }
        
        // Fill container
        $raw_data = file_get_contents($db_dumpfile);
        $lines = explode("\n", $raw_data);
        
        foreach ($lines as $line) {
            // Ignore commented lines
            if (preg_match('#^--#', $line)) {
                continue;
            }
            
            $this->container[] = $line;
        }
        
        $this->position = 0;
    }
    
    public function rewind()
    {
        $this->position = 0;
    }
 
    public function valid()
    {
        return array_key_exists($this->position, $this->container);
    }
 
    public function key()
    {
        return $this->position;
    }
 
    public function current()
    {
        return $this->container[$this->position];
    }
 
    public function next()
    {
        ++$this->position;
    }
}