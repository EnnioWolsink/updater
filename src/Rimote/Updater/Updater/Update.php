<?php

namespace Rimote\Updater\Updater;

use Rimote\Updater\Util\Logger;

/**
 * Update
 */
class Update
{
    private $logger;
    
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * Push all new notifications.
     */
    public function update() {}
}