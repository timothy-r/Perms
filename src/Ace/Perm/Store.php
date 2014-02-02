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
        $sql = "SELECT * FROM perm WHERE object_id = ? AND object_type = ? AND subject_id = ? AND subject_type = ?";
        $options = [$subject->getId(), $subject->getType(), $object->getId(), $object->getType()];
        $result = $this->db->fetchAssoc($sql, $options);
        $perm = new Perm;
        return $perm;
    }
}
