<?php

namespace Rimote\Updater\Util;

use Rimote\Updater\Updater\Exception\LoggerException;

class Logger
{
    private $log;
    private $file = null;
    
    /**
     * Log message
     */
    public function log($message)
    {
        $this->log .= $message . "\n";
    }
    
    /**
     * Get all log messages
     */
    public function getLog()
    {
        return $this->log;
    }
    
    /**
     * Set log file
     */
    public function setFile($file)
    {
        if (!empty($file)) {
            $this->file = $file;
        }
    }
    
    /**
     * Write log messages to file
     */
    public function write()
    {
        if (is_null($this->file)) {
            throw new LoggerException("Log file not set");
        }
        
        if (!file_put_contents($this->file, $this->getLog())) {
            throw new LoggerException("Could not write/create log file " . 
                __DIR__ . DIRECTORY_SEPARATOR . $this->file);
        }
    }
}