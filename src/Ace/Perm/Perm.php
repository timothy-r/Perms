<?php namespace Ace\Perm;


class Perm
{
    /**
    * @var string
    */
    private $subject;

    /**
    * @var string
    */
    private $object;

    /**
    * @var array
    */
    private $values = [];

    /**
    * @var array
    */
    private $added = [];

    /**
    * @var array
    */
    private $removed = [];

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
        if (!isset($this->values[$name])){
            $this->values[$name] = true;
            $this->added[$name]= true;
            if (in_array($name, $this->removed)){
                unset($this->removed[$name]);
            }
        }
    }

    public function remove($name)
    {
        if (isset($this->values[$name])){
            unset($this->values[$name]);
            $this->removed[$name] = true;
            if (in_array($name, $this->added)){
                unset($this->added[$name]);
            }
        }
    }

    public function added()
    {
        return array_keys($this->added);
    }

    public function removed()
    {
        return array_keys($this->removed);
    }
}
