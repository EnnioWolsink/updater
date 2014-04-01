<?php

namespace Rimote\Updater\Util;

class BaseIterator implements \Iterator
{
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