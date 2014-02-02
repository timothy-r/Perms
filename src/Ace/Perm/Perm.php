<?php namespace Ace\Perm;

use ArrayAccess;

class Perm implements ArrayAccess
{
    private $values = [];

    public function __construct(array $values = [])
    {
        foreach($values as $value){
            $this->values[$value] = true;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->values[$offset]);
    }

    public function offsetGet($offset)
    {   
        return isset($this->values[$offset]) ? $this->values[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
    }

    public function offsetUnset($offset)
    {
    }
}
