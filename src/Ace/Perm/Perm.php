<?php namespace Ace\Perm;

use Ace\Perm\SubjectInterface;
use Ace\Perm\ObjectInterface;

class Perm
{
    private $subject;
    private $object;
    private $values = [];

    public function __construct(SubjectInterface $subject, ObjectInterface $object, array $values = [])
    {
        $this->subject = $subject;
        $this->object = $object;

        foreach($values as $value){
            $this->values[$value] = true;
        }
    }
    
    public function hasPerm($name)
    {
        return isset($this->values[$name]);
    }
}
