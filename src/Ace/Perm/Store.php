<?php namespace Ace\Perm;

use Ace\Perm\StoreInterface;
use Ace\Perm\SubjectInterface;
use Ace\Perm\ObjectInterface;
use Ace\Perm\Perm;
use Doctrine\DBAL\Connection;

class Store implements StoreInterface
{
    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function get(SubjectInterface $subject, ObjectInterface $object)
    {
        return new Perm;
    }
}
