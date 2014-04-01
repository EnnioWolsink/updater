<?php

namespace Rimote\Updater\Util\Database;

use Rimote\Updater\Updater\Exception\UnexpectedException;
use Rimote\Updater\Util\BaseIterator;

class Commands extends BaseIterator
{
    protected $container = array();
    protected $position = 0;
    
    /**
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
}