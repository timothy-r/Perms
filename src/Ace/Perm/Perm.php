<?php namespace Ace\Perm;


class Perm
{
    private $subject;
    private $object;
    private $values = [];

    public function __construct($subject, $object, array $values = [])
    {
        $this->subject = $subject;
        $this->object = $object;

        foreach($values as $value){
            $this->values[$value] = true;
        }
    }
    
    public function getSubject()
    {
        return $this->subject;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function hasPerm($name)
    {
        return isset($this->values[$name]);
    }

    public function allPerms()
    {
        return array_keys($this->values);
    }

    public function add($name)
    {
        $this->values[$name] = true;
    }
}
