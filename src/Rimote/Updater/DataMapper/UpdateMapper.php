<?php

namespace Rimote\Updater\DataMapper;

use Rimote\Updater\Entity\Device;

class UpdateMapper
{
    private $pdo;
    
    /**
     * Constructor
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }
}