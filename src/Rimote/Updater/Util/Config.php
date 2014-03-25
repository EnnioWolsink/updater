<?php

namespace Rimote\Updater\Util;

class Config
{
    /**
     * Fetch config
     */
    public function fetch()
    {
        $config_file = __DIR__ . '/../../../../app/config/config.php';
        
        if (!file_exists($config_file)) {
            echo "ERROR: No config found. Please create `config/config.php`.\n";
            exit;
        }
        
        return require $config_file;
    }
}