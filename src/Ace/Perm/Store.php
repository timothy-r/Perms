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
        $sql = "SELECT * FROM perm WHERE subject_id = ? AND subject_type = ? AND object_id = ? AND object_type = ?";
        $options = [$subject->getId(), $subject->getType(), $object->getId(), $object->getType()];
        $results = $this->db->fetchAll($sql, $options);
        $perms = [];
        foreach($results as $result){
            // remove any duplicates
            $perms [$result['value']]= $result['value'];
        }
        $perm = new Perm($subject, $object, $perms);
        return $perm;
    }

    public function add(SubjectInterface $subject, ObjectInterface $object, $value)
    {
        $table = 'perm';
        $options = ['subject_id' => $subject->getId(), 'subject_type' => $subject->getType(), 'object_id' => $object->getId(), 'object_type' => $object->getType(), 'value' => $value];
        $types = ['text', 'text', 'text', 'text', 'text'];
        $result = $this->db->insert($table, $options, $types);
        return true;
    }

    public function remove(Perm $perm, $value)
    {
        $table = 'perm';
        $subject = $perm->getSubject();
        $object = $perm->getObject();
        $options = ['subject_id' => $subject->getId(), 'subject_type' => $subject->getType(), 'object_id' => $object->getId(), 'object_type' => $object->getType(), 'value' => $value];
        $result = $this->db->delete($table, $options);
        return true;

    }
}
