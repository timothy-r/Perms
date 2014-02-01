<?php namespace Ace\Perm;

use Ace\Perm\SubjectInterface;
use Ace\Perm\ObjectInterface;

interface StoreInterface
{
    public function get(SubjectInterface $subject, ObjectInterface $object);
}
