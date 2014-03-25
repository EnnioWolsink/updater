<?php

namespace Rimote\Updater\Util;

class Logger
{
    private $log;
    
    public function log($message)
    {
        $this->log .= $message . "\n";
    }
    
    public function getLog()
    {
        return $this->log;
    }
}
