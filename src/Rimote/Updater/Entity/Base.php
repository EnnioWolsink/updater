<?php

namespace Rimote\Updater\Entity;

/**
 * Base entity class
 */
class Base
{
    /**
     * Contains mapped user data
     */
    private $_data = array();
    
    /**
     * Constructor
     */
    public function __construct($attributes = array())
    {
        if(count($attributes) > 0) {
            $this->_data = $attributes;
        }
    }
    
    /**
     * Get attributes
     */
    public function __get($name)
    {
        if(!array_key_exists($name, $this->_data)) {
            throw new \Exception("Can't find property " . get_class($this) 
                . ':' . htmlspecialchars($name));
        }
        
        return $this->_data[$name];
    }
}