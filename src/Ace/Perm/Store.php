<?php namespace Ace\Perm;

use Ace\Perm\StoreInterface;
use Ace\Perm\SubjectInterface;
use Ace\Perm\ObjectInterface;
use Ace\Perm\Perm;

class Store implements StoreInterface
{
    public function get(SubjectInterface $subject, ObjectInterface $object)
    {
        return new Perm;
    }
}
