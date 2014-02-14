<?php namespace Ace\Perm;

use Ace\Perm\SubjectInterface;
use Ace\Perm\ObjectInterface;

interface StoreInterface
{
    /**
    * get all perm names for this Subject Object pair
    * @return Ace\Perm\Perm
    */
    public function get(SubjectInterface $subject, ObjectInterface $object);

    /**
    * Adds this perm name to the Subject Object pair
    */
    public function add(SubjectInterface $subject, ObjectInterface $object, $value);
}
