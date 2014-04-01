<?php

namespace Rimote\Updater\Util;

class BaseIterator implements \Iterator, \ArrayAccess
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
    
    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }
    public function offsetExists($offset) {
        return isset($this->container[$offset]);
    }
    public function offsetUnset($offset) {
        unset($this->container[$offset]);
    }
    public function offsetGet($offset) {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }
}